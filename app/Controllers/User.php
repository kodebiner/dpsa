<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;

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
        // Calling Model
        $GroupModel             = new GroupModel();
        $PermissionModel        = new PermissionModel();

        // Populating data
        $this->builder->where('deleted_at', null);
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        // if ($this->data['role'] === 'supervisor') {
        //     $this->builder->where('auth_groups.name', 'operator');
        // }
        $this->builder->where('users.id !=', $this->data['uid']);
        $this->builder->where('auth_groups.name !=', 'client pusat');
        $this->builder->where('auth_groups.name !=', 'client cabang');
        $this->builder->select('users.id as id, users.username as username, users.firstname as firstname, users.lastname as lastname, users.email as email, auth_groups.id as group_id, auth_groups.name as role');
        $query =   $this->builder->get();

        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = lang('Global.usersList');
        $data['description']    = lang('Global.usersListDesc');
        $data['roles']          = $GroupModel->findAll();
        $data['permissions']    = $PermissionModel->findAll();
        $data['users']          = $query->getResult();

        return view('users', $data);
    }

    public function create()

    {
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
        if (!empty($input['parent'])) {
            $newUser->parentid = $input['parent'];
        }

        // Save new user
        $UserModel->insert($newUser);

        // Get new user id
        $userId = $UserModel->getInsertID();

        // Adding new user to group
        $authorize->addUserToGroup($userId, $input['role']);


        // Return back to index
        if (!empty($input['parent'])) {
            return redirect()->to('users/client')->with('massage', lang('Global.saved'));
        } else {
            return redirect()->to('users')->with('massage', lang('Global.saved'));
        }
    }

    public function update($id)

    {
        $authorize = service('authorization');

        // Calling Entities
        $updateUser = new \App\Entities\User();

        // Calling Model
        $UserModel      = new UserModel();
        $GroupUserModel = new GroupUserModel();

        $GroupModel     = new GroupModel();
        $groups = $authorize->groups();

        
        foreach ($groups as $gr){
            if($gr->name === "client pusat"){
                $pusatid = $gr->id;
            }
        }

        // Defining input
        $input = $this->request->getPost();

        // Finding user
        $user = $UserModel->find($id);

        // Validation basic form
        if (!empty($input['username'])) {
            $rules['username']  = 'alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]';
        }
        if (!empty($input['email'])) {
            $rules['email']     = 'valid_email|is_unique[users.email]';
        }

        $rules['role'] = 'required';

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
        if (!empty($input['phone'])) {
            $updateUser->phone     = $input['phone'];
        } else {
            $updateUser->phone  = $user->phone;
        }
        if (!empty($input['parent'])) {
            $updateUser->parentid    = $input['parent'];
        } else {
            $updateUser->parentid  = $user->parentid;
        }
        if($input['role'] === $pusatid) {
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
        $authorize->addUserToGroup($id, $input['role']);

        // Redirect to user management
        if (!empty($input['parent'])) {
            return redirect()->to('users/client')->with('massage', lang('Global.saved'));
        } elseif (empty($input['parent'])) {
            return redirect()->to('users')->with('massage', lang('Global.saved'));
        }
    }

    public function delete($id)
    {

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
    }

    public function deleteclient($id)
    {

        $authorize = service('authorization');
        $usersModel = new UserModel();
        $GroupUserModel = new GroupUserModel();

        $users = $usersModel->findAll();

        // $userchild = $usersModel->where('parentid',$id)->find();
        $updateUser = new \App\Entities\User();
        
        $parid = [];
        foreach ($users as $child) {
            if($child->parentid === $id) {
                $parid = [
                    'id' => $child->id,
                ];
            }
        }

    
            foreach ($users as $user) {
                if ($user->id === $parid['id']) {
                    $data = [
                        'id'        => $parid['id'],
                        'parentid'  => 0,
                    ];
                    $usersModel->save($data);
                }
            
        }

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
        
        return redirect()->to('users/client')->with('massage', lang('Global.deleted'));
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
        // Calling Model
        $GroupModel             = new GroupModel();
        $PermissionModel        = new PermissionModel();

        // Populating data
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

        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = lang('Global.clientList');
        $data['description']    = lang('Global.clientListDesc');
        $data['roles']          = $GroupModel->findAll();
        $data['permissions']    = $PermissionModel->findAll();
        $data['users']          = $query->getResult();
        $data['parent']         = $parentid;

        return view('client', $data);
    }
}
