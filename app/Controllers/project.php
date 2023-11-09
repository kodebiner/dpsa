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
        $projects       = $ProjectModel->find();

        $this->builder->where('deleted_at', null);
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id !=', $this->data['uid']);
        $this->builder->where('auth_groups.name', 'client pusat');
        $this->builder->orWhere('auth_groups.name', 'client cabang');
        $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
        $query =   $this->builder->get();

        $users = $query->getResult();
        $parentid = [];
        foreach ($users as $user) {
            if ($user->parent != "") {
                $parentid[] = $user->parent;
            }
        }

        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['clients']        =   $query->getResultArray();
        $data['projects']       =   $projects;
        $data['parent']         =   $parentid;

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
            'status'        => $input['status'],
            'production'    => $input['qty'],
        ];
        $ProjectModel->save($project);

        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function update($id)
    {
        $ProjectModel = new ProjectModel;

        // initialisation
        $input = $this->request->getPost();
        $pro = $ProjectModel->find($id);

        if (empty($input['client'])){
            $client = $pro['clientid'];
        }else{
            $client = $input['client'];
        }

        if (empty($input['status'])){
            $status = $pro['status'];
        }else{
            $status = $input['status'];
        }

        $project = [
            'id'            => $id,
            'name'          => $input['name'],
            'brief'         => $input['brief'],
            'clientid'      => $client,
            'status'        => $status,
            'production'    => $input['qty'],
        ];
        $ProjectModel->save($project);

        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function delete($id)
    {
        $ProjectModel = new ProjectModel;
        // Delete Project
        $project = $ProjectModel->find($id);
        $ProjectModel->delete($project);

        return redirect()->to('project')->with('massage', lang('Global.deleted'));
    }

    public function approve()
    {
    }
}
