<?php

namespace App\Controllers;
use App\Models\MajorGroupModel;
use App\Models\SurveyUserModel;
class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function show()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page');
        }
        $majorGroupmodel= new MajorGroupModel();
        $majorGroupData=$majorGroupmodel->where('status','1')->findAll();
        $session = session();

        //echo '<pre>';print_r($session);die;
        $user = $session->get('me');
        //echo '<pre>';print_r($user);die;
        return view('home', ['majorGroupData' => $majorGroupData,'user' => $user]);
    }

    function login(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to('/survey');;
        }
        return view('auth/fe/login');
    }

    function register(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to('/survey');;
        }
        return view('auth/fe/register');
    }

    function registerProcess(){
        $session = session();
        $validationRules = [
            'name' => 'required|min_length[3]',
            'user_type' => 'required',
            'email' => [
                'rules' => 'required|valid_email|is_unique[survey_users.email]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please provide a valid email address.',
                    'is_unique' => 'This email is already registered.'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 5 characters long.'
                ]
            ],
           'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Please confirm your password.',
                    'matches' => 'Password confirmation does not match the password.'
                ]
            ]
        ];

        // Run validation
        if (!$this->validate($validationRules)) {
            // If validation fails, return to the login page with errors
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
            //return view('auth/fe/register',['validation' => $this->validator]);
        }

        $SurveyUserModel = new SurveyUserModel();
         $data = [
            'name'=> $this->request->getPost('name'),
            'phone'=> $this->request->getPost('phone'),
             'email' => $this->request->getPost('email'),
             'address' => $this->request->getPost('address'),
             'user_type' => $this->request->getPost('user_type'),
             'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
             'status' => 1
         ];
         $SurveyUserModel->save($data);
 
         $session->setFlashdata('success', 'Registration successful! You can now log in.');
         return redirect()->to('/login');
    }

    function loginProcess(){
        $session = session();
        $model = new SurveyUserModel();

         // Define validation rules
         $validationRules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[5]'
        ];

        // Run validation
        if (!$this->validate($validationRules)) {
            // If validation fails, return to the login page with errors
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();
        //echo '<pre>';print_r($user);die;
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session->set('isLoggedIn', true);
                $session->set('userId', $user['id']);
                $session->set('me', $user);
                return redirect()->to('/survey'); // Redirect to your CRUD or dashboard page
            } else {
                $session->setFlashdata('error', 'Invalid Password');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('error', 'Email not found');
            return redirect()->back();
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
