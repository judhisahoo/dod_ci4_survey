<?php

namespace App\Controllers;
use App\Models\MajorGroupModel;
class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function show()
    {
        $majorGroupmodel= new MajorGroupModel();
        $majorGroupData=$majorGroupmodel->where('status','1')->findAll();
        //echo '<pre>';print_r($majorGroupData);die;
        return view('home', ['majorGroupData' => $majorGroupData]);
    }
}
