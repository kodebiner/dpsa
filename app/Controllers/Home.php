<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {

        $data = $this->data;
        $data['title']          =   lang('Auth.Dashboard');
        $data['description']    =   lang('Auth.dashboardDescription');

        return view('layout', $data);
    }
}
