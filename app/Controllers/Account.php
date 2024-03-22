<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogModel;

class Account extends BaseController
{
    protected $auth;
    protected $config;
    
    protected $data;
    protected $session;

    public function __construct()
    {
        // Most services in this controller require
        // the session to be started - so fire it up!
        $this->session = service('session');

        $this->config = config('Auth');
        $this->auth   = service('authentication');
    }

    public function index()
    {
        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = lang('Global.userProfile');
        $data['description']    = lang('Global.userProfileDesc');

        return view('Views/account', $data);
    }

    public function updateaccount()
    {
        // Calling Models
        $UserModel = new UserModel();
        $LogModel = new LogModel();
        
        // Calling Entities
        $updateUser = new \App\Entities\User();

        // Populating data
        $input = $this->request->getPost();

        // Validation basic data
        $rule = [
            'id'            => 'max_length[19]|is_natural_no_zero',
            'username'      => 'required|max_length[255]|is_unique[users.username,id,{id}]',
            'email'         => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
            'firstname'     => 'required|max_length[255]',
            'lastname'      => 'required|max_length[255]',
        ];
        if (! $this->validate($rule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //Parsing User Basic Data
        $updateUser->id         = $this->data['uid'];
        $updateUser->username   = $input['username'];
        $updateUser->email      = $input['email'];
        $updateUser->firstname  = $input['firstname'];
        $updateUser->lastname   = $input['lastname'];

        // Validating new password
        if (!empty($input['newPass'])) {
            $rules = [
                // 'id'            => 'required',
                'oldPass'       => 'required',
                'newPass'       => 'required',
                'newPassConf'   => 'required|matches[newPass]'
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Determining credential variable
            $login      = $input['username'];
            $password   = $input['oldPass'];

            // Determine credential type
            $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Validating credential
            if (! $this->auth->attempt([$type => $login, 'password' => $password])) {
                return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
            }

            // Parsing New Password Variable
            $updateUser->password   = $input['newPass'];
            $updateUser->reset_at   = date('Y-m-d H:i:s');

        }

        $newdata = [
            'id' =>  $this->data['uid'],
            'username' => $input['username'],
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
        ];
        
        // Saving user data
        $UserModel->save($updateUser);

        // Record Log
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Merubah data akun sendiri']);

        // redirectiong
        return redirect()->back()->with('message', 'Data berhasil disimpan');
    }
}