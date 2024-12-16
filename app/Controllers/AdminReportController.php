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
            'subTaskId' => 'required',
        ];

        // Run validation
        if (!$this->validate($validationRules)) {
            // If validation fails, return to the login page with errors
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        $mergedArray = array();
        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $subTaskId = $this->request->getPost('subTaskId');

        $surveySubtaskRattingModel = new SurveySubtaskRattingModel();
        $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 1); // this is for Employer
        $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 2); // this is for Institution
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

    function showGraph($subTaskId)
    {
        if (!isset($subTaskId)) {
            redirect('/adminpanel');
        }

        $db = \Config\Database::connect();

        $query = $db->query('SELECT task_id FROM subtasks where id=' . $subTaskId);
        $rowSubtasks   = $query->getRowArray();
        if (count($rowSubtasks) == 0) {
            redirect('/adminpanel');
        }

        $query = $db->query('SELECT submajorgroup_id FROM tasks where id=' . $rowSubtasks['task_id']);
        $rowTasks   = $query->getRowArray();

        $query = $db->query('SELECT majorgroup_id FROM submajorgroups where id=' . $rowTasks['submajorgroup_id']);
        $rowSubmajorGroups   = $query->getRowArray();

        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $surveySubtaskRattingModel = new SurveySubtaskRattingModel();
        $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 1); // this is for Employer
        $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 2); // this is for Institution
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
            redirect('/adminpanel');
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
        $data['SubTasks'] = $subTaskModel->where('task_id', $rowSubtasks['task_id'])->findAll();

        $data['task_id'] = $rowSubtasks['task_id'];
        $taskmodel = new TaskModel();
        $data['Tasks'] = $taskmodel->where('submajorgroup_id', $rowTasks['submajorgroup_id'])->findAll();

        $data['majorGroupId'] = $rowTasks['submajorgroup_id'];
        $subMajorGroupModel = new SubMajorGroupModel();
        $data['SubmajorGroups'] = $subMajorGroupModel->where('majorgroup_id', $rowSubmajorGroups['majorgroup_id'])->findAll();

        $data['submajor_group_id'] = $rowSubmajorGroups['majorgroup_id'];

        $data['reportsData'] = array_column($mergedArray, 'skill_score');
        $data['reportsCategory'] = array_column($mergedArray, 'name');

        return view('adminpanel/survey-report/graph', $data);
    }

    function aiReport()
    {
        $request = service('request');
        // Replace with your actual API key
        $apiKey = 'AIzaSyAhQ34euf817uQOK7kbh4gnC_I6xitadbs';

        // Prompt for the Gemini API
        $prompt = "Analyze the following data and provide me a report";


        $this->title = "AI Report";
        $page    = 1;
        // Define the number of records per page
        $perPage = 30;
        $data['title'] = $this->title;
        $data['errors'] = [];
        $data['pageSlNo'] = 1;
        if ($request->is('get')) { //die('rr');
            return view('adminpanel/survey-report/ai-report', $data);
        }
        if ($request->is('post')) {
            $this->title = "AI Genered Report";
            $data['title'] = $this->title;
            $validationRule = [
                'aiDataFile' => [
                    'label' => 'Upload Table Image Report',
                    'rules' => [
                        'uploaded[aiDataFile]',
                        'is_image[aiDataFile]',
                        'mime_in[aiDataFile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                        'max_size[aiDataFile,1024]',
                        //'max_dims[aiDataFile,1024,768]',
                    ],
                ],
            ];
            if (! $this->validateData([], $validationRule)) {
                $data['errors'] = $this->validator->getErrors();
                return view('adminpanel/survey-report/ai-report', $data);
            }

            $img = $request->getFile('aiDataFile');
            
            if (! $img->hasMoved()) {
                $newName = $img->getRandomName();
                $img->move(WRITEPATH . 'uploads/'.date('Ymd'), $newName);
                // Path to the image file
                $imagePath = WRITEPATH . 'uploads/'.date('Ymd').'/'. $newName;

                $gemini = new Client($apiKey);
                
                try {
                    // Read the image file content and encode it in base64
                    $imageData = base64_encode(file_get_contents($imagePath));

                    // Create ImagePart and TextPart objects
                    $imagePart = new ImagePart(MimeType::IMAGE_PNG, $imageData);
                    $textPart = new TextPart($prompt);

                    // Create a GenerateContentRequest object
                    //$request = new GenerateContentRequest('gemini-1.5-flash',[$textPart, $imagePart]);
                    // Create a Content object and add the parts
                    $content = new Content([$textPart, $imagePart], Role::User);
                    //$content->addPart($textPart);
                    //$content->addPart($imagePart);

                    // Create a GenerateContentRequest object with the content
                    $request = new GenerateContentRequest('gemini-1.5-flash', [$content]);

                    // Send the image and text to Gemini API for processing
                    //$response = $gemini->generateContent($textPart, $imagePart, 'gemini-1.5-flash');
                    $response = $gemini->generateContent($request);
                    //$response = $gemini->generateContent($request, 'gemini-1.5-flash');

                    // Extract the JSON response
                    $content = $response->text();

                    $content1 = $this->formatContentToHTML($content);

                    // Handle the response (e.g., display the generated report)
                    $data['aiResponse']=$content1;

                    return view('adminpanel/survey-report/ai-response', $data);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();die;
                }
            }

            $data['errors'] = 'The file has already been moved.';

            return view('adminpanel/survey-report/ai-report', $data);
        }
    }

    function formatContentToHTML($content) {
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
}
