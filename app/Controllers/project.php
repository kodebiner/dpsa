<?php

namespace App\Controllers;

use App\Models\BarModel;
use App\Models\ProjectModel;
use App\Models\UserModel;


class Project extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('users');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');

        
    }

    public function index()
    {
        // Find Model
        $BarModel       = new BarModel;
        $ProjectModel   = new ProjectModel;
        $UserModel      = new UserModel;

        // Populating Data
        $bars       = $BarModel->find(1);
        $users      = $UserModel->findAll();

        // Data Quantiti
        $qty = $bars['qty'];
        
        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['qty']            =   $qty;
        $data['clients']        =   $users;

        return view('project', $data);
    }

    public function create()
    {
        $ProjectModel = new ProjectModel;

        $input  =   $this->request->getPost();
        $time   =   date('Y-m-d H:i:s');

        $project = [
            'name'          => $input['name'],
            'brief'         => $input['brief'],
            'clientid'      => $input['client'],
            'created_at'    => $time,
        ];
        $ProjectModel->save($project);
        
        $idPro = getInsertId($ProjectModel);

        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function update($id)
    {
        $ProjectModel = new ProyekModel;
    }

    public function delete(){

    }

    public function approve(){

    }

}
