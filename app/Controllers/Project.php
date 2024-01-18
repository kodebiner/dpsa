<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\PaketModel;
use App\Models\RabModel;
use App\Models\DesignModel;
use App\Models\ProductionModel;

use Mpdf\Mpdf;

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
            $DesignModel            = new DesignModel();
            $ProductionModel        = new ProductionModel();

            // Populating Data
            $pakets                 = $PaketModel->findAll();
            $company                = $CompanyModel->where('status !=', "0")->find();
            $projects               = $ProjectModel->paginate(10, 'projects');

            $projectdata    = [];
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    $paketid    = [];

                    // RAB
                    $rabs       = $RabModel->where('projectid', $project['id'])->find();
                    foreach ($rabs as $rab) {
                        $paketid[]  = $rab['paketid'];

                        // MDL RAB
                        $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                        foreach ($rabmdl as $mdlr) {
                            $projectdata[$project['id']]['rab'][$rab['id']]  = [
                                'id'            => $mdlr['id'],
                                'name'          => $mdlr['name'],
                                'length'        => $mdlr['length'],
                                'width'         => $mdlr['width'],
                                'height'        => $mdlr['height'],
                                'volume'        => $mdlr['volume'],
                                'denomination'  => $mdlr['denomination'],
                                'keterangan'    => $mdlr['keterangan'],
                                'qty'           => $rab['qty'],
                                'price'         => (Int)$rab['qty'] * (Int)$mdlr['price'],
                            ];
                        }
                    }

                    if (!empty($rabs)) {
                        // Paket
                        $paketdata      = [];
                        $paketproject   = $PaketModel->find($paketid);
                        foreach ($paketproject as $pack) {
                            $paketdata[]    = $pack['id'];
                            $projectdata[$project['id']]['paket'][$pack['id']]['name'] = $pack['name'];
        
                            // MDL Paket
                            $mdlpack        = $MdlModel->where('paketid', $pack['id'])->find();
                            foreach ($mdlpack as $mdl) {
                                $projectdata[$project['id']]['paket'][$pack['id']]['mdl'][$mdl['id']] = [
                                    'id'            => $mdl['id'],
                                    'name'          => $mdl['name'],
                                    'length'        => $mdl['length'],
                                    'width'         => $mdl['width'],
                                    'height'        => $mdl['height'],
                                    'volume'        => $mdl['volume'],
                                    'denomination'  => $mdl['denomination'],
                                    'keterangan'    => $mdl['keterangan'],
                                    'price'         => $mdl['price'],
                                ];
        
                                // Checklist RAB
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
                    } else {
                        $paketdata      = [];
                        $paketproject   = [];
                    }

                    // Autocomplete Paket
                    if (!empty($paketdata)) {
                        $projectdata[$project['id']]['autopaket']   = $PaketModel->whereNotIn('id', $paketdata)->find();
                    } else {
                        $projectdata[$project['id']]['autopaket']   = [];
                    }

                    // Design
                    $projectdata[$project['id']]['design']          = $DesignModel->where('projectid', $project['id'])->first();

                    // Production
                    $productions                                    = $ProductionModel->where('projectid', $project['id'])->find();
                    if (!empty($productions)) {
                        foreach ($productions as $production) {
    
                            // MDL Production
                            $mdlprod    = $MdlModel->where('id', $production['mdlid'])->find();
                            foreach ($mdlprod as $mdlp) {
                                $projectdata[$project['id']]['production'][$production['id']]  = [
                                    'id'                => $production['id'],
                                    'name'              => $mdlp['name'],
                                    'gambar_kerja'      => $production['gambar_kerja'],
                                    'mesin_awal'        => $production['mesin_awal'],
                                    'tukang'            => $production['tukang'],
                                    'mesin_lanjutan'    => $production['mesin_lanjutan'],
                                    'finishing'         => $production['finishing'],
                                    'packing'           => $production['packing'],
                                    'setting'           => $production['setting'],
                                ];
                            }
                        }
                    } else {
                        $mdlprod    = [];
                        $projectdata[$project['id']]['production']   = [];
                    }
                }
            } else {
                $rabs           = [];
            }

            $data                   = $this->data;
            $data['title']          = "Proyek";
            $data['description']    = "Data Proyek";
            $data['projects']       = $projects;
            $data['projectdata']    = $projectdata;
            $data['company']        = $company;
            $data['pakets']         = $pakets;
            $data['rabs']           = $rabs;
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
                'company' => [
                    'label'  => 'Nama Klien',
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
                'clientid'      => $input['company'],
                'status'        => 1,
            ];
            $ProjectModel->insert($project);

            return redirect()->back()->with('message', "Data berhasil di simpan.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('production.project.edit', $this->data['uid'])) {
            // Calling Model
            $ProjectModel       = new ProjectModel();
            $RabModel           = new RabModel();
            $MdlModel           = new MdlModel();
            $DesignModel        = new DesignModel();
            $ProductionModel    = new ProductionModel();

            // initialize
            $input  = $this->request->getPost();
            $pro    = $ProjectModel->find($id);

            if ($input['name'] != $pro['name']) {
                $name = $input['name'];
            } else {
                $name = $pro['name'];
            }

            if ($input['company'] != $pro['clientid']) {
                $client = $input['company'];
            } else {
                $client = $pro['clientid'];
            }

            if (!empty($input['spk'])) {
                if ($input['spk'] != $pro['spk']) {
                    unlink(FCPATH . '/img/spk/' . $pro['spk']);
                    $spk        = $input['spk'];
                    $statusspk  = 1;
                    $status     = 4;

                    // Crating Rows In Production
                    $sphs = $RabModel->where('projectid', $id)->where('qty !=', '0')->find();
                    foreach ($sphs as $sph) {
                        for ($i = 1; $i <= $sph['qty']; $i++) {
                            $productiondata = [
                                'mdlid'     => $sph['mdlid'],
                                'projectid' => $sph['projectid'],
                            ];
                            $ProductionModel->insert($productiondata);
                        }
                    }
                } else {
                    $spk        = $pro['spk'];
                    $statusspk  = $pro['status_spk'];
                }
            } else {
                $spk        = $pro['spk'];
                $statusspk  = $pro['status_spk'];
            }

            // Validation Rules
            $rules = [
                'name' => [
                    'label'  => 'Nama Proyek',
                    'rules'  => 'required|is_unique[project.name,project.id,'.$id.']',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Harap menggunakan {field} lain',
                    ],
                ],
                'company' => [
                    'label'  => 'Nama Klien',
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
            if (isset($input['checked' . $id])) {
                foreach ($input['eqty' . $id] as $mdlid => $qty) {
                    if (isset($input['checked' . $id][$mdlid])) {
                        $rab = $RabModel->where('mdlid', $mdlid)->where('projectid', $id)->first();
                        if ((!empty($rab)) && ($rab['qty'] != $input['eqty' . $id][$mdlid])) {
                            if ($input['eqty' . $id][$mdlid] != 0) {
                                $RabModel->save(['id' => $rab['id'], 'qty' => $qty]);
                            } else {
                                $RabModel->delete($rab);
                            }
                        } elseif (empty($rab)) {
                            $mdl = $MdlModel->find($mdlid);
                            if ($input['eqty' . $id][$mdlid] != 0) {
                                $datarab = [
                                    'mdlid'     => $mdlid,
                                    'projectid' => $id,
                                    'paketid'   => $mdl['paketid'],
                                    'qty'       => $qty
                                ];
                                $RabModel->save($datarab);
                            }
                        }
                    } else {
                        $rab = $RabModel->where('mdlid', $mdlid)->where('projectid', $id)->first();
                        if (!empty($rab)) {
                            $RabModel->delete($rab['id']);
                        }
                    }
                }
            }

            // Design Data
            if (!empty($input['submitted'])) {
                $design = $DesignModel->where('projectid', $id)->first();
                if (empty($design)) {
                    $datadesign = [
                        'projectid'     => $id,
                        'submitted'     => $input['submitted'],
                        'status'        => 0,
                    ];
                    $DesignModel->insert($datadesign);
                } else {
                    unlink(FCPATH.'/img/design/'.$design['submitted']);
                    $datadesign = [
                        'id'            => $design['id'],
                        'projectid'     => $id,
                        'submitted'     => $input['submitted'],
                        'status'        => 0,
                    ];
                    $DesignModel->save($datadesign);
                }

                // Save Status Project
                $status = 2;
            } else {
                $status = 1;
            }

            // Production Data
            // Gambar Kerja
            if (isset($input['gambarkerja' . $id])) {
                foreach ($input['gambarkerja' . $id] as $prodid => $gambar) {
                    $productioninput = [
                        'id'                => $prodid,
                        'gambar_kerja'      => $gambar,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Mesin Awal
            if (isset($input['mesinawal' . $id])) {
                foreach ($input['mesinawal' . $id] as $prodid => $mesinawal) {
                    $productioninput = [
                        'id'                => $prodid,
                        'mesin_awal'        => $mesinawal,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Tukang
            if (isset($input['tukang' . $id])) {
                foreach ($input['tukang' . $id] as $prodid => $tukang) {
                    $productioninput = [
                        'id'                => $prodid,
                        'tukang'            => $tukang,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Mesin Lanjutan
            if (isset($input['mesinlanjutan' . $id])) {
                foreach ($input['mesinlanjutan' . $id] as $prodid => $mesinlanjutan) {
                    $productioninput = [
                        'id'                => $prodid,
                        'mesin_lanjutan'    => $mesinlanjutan,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Finishing
            if (isset($input['finishing' . $id])) {
                foreach ($input['finishing' . $id] as $prodid => $finishing) {
                    $productioninput = [
                        'id'                => $prodid,
                        'finishing'         => $finishing,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Packing
            if (isset($input['packing' . $id])) {
                foreach ($input['packing' . $id] as $prodid => $packing) {
                    $productioninput = [
                        'id'                => $prodid,
                        'packing'           => $packing,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Setting
            if (isset($input['setting' . $id])) {
                foreach ($input['setting' . $id] as $prodid => $setting) {
                    $productioninput = [
                        'id'                => $prodid,
                        'setting'           => $setting,
                    ];
                    $ProductionModel->save($productioninput);
                }
            }

            // Project Data
            $project = [
                'id'            => $id,
                'name'          => $name,
                'clientid'      => $client,
                'spk'           => $spk,
                'status_spk'    => $statusspk,
                'status'        => $status,
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
            $ProjectModel   = new ProjectModel();
            $RabModel       = new RabModel();
            $DesignModel    = new DesignModel();

            // Populating Data
            $rabs       = $RabModel->where('projectid', $id)->find();
            $design     = $DesignModel->where('projectid', $id)->first();

            // Deleting Rab
            foreach ($rabs as $rab) {
                $RabModel->delete($rab['id']);
            }

            // Deleting Design
            $DesignModel->delete($design['id']);

            // Delete Project
            $ProjectModel->delete($id);

            return redirect()->back()->with('error', "Data berhasil di hapus");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function sphprint($id)
    {
        // Calling models
        $ProjectModel   = new ProjectModel;
        $CompanyModel   = new CompanyModel();
        $RabModel       = new RabModel();
        $PaketModel     = new PaketModel();
        $MdlModel       = new MdlModel();

        $projects = $ProjectModel->find($id);
        $client   = $CompanyModel->where('id', $projects['clientid'])->first();

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = lang('Global.titleDashboard');
        $data['description']    = lang('Global.dashboardDescription');
        $data['projects']       = $projects;
        $data['rabs']           = $RabModel->findAll();
        $data['pakets']         = $PaketModel->findAll();
        $data['mdls']           = $MdlModel->findAll();
        $data['client']         = $client;

        require_once(APPPATH . "ThirdParty/mpdf_v8.0.3-master/vendor/autoload.php");
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage("P", "", "", "", "", "15", "15", "15", "15", "", "", "", "", "", "", "", "", "", "", "", "A4");

        $date = date_create($projects['created_at']);
        $filename = "LaporanSph".$projects['name']." ".date_format($date,'d-m-Y').".pdf";
        $html = view('Views/sphprint',$data);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');

    }

}
