<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\MajorGroupModel;
use App\Models\SubMajorGroupModel;
use App\Models\TaskModel;
use App\Models\SubtaskModel;
use App\Models\SurveyModel;
use App\Models\SurveySubtaskRattingModel;
use CodeIgniter\Files\File;
use Exception;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Enums\MimeType;
use GeminiAPI\Enums\Role;
use GeminiAPI\Requests\GenerateContentRequest;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\ImagePart;

use Dompdf\Dompdf;
use Dompdf\Options;
class AdminReportController extends BaseController
{
    public  $title = "CMI Score & Report", $isLastSegments = '';
    public $majorGroupId = 0, $submajor_group_id = 0, $task_id = 0, $subTaskId = 0;

    protected $helpers = ['form'];

    public function index()
    {

        $page    = 1;
        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();
        // Define the number of records per page
        $perPage = 30;
        $data['reports'] = array();
        $data['title'] = $this->title;
        //$data['pager'] = $cModel->pager;
        $data['pageSlNo'] = 1;
        $data['majorgroups'] = $majorGroupData;
        $data['majorGroupId'] = $this->majorGroupId;
        $data['submajor_group_id'] = $this->submajor_group_id;
        $data['task_id'] = $this->task_id;
        $data['subTaskId'] = $this->subTaskId;

        $data['SubmajorGroups'] = array();
        $data['Tasks'] = array();
        $data['SubTasks'] = array();

        return view('adminpanel/survey-report/index', $data);
    }

    public function showReport()
    {
        $validationRules = [
            'major-group' => 'required',
            'submajor_group_id' => 'required',
            'task_id' => 'required',
            //'subTaskId' => 'required',
        ];

        // Run validation
        if (!$this->validate($validationRules)) {
            // If validation fails, return to the login page with errors
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        $mergedArray = array();
        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $surveySubtaskRattingModel = new SurveySubtaskRattingModel();

        $subTaskId = $this->request->getPost('subTaskId');
        if ($subTaskId != null) {
            $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 1); // this is for Employer
            $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 2); // this is for Institution
        } else {
            $task_id = $this->request->getPost('task_id');
            $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($task_id, 1, 'task'); // this is for Employer
            $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($task_id, 2, 'task'); // this is for Institution
        }




        //echo '<pre>';
        //print_r($InstututionRatting);
        //die;

        // Merge arrays
        foreach ($employerRatting as $employer) {
            // Find a matching item from institution data
            $match = array_filter($InstututionRatting, function ($institution) use ($employer) {
                return $employer['ratting_id'] === $institution['ratting_id'];
            });

            $institution = reset($match); // Get the first matched item or null

            // Merge the two items
            $merged = array_merge($employer, $institution ?: []);

            // Calculate checked_status if both ratings exist
            if (isset($merged['employer_ratting']) && isset($merged['institution_ratting'])) {
                $employerRating = $merged['employer_ratting'];
                $institutionRating = $merged['institution_ratting'];
                $merged['skill_score'] = round(pow($employerRating - $institutionRating, 2) / pow(9, 2), PHP_ROUND_HALF_UP);
            } else {
                $merged['skill_score'] = null; // Set to null if ratings are incomplete
            }

            $mergedArray[] = $merged;
        }


        // Output the merged array
        //print_r($mergedArray);die;

        $page    = 1;
        $cModel = new SurveyModel();
        // Define the number of records per page
        $perPage = 30;
        $data['reports'] = $mergedArray;
        $data['title'] = $this->title;
        //$data['pager'] = $cModel->pager;
        $data['pageSlNo'] = 1;
        $data['majorgroups'] = $majorGroupData;

        $data['majorGroupId'] = $this->request->getPost('major-group');
        $data['submajor_group_id'] = $this->request->getPost('submajor_group_id');
        $data['task_id'] = $this->request->getPost('task_id');
        $data['subTaskId'] = $subTaskId;

        $subMajorGroupModel = new SubMajorGroupModel();
        $data['SubmajorGroups'] = $subMajorGroupModel->where('majorgroup_id', $data['majorGroupId'])->findAll();

        $taskmodel = new TaskModel();
        $data['Tasks'] = $taskmodel->where('submajorgroup_id', $data['submajor_group_id'])->findAll();

        $subTaskModel =  new SubtaskModel();
        $data['SubTasks'] = $subTaskModel->where('task_id', $data['task_id'])->findAll();
        return view('adminpanel/survey-report/index', $data);
    }

    function showGraph($Id, $taskType = "subtask")
    {
        if (!isset($Id)) {
            redirect('/adminpanel');
        }

        $db = \Config\Database::connect();

        if ($taskType == 'subtask') {
            $subTaskId = $Id;
            $query = $db->query('SELECT task_id FROM subtasks where id=' . $Id);
            $rowSubtasks   = $query->getRowArray();
            if (!$rowSubtasks) {

                return redirect()->to(site_url('/adminpanel'));
            }

            $query = $db->query('SELECT submajorgroup_id FROM tasks where id=' . $rowSubtasks['task_id']);
            $rowTasks   = $query->getRowArray();
            $taskId = $rowSubtasks['task_id'];
        } else {
            $subTaskId = 0;
            $taskId = $Id;
            $query = $db->query('SELECT submajorgroup_id FROM tasks where id=' . $Id);
            $rowTasks   = $query->getRowArray();
            if (!$rowTasks) {
                return redirect()->to(site_url('/adminpanel'));
            }
        }

        $query = $db->query('SELECT majorgroup_id FROM submajorgroups where id=' . $rowTasks['submajorgroup_id']);
        $rowSubmajorGroups   = $query->getRowArray();

        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $surveySubtaskRattingModel = new SurveySubtaskRattingModel();
        $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($Id, 1, $taskType); // this is for Employer
        $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($Id, 2, $taskType); // this is for Institution
        //echo '<pre>';
        //print_r($InstututionRatting);
        //die;

        $mergedArray = array();

        // Merge arrays
        foreach ($employerRatting as $employer) {
            // Find a matching item from institution data
            $match = array_filter($InstututionRatting, function ($institution) use ($employer) {
                return $employer['ratting_id'] === $institution['ratting_id'];
            });

            $institution = reset($match); // Get the first matched item or null

            // Merge the two items
            $merged = array_merge($employer, $institution ?: []);

            // Calculate checked_status if both ratings exist
            if (isset($merged['employer_ratting']) && isset($merged['institution_ratting'])) {
                $employerRating = $merged['employer_ratting'];
                $institutionRating = $merged['institution_ratting'];
                $merged['skill_score'] = round(pow($employerRating - $institutionRating, 2) / pow(9, 2), PHP_ROUND_HALF_UP);
            } else {
                $merged['skill_score'] = null; // Set to null if ratings are incomplete
            }

            $mergedArray[] = $merged;
        }

        if (count($mergedArray) == 0) {
            return redirect()->to(site_url('/adminpanel'));
        }


        $page    = 1;
        $cModel = new SurveyModel();
        // Define the number of records per page
        $perPage = 30;
        $data['title'] = $this->title;
        //$data['pager'] = $cModel->pager;
        $data['pageSlNo'] = 1;
        $data['majorgroups'] = $majorGroupData;
        $data['reports'] = $mergedArray;

        $data['subTaskId'] = $subTaskId;
        $subTaskModel =  new SubtaskModel();
        $data['SubTasks'] = $subTaskModel->where('task_id', $taskId)->findAll();
        $data['task_id'] = $taskId;

        $taskmodel = new TaskModel();
        $data['Tasks'] = $taskmodel->where('submajorgroup_id', $rowTasks['submajorgroup_id'])->findAll();

        $data['submajor_group_id'] = $rowTasks['submajorgroup_id'];
        $subMajorGroupModel = new SubMajorGroupModel();
        $data['SubmajorGroups'] = $subMajorGroupModel->where('majorgroup_id', $rowSubmajorGroups['majorgroup_id'])->findAll();

        $data['majorGroupId'] = $rowSubmajorGroups['majorgroup_id'];

        $data['reportsData'] = array_column($mergedArray, 'skill_score');
        $data['reportsCategory'] = array_column($mergedArray, 'name');

        return view('adminpanel/survey-report/graph', $data);
    }

    function aiReport()
    {
        $request = service('request');

        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $this->title = "AI Report";
        $page    = 1;
        // Define the number of records per page
        $perPage = 30;
        $data['title'] = $this->title;
        $data['errors'] = [];
        $data['pageSlNo'] = 1;
        $data['majorgroups'] = $majorGroupData;
        $data['majorGroupId'] = $this->majorGroupId;
        $data['submajor_group_id'] = $this->submajor_group_id;
        $data['task_id'] = $this->task_id;
        $data['subTaskId'] = $this->subTaskId;

        $data['SubmajorGroups'] = array();
        $data['Tasks'] = array();
        $data['SubTasks'] = array();
        if ($request->is('get')) {
            //$imagePath  = WRITEPATH . 'uploads/20241225074032.png';
            //return $this->aiGenerateReport($data, $imagePath);
            return view('adminpanel/survey-report/ai-report', $data);
        }

        if ($request->is('post')) {
            $this->title = "AI Genered Report";
            $data['title'] = $this->title;
            $validationRules = [
                'major-group' => 'required',
                'submajor_group_id' => 'required',
                'task_id' => 'required',
                //'subTaskId' => 'required',
            ];

            // Run validation
            if (!$this->validate($validationRules)) {
                // If validation fails, return to the login page with errors
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            
            $surveySubtaskRattingModel = new SurveySubtaskRattingModel();
            $subTaskId = $this->request->getPost('subTaskId');
            if ($subTaskId != null) {
                $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 1); // this is for Employer
                $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 2); // this is for Institution
            } else {
                $task_id = $this->request->getPost('task_id');
                $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($task_id, 1, 'task'); // this is for Employer
                $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($task_id, 2, 'task'); // this is for Institution
            }

            // Merge arrays
            foreach ($employerRatting as $employer) {
                // Find a matching item from institution data
                $match = array_filter($InstututionRatting, function ($institution) use ($employer) {
                    return $employer['ratting_id'] === $institution['ratting_id'];
                });

                $institution = reset($match); // Get the first matched item or null

                // Merge the two items
                $merged = array_merge($employer, $institution ?: []);

                // Calculate checked_status if both ratings exist
                if (isset($merged['employer_ratting']) && isset($merged['institution_ratting'])) {
                    $employerRating = $merged['employer_ratting'];
                    $institutionRating = $merged['institution_ratting'];
                    $merged['skill_score'] = round(pow($employerRating - $institutionRating, 2) / pow(9, 2), PHP_ROUND_HALF_UP);
                } else {
                    $merged['skill_score'] = null; // Set to null if ratings are incomplete
                }

                $mergedArray[] = $merged;
            }

            //$view = service('renderer');
            $tmpData = [
                'reports' => $mergedArray,
                'pageSlNo' => 1 // Pass the initial serial number for the table
            ];
           // echo '<pre>';print_r($tmpData);die;
            $htmlContent = view('adminpanel/survey-report/html-table-report',$tmpData);
            //echo $htmlContent;die;
            $imagePath = $this->htmlContent2Image($htmlContent);
            //echo $imagePath;die;
            return $this->aiGenerateReport($data,$imagePath);
        }
    }

    function formatContentToHTML($content)
    {
        // Convert headings marked with '**' to HTML <h2>
        $content = preg_replace('/\*\*(.+?)\*\*/', '<h4>$1</h4>', $content);

        // Convert bullet points marked with '*' to <ul><li>
        $content = preg_replace('/\* (.+?)(?=\*|$)/s', '<li>$1</li>', $content);

        // Wrap bullet points in <ul> tags
        $content = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $content);

        // Add line breaks for better formatting
        $content = nl2br($content);

        return $content;
    }

    public function htmlContent2Image($htmlContent){
        $nowContentDirectory = WRITEPATH . 'uploads/'.date('Ymd');
        if(!is_dir($nowContentDirectory)) //create the folder if it's not exists
        {
            mkdir($nowContentDirectory,0755,TRUE);
        } 

        $newName= date('YmdHis').'.png';
        // Path to the image file
        $imageFilePath  = $nowContentDirectory . '/'.$newName;

        // Configure Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        // Use "custom" paper size to match content
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        // Dynamically calculate dimensions based on content
        $canvas = $dompdf->get_canvas();
        $contentWidth = $canvas->get_width();
        $contentHeight = $canvas->get_height();

        // Convert PDF to Image
        $pdfOutput = $dompdf->output();

        // Save PDF to a temporary file
        $tempPdfPath = $nowContentDirectory . '/temp-report.pdf';
        file_put_contents($tempPdfPath, $pdfOutput);

        try {
            $im = new \Imagick();
            $im->readImage($tempPdfPath . '[0]'); // Read the first page
            $im->setImageFormat('png');

            // Crop image to the content size
            $im->cropImage($contentWidth, $contentHeight, 0, 0);

            // Save the final image
            $im->writeImage($imageFilePath);

            // Free memory
            $im->clear();
            $im->destroy();

            // Cleanup temporary file
            unlink($tempPdfPath);

            //echo "Image successfully generated at: " . $imageFilePath;
            return $imageFilePath;
        } catch (\ImagickException $e) {
            echo "Imagick error: " . $e->getMessage();
        }
       
    }

    function aiGenerateReport($data, $imagePath)
    {
        // Replace with your actual API key
        $apiKey = env('GEMINI_API_KEY');

        // Prompt for the Gemini API
        $prompt = env('GEMINI_PROMPT');

        log_message('info', 'going to initialize gemini.');
        $gemini = new Client($apiKey);
        log_message('error', 'going to initialize gemini completed.');
        try {
            // Read the image file content and encode it in base64
            $imageData = base64_encode(file_get_contents($imagePath));

            log_message('info', '$imageData :::'.$imageData);

            // Create ImagePart and TextPart objects
            $imagePart = new ImagePart(MimeType::IMAGE_PNG, $imageData);
            $textPart = new TextPart($prompt);
            log_message('info', 'by using gemini api $imagePart and $textPart done.');
            // Create a GenerateContentRequest object
            //$request = new GenerateContentRequest('gemini-1.5-flash',[$textPart, $imagePart]);
            // Create a Content object and add the parts
            $content = new Content([$textPart, $imagePart], Role::User);
            log_message('info', 'by using gemini api $content done by $imagePart and $textPart');
            //$content->addPart($textPart);
            //$content->addPart($imagePart);

            // Create a GenerateContentRequest object with the content
            $request = new GenerateContentRequest('gemini-1.5-flash', [$content]);
            log_message('info', 'by using gemini api GenerateContentRequest done by $content');
            // Send the image and text to Gemini API for processing
            //$response = $gemini->generateContent($textPart, $imagePart, 'gemini-1.5-flash');
            $response = $gemini->generateContent($request);
            //$response = $gemini->generateContent($request, 'gemini-1.5-flash');
            log_message('info', 'by using gemini api $gemini->generateContent() done by $request');
            // Extract the JSON response
            $content = $response->text();
            log_message('info', 'by using gemini api $response->text() done by $content ::'.$content);

            $content1 = $this->formatContentToHTML($content);
            log_message('info', 'by using gemini api $this->formatContentToHTML() done by $content ::'.$content1);
            // Handle the response (e.g., display the generated report)
            $data['aiResponse'] = $content1;
            //echo 'KKK';
            return view('adminpanel/survey-report/ai-response', $data);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function generateImage()
    {
        // Data for the table
        $tableData = [
            ['Sl No', 'Unit Title', 'Demand By Employer', 'Supplied By Institution', 'Individual Skill Scores'],
            [1, 'Unit A', 6, 3, 0.1],
            [2, 'Unit B', 7, 9, 0.0],
            [3, 'Unit C', 8, 5, 0.1],
            [4, 'Unit D', 8, 9, 0.0],
            [5, 'Unit E', 8, 6, 0.0]
        ];

        // Image dimensions
        $width = 800;
        $height = 400;

        // Create an image
        $image = imagecreatetruecolor($width, $height);

        // Set colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 200, 200, 200);

        // Fill the background
        imagefill($image, 0, 0, $white);

        // Set font size and path (use a built-in font for shared hosting)
        $fontSize = 3; // GD built-in font size
        $cellPadding = 5;

        // Calculate cell dimensions
        $rowHeight = 25;
        $columnWidths = [50, 250, 150, 150, 150]; // Define column widths

        // Draw table header and rows
        $y = 10; // Start Y position
        foreach ($tableData as $rowIndex => $row) {
            $x = 10; // Start X position
            foreach ($row as $colIndex => $cell) {
                // Draw cell background
                $cellColor = $rowIndex === 0 ? $gray : $white;
                imagefilledrectangle(
                    $image,
                    $x,
                    $y,
                    $x + $columnWidths[$colIndex],
                    $y + $rowHeight,
                    $cellColor
                );

                // Add text
                imagestring($image, $fontSize, $x + $cellPadding, $y + $cellPadding, (string) $cell, $black);

                // Draw cell border
                imagerectangle(
                    $image,
                    $x,
                    $y,
                    $x + $columnWidths[$colIndex],
                    $y + $rowHeight,
                    $black
                );

                $x += $columnWidths[$colIndex]; // Move to the next column
            }
            $y += $rowHeight; // Move to the next row
        }

        // Save the image to the writable/uploads directory
        $filePath = WRITEPATH . 'uploads/report-image.png';
        imagepng($image, $filePath);

        // Free memory
        imagedestroy($image);

        // Return success message
        echo "Image successfully generated at: " . $filePath;
    }
}
