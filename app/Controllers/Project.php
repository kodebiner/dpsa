<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\PaketModel;
use App\Models\RabModel;

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
            $ProjectModel           = new ProjectModel();
            $CompanyModel           = new CompanyModel();
            $MdlModel               = new MdlModel();
            $PaketModel             = new PaketModel();
            $RabModel               = new RabModel();

            // Populating Data
            $pakets                 = $PaketModel->findAll();
            $company                = $CompanyModel->where('status !=', "0")->find();
            $projects               = $ProjectModel->paginate(10, 'projects');

            $projectdata = [];
            foreach ($projects as $project) {
                $paketid = [];
                $mdlid = [];
                $rabs = $RabModel->where('projectid', $project['id'])->find();
                foreach ($rabs as $rab) {
                    $paketid[] = $rab['paketid'];
                    $mdlid[] = $rab['mdlid'];
                }
                $paketdata = [];
                $paketproject = $PaketModel->find($paketid);
                foreach ($paketproject as $pack) {
                    $paketdata[] = $pack['id'];
                    $projectdata[$project['id']]['paket'][$pack['id']]['name'] = $pack['name'];
                    $mdlpack = $MdlModel->where('paketid', $pack['id'])->find();
                    foreach ($mdlpack as $mdl) {
                        $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']] = [
                            'id'            => $mdl['id'],
                            'name'          => $mdl['name'],
                            'length'        => $mdl['length'],
                            'width'         => $mdl['width'],
                            'height'        => $mdl['height'],
                            'volume'        => $mdl['volume'],
                            'denomination'  => $mdl['denomination'],
                            'price'         => $mdl['price'],
                        ];
                        $rabpack = $RabModel->where('mdlid', $mdl['id'])->where('projectid', $project['id'])->where('paketid', $pack['id'])->first();
                        if (!empty($rabpack)) {
                            $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']]['qty'] = $rabpack['qty'];
                            $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']]['checked'] = true;
                        } else {
                            $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']]['qty'] = 0;
                            $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']]['checked'] = false;
                        }
                    }
                }
                $projectdata[$project['id']]['autopaket'] = $PaketModel->whereNotIn('id', $paketdata)->find();
            }

            $data                   = $this->data;
            $data['title']          = "Proyek";
            $data['description']    = "Data Proyek";
            $data['projects']       = $projects;
            $data['projectdata']    = $projectdata;
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
            $ProjectModel   = new ProjectModel();
            $MdlModel       = new MdlModel();
            $RabModel       = new RabModel();

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

            // Project Data
            $project = [
                'name'          => $input['name'],
                'brief'         => $input['brief'],
                'clientid'      => $input['company'],
                'status'        => $input['status'],
                'production'    => $input['proqty'],
            ];
            $ProjectModel->insert($project);

            // Get Purchase ID
            $projectid = $ProjectModel->getInsertID();

            // RAB Data
            foreach ($input['checklist'] as $mdlid => $checklist) {
                if ($checklist) {
                    $mdl = $MdlModel->find($mdlid);
                    $datarab     = [
                        'projectid' => $projectid,
                        'paketid'   => $mdl['paketid'],
                        'mdlid'     => $mdlid,
                        'qty'       => $input['qty'][$mdlid],
                    ];
                    // Save Data RAB
                    $RabModel->insert($datarab);
                }
            }

            return redirect()->back()->with('message', "Data berhasil di simpan.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('production.project.edit', $this->data['uid'])) {
            // Calling Model
            $ProjectModel = new ProjectModel();
            $RabModel = new RabModel();
            $MdlModel = new MdlModel();

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

            if (isset($input['proqty'])) {
                $qty = $pro['production'];
            } else {
                $qty = $input['proqty'];
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

            // RAB Data
            foreach ($input['eqty'.$id] as $mdlid => $qty) {
                if (isset($input['checked'.$id][$mdlid])) {
                    $rab = $RabModel->where('mdlid', $mdlid)->where('projectid', $id)->first();
                    if ((!empty($rab)) && ($rab['qty'] != $input['eqty'.$id][$mdlid])) {
                        $RabModel->save(['id' => $rab['id'], 'qty' => $qty]);
                    } elseif (empty($rab)) {
                        $mdl = $MdlModel->find($mdlid);
                        $datarab = [
                            'mdlid'     => $mdlid,
                            'projectid' => $id,
                            'paketid'   => $mdl['paketid'],
                            'qty'       => $qty
                        ];
                        $RabModel->save($datarab);
                    }
                } else {
                    $rab = $RabModel->where('mdlid', $mdlid)->where('projectid', $id)->first();
                    if (!empty($rab)) {
                        $RabModel->delete($rab['id']);
                    }
                }
            }

            $project = [
                'id'            => $id,
                'name'          => $name,
                'brief'         => $brief,
                'clientid'      => $client,
                'status'        => $status,
                'production'    => $qty,
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
            $ProjectModel = new ProjectModel();
            $RabModel = new RabModel();

            // Populating Data
            $rabs       = $RabModel->where('proejctid', $id)->find();

            // Deleting Rab
            foreach ($rabs as $rab) {
                $RabModel->delete($rab['id']);
            }
            // Delete Project
            $ProjectModel->delete($id);

            return redirect()->back()->with('errors', "Data berhasil di hapus");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
