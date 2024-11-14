<?php

namespace App\Controllers;

use App\Models\SubMajorGroupModel;
use App\Models\MajorGroupModel;
use CodeIgniter\RESTful\ResourceController;

class SubMajorGroupController extends ResourceController
{
    //protected $modelName = 'App\Models\SubMajorGroupModel';
    protected $format    = 'json';
    public $cModel,$cMajorModel,$title="Major Groups";

    function __construct()
    {
        $this->cModel= new SubMajorGroupModel();
        $this->cMajorModel = new MajorGroupModel();
    }
    public function index()
    {
        $data['submajorgroups'] = $this->cModel->findAll();
        $data['title'] = $this->title;
        return view('adminpanel/submajorgroup/index', $data);
    }

    public function create()
    {
        $data['majorgroups'] = $this->cMajorModel->findAll();
        $data['title'] = $this->title;
        return view('adminpanel/submajorgroup/create', $data);
    }

    public function store()
    {
        $majorgroup_id=$this->request->getPost('majorgroup_id');
        $insertDataArr=[
            'majorgroup_id' => $majorgroup_id,
            'name' => $this->request->getPost('name'),
            'status' => 1,
            'code' => $this->request->getPost('code')
        ];
        //print_r($insertDataArr);die;
        $this->cModel->save($insertDataArr);
        return redirect()->to('/adminpanel/submajorgroup');
    }

    public function edit($id = null)
    {
        $data['majorgroups'] = $this->cMajorModel->findAll();
        $data['submajorgroup'] = $this->cModel->find($id);
        $data['title'] = $this->title;
        return view('adminpanel/submajorgroup/edit', $data);
    }

    public function update($id = null)
    {
        $this->cModel->update($id, [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code')
        ]);
        return redirect()->to('/adminpanel/submajorgroup');
    }

    public function delete($id = null)
    {
        $this->cModel->delete($id);
        return redirect()->to('/adminpanel/submajorgroup');
    }
}
