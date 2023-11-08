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
        $this->builder->where('deleted_at', null);
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id !=', $this->data['uid']);
        $this->builder->where('auth_groups.name', 'client pusat');
        $this->builder->orWhere('auth_groups.name', 'client cabang');
        $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
        $query =   $this->builder->get();

        // Data Quantiti
        $qty = $bars['qty'];

        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['qty']            =   $qty;
        $data['clients']        =   $query->getResultArray();
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

    public function delete($id)
    {

        $ProjectModel = new ProjectModel;
        $RabModel = new RabModel;

        // Delete Rab Project
        $datarab = $RabModel->findAll();
        foreach ($datarab as $rabs) {
            if ($rabs['projectid'] === $id) {
                $rabdata[] = $rabs['id'];
            }
        }
        if (!empty($rabdata)) {
            $RabModel->delete($rabdata);
        }

        // Delete Project
        $project = $ProjectModel->find($id);
        $ProjectModel->delete($project);

        return redirect()->to('project')->with('massage', lang('Global.deleted'));
    }

    public function approve()
    {
    }
}
