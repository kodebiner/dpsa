<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CompanyModel;
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
            $this->builder->select('company.id as id, company.rsname as rs, company.ptname as pt, company.address as address, company.phone as phone, company.parentid as parent, company.status as status');
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
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    // public function delete($id)
    // {
    //     if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
    //         $authorize = service('authorization');
    //         $usersModel = new UserModel();
    //         $GroupUserModel = new GroupUserModel();

    //         $user = $usersModel->find($id);
    //         $groups = $GroupUserModel->where('user_id', $id)->find();
    //         $input = $this->request->getPost();

    //         $groupid = "";
    //         foreach ($groups as $group) {
    //             $groupid = $group['group_id'];
    //         }

    //         $data['users'] = $usersModel->find($id);
    //         if (empty($data)) {
    //             throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Password Tidak Ditemukan !');
    //         }

    //         // remove from user group
    //         $authorize->removeUserFromGroup($id, $groupid);

    //         $usersModel->update($id, [
    //             'active'   => '0'
    //         ]);
    //         $usersModel->delete($id);

    //         return redirect()->to('users')->with('massage', lang('Global.deleted'));
    //     } else {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }
    // }

    // public function deleteclient($id)
    // {
    //     if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
    //         $authorize = service('authorization');

    //         // Calling Model
    //         $usersModel = new UserModel();
    //         $GroupUserModel = new GroupUserModel();
    //         // $ProjectModel = new ProjectModel;
    //         $ProjectTempModel = new ProjectTempModel;

    //         $Project = $ProjectTempModel->where('clientid', $id)->find();

    //         // remove project
    //         if (!empty($Project)) {
    //             foreach ($Project as $project) {
    //                 $ProjectTempModel->delete($project['id']);
    //             }
    //         }

    //         $users = $usersModel->findAll();

    //         // Remove Parent
    //         $parid = [];
    //         foreach ($users as $child) {
    //             if ($child->parentid === $id) {
    //                 $parid = [
    //                     'id' => $child->id,
    //                 ];
    //             }
    //         }

    //         if (!empty($parid)) {
    //             foreach ($users as $user) {
    //                 if ($user->id === $parid['id']) {
    //                     $data = [
    //                         'id'        => $parid['id'],
    //                         'parentid'  => 0,
    //                     ];
    //                     $usersModel->save($data);
    //                 }
    //             }
    //         }

    //         $groups = $GroupUserModel->where('user_id', $id)->find();

    //         $groupid = "";
    //         foreach ($groups as $group) {
    //             $groupid = $group['group_id'];
    //         }

    //         $data['users'] = $usersModel->find($id);
    //         if (empty($data)) {
    //             throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Password Tidak Ditemukan !');
    //         }

    //         // remove from user group
    //         $authorize->removeUserFromGroup($id, $groupid);

    //         $usersModel->update($id, [
    //             'active'   => '0'
    //         ]);
    //         $usersModel->delete($id);

    //         return redirect()->to('users/client')->with('massage', lang('Global.deleted'));
    //     } else {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }
    // }

    // public function accesscontrol()
    // {
    //     if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
    //         $authorize = service('authorization');
    //         // Calling Libraries and Services
    //         $authorize = $auth = service('authorization');

    //         // Populating Data
    //         $groups = $authorize->groups();
    //         $permission = $authorize->permissions();

    //         // permanent groups
    //         $grouparr = ['superuser', 'admin', 'owner', 'marketing', 'design', 'production', 'client pusat', 'client cabang'];

    //         // Parsing data to view
    //         $data                   = $this->data;
    //         $data['title']          = lang('Global.employeeList');
    //         $data['description']    = lang('Global.employeeListDesc');
    //         $data['groups']         = $groups;
    //         $data['permissions']    = $permission;
    //         $data['GroupModel']     = new GroupModel();
    //         $data['grouparr']       = $grouparr;

    //         return view('accesscontrol', $data);
    //     } else {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }
    // }

    // public function createaccess()
    // {
    //     if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) {
    //         $authorize = service('authorization');
    //         // Calling Libraries and Services
    //         $authorize = $auth = service('authorization');

    //         // Populating Data
    //         $groups = $authorize->groups();

    //         // Initialize
    //         $input = $this->request->getPost();

    //         // Create Group
    //         $id = $authorize->createGroup($input['group'], $input['description']);

    //         $permission = [];
    //         foreach ($input['permission'] as $key => $permit) {
    //             $permission[] = $permit;
    //         }

    //         foreach ($permission as $permissionid) {
    //             $authorize->addPermissionToGroup($permissionid, $id);
    //         }

    //         return redirect()->to('users/access-control')->with('message', lang('Global.saved'));
    //     } else {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }
    // }

    // public function updateaccess($id)
    // {

    //     // Caling Libraries
    //     $authorize = $auth = service('authorization');

    //     // Populating Data
    //     $groups = $authorize->groups();
    //     $GroupModel = new GroupModel;
    //     $Grouppermissions = $GroupModel->getPermissionsForGroup($id);

    //     // Initialize
    //     $input = $this->request->getPost();
    //     $group = $authorize->group($id);

    //     // Update Group
    //     $datagroup = [
    //         'name'        => $input['name'],
    //         'description' => $input['description'],
    //     ];
    //     // dd($datagroup);

    //     $cek = $authorize->updateGroup($id, $datagroup['name'], $datagroup['description']);

    //     // Get Group Permissions
    //     $permissiongroup = [];
    //     foreach ($Grouppermissions as $Grouppermission) {
    //         $permissiongroup[] = $Grouppermission->id;
    //     }

    //     if (!empty($Grouppermissions)) {
    //         // Remove Permission From Group
    //         foreach ($permissiongroup as $permit => $permissionid) {
    //             $authorize->removePermissionFromGroup($permissionid, $id);
    //         }
    //     }

    //     if (!empty($input['permission'])) {
    //         // Add New Permissions
    //         $permission = [];
    //         foreach ($input['permission'] as $key => $permit) {
    //             $permission[] = $permit;
    //         }

    //         foreach ($permission as $permissionid) {
    //             $authorize->addPermissionToGroup($permissionid, $id);
    //         }
    //     }

    //     return redirect()->to('users/access-control')->with('message', lang('Global.saved'));
    // }

    // public function deleteaccess($id)
    // {
    //     // Caling Libraries
    //     $authorize = $auth = service('authorization');
    //     // Populating Data
    //     $groups = $authorize->groups();
    //     $GroupModel = new GroupModel;
    //     $Grouppermissions = $GroupModel->getPermissionsForGroup($id);

    //     // Get Group Permissions
    //     $permissiongroup = [];
    //     foreach ($Grouppermissions as $Grouppermission) {
    //         $permissiongroup[] = $Grouppermission->id;
    //     }


    //     // Remove Permissions 
    //     foreach ($permissiongroup as $permit => $permissionid) {
    //         $authorize->removePermissionFromGroup($permissionid, $id);
    //     }

    //     // Delete Group
    //     $authorize->deleteGroup($id);

    //     return redirect()->to('users/access-control')->with('message', lang('Global.deleted'));
    // }

    // public function client()
    // {
    //     if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
    //         // Calling Services
    //         $pager = \Config\Services::pager();

    //         // Calling Model
    //         $UserModel              = new UserModel();
    //         $GroupModel             = new GroupModel();
    //         $PermissionModel        = new PermissionModel();

    //         $users = $UserModel->findAll();
    //         // Populating data
    //         $input = $this->request->getGet();

    //         if (isset($input['perpage'])) {
    //             $perpage = $input['perpage'];
    //         } else {
    //             $perpage = 10;
    //         }

    //         $page = (@$_GET['page']) ? $_GET['page'] : 1;
    //         $offset = ($page - 1) * $perpage;

    //         $this->builder->where('deleted_at', null);
    //         $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
    //         $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
    //         $this->builder->where('users.id !=', $this->data['uid']);
    //         // $this->builder->where('auth_groups.name !=', 'superuser');
    //         // $this->builder->where('auth_groups.name !=', 'owner');
    //         // $this->builder->where('auth_groups.name !=', 'admin');
    //         // $this->builder->where('auth_groups.name !=', 'marketing');
    //         // $this->builder->where('auth_groups.name !=', 'design');
    //         // $this->builder->where('auth_groups.name !=', 'production');
    //         // $this->builder->where('auth_groups.name !=', 'guests');
    //         if (isset($input['search']) && !empty($input['search'])) {
    //             $this->builder->like('users.username', $input['search']);
    //             $this->builder->orLike('users.firstname', $input['search']);
    //             $this->builder->orLike('users.lastname', $input['search']);
    //         }
    //         if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
    //             $this->builder->where('auth_groups.id', $input['rolesearch']);
    //         }
    //         $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
    //         $query =   $this->builder->get($perpage, $offset)->getResult();

    //         $total = $this->builder
    //             ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
    //             ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
    //             ->where('users.id !=', $this->data['uid'])
    //             ->where('auth_groups.name', 'client pusat')
    //             ->orWhere('auth_groups.name', 'client cabang')
    //             ->countAllResults();

    //         $parentid = [];
    //         foreach ($users as $user) {
    //             $parentid[] = [
    //                 'id' => $user->id,
    //                 'name' => $user->username,
    //             ];
    //         }

    //         // Parsing data to view
    //         $data                   = $this->data;
    //         $data['title']          = lang('Global.clientList');
    //         $data['description']    = lang('Global.clientListDesc');
    //         $data['roles']          = $GroupModel->where('name', "client pusat")->orWhere('name', 'client cabang')->find();
    //         $data['users']          = $query;
    //         $data['parent']         = $parentid;
    //         $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
    //         $data['input']          = $input;

    //         return view('client', $data);
    //     } else {
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    //     }
    // }
}
