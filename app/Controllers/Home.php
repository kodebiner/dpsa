<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = $this->data;
        $data['title']          =  lang('Global.titleDashboard');
        $data['description']    =  lang('Global.dashboardDescription');

        return view('layout', $data);
    }

}
