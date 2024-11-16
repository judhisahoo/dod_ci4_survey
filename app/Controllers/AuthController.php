<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        $data['title']="login";
        return view('auth/login',$data);
    }

    public function register()
    {
        $data['title']="Create an Account";
        return view('auth/register',$data);
    }

    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();

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

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session->set('isLoggedIn', true);
                $session->set('userId', $user['id']);
                return redirect()->to('/adminpanel'); // Redirect to your CRUD or dashboard page
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
        return redirect()->to('/admin-login');
    }


    public function registerProcess()
    {
        $session = session();
        $model = new UserModel();

         // Define validation rules
         $validationRules = [
            'fname' => 'required|min_length[3]|alpha|trim',
            'lname' => 'required|min_length[3]|alpha|trim',
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
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
        }

         // If validation passes, save the user
         $model = new UserModel();
         $data = [
            'fname'=> $this->request->getPost('fname'),
            'lname'=> $this->request->getPost('lname'),
             'email' => $this->request->getPost('email'),
             'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
             'status' => 1
         ];
         $model->save($data);
 
         $session->setFlashdata('success', 'Registration successful! You can now log in.');
         return redirect()->to('/admin-login');
    }
}
