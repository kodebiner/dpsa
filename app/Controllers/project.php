<?php

namespace App\Controllers;

use App\Models\BarModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\MdlModel;
use App\Models\RabModel;


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
        $MdlModel       = new MdlModel;
        $RabModel       = new RabModel;

        // Populating Data
        $bars       = $BarModel->find(1);
        $projects   = $ProjectModel->findAll();
        $users      = $UserModel->findAll();

        // Data Quantiti
        $qty = $bars['qty'];
        
        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['qty']            =   $qty;
        $data['clients']        =   $users;
        $data['projects']       =   $projects;
        $data['mdls']           =   $MdlModel->findAll();
        $data['rabs']           =   $RabModel->findAll();

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
        
        return redirect()->to('project')->with('massage', lang('Global.saved'));
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

    public function delete($id){

        $ProjectModel = new ProjectModel;
        $RabModel = new RabModel;

        // Delete Rab Project
        $datarab = $RabModel->findAll();
        foreach ($datarab as $rabs){
            if($rabs['projectid'] === $id){
                $rabdata [] = $rabs['id'];
            }
        }
        if(!empty($rabdata)){
            $RabModel->delete($rabdata);
        }

        // Delete Project
        $project = $ProjectModel->find($id);
        $ProjectModel->delete($project);

        return redirect()->to('project')->with('massage', lang('Global.deleted'));
    }

    public function approve(){

    }

}
