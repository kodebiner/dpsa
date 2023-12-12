<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\PaketModel;

class Project extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('project');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
        $pager          = \Config\Services::pager();
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) {
            // Calling Model
            $ProjectModel   = new ProjectModel();
            $CompanyModel   = new CompanyModel();
            $MdlModel       = new MdlModel();
            $PaketModel     = new PaketModel();

            // Populating Data
            $pakets         = $PaketModel->findAll();
            $company        = $CompanyModel->where('status !=', "0")->where('deleted_at',null)->find();
            $projects       = $ProjectModel->where('deleted_at', null)->paginate(10, 'projects');

            $data = $this->data;
            $data['title']          = "Proyek";
            $data['description']    = "Data Proyek";
            $data['projects']       = $projects;
            $data['company']        = $company;
            $data['pakets']         = $pakets;
            $data['pager']          = $ProjectModel->pager;

            return view('project', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function mdl()
    {
        // Calling Model
        $MdlModel       = new MdlModel();
        $PaketModel     = new PaketModel();

        // Initialize
        $input      = $this->request->getPost();

        // Populating Data
        $pakets     = $PaketModel->find($input['id']);
        $mdls       = $MdlModel->where('paketid', $input['id'])->find();

        $mdlid = array();
        foreach ($mdls as $mdl) {
            $mdlid[]    = $mdl['id'];
        }

        $return = array();
        foreach ($mdls as $mdl) {
            $return[] = [
                'id'            => $mdl['id'],
                'name'          => $mdl['name'],
                'length'        => $mdl['length'],
                'width'         => $mdl['width'],
                'height'        => $mdl['height'],
                'volume'        => $mdl['volume'],
                'denomination'  => $mdl['denomination'],
                'price'         => $mdl['price'],
            ];
        }
        
        die(json_encode($return));
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.project.create', $this->data['uid'])) {
            // Calling Model
            $ProjectModel = new ProjectModel;

            // initialize
            $input  = $this->request->getPost();

            // Validation Rules
            $rules = [
                'name' => [
                    'label'  => 'Nama Proyek',
                    'rules'  => 'required|is_unique[project.name]',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'brief' => [
                    'label'  => 'Detail Pesanan',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'company' => [
                    'label'  => 'Nama Klien',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'status' => [
                    'label'  => 'Progres Proyek',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $project = [
                'name'          => $input['name'],
                'brief'         => $input['brief'],
                'clientid'      => $input['company'],
                'status'        => $input['status'],
                'production'    => $input['qty'],
                'created_at'    => date('Y-m-d h:i:s'),
            ];
            $ProjectModel->save($project);

            return redirect()->back()->with('message', "Data berhasil di simpan.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('production.project.edit', $this->data['uid'])) {
            // Calling Model
            $ProjectModel = new ProjectModel;

            // initialize
            $input = $this->request->getPost();
            $pro = $ProjectModel->find($id);

            if (isset($input['name'])) {
                $name = $pro['name'];
            } else {
                $name = $input['name'];
            }

            if (isset($input['brief'])) {
                $brief = $pro['brief'];
            } else {
                $brief = $input['brief'];
            }
            
            if (isset($input['company'])) {
                $client = $pro['clientid'];
            } else {
                $client = $input['company'];
            }

            if (isset($input['status'])) {
                $status = $pro['status'];
            } else {
                $status = $input['status'];
            }

            if (isset($input['qty'])) {
                $qty = $pro['production'];
            } else {
                $qty = $input['qty'];
            }

            if ($input['name'] === $pro['name']) {
                $is_unique =  '';
            } else {
                $is_unique =  'is_unique[project.name]';
            }

            // Validation Rules
            $rules = [
                'name' => [
                    'label'  => 'Nama Proyek',
                    'rules'  => 'required|'.$is_unique,
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'brief' => [
                    'label'  => 'Detail Pemesanan',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'company' => [
                    'label'  => 'Nama Klien',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
                'status' => [
                    'label'  => 'Progres Proyek',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $project = [
                'id'            => $id,
                'name'          => $name,
                'brief'         => $brief,
                'clientid'      => $client,
                'status'        => $status,
                'production'    => $qty,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];
            $ProjectModel->save($project);

            return redirect()->back()->with('message', "Data berhasil di perbaharui.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.delete', $this->data['uid'])) {
            // Calling Model
            $ProjectModel = new ProjectModel;

            // Soft Delete Project
            $data   = [
                'id'            => $id,
                'deleted_at'    => date('Y-m-d H:i:s'),
            ];
            $ProjectModel->save($data);

            return redirect()->back()->with('errors', "Data berhasil di hapus");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
