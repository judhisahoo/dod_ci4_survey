<?php

namespace App\Controllers;

use App\Models\SubtaskRattingModel;
use App\Models\SubtaskModel;
use CodeIgniter\RESTful\ResourceController;

class SubtaskRattingController extends ResourceController
{
    protected $modelName = 'App\Models\SubtaskRattingModel';
    protected $format    = 'json';

    public $cModel, $stModel,$title="Rating topic (based on Minor Group)";
    function __construct()
    {
        $this->cModel = new SubTaskRattingModel();
        $this->stModel =  new SubtaskModel();
    }
    public function index()
    {
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 30;
        $data['subtasksrattings'] = $this->cModel->orderBy('id','DESC')->paginate($perPage);
        $data['title'] = $this->title;
        $data['pager'] = $this->cModel->pager;
        $data['pageSlNo'] = $perPage*($page-1)+1;
        return view('adminpanel/subtaskratting/index', $data);
    }

    public function create()
    {
        $data['subtasks'] = $this->stModel->findAll();
        $data['title'] = $this->title;
        return view('adminpanel/subtaskratting/create', $data);
    }

    public function store()
    {
        $subtask_id=$this->request->getPost('subtask_id');
        $dataArr=$this->cModel->getLatestIdBasedOnParent($subtask_id);
        if(empty($dataArr)){
            $dataArr = $this->stModel->where('id',$subtask_id)->get()->getRowArray();
            $lastID=$dataArr['code'].'1';
        }else{
            $lastID=$dataArr['code']+1;
        }
        $insertDataArr=[
            'subtask_id' => $subtask_id,
            'name' => $this->request->getPost('name'),
            'code' => $lastID,
            'status' => 1,
        ];
        //print_r($insertDataArr);die;
        $this->cModel->save($insertDataArr);
        return redirect()->to('/adminpanel/subtaskratting');
    }

    public function edit($id = null)
    {
        $data['subtasks'] = $this->stModel->findAll();
        $data['subtaskratting'] = $this->cModel->find($id);
        $data['title'] = $this->title;
        return view('adminpanel/subtaskratting/edit', $data);
    }

    public function update($id = null)
    {
        $this->cModel->update($id, [
            'subtask_id' => $this->request->getPost('subtask_id'),
            'name' => $this->request->getPost('name'),
        ]);
        return redirect()->to('/adminpanel/subtaskratting');
    }

    public function delete($id = null)
    {
        $this->cModel->delete($id);
        return redirect()->to('/adminpanel/subtaskratting');
    }
}
