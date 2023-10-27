<?php

namespace App\Controllers;

use App\Models\BarModel;


class Home extends BaseController
{
    public function index(): string
    {

        // Find Model
        $BarModel = new BarModel;

        // Populating Data
        $bars = $BarModel->find(1);

        // Data Quantiti
        $qty = $bars['qty'];
        
        $data = $this->data;
        $data['title']          =  lang('Global.titleDashboard');
        $data['description']    =  lang('Global.dashboardDescription');
        $data['qty']            = $qty;
        return view('bar', $data);
    }

}
