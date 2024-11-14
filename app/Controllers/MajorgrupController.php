<?php

namespace App\Controllers;

use App\Models\MajorGroupModel;
use CodeIgniter\RESTful\ResourceController;

class MajorgrupController extends ResourceController
{
    protected $modelName = 'App\Models\MajorgrupModel';
    protected $format    = 'json';
    public  $modelObj,$title="Top Groups",$isLastSegments;

    function __construct()
    {
        $this->modelObj= new MajorGroupModel();
        $uri = current_url(true);
        //echo $uri->getTotalSegments();die;
        if($uri->getTotalSegments()==4){
            if($uri->getSegment(4)=='edit'){
                $this->isLastSegments='edit';
            }else{
                $this->isLastSegments='';
            }
        }
        if($uri->getTotalSegments()==3){
            if($uri->getSegment(3)=='create'){
                $this->isLastSegments='create';
            }else{
                $this->isLastSegments='';
            }
        }
        
        if($uri->getTotalSegments()==2 && $this->isLastSegments!=''){
            $this->isLastSegments='list';
        }
    }
    public function index()
    {
        $data['majorgrups'] = $this->modelObj->findAll();
        $data['title'] = $this->title;
        $data['isLastSegments'] = $this->isLastSegments;
        //print_r($data);die;
        return view('adminpanel/majorgrup/index', $data);
    }

    public function create()
    {
        $data['title'] = $this->title;
        $data['isLastSegments'] = $this->isLastSegments;
        return view('adminpanel/majorgrup/create',$data);
    }

    public function store()
    {
        $insertDataArr=[
            'name' => $this->request->getPost('name'),
            'status' => 1,
            'code' => $this->request->getPost('code')
        ];
        //print_r($insertDataArr);die;
        $this->modelObj->save($insertDataArr);
        return redirect()->to('/adminpanel/majorgrup');
    }

    public function edit($id = null)
    {
        $data['majorgrup'] = $this->modelObj->find($id);
        $data['title'] = $this->title;
        $data['isLastSegments'] = $this->isLastSegments;
        return view('/adminpanel/majorgrup/edit', $data);
    }

    public function update($id = null)
    {
        $this->modelObj->update($id, [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code')
        ]);
        return redirect()->to('/adminpanel/majorgrup');
    }

    public function delete($id = null)
    {
        $this->modelObj->delete($id);
        return redirect()->to('/adminpanel/majorgrup');
    }
}
