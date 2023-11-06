<?php

namespace App\Controllers;

use App\Models\BarModel;
use App\Entities\User;
use App\Models\UserModel;


class Home extends BaseController
{
    protected $data;
    
    public function index(): string
    {

        // Find Model
        $BarModel = new BarModel;

        // Populating Data
        $bars = $BarModel->find(1);

        // Data Quantiti
        $qty = $bars['qty'];
        
        $data = $this->data;
        $data['title']          =  lang('Global.titleDashboard');
        $data['description']    =  lang('Global.dashboardDescription');
        $data['qty']            = $qty;
        return view('bar', $data);
    }

    public function installation()
    {
        // Calling Libraries and Services
        $authorize = service('authorization');

        // Calling Models
        $UserModel = new UserModel();

        // Remove All Users
        $users = $UserModel->findAll();
        $uids = array();
        foreach ($users as $user) {
            $uids[] = $user->id;
        }
        $UserModel->delete($uids);
        $UserModel->purgeDeleted();

        // Database Migration
        command('migrate --all');

        // Remove Old Permission
        $permissions = $authorize->permissions();
        foreach ($permissions as $permission) {
            $authorize->deletePermission($permission['id']);
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
        foreach ($groups as $group) {
            $authorize->deleteGroup($group->id);
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

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Vaalidating Password
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
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

    public function trial()
    {
        $UserModel = new UserModel();

        $users = $UserModel->findAll();

        foreach ($users as $user) {
            echo $user->id.'<br/>';
        }
    }

}
