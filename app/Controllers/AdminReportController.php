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

class AdminReportController extends BaseController
{
    public  $title = "CMI Score & Report", $isLastSegments = '';
    public $majorGroupId = 0, $submajor_group_id = 0, $task_id = 0, $subTaskId = 0;

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
        $data ['majorGroupId'] = $this->majorGroupId;
        $data ['submajor_group_id'] = $this->submajor_group_id;
        $data ['task_id'] = $this->task_id;
        $data ['subTaskId'] = $this->subTaskId;

        $data ['SubmajorGroups']= array();
        $data ['Tasks']= array();
        $data ['SubTasks']= array();

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
            $match = array_filter($InstututionRatting, function($institution) use ($employer) {
                return $employer['ratting_id'] === $institution['ratting_id'];
            });
        
            $institution = reset($match); // Get the first matched item or null
        
            // Merge the two items
            $merged = array_merge($employer, $institution ?: []);
        
            // Calculate checked_status if both ratings exist
            if (isset($merged['employer_ratting']) && isset($merged['institution_ratting'])) {
                $employerRating = $merged['employer_ratting'];
                $institutionRating = $merged['institution_ratting'];
                $merged['skill_score'] = round(pow($employerRating - $institutionRating, 2) / pow(9, 2),PHP_ROUND_HALF_UP);
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

        $data ['majorGroupId'] = $this->request->getPost('major-group');
        $data ['submajor_group_id'] = $this->request->getPost('submajor_group_id');
        $data ['task_id'] = $this->request->getPost('task_id');
        $data ['subTaskId'] = $subTaskId;

        $subMajorGroupModel = new SubMajorGroupModel();
        $data ['SubmajorGroups'] = $subMajorGroupModel->where('majorgroup_id', $data ['majorGroupId'])->findAll();

        $taskmodel = new TaskModel();
        $data ['Tasks'] = $taskmodel->where('submajorgroup_id',$data ['submajor_group_id'])->findAll();

        $subTaskModel =  new SubtaskModel();
        $data ['SubTasks'] = $subTaskModel->where('task_id', $data ['task_id'])->findAll();
        return view('adminpanel/survey-report/index', $data);
    }

    function showGraph($subTaskId){
        if(!isset($subTaskId)){
            redirect('/adminpanel');
        }

        $db = \Config\Database::connect();

        $query = $db->query('SELECT task_id FROM subtasks where id='.$subTaskId);
        $rowSubtasks   = $query->getRowArray();
        if(count($rowSubtasks)==0){
            redirect('/adminpanel');
        }

        $query = $db->query('SELECT submajorgroup_id FROM tasks where id='.$rowSubtasks['task_id']);
        $rowTasks   = $query->getRowArray();

        $query = $db->query('SELECT majorgroup_id FROM submajorgroups where id='.$rowTasks['submajorgroup_id']);
        $rowSubmajorGroups   = $query->getRowArray();

        $majorGroupmodel = new MajorGroupModel();
        $majorGroupData = $majorGroupmodel->where('status', '1')->findAll();

        $surveySubtaskRattingModel = new SurveySubtaskRattingModel();
        $employerRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 1); // this is for Employer
        $InstututionRatting = $surveySubtaskRattingModel->getAllRattingByMinorId($subTaskId, 2); // this is for Institution
        //echo '<pre>';
        //print_r($InstututionRatting);
        //die;

        // Merge arrays
        foreach ($employerRatting as $employer) {
            // Find a matching item from institution data
            $match = array_filter($InstututionRatting, function($institution) use ($employer) {
                return $employer['ratting_id'] === $institution['ratting_id'];
            });
        
            $institution = reset($match); // Get the first matched item or null
        
            // Merge the two items
            $merged = array_merge($employer, $institution ?: []);
        
            // Calculate checked_status if both ratings exist
            if (isset($merged['employer_ratting']) && isset($merged['institution_ratting'])) {
                $employerRating = $merged['employer_ratting'];
                $institutionRating = $merged['institution_ratting'];
                $merged['skill_score'] = round(pow($employerRating - $institutionRating, 2) / pow(9, 2),PHP_ROUND_HALF_UP);
            } else {
                $merged['skill_score'] = null; // Set to null if ratings are incomplete
            }
        
            $mergedArray[] = $merged;
        }

        if(count($mergedArray)==0){
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
       
        $data ['subTaskId'] = $subTaskId;
        $subTaskModel =  new SubtaskModel();
        $data ['SubTasks'] = $subTaskModel->where('task_id', $rowSubtasks['task_id'])->findAll();

        $data ['task_id'] = $rowSubtasks['task_id'];
        $taskmodel = new TaskModel();
        $data ['Tasks'] = $taskmodel->where('submajorgroup_id',$rowTasks ['submajorgroup_id'])->findAll();

        $data ['majorGroupId'] = $rowTasks ['submajorgroup_id'];
        $subMajorGroupModel = new SubMajorGroupModel();
        $data ['SubmajorGroups'] = $subMajorGroupModel->where('majorgroup_id', $rowSubmajorGroups ['majorgroup_id'])->findAll();

        $data ['submajor_group_id'] = $rowSubmajorGroups ['majorgroup_id'];
        
        $data ['reportsData'] = array_column($mergedArray, 'skill_score');
        $data ['reportsCategory'] = array_column($mergedArray, 'name');

        return view('adminpanel/survey-report/graph', $data);
    }
}
