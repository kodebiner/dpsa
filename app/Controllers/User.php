<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\ProjectModel;
use App\Models\ProjectTempModel;

class User extends BaseController
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
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $GroupModel             = new GroupModel();
            $PermissionModel        = new PermissionModel();

            // Populating data
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('users.id !=', $this->data['uid']);
            $this->builder->where('auth_groups.name !=', 'superuser');
            $this->builder->where('auth_groups.name !=', 'owner');
            $this->builder->where('auth_groups.name !=', 'client pusat');
            $this->builder->where('auth_groups.name !=', 'client cabang');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('users.username', $input['search']);
                $this->builder->orLike('users.firstname', $input['search']);
                $this->builder->orLike('users.lastname', $input['search']);
            }
            if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                $this->builder->where('auth_groups.id', $input['rolesearch']);
            }
            $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, auth_groups.id as group_id, auth_groups.name as role');
            $query =   $this->builder->get($perpage, $offset)->getResult();
            
            $total = $this->builder
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->where('users.id !=', $this->data['uid'])
                    ->where('auth_groups.name !=', 'superuser')
                    ->where('auth_groups.name !=', 'owner')
                    ->where('auth_groups.name !=', 'client pusat')
                    ->where('auth_groups.name !=', 'client cabang')
                    ->countAllResults();

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = lang('Global.usersList');
            $data['description']    = lang('Global.usersListDesc');
            $data['roles']          = $GroupModel->where('name !=', 'client cabang')->where('name !=', 'client pusat')->find();
            $data['permissions']    = $PermissionModel->findAll();
            $data['users']          = $query;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;

            return view('users', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) {
            $authorize = service('authorization');

            // Calling Entities
            $newUser                = new \App\Entities\User();

            // Calling Model
            $UserModel = new UserModel();

            // Defining input
            $input = $this->request->getPost();

            // Validation basic form
            $rules = [
                'username'      => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
                'email'         => 'required|valid_email|is_unique[users.email]',
                'firstname'     => 'required',
                'lastname'      => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Validation password
            $rules = [
                'password'     => 'required|strong_password',
                'pass_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // New user data
            $newUser->username  = $input['username'];
            $newUser->email     = $input['email'];
            $newUser->firstname = $input['firstname'];
            $newUser->lastname  = $input['lastname'];
            $newUser->password  = $input['password'];
            $newUser->active    = 1;
            if (!empty($input['child'])) {
                $newUser->parentid = $input['child'];
            }

            // Save new user
            $UserModel->insert($newUser);

            // Get new user id
            $userId = $UserModel->getInsertID();

            // Adding new user to group
            if (isset($input['parent'])) {
                $authorize->addUserToGroup($userId, $input['parent']);
            } elseif (isset($input['role'])) {
                $authorize->addUserToGroup($userId, $input['role']);
            }

            // Return back to index
            if (isset($input['parent'])) {
                return redirect()->to('users/client')->with('massage', lang('Global.saved'));
            } elseif (empty($input['parent'])) {
                return redirect()->to('users')->with('massage', lang('Global.saved'));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) {
            $authorize = service('authorization');

            // Calling Entities
            $updateUser = new \App\Entities\User();

            // Calling Model
            $UserModel      = new UserModel();
            $GroupUserModel = new GroupUserModel();
            $groups = $authorize->groups();

            foreach ($groups as $gr) {
                if ($gr->name === "client pusat") {
                    $pusatid = $gr->id;
                }
            }

            // Defining input
            $input = $this->request->getPost();

            // Finding user
            $user = $UserModel->find($id);

            // Validation basic form
            if (!empty($input['username'])) {
                $rules['username']  = 'alpha_numeric_space|min_length[3]|max_length[30]';
            }
            if (!empty($input['email'])) {
                $rules['email']     = 'valid_email';
            }

            $rules['role'] = 'max_length[30]';

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Data user update
            $updateUser->id = $id;
            if (!empty($input['username']) && $input['username'] != "") {
                $updateUser->username  = $input['username'];
            } else {
                $updateUser->username  = $user->username;
            }
            if (!empty($input['email']) && $input['email'] != "") {
                $updateUser->email     = $input['email'];
            } else {
                $updateUser->email  = $user->email;
            }
            if (!empty($input['firstname'])) {
                $updateUser->firstname     = $input['firstname'];
            } else {
                $updateUser->firstname  = $user->firstname;
            }
            if (!empty($input['lastname'])) {
                $updateUser->lastname     = $input['lastname'];
            } else {
                $updateUser->lastname  = $user->lastname;
            }
            if (!empty($input['child'])) {
                $updateUser->parentid    = $input['child'];
            } else {
                $updateUser->parentid  = $user->parentid;
            }
            if (isset($input['parent'])) {
                if ($input['parent'] === $pusatid) {
                    $updateUser->parentid = Null;
                }
            } elseif (isset($input['role'])) {
                $updateUser->parentid = Null;
            }

            // Reset password
            if (!empty($input['password'])) {
                $rules = [
                    'password'      => 'strong_password',
                    'pass_confirm'  => 'required|matches[password]'
                ];

                if (!$this->validate($rules)) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }

                $updateUser->password   = $input['password'];
                $updateUser->reset_at   = date('Y-m-d H:i:s');
            }

            // Updating user info
            $UserModel->save($updateUser);

            // Finding group
            $groups = $GroupUserModel->where('user_id', $id)->find();

            // Removing from group
            foreach ($groups as $group) {
                $authorize->removeUserFromGroup($id, $group['group_id']);
            }

            // Adding to group
            if (isset($input['parent'])) {
                $authorize->addUserToGroup($id, $input['parent']);
            } elseif (isset($input['role'])) {
                $authorize->addUserToGroup($id, $input['role']);
            }

            // Redirect to user management
            if (isset($input['parent'])) {
                return redirect()->to('users/client')->with('massage', lang('Global.saved'));
            } elseif (empty($input['parent'])) {
                return redirect()->to('users')->with('massage', lang('Global.saved'));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
            $authorize = service('authorization');
            $usersModel = new UserModel();
            $GroupUserModel = new GroupUserModel();

            $user = $usersModel->find($id);
            $groups = $GroupUserModel->where('user_id', $id)->find();
            $input = $this->request->getPost();

            $groupid = "";
            foreach ($groups as $group) {
                $groupid = $group['group_id'];
            }

            $data['users'] = $usersModel->find($id);
            if (empty($data)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Password Tidak Ditemukan !');
            }

            // remove from user group
            $authorize->removeUserFromGroup($id, $groupid);

            $usersModel->update($id, [
                'active'   => '0'
            ]);
            $usersModel->delete($id);

            return redirect()->to('users')->with('massage', lang('Global.deleted'));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deleteclient($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
            $authorize = service('authorization');

            // Calling Model
            $usersModel = new UserModel();
            $GroupUserModel = new GroupUserModel();
            // $ProjectModel = new ProjectModel;
            $ProjectTempModel = new ProjectTempModel;

            $Project = $ProjectTempModel->where('clientid', $id)->find();

            // remove project
            if (!empty($Project)) {
                foreach ($Project as $project) {
                    $ProjectTempModel->delete($project['id']);
                }
            }

            $users = $usersModel->findAll();

            // Remove Parent
            $parid = [];
            foreach ($users as $child) {
                if ($child->parentid === $id) {
                    $parid = [
                        'id' => $child->id,
                    ];
                }
            }

            if (!empty($parid)) {
                foreach ($users as $user) {
                    if ($user->id === $parid['id']) {
                        $data = [
                            'id'        => $parid['id'],
                            'parentid'  => 0,
                        ];
                        $usersModel->save($data);
                    }
                }
            }

            $groups = $GroupUserModel->where('user_id', $id)->find();

            $groupid = "";
            foreach ($groups as $group) {
                $groupid = $group['group_id'];
            }

            $data['users'] = $usersModel->find($id);
            if (empty($data)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Password Tidak Ditemukan !');
            }

            // remove from user group
            $authorize->removeUserFromGroup($id, $groupid);

            $usersModel->update($id, [
                'active'   => '0'
            ]);
            $usersModel->delete($id);

            return redirect()->to('users/client')->with('massage', lang('Global.deleted'));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function accesscontrol()
    {
        // Calling Libraries and Services
        $authorize = $auth = service('authorization');

        // Populating Data
        $groups = $authorize->groups();
        $permission = $authorize->permissions();

        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = lang('Global.employeeList');
        $data['description']    = lang('Global.employeeListDesc');
        $data['groups']         = $groups;
        $data['permissions']    = $permission;
        $data['GroupModel']     = new GroupModel();

        return view('accesscontrol', $data);
    }

    public function createaccess()
    {

        // Calling Libraries and Services
        $authorize = $auth = service('authorization');

        // Populating Data
        $groups = $authorize->groups();

        // Initialize
        $input = $this->request->getPost();

        // Create Group
        $id = $authorize->createGroup($input['group'], $input['description']);

        $permission = [];
        foreach ($input['permission'] as $key => $permit) {
            $permission[] = $permit;
        }

        foreach ($permission as $permissionid) {
            $authorize->addPermissionToGroup($permissionid, $id);
        }

        return redirect()->to('users/access-control')->with('message', lang('Global.saved'));
    }

    public function updateaccess($id)
    {

        // Caling Libraries
        $authorize = $auth = service('authorization');

        // Populating Data
        $groups = $authorize->groups();
        $GroupModel = new GroupModel;
        $Grouppermissions = $GroupModel->getPermissionsForGroup($id);

        // Initialize
        $input = $this->request->getPost();
        $group = $authorize->group($id);

        // Update Group
        $datagroup = [
            'name'        => $input['name'],
            'description' => $input['description'],
        ];
        // dd($datagroup);

        $cek = $authorize->updateGroup($id, $datagroup['name'], $datagroup['description']);

        // Get Group Permissions
        $permissiongroup = [];
        foreach ($Grouppermissions as $Grouppermission) {
            $permissiongroup[] = $Grouppermission->id;
        }

        if (!empty($Grouppermissions)) {
            // Remove Permission From Group
            foreach ($permissiongroup as $permit => $permissionid) {
                $authorize->removePermissionFromGroup($permissionid, $id);
            }
        }

        if (!empty($input['permission'])) {
            // Add New Permissions
            $permission = [];
            foreach ($input['permission'] as $key => $permit) {
                $permission[] = $permit;
            }

            foreach ($permission as $permissionid) {
                $authorize->addPermissionToGroup($permissionid, $id);
            }
        }

        return redirect()->to('users/access-control')->with('message', lang('Global.saved'));
    }

    public function deleteaccess($id)
    {
        // Caling Libraries
        $authorize = $auth = service('authorization');
        // Populating Data
        $groups = $authorize->groups();
        $GroupModel = new GroupModel;
        $Grouppermissions = $GroupModel->getPermissionsForGroup($id);

        // Get Group Permissions
        $permissiongroup = [];
        foreach ($Grouppermissions as $Grouppermission) {
            $permissiongroup[] = $Grouppermission->id;
        }


        // Remove Permissions 
        foreach ($permissiongroup as $permit => $permissionid) {
            $authorize->removePermissionFromGroup($permissionid, $id);
        }

        // Delete Group
        $authorize->deleteGroup($id);

        return redirect()->to('users/access-control')->with('message', lang('Global.deleted'));
    }

    public function client()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $UserModel              = new UserModel();
            $GroupModel             = new GroupModel();
            $PermissionModel        = new PermissionModel();

            $users = $UserModel->findAll();
            // Populating data
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('users.id !=', $this->data['uid']);
            $this->builder->where('auth_groups.name !=', 'superuser');
            $this->builder->where('auth_groups.name !=', 'owner');
            $this->builder->where('auth_groups.name !=', 'admin');
            $this->builder->where('auth_groups.name !=', 'marketing');
            $this->builder->where('auth_groups.name !=', 'design');
            $this->builder->where('auth_groups.name !=', 'production');
            $this->builder->where('auth_groups.name !=', 'guests');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('users.username', $input['search']);
                $this->builder->orLike('users.firstname', $input['search']);
                $this->builder->orLike('users.lastname', $input['search']);
            }
            if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                $this->builder->where('auth_groups.id', $input['rolesearch']);
            }
            $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $query =   $this->builder->get($perpage, $offset)->getResult();

            $total = $this->builder
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->where('users.id !=', $this->data['uid'])
                    ->where('auth_groups.name', 'client pusat')
                    ->orWhere('auth_groups.name', 'client cabang')
                    ->countAllResults();

            $parentid = [];
            foreach ($users as $user) {
                $parentid[] = [
                    'id' => $user->id,
                    'name' => $user->username,
                ];
            }

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = lang('Global.clientList');
            $data['description']    = lang('Global.clientListDesc');
            $data['roles']          = $GroupModel->where('name', "client pusat")->orWhere('name', 'client cabang')->find();
            $data['users']          = $query;
            $data['parent']         = $parentid;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;

            return view('client', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
