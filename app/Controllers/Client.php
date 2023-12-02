<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\ProjectTempModel;

class Client extends BaseController
{

    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('company');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $CompanyModel              = new CompanyModel();

            // Populating data
            $companys = $CompanyModel->findAll();

            // Initialize
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('company.rsname', $input['search']);
                $this->builder->orLike('company.ptname', $input['search']);
                $this->builder->orLike('company.address', $input['search']);
            }
            if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                if ($input['rolesearch'] === "1") {
                    $this->builder->where('company.parentid', "0");
                } elseif ($input['rolesearch'] === "2") {
                    $this->builder->where('company.parentid !=', "0");
                }
            }
            $this->builder->select('company.id as id, company.rsname as rs, company.ptname as pt, company.npwp as npwp, company.address as address, company.phone as phone, company.parentid as parent, company.status as status');
            $query = $this->builder->get($perpage, $offset)->getResultArray();

            $total = $this->builder->countAllResults();

            $parentid = [];
            foreach ($companys as $company) {
                $parentid[] = [
                    'id' => $company['id'],
                    'name' => $company['rsname'],
                ];
            }

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = lang('Global.clientList');
            $data['description']    = lang('Global.clientListDesc');
            $data['roles']          = $CompanyModel->findAll();
            $data['company']        = $query;
            $data['parent']         = $parentid;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;

            return view('client', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) {

            // Calling Models
            $CompanyModel = new CompanyModel();

            // Initialize
            $input = $this->request->getPost();

            $data = [
                'rsname'    => $input['rsname'],
                'ptname'    => $input['ptname'],
                'address'   => $input['address'],
                'npwp'      => $input['npwp'],
                'phone'     => $input['notelp'],
                'status'    => '1',
                'parentid'  => $input['parent'],
            ];
            $CompanyModel->save($data);

            return redirect()->to('client')->with('massage', 'Data Berhasil Disimpan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) {
            // Calling Models
            $CompanyModel = new CompanyModel();

            // Initialize
            $input = $this->request->getPost();

            $data = [
                'id'        => $id,
                'rsname'    => $input['rsname'],
                'ptname'    => $input['ptname'],
                'address'   => $input['address'],
                'npwp'      => $input['npwp'],
                'phone'     => $input['notelp'],
                'status'    => $input['status'],
                'parentid'  => $input['parent'],
            ];
            $CompanyModel->save($data);
            return redirect()->to('client')->with('massage', 'Data Berhasil Disimpan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {

            // Calling Model
            $CompanyModel = new CompanyModel();
            $ProjectModel = new ProjectTempModel();

            // Getting Data 
            $company = $CompanyModel->find($id);
            $companys = $CompanyModel->findAll();
            $Project = $ProjectModel->where('clientid', $id)->find();

            // remove project
            if (!empty($Project)) {
                foreach ($Project as $project) {
                    $ProjectModel->delete($project['id']);
                }
            }

            // Remove Parent
            $parid = [];
            foreach ($companys as $comp) {
                if ($comp['parentid'] === $id) {
                    $parid[] = $comp['id'];
                }
            }

            if (!empty($parid)) {
                foreach ($companys as $comps) {
                    foreach ($parid as $pid) {
                        if ($comps['id'] === $pid) {
                            $data = [
                                'id'        => $pid,
                                'parentid'  => 0,
                            ];
                            $CompanyModel->save($data);
                        }
                    }
                }
            }

            // Remove Data Client
            $CompanyModel->delete($company['id']);

            // Redirect to View
            return redirect()->to('client')->with('massage', lang('Global.deleted'));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
