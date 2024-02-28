<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\PaketModel;
use App\Models\RabModel;
use App\Models\DesignModel;
use App\Models\ProductionModel;
use App\Models\BastModel;
use App\Models\InvoiceModel;
use App\Models\ReferensiModel;
use App\Models\UserModel;
use App\Models\CustomRabModel;
use App\Models\GconfigModel;
use Mpdf\Tag\Em;

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
        // Calling Model
        $ProjectModel           = new ProjectModel();
        $BastModel              = new BastModel();
        $CompanyModel           = new CompanyModel();
        $MdlModel               = new MdlModel();
        $MdlPaketModel          = new MdlPaketModel();
        $PaketModel             = new PaketModel();
        $RabModel               = new RabModel();
        $DesignModel            = new DesignModel();
        $ProductionModel        = new ProductionModel();
        $InvoiceModel           = new InvoiceModel();
        $ReferensiModel         = new ReferensiModel();
        $UserModel              = new UserModel();
        $CustomRabModel         = new CustomRabModel();

        // Populating Data
        $pakets                 = $PaketModel->where('parentid !=', 0)->find();
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
                            'proid'         => $project['id'],
                            'name'          => $mdlr['name'],
                            'length'        => $mdlr['length'],
                            'width'         => $mdlr['width'],
                            'height'        => $mdlr['height'],
                            'volume'        => $mdlr['volume'],
                            'denomination'  => $mdlr['denomination'],
                            'keterangan'    => $mdlr['keterangan'],
                            'qty'           => $rab['qty'],
                            'price'         => (int)$rab['qty'] * (int)$mdlr['price'],
                            'oriprice'      => (int)$mdlr['price'],
                        ];
                    }
                }

                // Custom RAB
                $projectdata[$project['id']]['customrab']         = $CustomRabModel->where('projectid', $project['id'])->find();

                if (!empty($rabs)) {
                    // Paket
                    $paketdata      = [];
                    $paketproject   = $PaketModel->find($paketid);
                    foreach ($paketproject as $pack) {
                        $paketdata[]    = $pack['id'];
                        $projectdata[$project['id']]['paket'][$pack['id']]['id']    = $pack['id'];
                        $projectdata[$project['id']]['paket'][$pack['id']]['name']  = $pack['name'];

                        // MDL Paket
                        $mdlpaket       = $MdlPaketModel->where('paketid', $pack['id'])->find();

                        // MDL
                        foreach ($mdlpaket as $mdlpak) {
                            $mdlpack        = $MdlModel->where('id', $mdlpak['mdlid'])->find();
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
                    }
                } else {
                    $paketdata      = [];
                    $paketproject   = [];
                }

                // Autocomplete Paket
                if (!empty($paketdata)) {
                    $projectdata[$project['id']]['autopaket']   = $PaketModel->whereNotIn('id', $paketdata)->where('parentid !=', 0)->find();
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
                                'mdlid'             => $production['mdlid'],
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

                // BAST
                $projectdata[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->find();

                // PRODUCTION VALUE
                if (!empty($projectdata[$project['id']]['rab'])) {
                    $price = [];
                    foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                        $price[] = [
                            'id'        => $mdldata['id'],
                            'proid'     => $mdldata['proid'],
                            'price'     => $mdldata['oriprice'],
                            'sumprice'  => $mdldata['price'],
                            'qty'       => $mdldata['qty']
                        ];
                    }

                    $total = array_sum(array_column($price, 'sumprice'));

                    $progresdata = [];
                    $datamdlid = [];
                    foreach ($price as $progresval) {
                        $progresdata[] = [
                            'id'    => $progresval['id'], // mdlid
                            'proid' => $progresval['proid'],
                            'val'   => (($progresval['price'] / $total) * 65) / 7,
                        ];
                        $datamdlid[] = $progresval['id'];
                    }

                    $productval = $ProductionModel->where('projectid', $project['id'])->whereIn('mdlid', $datamdlid)->find(); // cek projectid

                    $progress = [];
                    foreach ($productval as $proses) {
                        foreach ($progresdata as $value) {
                            if ($proses['mdlid'] === $value['id']) {
                                if ($proses['gambar_kerja'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['mesin_awal'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['tukang'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['mesin_lanjutan'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['finishing'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['packing'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                                if ($proses['setting'] === "1") {
                                    array_push($progress, $value['val']);
                                }
                            }
                        }
                    }

                    $projectdata[$project['id']]['progress']   = array_sum($progress);
                }

                // INVOICE
                $projectdata[$project['id']]['invoice1'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '1')->first();
                $projectdata[$project['id']]['invoice2'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '2')->first();
                $projectdata[$project['id']]['invoice3'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '3')->first();
                $projectdata[$project['id']]['invoice4'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '4')->first();

                // REFERENSI
                $projectdata[$project['id']]['referensi']   = $ReferensiModel->findAll();

                // PIC
                $projectdata[$project['id']]['pic']         = $UserModel->where('parentid', null)->find();
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
    }

    public function mdl()
    {
        // Calling Model
        $MdlModel       = new MdlModel();
        $MdlPaketModel  = new MdlPaketModel();
        $PaketModel     = new PaketModel();

        // Initialize
        $input      = $this->request->getPost();

        // Populating Data
        $pakets     = $PaketModel->find($input['id']);
        $mdlpaket   = $MdlPaketModel->where('paketid', $input['id'])->find();
        $return     = array();
        $mdlid      = array();
        foreach ($mdlpaket as $mdlpak) {
            $mdls       = $MdlModel->where('id', $mdlpak['mdlid'])->find();

            foreach ($mdls as $mdl) {
                $mdlid[]    = $mdl['id'];
            }

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
        }

        die(json_encode($return));
    }

    public function create()
    {
        // Calling Model
        $ProjectModel   = new ProjectModel();
        $MdlModel       = new MdlModel();
        $RabModel       = new RabModel();
        $DesignModel    = new DesignModel();

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
            'status'        => 1
        ];

        if (isset($input['designtype'])) {
            $project['type_design'] = 1;
            $project['ded'] = $input['design'];
        } else {
            $project['type_design'] = 0;
        }

        $ProjectModel->insert($project);

        $projectid = $ProjectModel->getInsertID();

        return redirect()->back()->with('message', "Data berhasil di simpan.");
    }

    public function update($id)
    {
        // Calling Model
        $ProjectModel       = new ProjectModel();
        $RabModel           = new RabModel();
        $MdlModel           = new MdlModel();
        $MdlPaketModel      = new MdlPaketModel();
        $DesignModel        = new DesignModel();
        $ProductionModel    = new ProductionModel();
        $BastModel          = new BastModel();
        $InvoiceModel       = new InvoiceModel();
        $CustomRabModel     = new CustomRabModel();


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
                'rules'  => 'required|is_unique[project.name,project.id,' . $id . ']',
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
            foreach ($input['eqty' . $id] as $paketid => $mdls) {
                foreach ($mdls as $mdlid => $qty) {
                    if (isset($input['checked' . $id][$mdlid])) {
                        $rab = $RabModel->where('mdlid', $mdlid)->where('paketid', $paketid)->where('projectid', $id)->first();
                        if ((!empty($rab)) && ($rab['qty'] != $input['eqty' . $id][$paketid][$mdlid])) {
                            if ($input['eqty' . $id][$paketid][$mdlid] != 0) {
                                $RabModel->save(['id' => $rab['id'], 'qty' => $qty]);
                            } else {
                                $RabModel->delete($rab);
                            }
                        } elseif (empty($rab)) {
                            if ($input['eqty' . $id][$paketid][$mdlid] != 0) {
                                $datarab = [
                                    'mdlid'     => $mdlid,
                                    'projectid' => $id,
                                    'paketid'   => $paketid,
                                    'qty'       => $qty
                                ];
                                $RabModel->save($datarab);
                            }
                        }
                    } else {
                        $rab = $RabModel->where('mdlid', $mdlid)->where('paketid', $paketid)->where('projectid', $id)->first();
                        if (!empty($rab)) {
                            $RabModel->delete($rab['id']);
                        }
                    }
                }
            }
        }

        // Custom RAB Data
        foreach ($input['customprice' . $id] as $priceKey => $cusprice) {
            if (!empty($cusprice)) {
                $custrab['price']       = $cusprice;
                $custrab['projectid']   = $id;

                foreach ($input['customname' . $id] as $nameKey => $cusname) {
                    if ($nameKey === $priceKey) {
                        $custrab['name']    = $cusname;
                    }
                }

                $CustomRabModel->insert($custrab);
            }
        }

        // Update Custom RAB
        if (!empty($input['pricecustrab' . $id])) {
            foreach ($input['pricecustrab' . $id] as $custrabid => $pricecustrab) {
                $customrabdata  = $CustomRabModel->find($custrabid);

                if ($pricecustrab != $customrabdata['price']) {
                    if (!empty($pricecustrab)) {
                        if ($pricecustrab != 0) {
                            $datacustomrab  = [
                                'id'    => $custrabid,
                                'price' => $pricecustrab,
                            ];
                            $CustomRabModel->save($datacustomrab);
                        } else {
                            $CustomRabModel->delete($customrabdata);
                        }
                    }
                    else {
                        $CustomRabModel->delete($customrabdata);
                    }
                }
            }
        }
        if (!empty($input['namecustrab' . $id])) {
            foreach ($input['namecustrab' . $id] as $idcustrab => $namecustrab) {
                $custrabdata  = $CustomRabModel->find($idcustrab);

                if (!empty($custrabdata)) {
                    if ($namecustrab != $custrabdata['name']) {
                        if (!empty($namecustrab)) {
                            $updatecustrab  = [
                                'id'    => $idcustrab,
                                'name'  => $namecustrab,
                            ];
                            
                            $CustomRabModel->save($updatecustrab);
                        } else {
                            $CustomRabModel->delete($customrabdata);
                        }
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
                unlink(FCPATH . '/img/design/' . $design['submitted']);
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

        // JATUH TEMPO BAST
        $tgltempobast = "";
        if (!empty($input['jatuhtempobast' . $id])) {
            $tgltempobast = $input['jatuhtempobast' . $id] . " 00:00:00";
        }

        // FINANCE

        // FUNCTION INVOICE
        $idinv1 = $InvoiceModel->where('projectid', $id)->where('status', '1')->first();
        $idinv2 = $InvoiceModel->where('projectid', $id)->where('status', '2')->first();
        $idinv3 = $InvoiceModel->where('projectid', $id)->where('status', '3')->first();
        $idinv4 = $InvoiceModel->where('projectid', $id)->where('status', '4')->first();

        // if (!empty($input['dateinvoice1' . $id]) && !empty($input['referensiinvoice1' . $id]) && !empty($input['pphinvoice1' . $id]) && !empty( $input['emailinvoice1' . $id]) && !empty($idinv1)){
        if (isset($input['dateinvoice1' . $id], $input['referensiinvoice1' . $id], $input['pphinvoice1' . $id], $input['emailinvoice1' . $id]) && !empty($idinv1)) {
            $date1 = $input['dateinvoice1' . $id];
            $newDate1 = date('Y-m-d H:i:s', strtotime($date1));
            $invoice1 = [
                'id'            => $idinv1['id'],
                'projectid'     => $id,
                'jatuhtempo'    => $newDate1,
                'referensi'     => $input['referensiinvoice1' . $id],
                'pph23'         => $input['pphinvoice1' . $id],
                'email'         => $input['emailinvoice1' . $id],
                'status'        => "1",
                'pic'           => $input['picinvoice1' . $id],
            ];
            $InvoiceModel->save($invoice1);
        } elseif (isset($input['referensiinvoice1' . $id], $input['picinvoice1' . $id]) && !empty($input['dateinvoice1' . $id]) && !empty($input['emailinvoice1' . $id]) && !empty($input['pphinvoice1' . $id])) {
            // } else {
            $invoice1 = [
                'projectid' => $id,
                'jatuhtempo' => $input['dateinvoice1' . $id] . " 00:00:00",
                'referensi' => $input['referensiinvoice1' . $id],
                'pph23'     => $input['pphinvoice1' . $id],
                'email'     => $input['emailinvoice1' . $id],
                'status'    => "1",
                'pic'       => $input['picinvoice1' . $id],
            ];
            $InvoiceModel->save($invoice1);
        }

        // INVOICE 2 UPDATE
        if (isset($input['dateinvoice2' . $id], $input['referensiinvoice2' . $id], $input['pphinvoice2' . $id], $input['emailinvoice2' . $id]) && !empty($idinv2)) {
            // if (!empty($input['dateinvoice2' . $id]) && !empty($input['referensiinvoice2' . $id]) && !empty($input['pphinvoice2' . $id]) && !empty( $input['emailinvoice2' . $id]) && !empty($idinv2)){
            $date2 = $input['dateinvoice2' . $id];
            $newDate2 = date('Y-m-d H:i:s', strtotime($date2));

            $invoice2 = [
                'id'        => $idinv2['id'],
                'projectid' => $id,
                'jatuhtempo' => $newDate2, //date_format($date, 'm-d-Y H:i:s'),
                'referensi' => $input['referensiinvoice2' . $id],
                'pph23'     => $input['pphinvoice2' . $id],
                'email'     => $input['emailinvoice2' . $id],
                'status'    => "2",
                'pic'       => $input['picinvoice2' . $id],
            ];

            $InvoiceModel->save($invoice2);
        } elseif (isset($input['referensiinvoice2' . $id], $input['picinvoice2' . $id]) && !empty($input['dateinvoice2' . $id]) && !empty($input['referensiinvoice2' . $id]) && !empty($input['emailinvoice2' . $id]) && !empty($input['pphinvoice2' . $id])) {
            // } else {
            $invoice1 = [
                'projectid' => $id,
                'jatuhtempo' => $input['dateinvoice2' . $id] . " 00:00:00",
                'referensi'  => $input['referensiinvoice2' . $id],
                'pph23'     => $input['pphinvoice2' . $id],
                'email'     => $input['emailinvoice2' . $id],
                'status'    => "2",
                'pic'       => $input['picinvoice2' . $id],
            ];
            $InvoiceModel->save($invoice1);
        }

        // INVOICE 3 UPDATE
        if (isset($input['dateinvoice3' . $id], $input['referensiinvoice3' . $id], $input['pphinvoice3' . $id], $input['emailinvoice3' . $id]) && !empty($idinv3)) {
            // if (!empty($input['dateinvoice3' . $id]) && !empty($input['referensiinvoice3' . $id]) && !empty($input['pphinvoice3' . $id]) && !empty( $input['emailinvoice3' . $id]) && !empty($idinv3)){
            $date3 = $input['dateinvoice3' . $id];
            $newDate3 = date('Y-m-d H:i:s', strtotime($date3));
            $invoice3 = [
                'id'        => $idinv3['id'],
                'projectid' => $id,
                'jatuhtempo' => $newDate3 . " 00:00:00", //date_format($date, 'm-d-Y H:i:s'),
                'referensi' => $input['referensiinvoice3' . $id],
                'pph23'     => $input['pphinvoice3' . $id],
                'email'     => $input['emailinvoice3' . $id],
                'status'    => "3",
                'pic'       => $input['picinvoice3' . $id],
            ];
            $InvoiceModel->save($invoice3);
        } elseif (isset($input['referensiinvoice3' . $id], $input['picinvoice3' . $id]) && !empty($input['dateinvoice3' . $id]) && !empty($input['referensiinvoice3' . $id]) && !empty($input['emailinvoice3' . $id]) && !empty($input['pphinvoice3' . $id])) {
            // } else {
            $invoice1 = [
                'projectid' => $id,
                'jatuhtempo' => $input['dateinvoice3' . $id] . " 00:00:00",
                'referensi'  => $input['referensiinvoice3' . $id],
                'pph23'     => $input['pphinvoice3' . $id],
                'email'     => $input['emailinvoice3' . $id],
                'status'    => "3",
                'pic'       => $input['picinvoice3' . $id],
            ];
            $InvoiceModel->save($invoice1);
        }

        // INVOICE 4 UPDATE
        if (isset($input['dateinvoice4' . $id], $input['referensiinvoice4' . $id], $input['pphinvoice4' . $id], $input['emailinvoice4' . $id]) && !empty($idinv4)) {
            // if (!empty($input['dateinvoice4' . $id]) && !empty($input['referensiinvoice4' . $id]) && !empty($input['pphinvoice4' . $id]) && !empty( $input['emailinvoice4' . $id]) && !empty($idinv4)){
            $date4 = $input['dateinvoice3' . $id];
            $newDate4 = date('Y-m-d H:i:s', strtotime($date4));
            $invoice4 = [
                'id'        => $idinv4['id'],
                'projectid' => $id,
                'jatuhtempo' => $newDate4 . " 00:00:00", //date_format($date, 'm-d-Y H:i:s'),
                'referensi' => $input['referensiinvoice4' . $id],
                'pph23'     => $input['pphinvoice4' . $id],
                'email'     => $input['emailinvoice4' . $id],
                'status'    => "4",
                'pic'       => $input['picinvoice4' . $id],
            ];
            $InvoiceModel->save($invoice4);
        } elseif (isset($input['referensiinvoice4' . $id], $input['picinvoice4' . $id]) && !empty($input['dateinvoice4' . $id]) && !empty($input['referensiinvoice4' . $id]) && !empty($input['emailinvoice4' . $id]) && !empty($input['pphinvoice4' . $id])) {
            // } else {
            $invoice1 = [
                'projectid' => $id,
                'jatuhtempo' => $input['dateinvoice4' . $id] . " 00:00:00",
                'referensi'  => $input['referensiinvoice4' . $id],
                'pph23'     => $input['pphinvoice4' . $id],
                'email'     => $input['emailinvoice4' . $id],
                'status'    => "4",
                'pic'       => $input['picinvoice4' . $id],
            ];
            $InvoiceModel->save($invoice1);
        }
        // END NEW INVOICE FUNCTION

        // Project Data
        $project = [
            'id'            => $id,
            'name'          => $name,
            'clientid'      => $client,
            'spk'           => $spk,
            'status_spk'    => $statusspk,
            'status'        => $status,
            'inv4'          => $tgltempobast,
        ];
        $ProjectModel->save($project);

        return redirect()->back()->with('message', "Data berhasil di perbaharui.");
    }

    public function delete($id)
    {
        // Calling Model
        $ProjectModel       = new ProjectModel();
        $RabModel           = new RabModel();
        $DesignModel        = new DesignModel();
        $BastModel          = new BastModel();
        $ProductionModel    = new ProductionModel();

        // Populating Data
        $rabs               = $RabModel->where('projectid', $id)->find();
        $design             = $DesignModel->where('projectid', $id)->first();
        $productions        = $ProductionModel->where('projectid', $id)->find();
        $basts              = $BastModel->where('projectid', $id)->find();

        // Deleting Rab
        if (!empty($rab)) {
            foreach ($rabs as $rab) {
                $RabModel->delete($rab['id']);
            }
        }

        // Deleting Design
        if (!empty($design)) {
            $DesignModel->delete($design['id']);
        }

        // Deleting Production
        if (!empty($productions)) {
            foreach ($productions as $production) {
                $ProductionModel->delete($production['id']);
            }
        }

        // Deleting Bast & Sertrim
        if (!empty($basts)) {
            foreach ($basts as $bast) {
                $BastModel->delete($bast['id']);
            }
        }

        // Delete Project
        $ProjectModel->delete($id);

        return redirect()->back()->with('error', "Data berhasil di hapus");
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

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 5,
        ]);
        $mpdf->Image('./img/logo.png', 80, 0, 210, 297, 'png', '', true, false);
        $mpdf->showImageErrors = true;
        $mpdf->AddPage("L", "", "", "", "", "15", "15", "2", "15", "", "", "", "", "", "", "", "", "", "", "", "A4-L");

        $date = date_create($projects['created_at']);
        $filename = "LaporanSph" . $projects['name'] . " " . date_format($date, 'd-m-Y') . ".pdf";
        $html = view('Views/sphprint', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
    }

    public function sphview($id)
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
        return view('sphprint', $data);
    }

    public function invoice($id)
    {
        // Calling models
        $ProjectModel   = new ProjectModel;
        $CompanyModel   = new CompanyModel();
        $RabModel       = new RabModel();
        $PaketModel     = new PaketModel();
        $MdlModel       = new MdlModel();
        $BastModel      = new BastModel();
        $GconfigModel   = new GconfigModel();
        $InvoiceModel   = new InvoiceModel();
        $ReferensiModel = new ReferensiModel();
        $UserModel      = new UserModel();

        // PROJECT DATA
        $projects = $ProjectModel->find($id);
        $client   = $CompanyModel->where('id', $projects['clientid'])->first();
        $gconf    = $GconfigModel->first();

        // INVOICE 
        $invoice1  = $InvoiceModel->where('projectid',$projects['id'])->where('status','1')->first();
        $invoice2  = $InvoiceModel->where('projectid',$projects['id'])->where('status','2')->first();
        $invoice3  = $InvoiceModel->where('projectid',$projects['id'])->where('status','3')->first();
        $invoice4  = $InvoiceModel->where('projectid',$projects['id'])->where('status','4')->first();

        // CLIENT DATA
        $client   = $CompanyModel->find($projects['clientid']);

        // BAST DATA
        $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
        $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();

        // RAB
        $rabs       = $RabModel->where('projectid', $projects['id'])->find();
        $rabdata    = [];
        foreach ($rabs as $rab) {
            $paketid[]  = $rab['paketid'];

            // MDL RAB
            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
            foreach ($rabmdl as $mdlr) {
                $rabdata[]  = [
                    'id'            => $mdlr['id'],
                    'proid'         => $projects['id'],
                    'name'          => $mdlr['name'],
                    'length'        => $mdlr['length'],
                    'width'         => $mdlr['width'],
                    'height'        => $mdlr['height'],
                    'volume'        => $mdlr['volume'],
                    'denomination'  => $mdlr['denomination'],
                    'keterangan'    => $mdlr['keterangan'],
                    'qty'           => $rab['qty'],
                    'price'         => (int)$rab['qty'] * (int)$mdlr['price'],
                    'oriprice'      => (int)$mdlr['price'],
                ];
            }
        }

        // TOTAL RAB PRICE
        $total = array_sum(array_column($rabdata, 'price'));
        // PPN
        $ppn        = $gconf['ppn'];

        // Invoice Data Array
        $termin     = "";
        $progress   = "";
        $nilaispk   = "";
        $dateinv    = "";
        $dateline   = "";
        $priceppn   = "";
        $pph        = "";
        $referensi  = "";
        $email      = "";
        $status     = "";
        $pic        = "";

        // INVOICE I
        if ($projects['status_spk'] === "1" && !empty($invoice1) && !empty($projects['inv1'])) {
            $termin     = "30";
            $progress   = "30";
            $nilaispk   = $total - ((70 / 100) * $total);
            $dateinv    = $projects['inv1'];
            $dateline   = $invoice1['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice1['pph23'];
            $referensi  = $invoice1['referensi'];
            $email      = $invoice1['email'];
            $status     = $invoice1['status'];
            $pic        = $invoice1['pic'];
        }

        // INVOICE II
        if (!empty($sertrim) && !empty($projects['inv2']) && !empty($invoice3)) {
            $termin     = "30";
            $progress   = "60";
            $nilaispk   = $total - ((70 / 100) * $total);
            $dateinv    = $projects['inv2'];
            $dateline   = $invoice2['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice2['pph23'];
            $referensi  = $invoice2['referensi'];
            $email      = $invoice2['email'];
            $status     = $invoice2['status'];
            $pic        = $invoice2['pic'];
        }

        // INVOICE III
        if (!empty($bast) && !empty($projects['inv3']) && !empty($invoice3)) {
            $termin     = "35";
            $progress   = "95";
            $nilaispk   = $total - ((65 / 100) * $total);
            $dateinv    = $projects['inv3'];
            $dateline   = $invoice3['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice3['pph23'];
            $referensi  = $invoice3['referensi'];
            $email      = $invoice3['email'];
            $status     = $invoice3['status'];
            $pic        = $invoice3['pic'];
        }

        // INVOICE IV
        if (!empty($bast) && !empty($projects['inv4']) && !empty($invoice4)) {

            $termin     = "5";
            $progress   = "100";
            $nilaispk   = $total - ((95 / 100) * $total);
            $dateinv    = $projects['inv4'];
            $dateline   = $invoice4['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice4['pph23'];
            $referensi  = $invoice4['referensi'];
            $email      = $invoice4['email'];
            $status     = $invoice4['status'];
            $pic        = $invoice4['pic'];


            // Dateline Invoice 2 Interval
            // $dateinv4       = $inv4;
            // $date           = date_create($dateinv4);
            // $dateformat     = date_format($date, "Y-m-d");
            // $hari           = date_create($dateformat);
            // date_add($hari, date_interval_create_from_date_string('14 days'));
            // $datelineinv4 = date_format($hari, 'Y-m-d');

            // $dateline = $datelineinv4;
        }

        // DATA REFERENSI
        $refdata    = "";
        $refname    = "";
        $refacc     = "";
        $refbank    = "";
        if(!empty($referensi)){
            $refdata = $ReferensiModel->where('id',$referensi)->first();
            $refname    = $refdata['name'];
            $refacc     = $refdata['no_rek'];
            $refbank    = $refdata['bank'];
        }

        // DATA PIC
        $picdata = "";
        $picname = "";
        if(!empty($pic)){
            $picdata    = $UserModel->where('id',$pic)->first();
            $picname    = $picdata->name;
        }


        $invoicedata = [
            'termin'    => $termin,
            'progress'  => $progress,
            'nilai_spk' => $nilaispk,
            'dateinv'   => $dateinv,
            'dateline'  => $dateline,
            'total'     => $total,
            'ppn'       => $gconf['ppn'],
            'priceppn'  => $total + (($gconf['ppn'] / 100) * $total),
            'pph'       => $pph,
            'referensi' => $refname,
            'refacc'    => $refacc,
            'refbank'   => $refbank,
            'email'     => $email,
            'pic'       => $picname,
        ];

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = lang('Global.titleDashboard');
        $data['description']    = lang('Global.dashboardDescription');
        $data['projects']       = $projects;
        $data['rabs']           = $rabdata;
        $data['pakets']         = $PaketModel->findAll();
        $data['mdls']           = $MdlModel->findAll();
        $data['client']         = $client;
        $data['invoice']        = $invoicedata;

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 5,
        ]);
        $mpdf->Image('./img/logo.png', 80, 0, 210, 297, 'png', '', true, false);
        $mpdf->showImageErrors = true;
        $mpdf->AddPage("L", "", "", "", "", "15", "15", "2", "15", "", "", "", "", "", "", "", "", "", "", "", "A4");

        $mpdf->SetWatermarkImage(
        './img/logo.png',
         0.3,
        '',
        // [50,50,50],
        // [50,50],
        [70,40],
        );
        $mpdf->showWatermarkImage = true;
        $mpdf->setFooter('{PAGENO} / {nb}');
        $date = date_create($projects['created_at']);
        $filename = "invoice" .$status."-".$projects['name'] . " " . date_format($date, 'd-m-Y') . ".pdf";
        $html = view('Views/invoice', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
    }

    public function invoiceview($id)
    {
        // // Calling models
        // $ProjectModel   = new ProjectModel;
        // $CompanyModel   = new CompanyModel();
        // $BastModel      = new BastModel();
        // $RabModel       = new RabModel();
        // $PaketModel     = new PaketModel();
        // $MdlModel       = new MdlModel();
        // $invoiceModel   = new InvoiceModel();

        // // PROJECT DATA
        // $projects = $ProjectModel->find($id);

        // // CLIENT DATA
        // $client   = $CompanyModel->find($projects['clientid']);

        // // BAST DATA
        // $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
        // $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();

        // // INVOICE DATA 
        // $invoiceI   = $invoiceModel->where('projectid', $id)->where('status', "1")->first();
        // $invoiceII  = $invoiceModel->where('projectid', $id)->where('status', "2")->first();
        // $invoiceIII = $invoiceModel->where('projectid', $id)->where('status', "3")->first();
        // $invoiceIV  = $invoiceModel->where('projectid', $id)->where('status', "4")->first();

        // // RAB
        // $rabs       = $RabModel->where('projectid', $projects['id'])->find();
        // $rabdata    = [];
        // foreach ($rabs as $rab) {
        //     $paketid[]  = $rab['paketid'];

        //     // MDL RAB
        //     $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
        //     foreach ($rabmdl as $mdlr) {
        //         $rabdata[]  = [
        //             'id'            => $mdlr['id'],
        //             'proid'         => $projects['id'],
        //             'name'          => $mdlr['name'],
        //             'length'        => $mdlr['length'],
        //             'width'         => $mdlr['width'],
        //             'height'        => $mdlr['height'],
        //             'volume'        => $mdlr['volume'],
        //             'denomination'  => $mdlr['denomination'],
        //             'keterangan'    => $mdlr['keterangan'],
        //             'qty'           => $rab['qty'],
        //             'price'         => (int)$rab['qty'] * (int)$mdlr['price'],
        //             'oriprice'      => (int)$mdlr['price'],
        //         ];
        //     }
        // }

        // $total = array_sum(array_column($rabdata, 'price'));

        // // New Database Invoice Configuration

        // // New Data Invoice Array
        // $newdatainv = [];

        // // VARIABLE DECLARATION
        // $termin     = "";
        // $progress   = "";
        // $spkval     = "";
        // $hal        = "";
        // $dateinv    = "";

        // // INV I
        // if ($projects['status_spk'] === "1" && empty($sertrim) && empty($bast) && !empty($projects['inv1']) && empty($projects['inv2']) && empty($projects['inv3']) && empty($projects['inv4'])) {

        //     // VARIABLE DATA I
        //     $termin     = "30";
        //     $progress   = "30";
        //     $spkval     = $total - ((70 / 100) * $total);
        //     $hal        = "1";
        //     $total      = $total;
        //     $dateinv    = $projects['inv1'];


        //     // Dateline Invoice 1 Interval
        //     $dateinvoice =  $dateinv;
        //     $date = date_create($dateinvoice);
        //     $dateformat = date_format($date, "Y-m-d");
        //     $hari = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinvoice = date_format($hari, 'Y-m-d');
        // }

        // // INV II
        // if ($projects['status_spk'] === "1" && !empty($sertrim) && empty($bast) && !empty($projects['inv1']) && !empty($projects['inv2']) && empty($projects['inv3']) && empty($projects['inv4'])) {

        //     // VARIABLE DATA II
        //     $termin     = "30";
        //     $progress   = "60";
        //     $spkval     = $total - ((70 / 100) * $total);
        //     $hal        = "2";
        //     $total      = $total;
        //     $dateinv    = $projects['inv2'];

        //     // Dateline Invoice 2 Interval
        //     $dateinvoice =  $dateinv;
        //     $date = date_create($dateinvoice);
        //     $dateformat = date_format($date, "Y-m-d");
        //     $hari = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinvoice = date_format($hari, 'Y-m-d');
        // }

        // // INV III
        // if ($projects['status_spk'] === "1" && !empty($sertrim) && !empty($bast) && !empty($projects['inv1']) && !empty($projects['inv2']) && !empty($projects['inv3']) && empty($projects['inv4'])) {

        //     // VARIABLE DATA II
        //     $termin     = "35";
        //     $progress   = "95";
        //     $spkval     = $total - ((65 / 100) * $total);
        //     $hal        = "3";
        //     $total      = $total;
        //     $dateinv    = $projects['inv3'];

        //     // Dateline Invoice 1 Interval
        //     $dateinvoice =  $dateinv;
        //     $date = date_create($dateinvoice);
        //     $dateformat = date_format($date, "Y-m-d");
        //     $hari = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinvoice = date_format($hari, 'Y-m-d');
        // }

        // // INV IV
        // if ($projects['status_spk'] === "1" && !empty($sertrim) && !empty($bast) && !empty($projects['inv1']) && !empty($projects['inv2']) && !empty($projects['inv3']) && !empty($projects['inv4'])) {

        //     // VARIABLE DATA II
        //     $termin     = "35";
        //     $progress   = "95";
        //     $spkval     = $total - ((95 / 100) * $total);
        //     $hal        = "4";
        //     $total      = $total;
        //     $dateinv    = $projects['inv4'];

        //     // Dateline Invoice 1 Interval
        //     $dateinvoice =  $dateinv;
        //     $date = date_create($dateinvoice);
        //     $dateformat = date_format($date, "Y-m-d");
        //     $hari = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinvoice = date_format($hari, 'Y-m-d');
        // }

        // $newdatainv = [
        //     'termin'    => $termin,
        //     'progress'  => $progress,
        //     'nilai_spk' => $spkval,
        //     'dateinv'   => $dateformat,
        //     'dateline'  => $datelineinvoice,
        //     'hal'       => $hal,
        //     'total'     => $total,
        // ];



        // // End New Database Invoice Configuration

        // // Invoice Data Array
        // $invoicedata = [];

        // // INVOICE I
        // if ($projects['status_spk'] === "1" && !empty($projects['inv1'])) {

        //     if (!empty($projects['inv1'])) {
        //         // Date Invoice
        //         $inv1 = $projects['inv1'];

        //         // Dateline Invoice 1 Interval
        //         $dateinv1 =  $inv1;
        //         $date = date_create($dateinv1);
        //         $dateformat = date_format($date, "Y-m-d");
        //         $hari = date_create($dateformat);
        //         date_add($hari, date_interval_create_from_date_string('14 days'));
        //         $datelineinv1 = date_format($hari, 'Y-m-d');
        //         $dateline = $datelineinv1;
        //     }

        //     $invoicedata = [
        //         'termin'    => "30",
        //         'progress'  => "30",
        //         'nilai_spk' => $total - ((70 / 100) * $total),
        //         'dateinv'   => $dateformat,
        //         'dateline'  => $dateline,
        //         'hal'       => "1",
        //         'total'     => $total,
        //     ];
        // }

        // // INVOICE II
        // if (!empty($sertrim) && !empty($projects['inv2'])) {

        //     // Date Invoice
        //     $inv2 = $projects['inv2'];

        //     // Dateline Invoice 2 Interval
        //     $dateinv2       = $inv2;
        //     $date           = date_create($dateinv2);
        //     $dateformat     = date_format($date, "Y-m-d");
        //     $hari           = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinv2 = date_format($hari, 'Y-m-d');

        //     $dateline = $datelineinv2;

        //     $invoicedata = [
        //         'termin'    => "30",
        //         'progress'  => "60",
        //         'nilai_spk' => $total - ((70 / 100) * $total),
        //         'dateinv'   => $dateformat,
        //         'dateline'  => $dateline,
        //         'hal'       => "2",
        //         'total'     => $total,
        //     ];
        // }

        // // INVOICE III
        // if (!empty($bast) && !empty($projects['inv3'])) {

        //     // Date Invoice
        //     $inv3 = $projects['inv3'];

        //     // Dateline Invoice 2 Interval
        //     $dateinv3       = $inv3;
        //     $date           = date_create($dateinv3);
        //     $dateformat     = date_format($date, "Y-m-d");
        //     $hari           = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinv3 = date_format($hari, 'Y-m-d');
        //     $dateline = $datelineinv3;

        //     $invoicedata = [
        //         'termin'    => "30",
        //         'progress'  => "95",
        //         'nilai_spk' => $total - ((65 / 100) * $total),
        //         'dateinv'   => $dateformat,
        //         'dateline'  => $dateline,
        //         'hal'       => "3",
        //         'total'     => $total,
        //     ];
        // }

        // // INVOICE IV
        // if (!empty($bast) && !empty($projects['inv4'])) {

        //     // Date Invoice
        //     $inv4 = $projects['inv4'];

        //     // Dateline Invoice 2 Interval
        //     $dateinv4       = $inv4;
        //     $date           = date_create($dateinv4);
        //     $dateformat     = date_format($date, "Y-m-d");
        //     $hari           = date_create($dateformat);
        //     date_add($hari, date_interval_create_from_date_string('14 days'));
        //     $datelineinv4 = date_format($hari, 'Y-m-d');

        //     $dateline = $datelineinv4;

        //     $invoicedata = [
        //         'termin'    => "5",
        //         'progress'  => "100",
        //         'nilai_spk' => $total - ((95 / 100) * $total),
        //         'dateinv'   => $dateformat,
        //         'dateline'  => $dateline,
        //         'hal'       => "4",
        //         'total'     => $total,
        //     ];
        // }

        // NEW FUNCTION INVOICE
        // Calling models
        $ProjectModel   = new ProjectModel;
        $CompanyModel   = new CompanyModel();
        $RabModel       = new RabModel();
        $PaketModel     = new PaketModel();
        $MdlModel       = new MdlModel();
        $BastModel      = new BastModel();
        $GconfigModel   = new GconfigModel();
        $InvoiceModel   = new InvoiceModel();
        $ReferensiModel = new ReferensiModel();
        $UserModel      = new UserModel();

        // PROJECT DATA
        $projects = $ProjectModel->find($id);
        $client   = $CompanyModel->where('id', $projects['clientid'])->first();
        $gconf    = $GconfigModel->first();

        // INVOICE 
        $invoice1  = $InvoiceModel->where('projectid',$projects['id'])->where('status','1')->first();
        $invoice2  = $InvoiceModel->where('projectid',$projects['id'])->where('status','2')->first();
        $invoice3  = $InvoiceModel->where('projectid',$projects['id'])->where('status','3')->first();
        $invoice4  = $InvoiceModel->where('projectid',$projects['id'])->where('status','4')->first();

        // CLIENT DATA
        $client   = $CompanyModel->find($projects['clientid']);

        // BAST DATA
        $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
        $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();

        // RAB
        $rabs       = $RabModel->where('projectid', $projects['id'])->find();
        $rabdata    = [];
        foreach ($rabs as $rab) {
            $paketid[]  = $rab['paketid'];

            // MDL RAB
            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
            foreach ($rabmdl as $mdlr) {
                $rabdata[]  = [
                    'id'            => $mdlr['id'],
                    'proid'         => $projects['id'],
                    'name'          => $mdlr['name'],
                    'length'        => $mdlr['length'],
                    'width'         => $mdlr['width'],
                    'height'        => $mdlr['height'],
                    'volume'        => $mdlr['volume'],
                    'denomination'  => $mdlr['denomination'],
                    'keterangan'    => $mdlr['keterangan'],
                    'qty'           => $rab['qty'],
                    'price'         => (int)$rab['qty'] * (int)$mdlr['price'],
                    'oriprice'      => (int)$mdlr['price'],
                ];
            }
        }

        // TOTAL RAB PRICE
        $total = array_sum(array_column($rabdata, 'price'));
        // PPN
        $ppn        = $gconf['ppn'];

        // Invoice Data Array
        $termin     = "";
        $progress   = "";
        $nilaispk   = "";
        $dateinv    = "";
        $dateline   = "";
        $priceppn   = "";
        $pph        = "";
        $referensi  = "";
        $email      = "";
        $status     = "";
        $pic        = "";

        // INVOICE I
        if ($projects['status_spk'] === "1" && !empty($invoice1) && !empty($projects['inv1'])) {
            $termin     = "30";
            $progress   = "30";
            $nilaispk   = $total - ((70 / 100) * $total);
            $dateinv    = $projects['inv1'];
            $dateline   = $invoice1['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice1['pph23'];
            $referensi  = $invoice1['referensi'];
            $email      = $invoice1['email'];
            $status     = $invoice1['status'];
            $pic        = $invoice1['pic'];
        }

        // INVOICE II
        if (!empty($sertrim) && !empty($projects['inv2']) && !empty($invoice3)) {
            $termin     = "30";
            $progress   = "60";
            $nilaispk   = $total - ((70 / 100) * $total);
            $dateinv    = $projects['inv2'];
            $dateline   = $invoice2['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice2['pph23'];
            $referensi  = $invoice2['referensi'];
            $email      = $invoice2['email'];
            $status     = $invoice2['status'];
            $pic        = $invoice2['pic'];
        }

        // INVOICE III
        if (!empty($bast) && !empty($projects['inv3']) && !empty($invoice3)) {
            $termin     = "35";
            $progress   = "95";
            $nilaispk   = $total - ((65 / 100) * $total);
            $dateinv    = $projects['inv3'];
            $dateline   = $invoice3['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice3['pph23'];
            $referensi  = $invoice3['referensi'];
            $email      = $invoice3['email'];
            $status     = $invoice3['status'];
            $pic        = $invoice3['pic'];
        }

        // INVOICE IV
        if (!empty($bast) && !empty($projects['inv4']) && !empty($invoice4)) {

            $termin     = "5";
            $progress   = "100";
            $nilaispk   = $total - ((95 / 100) * $total);
            $dateinv    = $projects['inv4'];
            $dateline   = $invoice4['jatuhtempo'];
            $priceppn   = $total + (($gconf['ppn'] / 100) * $total);
            $pph        = $invoice4['pph23'];
            $referensi  = $invoice4['referensi'];
            $email      = $invoice4['email'];
            $status     = $invoice4['status'];
            $pic        = $invoice4['pic'];


            // Dateline Invoice 2 Interval
            // $dateinv4       = $inv4;
            // $date           = date_create($dateinv4);
            // $dateformat     = date_format($date, "Y-m-d");
            // $hari           = date_create($dateformat);
            // date_add($hari, date_interval_create_from_date_string('14 days'));
            // $datelineinv4 = date_format($hari, 'Y-m-d');

            // $dateline = $datelineinv4;
        }

        // DATA REFERENSI
        $refdata    = "";
        $refname    = "";
        $refacc     = "";
        $refbank    = "";
        if(!empty($referensi)){
            $refdata = $ReferensiModel->where('id',$referensi)->first();
            $refname    = $refdata['name'];
            $refacc     = $refdata['no_rek'];
            $refbank    = $refdata['bank'];
        }

        // DATA PIC
        $picdata = "";
        $picname = "";
        if(!empty($pic)){
            $picdata    = $UserModel->where('id',$pic)->first();
            $picname    = $picdata->name;
        }


        $invoicedata = [
            'termin'    => $termin,
            'progress'  => $progress,
            'nilai_spk' => $nilaispk,
            'dateinv'   => $dateinv,
            'dateline'  => $dateline,
            'total'     => $total,
            'ppn'       => $gconf['ppn'],
            'priceppn'  => $total + (($gconf['ppn'] / 100) * $total),
            'pph'       => $pph,
            'referensi' => $refname,
            'refacc'    => $refacc,
            'refbank'   => $refbank,
            'email'     => $email,
            'pic'       => $picname,
        ];
        // END NEW FUCTION 

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = lang('Global.titleDashboard');
        $data['description']    = lang('Global.dashboardDescription');
        $data['projects']       = $projects;
        $data['rabs']           = $rabdata;
        $data['pakets']         = $PaketModel->findAll();
        $data['mdls']           = $MdlModel->findAll();
        $data['client']         = $client;
        $data['invoice']        = $invoicedata;

        return view('invoice', $data);
    }

    public function removesertrim($id)
    {
        $BastModel = new BastModel;

        $bast = $BastModel->find($id);
        $filename = $bast['file'];

        if (!empty($filename)) {
            if ($bast['status'] === "0") {
                unlink(FCPATH . 'img/sertrim/' . $filename);
            } elseif ($bast['status'] === "1") {
                unlink(FCPATH . 'img/bast/' . $filename);
            }
        }
        $BastModel->delete($bast);

        die(json_encode(array($filename)));
    }

    public function inv4($id)
    {
        $ProjectModel = new ProjectModel();
        $input = $this->request->getPost();

        $date = date_create($input['dateline']);
        $day = date_format($date, "Y-m-d H:i:s");

        $invoice = [
            'id'    => $id,
            'inv4'  => $day,
        ];
        $ProjectModel->save($invoice);

        die(json_encode($invoice));
    }
}
