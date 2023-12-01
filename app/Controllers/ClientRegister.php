<?php

namespace App\Controllers;

use App\Models\UserModel;

class ClientRegister extends BaseController
{
    protected $auth;
    protected $config;
    protected $request;
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth   = service('authentication');
    }

    public function index()
    {
        $data                   = $this->data;
        $data['title']          = 'Pendaftaran Akun Client';
        $data['description']    = '';

        return view('Views/Client/registerindex', $data);
    }
}