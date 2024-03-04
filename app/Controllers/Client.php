<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\ProjectTempModel;
use App\Models\LogModel;

class Client extends BaseController
{

    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $helpers = ['form'];
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
            $UserModel                 = new UserModel();

            // Populating data
            $companys = $CompanyModel->findAll();
            $users    = $UserModel->where('parentid !=', null)->find();

            // Initialize
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->builder->where('deleted_at', null);
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
            $this->builder->select('company.id as id, company.rsname as rs, company.ptname as pt, company.npwp as npwp, company.address as address, company.phone as phone, company.pic as pic, company.parentid as parent, company.status as status, company.bank as bank, company.no_rek as no_rek, company.rscode as rscode');
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
            $data['roles']          = $CompanyModel->where('deleted_at',null)->find();
            $data['company']        = $query;
            $data['parent']         = $parentid;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;
            $data['users']          = $users;

            return view('client', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.user.create', $this->data['uid'])) {

            // Calling Models & Services
            $validation = \Config\Services::validation();
            $CompanyModel = new CompanyModel();
            $LogModel = new LogModel();

            // Initialize
            $input = $this->request->getPost();

            // Validation Rules
            $rules = [
                'rsname' => [
                    'label'  => 'Nama Rumah Sakit / Nama Alias',
                    'rules'  => 'required|is_unique[company.rsname]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'ptname' => [
                    'label'  => 'Nama PT',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'address' => [
                    'label'  => 'Alamat',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'notelp' => [
                    'label'  => 'No Telepon',
                    'rules'  => 'required|is_unique[company.phone]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'npwp' => [
                    'label'  => 'NPWP',
                    'rules'  => 'required|is_unique[company.npwp]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'rsname'        => $input['rsname'],
                'ptname'        => $input['ptname'],
                'address'       => $input['address'],
                'npwp'          => $input['npwp'],
                'phone'         => $input['notelp'],
                'bank'          => $input['bank'],
                'no_rek'        => $input['norek'],
                'rscode'        => $input['koders'],
                'status'        => '1',
                'parentid'      => $input['parent'],
                'created_at'    => date('Y-m-d h:i:s'),
            ];
            $CompanyModel->save($data);

            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan client '.$input['ptname']]);

            return redirect()->to('client')->with('message', 'Data berhasil di simpan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.edit', $this->data['uid'])) {

            // Calling Models
            $CompanyModel = new CompanyModel();
            $LogModel = new LogModel();

            // Initialize
            $input = $this->request->getPost();
            $company = $CompanyModel->find($id);

            if ($input['rsname'] === $company['rsname']) {
                $is_unique =  '';
            } else {
                $is_unique =  '|is_unique[company.rsname,'.$id.']';
            }
            if ($input['ptname'] === $company['ptname']) {
                $ptnameis_unique =  '';
            } else {
                $ptnameis_unique =  '|is_unique[company.ptname,'.$id.']';
            }
            if ($input['address'] === $company['address']) {
                $addressis_unique =  '';
            } else {
                $addressis_unique =  '|is_unique[company.address]';
            }
            if ($input['notelp'] === $company['phone']) {
                $notelpis_unique =  '';
            } else {
                $notelpis_unique =  '|is_unique[company.phone]';
            }
            if ($input['npwp'] === $company['npwp']) {
                $npwpis_unique =  '';
            } else {
                $npwpis_unique =  '|is_unique[company.npwp]';
            }

            $rules = [
                'rsname' => [
                    'label'  => 'Nama Rumah Sakit / Nama Alias',
                    'rules'  => 'required'.$is_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'ptname' => [
                    'label'  => 'Nama PT',
                    'rules'  => 'required'.$ptnameis_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'address' => [
                    'label'  => 'Alamat',
                    'rules'  => 'required'.$addressis_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'notelp' => [
                    'label'  => 'No Telepon',
                    'rules'  => 'required'.$notelpis_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'npwp' => [
                    'label'  => 'NPWP',
                    'rules'  => 'required'.$npwpis_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'id'            => $id,
                'rsname'        => $input['rsname'],
                'ptname'        => $input['ptname'],
                'address'       => $input['address'],
                'npwp'          => $input['npwp'],
                'pic'           => $input['picklien'],
                'phone'         => $input['notelp'],
                'bank'          => $input['bank'],
                'no_rek'        => $input['norek'],
                'rscode'        => $input['koders'],
                'status'        => $input['status'],
                'parentid'      => $input['parent'],
                'updated_at'    => date('Y-m-d h:i:s'),
            ];
            $CompanyModel->save($data);

            // Recording Logs
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Merubah data '.$input['ptname']]);

            return redirect()->to('client')->with('message', 'Data berhasil di perbaharui');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.user.delete', $this->data['uid'])) {

            // Calling Model
            $CompanyModel = new CompanyModel();
            $ProjectModel = new ProjectModel();
            $LogModel = new LogModel();

            // Getting Data 
            $companys = $CompanyModel->where('parentid', $id)->find();
            $Project = $ProjectModel->where('clientid', $id)->find();

            // Remove this client projects
            if (!empty($Project)) {
                foreach ($Project as $project) {
                    $ProjectModel->delete($project['id']);
                }
            }

            // Child Company
            if (!empty($companys)) {
                foreach ($companys as $company) {
                    $comprojects = $ProjectModel->where('clientid', $company['id'])->find();

                    // Remove Child Projects
                    if (!empty($comprojects)) {
                        foreach ($comprojects as $comproject) {
                            $ProjectModel->delete($comproject['id']);
                        }
                    }

                    // Remove Child
                    $CompanyModel->delete($company['id']);
                }
            }

            // Recording Logs
            $currentcompany = $CompanyModel->find($id);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus client '.$currentcompany['ptname'].' dan seluruh client di bawahnya beserta semua proyek yang bersangkutan.']);

            // Remove Current Company
            $CompanyModel->delete($id);

            // Redirect to View
            return redirect()->to('client')->with('message', "Data berhasil di hapus");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
