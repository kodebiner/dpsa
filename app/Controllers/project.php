<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
// use App\Models\ProjectModel;


class ProjectTemp extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('users');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
        $pager          = \Config\Services::pager();
    }

    public function index()
    {
        // Find Model
        $ProjectModel = new ProjectModel;
        $CompanyModel = new CompanyModel();
        $company = $CompanyModel->where('status !=', "0")->find();
        
        $projects   = $ProjectModel->paginate(10, 'projects');
        $uids = array();
        foreach ($projects as $project) {
            $uids[] = $project['clientid'];
        }

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
            $parentid[] = [
                'id' => $user->id,
                'name' => $user->username,
            ];
        }

        $data = $this->data;
        $data['title']          = lang('Global.titleProject');
        $data['description']    = lang('Global.projectDescription');
        $data['clients']        = $query->getResultArray();
        $data['projects']       = $projects;
        $data['company']        = $company;
        $data['parent']         = $parentid;
        $data['pager']          = $ProjectModel->pager;

        return view('projectTemp', $data);
    }

    public function create()
    {
        // Calling Model
        $ProjectModel = new ProjectModel;

        // initialize
        $input  = $this->request->getPost();
        $time   = time();
        $project = [
            'name'          => $input['name'],
            'brief'         => $input['brief'],
            'clientid'      => $input['company'],
            'status'        => $input['status'],
            'production'    => $input['qty'],
            'created_at'    => date('Y-m-d H:i:s',$time),
        ];
        $ProjectModel->save($project);

        return redirect()->to('project')->with('message', "Data berhasil di simpan.");
    }

    public function update($id)
    {
        // Calling Model
        $ProjectModel = new ProjectModel;

        // initialize
        $input = $this->request->getPost();
        $pro = $ProjectModel->find($id);
        $time   = time();

        if (empty($input['client'])) {
            $client = $pro['clientid'];
        } else {
            $client = $input['client'];
        }

        if (empty($input['status'])) {
            $status = $pro['status'];
        } else {
            $status = $input['status'];
        }

        $project = [
            'id'            => $id,
            'name'          => $input['name'],
            'brief'         => $input['brief'],
            'clientid'      => $client,
            'status'        => $status,
            'production'    => $input['qty'],
            'updated_at'    => date('Y-m-d H:i:s',$time),
        ];
        $ProjectModel->save($project);

        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function delete($id)
    {
        // Calling Model
        $ProjectModel = new ProjectModel;

        // Soft Delete Project
        $project = $ProjectModel->find($id);
        $time   = time();
        $data   = [
            'id'            => $id,
            'deleted_at'    => date('Y-m-d H:i:s',$time),
        ];
        $ProjectModel->save($data);

        return redirect()->to('project')->with('massage', lang('Global.deleted'));
    }

    public function approve()
    {
    }
}
