<?php

namespace App\Controllers;

use App\Models\BarModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\MdlModel;
use App\Models\RabModel;


class Rab extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $config;


    public function index()
    {
        // Find Model
        $ProjectModel   = new ProjectModel;
        $UserModel      = new UserModel;
        $MdlModel       = new MdlModel;
        $RabModel       = new RabModel;

        // Populating Data
        $projects   = $ProjectModel->findAll();
        $users      = $UserModel->findAll();
        $rabs       = $RabModel->findAll();
        
        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['clients']        =   $users;
        $data['projects']       =   $projects;
        $data['mdls']           =   $MdlModel->findAll();
        $data['rabs']           =   $RabModel->findAll();

        return view('rab', $data);
    }

    public function create()
    {
        $RabModel = new RabModel;

        $input  =   $this->request->getPost();

        $project = [
            'qty'               => $input['qty'],
            'qty_deliver'       => $input['qtydeliv'],
            'qty_complete'      => $input['qtycomp'],
            'projectid'         => $input['pro'],
            'mdlid'             => $input['mdl'],
        ];
        $RabModel->save($project);
        
        return redirect()->to('rab')->with('massage', lang('Global.saved'));
    }

    public function update($id)
    {
        $ProjectModel = new ProjectModel;

        // initialisation
        $input = $this->request->getPost();
        $time   = date('Y-m-d H:i:s');

        $project = [
            'id'            => $id,
            'name'          => $input['name'],
            'brief'         => $input['brief'],
            'clientid'      => $input['client'],
            'updated_at'    => $time,
        ];
        $ProjectModel->save($project);
        
        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function delete(){

    }

    public function approve(){

    }

}
