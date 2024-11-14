<?php

namespace App\Controllers;

use App\Models\SubtaskRattingModel;
use App\Models\SubtaskModel;
use CodeIgniter\RESTful\ResourceController;

class SubtaskRattingController extends ResourceController
{
    protected $modelName = 'App\Models\SubtaskRattingModel';
    protected $format    = 'json';

    public $cModel, $stModel,$title="Ratting topic(based on Minor Group)";
    function __construct()
    {
        $this->cModel = new SubTaskRattingModel();
        $this->stModel =  new SubtaskModel();
    }
    public function index()
    {
        $data['subtasksrattings'] = $this->cModel->orderBy('id','DESC')->paginate(10);
        $data['title'] = $this->title;
        $data['pager'] = $this->cModel->pager;
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
