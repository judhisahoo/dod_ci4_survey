<?php

namespace App\Controllers;

use App\Models\SubtaskModel;
use App\Models\TaskModel;
use CodeIgniter\RESTful\ResourceController;

class SubtaskController extends ResourceController
{
    //protected $modelName = 'App\Models\SubtaskModel';
    protected $format    = 'json';

    public $cModel,$tModel,$title="Minor Groups";

    function __construct()
    {
        $this->cModel = new SubtaskModel();
        $this->tModel = new TaskModel();
    }
    public function index()
    {
        $data['subtasks'] = $this->cModel->orderBy('id','DESC')->paginate(30);
        $data['title'] = $this->title;
        $data['pager'] = $this->cModel->pager;
        return view('adminpanel/subtask/index', $data);
    }

    public function create()
    {
        $data['tasks'] = $this->tModel->findAll();
        $data['title'] = $this->title;
        return view('adminpanel/subtask/create', $data);
    }

    public function store()
    {
        $task_id=$this->request->getPost('task_id');
        $insertDataArr=[
            'task_id' => $task_id,
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'status' => 1
        ];
        //print_r($insertDataArr);die;
        $this->cModel->save($insertDataArr);
        return redirect()->to('/adminpanel/subtask');
    }

    public function edit($id = null)
    {
        $data['subtask'] = $this->cModel->find($id);
        $data['title'] = $this->title;
        return view('adminpanel/subtask/edit', $data);
    }

    public function update($id = null)
    {
        $this->cModel->update($id, [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code')
        ]);
        return redirect()->to('/adminpanel/subtask');
    }

    public function delete($id = null)
    {
        $this->cModel->delete($id);
        return redirect()->to('/adminpanel/subtask');
    }
}
