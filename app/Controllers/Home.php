<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Models\ProjectModel;


class Home extends BaseController
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
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Models
            $UserModel = new UserModel();
            $ProjectModel = new ProjectModel();
            $CompanyModel   = new CompanyModel();

            // Populating data
            $input = $this->request->getGet();

            // parsing data to view
            $data           = $this->data;
            $data['input']  = $input;

            // Variable for pagination
            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if (($this->data['role'] === 'superuser') || ($this->data['role'] === 'owner')) {

                // New Client Data 
                $clients = $this->db->table('company')->where('deleted_at',null);
                $clients->select('company.id as id, company.rsname as rsname, company.ptname as ptname, company.address as address');
                $clients->where('company.status !=', '0');
                if (isset($input['search']) && !empty($input['search'])) {
                    $clients->like('company.name', $input['search']);
                    $clients->orLike('company.name', $input['search']);
                }
                $query = $clients->get($perpage, $offset)->getResultArray();

                if (isset($input['search']) && !empty($input['search'])) {
                    $total = $clients
                        ->where('company.status !=', '0')
                        ->like('company.name', $input['search'])
                        ->orLike('company.name', $input['search'])
                        ->countAllResults();
                } else {
                    $total = $clients
                        ->where('company.status !=', '0')
                        ->countAllResults();
                }

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['clients']        = $query;
                $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');

                return view('dashboard-superuser', $data);
            } elseif ($this->data['role'] === 'client pusat') {

                // New Client Pusat Function
                $clients = array();

                if (isset($input['search']) && !empty($input['search'])) {
                    $branches = $CompanyModel->where('parentid', $this->data['parentid'])->where('deleted_at',null)->like('rsname', $input['search'])->orLike('ptname', $input['search'])->find();
                } else {
                    $projectpusat = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at',null)->find();
                    if (!empty($projectpusat)) {
                        $company = $CompanyModel->where('id', $this->data['parentid'])->first();
                        $clients[] = [
                            'id'        => $company['id'],
                            'rsname'    => $company['rsname'],
                        ];
                    }
                    $branches = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at',null)->find();
                }

                // dd($this->data['parentid']);
                foreach ($branches as $branch) {
                    $clients[] = [
                        'id'        => $branch['id'],
                        'rsname'    => $branch['rsname'],
                    ];
                }

                $total = count($clients);

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['clients']        = array_slice($clients, $offset, $perpage);
                $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');

                return view('dashboard-superuser', $data);
            } elseif ($this->data['role'] === 'client cabang') {
                // Client Cabang function

                if (isset($input['search']) && !empty($input['search'])) {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at',null)->like('name', $input['search'])->paginate($perpage, 'projects');
                } else {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at',null)->paginate($perpage, 'projects');
                }

                $company = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at',null)->find();
                
                $clients = array();
                foreach ($company as $comp){
                    if (!empty($clients)){
                        $clients[] = [
                            'id'  => $comp['id'],
                            'rsname' => $comp['rsname'],
                        ];
                    }
                }

                // Parsing Data to View
                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['client']         = $clients;
                $data['projects']       = $projects;
                $data['pager']          = $pager->links('projects', 'uikit_full');

                return view('dashboard', $data);
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function clientdashboard($id)
    {
        if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) {

            // Calling Services
            $pager = \Config\Services::pager();
            // Calling models
            $ProjectModel = new ProjectModel;
            $CompanyModel   = new CompanyModel();

            // Populating Data
            $input = $this->request->getGet();

            if (isset($input['perpage']) && !empty($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $client = $CompanyModel->find($id);

            if (isset($input['search']) && !empty($input['search'])) {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at',null)->like('name', $input['search'])->paginate($perpage, 'projects');
            } else {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at',null)->paginate($perpage, 'projects');
            }

            // Parsing Data to View
            $data                   = $this->data;
            $data['title']          = lang('Global.titleDashboard');
            $data['description']    = lang('Global.dashboardDescription');
            $data['client']         = $client;
            $data['projects']       = $projects;
            $data['pager']          = $pager->links('projects', 'uikit_full');

            return view('dashboard', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function installation()
    {
        // Database Migration
        command('migrate --all');

        // Calling Libraries and Services
        $authorize = service('authorization');

        // Calling Models
        $UserModel = new UserModel();

        // Remove All Users
        $users = $UserModel->findAll();
        if (!empty($users)) {
            $uids = array();
            foreach ($users as $user) {
                $uids[] = $user->id;
            }
            $UserModel->delete($uids);
            $UserModel->purgeDeleted();
        }

        // Remove Old Permission
        $permissions = $authorize->permissions();
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                $authorize->deletePermission($permission['id']);
            }
        }

        // Creating Permissions
        $authorize->createPermission('client.read', 'Melihat daftar Proyek.');
        $authorize->createPermission('client.auth.holding', 'Otorisasi pusat.');
        $authorize->createPermission('client.auth.branch', 'Otorisasi cabang.');
        $authorize->createPermission('admin.user.read', 'Melihat daftar pengguna.');
        $authorize->createPermission('admin.user.create', 'Membuat pengguna baru.');
        $authorize->createPermission('admin.user.edit', 'Merubah data pengguna.');
        $authorize->createPermission('admin.user.delete', 'Menghapus pengguna.');
        $authorize->createPermission('admin.mdl.read', 'Melihat daftar MDL.');
        $authorize->createPermission('admin.mdl.create', 'Membuat data MDL baru.');
        $authorize->createPermission('admin.mdl.edit', 'Merubah data MDL.');
        $authorize->createPermission('admin.mdl.delete', 'Menghapus data MDL.');
        $authorize->createPermission('admin.project.read', 'Melihat daftar proyek.');
        $authorize->createPermission('admin.project.delete', 'Menghapus proyek.');
        $authorize->createPermission('admin.project.create', 'Membuat proyek baru.');
        $authorize->createPermission('marketing.project.edit', 'Merubah data proyek.');
        $authorize->createPermission('design.project.edit', 'Merubah data design proyek.');
        $authorize->createPermission('production.project.edit', 'Merubah data produksi proyek.');

        // Remove Old Groups
        $groups = $authorize->groups();
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $authorize->deleteGroup($group->id);
            }
        }

        // Creating Prebuild Groups
        $authorize->createGroup('superuser', 'Site Administrators with god-like powers.');
        $authorize->createGroup('owner', 'Pemilik.');
        $authorize->createGroup('admin', 'Admin.');
        $authorize->createGroup('marketing', 'Divisi Marketing.');
        $authorize->createGroup('design', 'Divisi Design.');
        $authorize->createGroup('production', 'Divisi Produksi.');
        $authorize->createGroup('client pusat', 'Client Pusat.');
        $authorize->createGroup('client cabang', 'Client Cabang.');
        $authorize->createGroup('guests', 'Unauthorized users.');

        // Prebuild permissions
        $authorize->addPermissionToGroup('client.read', 'superuser');
        $authorize->addPermissionToGroup('client.auth.holding', 'superuser');
        $authorize->addPermissionToGroup('client.auth.branch', 'superuser');
        $authorize->addPermissionToGroup('admin.user.read', 'superuser');
        $authorize->addPermissionToGroup('admin.user.create', 'superuser');
        $authorize->addPermissionToGroup('admin.user.edit', 'superuser');
        $authorize->addPermissionToGroup('admin.user.delete', 'superuser');
        $authorize->addPermissionToGroup('admin.mdl.read', 'superuser');
        $authorize->addPermissionToGroup('admin.mdl.create', 'superuser');
        $authorize->addPermissionToGroup('admin.mdl.edit', 'superuser');
        $authorize->addPermissionToGroup('admin.mdl.delete', 'superuser');
        $authorize->addPermissionToGroup('admin.project.read', 'superuser');
        $authorize->addPermissionToGroup('admin.project.create', 'superuser');
        $authorize->addPermissionToGroup('admin.project.delete', 'superuser');
        $authorize->addPermissionToGroup('marketing.project.edit', 'superuser');
        $authorize->addPermissionToGroup('design.project.edit', 'superuser');
        $authorize->addPermissionToGroup('production.project.edit', 'superuser');
        $authorize->addPermissionToGroup('admin.user.read', 'owner');
        $authorize->addPermissionToGroup('admin.user.create', 'owner');
        $authorize->addPermissionToGroup('admin.user.edit', 'owner');
        $authorize->addPermissionToGroup('admin.user.delete', 'owner');
        $authorize->addPermissionToGroup('admin.mdl.read', 'owner');
        $authorize->addPermissionToGroup('admin.mdl.create', 'owner');
        $authorize->addPermissionToGroup('admin.mdl.edit', 'owner');
        $authorize->addPermissionToGroup('admin.mdl.delete', 'owner');
        $authorize->addPermissionToGroup('admin.project.read', 'owner');
        $authorize->addPermissionToGroup('admin.project.create', 'owner');
        $authorize->addPermissionToGroup('admin.project.delete', 'owner');
        $authorize->addPermissionToGroup('marketing.project.edit', 'owner');
        $authorize->addPermissionToGroup('design.project.edit', 'owner');
        $authorize->addPermissionToGroup('production.project.edit', 'owner');
        $authorize->addPermissionToGroup('admin.user.read', 'admin');
        $authorize->addPermissionToGroup('admin.user.create', 'admin');
        $authorize->addPermissionToGroup('admin.user.edit', 'admin');
        $authorize->addPermissionToGroup('admin.user.delete', 'admin');
        $authorize->addPermissionToGroup('admin.mdl.read', 'admin');
        $authorize->addPermissionToGroup('admin.mdl.create', 'admin');
        $authorize->addPermissionToGroup('admin.mdl.edit', 'admin');
        $authorize->addPermissionToGroup('admin.mdl.delete', 'admin');
        $authorize->addPermissionToGroup('admin.project.read', 'admin');
        $authorize->addPermissionToGroup('admin.project.create', 'admin');
        $authorize->addPermissionToGroup('admin.project.delete', 'admin');
        $authorize->addPermissionToGroup('admin.mdl.read', 'marketing');
        $authorize->addPermissionToGroup('admin.mdl.create', 'marketing');
        $authorize->addPermissionToGroup('admin.mdl.edit', 'marketing');
        $authorize->addPermissionToGroup('admin.mdl.delete', 'marketing');
        $authorize->addPermissionToGroup('admin.project.read', 'marketing');
        $authorize->addPermissionToGroup('admin.project.create', 'marketing');
        $authorize->addPermissionToGroup('admin.project.delete', 'marketing');
        $authorize->addPermissionToGroup('marketing.project.edit', 'marketing');
        $authorize->addPermissionToGroup('admin.project.read', 'design');
        $authorize->addPermissionToGroup('design.project.edit', 'design');
        $authorize->addPermissionToGroup('admin.project.read', 'production');
        $authorize->addPermissionToGroup('production.project.edit', 'production');
        $authorize->addPermissionToGroup('client.read', 'client pusat');
        $authorize->addPermissionToGroup('client.auth.holding', 'client pusat');
        $authorize->addPermissionToGroup('client.read', 'client cabang');
        $authorize->addPermissionToGroup('client.auth.branch', 'client cabang');

        // Parsing Data to View
        $data = $this->data;
        $data['title']          =  'Installation';
        $data['description']    =  'Installation';

        // Rendering View
        return view('installation', $data);
    }

    public function attempinstallation()
    {
        // Calling Libraries and Services
        $authorize = service('authorization');

        // Calling Models & Entities
        $newUser    = new User();
        $UserModel  = new UserModel();

        // Populating Data
        $input = $this->request->getPost();

        // Validating User Data
        $rules = [
            'firstname' => 'required',
            'username'  => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Vaalidating Password
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // New User Data
        $newUser->username  = $input['username'];
        $newUser->email     = $input['email'];
        $newUser->firstname = $input['firstname'];
        $newUser->lastname  = $input['lastname'];
        $newUser->password  = $input['password'];
        $newUser->active    = 1;

        // Save new user
        $UserModel->insert($newUser);

        // Get new user id
        $userId = $UserModel->getInsertID();

        // Adding new user to group
        $authorize->addUserToGroup($userId, 'superuser');

        // Redirect to Login
        return redirect()->to('login')->with('message', 'Aplikasi berhasil terpasang. Silahkan melakukan Login');
    }

    public function logedin()
    {
        $data = $this->data;

        if (($data['role'] === 'client pusat') || ($data['role'] === 'client cabang')) {
            return redirect()->to('');
        } else {
            return redirect()->to('project');
        }
    }

    public function trial()
    {
        $authorize = service('authorization');
        $authorize->removeUserFromGroup(3, 9);
    }

    public function information()
    {
        phpinfo();
    }
}
