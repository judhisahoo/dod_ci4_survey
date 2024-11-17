<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\SubMajorGroupModel;
use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format    = 'json';

    public $cModel,$subMojorModel,$title="Sub Major Groups";

    function __construct()
    {
        $this->cModel = new TaskModel();
        $this->subMojorModel =  new SubMajorGroupModel();
    }

    public function index()
    {
        $data['tasks'] = $this->cModel->orderBy('id','DESC')->paginate(30);
        $data['title'] = $this->title;
        $data['pager'] = $this->cModel->pager;
        return view('adminpanel/task/index', $data);
    }

    public function create()
    {
        $data['submajorgroups'] = $this->subMojorModel->findAll();
        $data['title'] = $this->title;
        return view('adminpanel/task/create', $data);
    }

    public function store()
    {
        $submajorgroup_id=$this->request->getPost('submajorgroup_id');
        $insertDataArr=[
            'submajorgroup_id' => $submajorgroup_id,
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'status' => 1,
        ];
        //print_r($insertDataArr);die;
        $this->cModel->save($insertDataArr);
        return redirect()->to('/adminpanel/task');
    }

    public function edit($id = null)
    {
        $data['task'] = $this->cModel->find($id);
        $data['title'] = $this->title;
        return view('adminpanel/task/edit', $data);
    }

    public function update($id = null)
    {
        $this->cModel->update($id, [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
        ]);
        return redirect()->to('/adminpanel/task');
    }

    public function delete($id = null)
    {
        $this->cModel->delete($id);
        return redirect()->to('/adminpanel/task');
    }
}
