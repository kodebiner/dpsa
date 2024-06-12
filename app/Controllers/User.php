<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\LogModel;
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
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $UserModel              = new UserModel();
            $GroupModel             = new GroupModel();
            $PermissionModel        = new PermissionModel();
            $CompanyModel           = new CompanyModel();

            // Populating data
            $users = $UserModel->findAll();
            $company = $CompanyModel->where('status !=', "0")->where('deleted_at', null)->find();

            // Initilize
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
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('users.username', $input['search']);
                $this->builder->orLike('users.firstname', $input['search']);
                $this->builder->orLike('users.lastname', $input['search']);
                $this->builder->orLike('users.email', $input['search']);
            }
            if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                $this->builder->where('auth_groups.id', $input['rolesearch']);
            }
            $this->builder->select('users.id as id, users.username as username, users.kode_marketing as kodemarketing, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $query =   $this->builder->get($perpage, $offset)->getResult();


            if (isset($input['search']) && !empty($input['search'])) {
                $total = $this->builder
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->like('users.username', $input['search'])
                    ->orLike('users.firstname', $input['search'])
                    ->orLike('users.lastname', $input['search'])
                    ->orLike('users.email', $input['search'])
                    ->where('users.id !=', $this->data['uid'])
                    ->countAllResults();
            }elseif (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                $total = $this->builder
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->where('auth_groups.id', $input['rolesearch'])
                    ->where('users.id !=', $this->data['uid'])
                    ->countAllResults();
            }else{
                $total = $this->builder
                    ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                    ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                    ->where('users.id !=', $this->data['uid'])
                    ->countAllResults();
            }

            $parentid = [];
            foreach ($users as $user) {
                $parentid[] = [
                    'id' => $user->id,
                    'name' => $user->username,
                ];
            }

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = "Daftar Pengguna";
            $data['description']    = "Daftar Pengguna Aplikasi";
            $data['roles']          = $GroupModel->where('name !=', 'superuser')->find();
            $data['users']          = $query;
            $data['parent']         = $parentid;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;
            $data['Companys']       = $company;

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
            $UserModel      = new UserModel();
            $LogModel       = new LogModel();

            // Defining input
            $input = $this->request->getPost();

            // Validation Rules
            $rules = [
                'username' => [
                    'label'  => 'Nama',
                    'rules'  => 'required|is_unique[users.username]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'required|is_unique[users.email]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'firstname' => [
                    'label'  => 'Nama Depan',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'lastname' => [
                    'label'  => 'Nama Belakang',
                    'rules'  => 'required',
                    'errors' => [
                        'required' => '{field} wajib diisi',
                    ],
                ],

                // Password Validation
                'password' => [
                    'label'  => 'Kata Sandi',
                    'rules'  => 'required|min_length[8]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'min_length'    => '{field} harus lebih panjang atau sama dengan {param}',
                    ],
                ],
                'pass_confirm' => [
                    'label'  => 'Konfirmasi Kata Sandi',
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'required'    => '{field} wajib diisi',
                        'matches'     => '{field} harus sama dengan {param}.',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->to('users')->withInput()->with('errors', $this->validator->getErrors());
            }

            // New user data
            $newUser->username          = $input['username'];
            $newUser->email             = $input['email'];
            $newUser->firstname         = $input['firstname'];
            $newUser->lastname          = $input['lastname'];
            $newUser->password          = $input['password'];
            $newUser->active            = 1;

            if(!empty($input['kodemarketing'])){
                $newUser->kode_marketing    = $input['kodemarketing'];
            }else{
                $newUser->kode_marketing    = Null;
            }

            if (!empty($input['compid'])) {
                $newUser->parentid = $input['compid'];
            } else {
                $newUser->parentid = NULL;
            }

            // Save new user
            $UserModel->insert($newUser);

            // Get new user id
            $userId = $UserModel->getInsertID();

            if (isset($input['role'])) {
                $authorize->addUserToGroup($userId, $input['role']);
            }

            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan' . $input['username'] . 'sebagai pengguna baru']);
            return redirect()->to('users')->with('message', 'Data pengguna berhasil di simpan');
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
            $LogModel       = new LogModel();
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

            // Validation Rules
            if ($input['username'] === $user->username) {
                $is_unique =  '';
            } else {
                $is_unique =  '|is_unique[users.username]';
            }
            if ($input['email'] === $user->email) {
                $emailis_unique =  '';
            } else {
                $emailis_unique =  '|is_unique[users.email]';
            }

            // Validation Rules
            $rules = [
                'username' => [
                    'label'  => 'Nama',
                    'rules'  => 'required' . $is_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'required' . $emailis_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'firstname' => [
                    'label'  => 'Nama Depan',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'lastname' => [
                    'label'  => 'Nama Belakang',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'role' => [
                    'label'  => 'Akses',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->to('users')->withInput()->with('errors', $this->validator->getErrors());
            }

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
            if (!empty($input['compid'])) {
                $updateUser->parentid    = $input['compid'];
            } else {
                $updateUser->parentid   = NULL;
            }
            if (!empty($input['status'])) {
                $updateUser->active    = $input['status'];
            } else {
                $updateUser->active     = $updateUser->active;
            }
            if(!empty($input['kodemarketing'])){
                $updateUser->kode_marketing    = $input['kodemarketing'];
            }else{
                $updateUser->kode_marketing    = Null;
            }

            // Reset password
            if (!empty($input['password'])) {
                $rules = [
                    // Password Validation
                    'password' => [
                        'label'  => 'Kata Sandi',
                        'rules'  => 'required|min_length[8]',
                        'errors' => [
                            'required'      => '{field} wajib diisi',
                            'min_length'    => '{field} harus lebih panjang atau sama dengan {param}',
                        ],
                    ],
                    'pass_confirm' => [
                        'label'  => 'Konfirmasi Kata Sandi',
                        'rules'  => 'required|matches[password]',
                        'errors' => [
                            'required'    => '{field} wajib diisi',
                            'matches'     => '{field} harus sama dengan {param}.',
                        ],
                    ],
                ];

                if (!$this->validate($rules)) {
                    return redirect()->to('users')->withInput()->with('errors', $this->validator->getErrors());
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
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah data' . $input['username']]);
            return redirect()->to('users')->with('message', 'Data berhasil diperbaharui.');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
            $authorize      = service('authorization');
            $usersModel     = new UserModel();
            $LogModel       = new LogModel();
            $GroupUserModel = new GroupUserModel();


            $user   = $usersModel->find($id);
            $groups = $GroupUserModel->where('user_id', $id)->find();
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus' . $user->username]);

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

            return redirect()->to('users')->with('message', 'Data berhasil dihapus');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function accesscontrol()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            $authorize = service('authorization');
            // Calling Libraries and Services
            $authorize = $auth = service('authorization');
            // Populating Data
            $groups = $authorize->groups();
            $permission = $authorize->permissions();
            // permanent groups
            $grouparr = ['superuser', 'admin', 'owner', 'marketing', 'design', 'production', 'client pusat', 'client cabang', 'finance'];
            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = 'Hak Akses';
            $data['description']    = 'Pengelolaan Hak Akses';
            $data['groups']         = $groups;
            $data['permissions']    = $permission;
            $data['GroupModel']     = new GroupModel();
            $data['grouparr']       = $grouparr;
            return view('accesscontrol', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function createaccess()
    {
        if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) {
            $authorize = service('authorization');
            // Calling Libraries and Services
            $authorize = $auth = service('authorization');
            // Populating Data
            $groups     = $authorize->groups();
            $LogModel   = new LogModel();
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
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan akses baru']);
            }
            return redirect()->to('users/access-control')->with('message', 'Data berhasil ditambahkan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function updateaccess($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) {

            // Caling Libraries
            $authorize = $auth = service('authorization');

            // Populating Data
            $groups     = $authorize->groups();
            $LogModel   = new LogModel();
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
                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah data akses']);
                    $authorize->addPermissionToGroup($permissionid, $id);
                }
            }

            return redirect()->to('users/access-control')->with('message', "Akses berhasil di perbaharui");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deleteaccess($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {
            // Caling Libraries
            $authorize = $auth = service('authorization');
            // Populating Data
            $groups     = $authorize->groups();
            $LogModel   = new LogModel();
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
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus data akses']);
            }

            // Delete Group
            $authorize->deleteGroup($id);

            return redirect()->to('users/access-control')->with('message', "Akses berhasil di delete.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function log()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Initilize
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->db       = \Config\Database::connect();
            $validation     = \Config\Services::validation();
            $this->builder  = $this->db->table('logrecords');
            $this->builder->orderBy('id', 'DESC');
            $this->builder->join('users', 'users.id = logrecords.uid');
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('users.username', $input['search']);
                $this->builder->orLike('logrecords.record', $input['search']);
            }
            if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
                $this->builder->where('auth_groups.id', $input['rolesearch']);
            }
            $this->builder->select('logrecords.id as id, users.username as username, logrecords.record as record, logrecords.created_at as created, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $query =   $this->builder->get($perpage, $offset)->getResult();

            $total = $this->builder
                ->join('users', 'users.id = logrecords.uid')
                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                ->countAllResults();

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = "Daftar Aktivitas Pengguna";
            $data['description']    = "Daftar Aktivitas Pengguna Aplikasi";
            $data['users']          = $query;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;

            return view('log', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
