<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MajorGroupModel;
use App\Models\SubMajorGroupModel;
use App\Models\TaskModel;
use App\Models\SubtaskModel;
use App\Models\SurveyModel;

use App\Models\SubtaskRattingModel;
use App\Models\SurveySubtaskModel;
use App\Models\SurveyUserSurveyModel;
use App\Models\SurveySubtaskRattingModel;

use CodeIgniter\HTTP\ResponseInterface;

use \App\Models\SurveyUserModel;
use Config\App;
use Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SurveyController extends BaseController
{
    public  $title="User Survey List",$isLastSegments = '';

    function showSurveyForm(){
        $majorGroupmodel= new MajorGroupModel();
        $majorGroupData=$majorGroupmodel->where('status','1')->findAll();
        //echo '<pre>';print_r($majorGroupData);die;
        return view('home', ['majorGroupData' => $majorGroupData]);
    }

    function getSubMajorGroup(){
        if ($this->request->isAJAX()) {
            $majorGroupId = $this->request->getPost('majorGroupId');
            $subMajorGroupModel = new SubMajorGroupModel();
            $subMajorGroup = $subMajorGroupModel->where('majorgroup_id', $majorGroupId)->findAll();

            return $this->response->setJSON($subMajorGroup);
        }
    }

    function getTasks(){
        if ($this->request->isAJAX()) {
            $subMajorGroupId = $this->request->getPost('subMajorGroupId');
            $taskmodel = new TaskModel();
            $task = $taskmodel->where('submajorgroup_id',$subMajorGroupId)->findAll();
            return $this->response->setJSON($task);
        }
    }

    function getSubTask(){
        if($this->request->isAJAX()){
            $taskId = $this->request->getPost('taskId');
            $subTaskModel =  new SubtaskModel();
            $subTask = $subTaskModel->where('task_id', $taskId)->findAll();

            return $this->response->setJSON($subTask);
        }
    }

    function getAllSubTask(){
        if($this->request->isAJAX()){
            $taskId = $this->request->getPost('taskId');//subtasksrattings
            $surveyModel = new SurveyModel();
            $subTask = $surveyModel->getAllSubTaskAndRatting($taskId);
            echo view('get-all-sub-task',['subTask'=>$subTask]);
        }
    }

    function subTaskDetails(){
        if($this->request->isAJAX()){
            $subtaskId = $this->request->getPost('subTaskId');//subtasksrattings
            $surveyModel = new SurveyModel();
            $subTask = $surveyModel->getSubTaskRattingDetails($subtaskId);
            //echo '<pre>';print_r($subTask);die;
            echo view('subtaskdetails',['subTask'=>$subTask,'subtaskId' => $subtaskId]);
        }
    }

    function getCSRFToken(){
        return csrf_hash();
    }

    function submitSurvey(){
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page');
        }
        $subTaskModel =  new SubtaskModel();
        $subtask_id = $this->request->getPost('subtask_id');
        $subTaskDataArr = $subTaskModel->where('task_id', $this->request->getPost('task_id'))->findAll();
        $isDataPosted = false;
        $subtaskRattingModel =  new SubtaskRattingModel();
        foreach($subTaskDataArr AS $k=>$v){
            $subtaskRattingDataArr= $subtaskRattingModel->where('subtask_id',$v['id'])->findAll();
            foreach($subtaskRattingDataArr As $m=>$n){
                $rattting_element =  $n['id'];
                //echo $rattting_element.'<br />';
                $ratting_value = $this->request->getPost($rattting_element);
                //echo $ratting_value.'<br />';
                if($ratting_value!=''){
                    /*$dataArr = [
                        'survey_subtask_id' => $v['id'],
                        'ratting_id' => $n['id'],
                        'ratting_value' => $ratting_value
                    ];
                    print_r($dataArr);
                    $multiDataArr[] = $dataArr;*/
                    $isDataPosted = true;
                    break;
                }
            }
            if($isDataPosted === true){
                break;
            }
        }

        if($isDataPosted === false){
            return redirect()->back()->withInput()->with('optionSelectionError', 'Please select any option.');
        }
         // Define validation rules
         $validationRules = [
            'name' => 'required',
            'name_of_business' => 'required',
            'sector' => 'required',
            'major_group_id' => 'required',
            'submajor_group_id' => 'required',
            'task_id' => 'required',
        ];

        // Run validation
        if (!$this->validate($validationRules)) {
            // If validation fails, return to the login page with errors
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $user = $session->get('me');

        $dataArr=[
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'name_of_business' => $this->request->getPost('name_of_business'),
            'sector' => $this->request->getPost('sector'),
            'address' => $this->request->getPost('address'),
            'status' => 1
        ];

        $surveyUserModel = model('SurveyUserModel');
        $surveyUserModel->update($user['id'],$dataArr); //insert($dataArr);
        $surveyUserId = $user['id'];//$surveyUserModel->getInsertID();

        $surveyUserSurveyModel = new SurveyUserSurveyModel();
        $surveyUserSurveyDataArr = [
            'survey_user_id' => $surveyUserId,
            'major_group_id' => $this->request->getPost('major_group_id'),
            'submajor_group_id' => $this->request->getPost('submajor_group_id'),
            'task_id' => $this->request->getPost('task_id'),
            'status' => 1
        ];

        $surveyUserSurveyModel->insert($surveyUserSurveyDataArr);
        $surveyUserSurveyId = $surveyUserSurveyModel->getInsertID();

        
        if($isDataPosted === true){
            $db = \Config\Database::connect();
            $builder = $db->table('survey_subtask_ratting');
            
            //$db = \Config\Database::connect();
            if($subtask_id==''){
                //////////// now going to store data for multiple subtask
                $subTaskModel =  new SubtaskModel();
                $subTaskDataArr = $subTaskModel->where('task_id', $this->request->getPost('task_id'))->findAll();
                foreach($subTaskDataArr AS $k=>$v){
                    $surveySubtaskDataArr = [
                        'survey_user_survey_id' => $surveyUserSurveyId,
                        'subtask_id' => $v['id'],
                        'status' => 1
                    ];
                    $surveySubtaskModel = new SurveySubtaskModel();
                    if(!empty($surveySubtaskDataArr)){
                        $surveySubtaskModel->insert($surveySubtaskDataArr);
                        $surveySubtaskId = $surveySubtaskModel->getInsertID();

                        //$multiDataArr = [];
                        $SurveySubtaskRattingModel = new SurveySubtaskRattingModel();
                        $subtaskRattingDataArr= $subtaskRattingModel->where('subtask_id',$v['id'])->findAll();
                        foreach($subtaskRattingDataArr AS $m=>$n){
                            $rattting_element =  $n['id'];
                            $ratting_value = $this->request->getPost($rattting_element);
                            if($ratting_value!=''){
                                $dataArr = [
                                    'survey_subtask_id' => $surveySubtaskId,
                                    'ratting_id' => $n['id'],
                                    'ratting_value' => $ratting_value,
                                    'status' => 1
                                ];
                                
                                if(!empty($dataArr)){
                                    $SurveySubtaskRattingModel->insert($dataArr);
                                }
                            }
                        }
                        //$builder->insertBatch($multiDataArr);
                    }
                }
            }else{
                /// going to show singple subtask
                $surveySubtaskModel = new SurveySubtaskModel();
                $surveySubtaskDataArr = [
                    'survey_user_survey_id' => $surveyUserSurveyId,
                    'subtask_id' => $this->request->getPost('subtask_id'),
                    'status' => 1
                ];

                if(!empty($surveySubtaskDataArr)){
                    $surveySubtaskModel->insert($surveySubtaskDataArr);
                    $surveySubtaskId = $surveySubtaskModel->getInsertID();

                    $subtaskRattingDataArr= $subtaskRattingModel->where('subtask_id',$this->request->getPost('subtask_id'))->findAll();
                    foreach($subtaskRattingDataArr AS $m=>$n){
                        $rattting_element =  $n['id'];
                        $ratting_value = $this->request->getPost($rattting_element);
                        if($ratting_value!=''){
                            $dataArr = [
                                'survey_subtask_id' => $surveySubtaskId,
                                'ratting_id' => $n['id'],
                                'ratting_value' => $ratting_value,
                                'status' => 1
                            ];
                            if(!empty($dataArr)){
                                $SurveySubtaskRattingModel = new SurveySubtaskRattingModel();
                                $SurveySubtaskRattingModel->insert($dataArr);
                            }
                        }
                    }
                    //$builder->insertBatch($multiDataArr);
                }
            }
        }
        return redirect()->back()->with('status', 'Thanks you to complete the survey.');
    }

    function listAllEmployerSuervey(){
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $cModel = new SurveyModel();
        // Define the number of records per page
        $perPage = 10;
        $data['surveys'] = $cModel->getAllSurveys($perPage);
        $data['title'] = $this->title;
        $data['pager'] = $cModel->pager;
        $data['pageSlNo'] = $perPage*($page-1)+1;
        return view('adminpanel/survey/index', $data);
    }


    public function exportToExcel()
    {
        $cModel = new SurveyModel();
        $surveys = $cModel->getAllSurveysWithoutPagination(); // Fetch all records without pagination

        if(count($surveys)==0){
            return redirect()->back()->with('status', 'There not record found for export as Excel Formate.');
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Name of Business');
        $sheet->setCellValue('E1', 'Sector');
        $sheet->setCellValue('F1', 'Top Group');
        $sheet->setCellValue('G1', 'Major Group');
        $sheet->setCellValue('H1', 'Sub Major Group');
        $sheet->setCellValue('I1', 'Minor Group');
        $sheet->setCellValue('J1', 'Rating Label');
        $sheet->setCellValue('K1', 'Rating Value');
        $sheet->setCellValue('L1', 'Survey User Type');
        // Add other headers as needed

        // Fill data
        $row = 2; // Start from the second row (first row is for headers)
        foreach ($surveys as $survey) {
            $userType = ($survey['user_type']==1)?'Employer':'institution';
            $sheet->setCellValue('A' . $row, $row-1);
            $sheet->setCellValue('B' . $row, $survey['name']);
            $sheet->setCellValue('C' . $row, $survey['email']);
            $sheet->setCellValue('D' . $row, $survey['name_of_business']);
            $sheet->setCellValue('E' . $row, $survey['sector']);
            $sheet->setCellValue('F' . $row, $survey['topGroupName']);
            $sheet->setCellValue('G' . $row, $survey['majorGroupName']);
            $sheet->setCellValue('H' . $row, $survey['subMajorGroupName']);
            $sheet->setCellValue('I' . $row, $survey['minorGroupName']);
            $sheet->setCellValue('J' . $row, $survey['rattingLabel']);
            $sheet->setCellValue('K' . $row, $survey['ratting_value']);
            $sheet->setCellValue('L' . $row, $userType);
            // Add other columns as needed
            $row++;
        }

        // Set the filename and output the file to download
        $filename = 'surveys_export_' . date('Y-m-d') . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Write the file and send to browser
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function getSurveyUsers(){
        $this->title="Survey Users";
        $cModel = new SurveyUserModel();
        // Define the number of records per page
        $perPage = 10;
        $data['surveys'] = $cModel->getAllSurveyUser($perPage);
        $data['title'] = $this->title;
        $data['pager'] = $cModel->pager;
        return view('adminpanel/survey-user/index', $data);
    }
}
