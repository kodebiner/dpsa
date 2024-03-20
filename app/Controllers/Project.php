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
use App\Models\BuktiModel;
use App\Models\LogModel;

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
            $CustomRabModel         = new CustomRabModel();
            $BuktiModel             = new BuktiModel();
            $LogModel               = new LogModel();

            // Populating Data
            $pakets                 = $PaketModel->where('parentid !=', 0)->find();
            $company                = $CompanyModel->where('status !=', "0")->find();
            $projects               = $ProjectModel->paginate(10, 'projects');

            // Users
            $this->builder = $this->db->table('users');
            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('users.id !=', $this->data['uid']);
            $this->builder->where('auth_groups.name !=', 'superuser');
            $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $users = $this->builder->get()->getResult();

            // User Marketing
            $this->builder = $this->db->table('users');
            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('auth_groups.name =', 'marketing');
            $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $marketings = $this->builder->get()->getResult();

            // User Production
            $this->builder = $this->db->table('users');
            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('auth_groups.name =', 'production');
            $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $picPro = $this->builder->get()->getResult();

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

                    
                    // Setrim
                    $projectdata[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                    // BAST
                    $projectdata[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                    $projectdata[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

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
                    $productions                                    = $ProductionModel->where('projectid', $project['id'])->orderBy('mdlid', 'DESC')->find();
                    if (!empty($productions)) {
                        foreach ($productions as $production) {

                            // MDL Production
                            $mdlprod        = $MdlModel->where('id', $production['mdlid'])->find();
                            $percentages    = [];
                            foreach ($mdlprod as $mdlp) {
                                // Percentage Production
                                if ($production['gambar_kerja'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['mesin_awal'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['tukang'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['mesin_lanjutan'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['finishing'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['packing'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['pengiriman'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($production['setting'] == 1) {
                                    $percentages[]    = 1;
                                }

                                $projectdata[$project['id']]['production'][$production['id']]  = [
                                    'id'                => $production['id'],
                                    'userid'            => $production['userid'],
                                    'mdlid'             => $mdlp['id'],
                                    'name'              => $mdlp['name'],
                                    'gambar_kerja'      => $production['gambar_kerja'],
                                    'mesin_awal'        => $production['mesin_awal'],
                                    'tukang'            => $production['tukang'],
                                    'mesin_lanjutan'    => $production['mesin_lanjutan'],
                                    'finishing'         => $production['finishing'],
                                    'packing'           => $production['packing'],
                                    'pengiriman'        => $production['pengiriman'],
                                    'setting'           => $production['setting'],
                                ];
                            }

                            $projectdata[$project['id']]['production'][$production['id']]['percentages']  = array_sum($percentages) / 8 * 100;
                        }
                    } else {
                        $mdlprod    = [];
                        $projectdata[$project['id']]['production']   = [];
                    }
                   

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
                                'val'   => (($progresval['price'] / $total) * 65) / 8,
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
                                    if ($proses['pengiriman'] === "1") {
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

                    if (!empty($projectdata[$project['id']]['bastfile'])) {
                        $day =  $projectdata[$project['id']]['bastfile']['updated_at'];
                        $date = date_create($day);
                        $key = date_format($date, "Y-m-d");
                        $hari = date_create($key);
                        date_add($hari, date_interval_create_from_date_string('3 month'));
                        $dateline = date_format($hari, 'Y-m-d');

                        $now = strtotime("now");
                        $nowtime = date("Y-m-d", $now);
                        $projectdata[$project['id']]['dateline'] = $dateline;
                        $projectdata[$project['id']]['now'] = $nowtime;
                    } else {
                        $projectdata[$project['id']]['dateline'] = "";
                        $projectdata[$project['id']]['now'] = "";
                    }

                    // INVOICE
                    $projectdata[$project['id']]['invoice1'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '1')->first();
                    $projectdata[$project['id']]['invoice2'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '2')->first();
                    $projectdata[$project['id']]['invoice3'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '3')->first();
                    $projectdata[$project['id']]['invoice4'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '4')->first();

                    // REFERENSI
                    $projectdata[$project['id']]['referensi']   = $ReferensiModel->findAll();

                    // PIC
                    $projectdata[$project['id']]['pic']         = $users;

                    // Bukti Pembayaran
                    $projectdata[$project['id']]['buktipembayaran']     = $BuktiModel->where('projectid', $project['id'])->where('status', "0")->find();

                    // Bukti Pengiriman
                    $projectdata[$project['id']]['buktipengiriman']     = $BuktiModel->where('projectid', $project['id'])->where('status', "1")->find();
                }
            } else {
                $rabs           = [];
            }

            // Parsing Data To View
            $data                   = $this->data;
            $data['title']          = "Proyek";
            $data['description']    = "Data Proyek";
            $data['users']          = $users;
            $data['projects']       = $projects;
            $data['projectdata']    = $projectdata;
            $data['company']        = $company;
            $data['pakets']         = $pakets;
            $data['rabs']           = $rabs;
            $data['marketings']     = $marketings;
            $data['picpro']         = $picPro;
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
        if ($this->data['authorize']->hasPermission('admin.project.create', $this->data['uid'])) {
            // Calling Model
            $ProjectModel   = new ProjectModel();
            $InvoiceModel   = new InvoiceModel();
            $BastModel      = new BastModel();
            $LogModel       = new LogModel();

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

            // This Year
            $yearEnd = date('Y');

            // DATA SPH
            $amountsph = "";

            // PROYEK TERAKHIR
            $lastproyek = $ProjectModel->orderBy('id', 'DESC')->first();

            $lastpro = "";
            if (!empty($lastproyek)) {
                $dateprox = date_create($lastproyek['tahun']);
                $lastpro =  date_format($dateprox, 'Y');
            }

            // DATE DATA
            if (!empty($lastproyek)) {
                if ($lastpro <= $yearEnd) {
                    $amountsph = $lastproyek['no_sph'] + 1;
                } elseif ($yearEnd > $lastpro) {
                    $amountsph = 1;
                } else {
                    $amountsph = 1;
                }
            } else {
                $amountsph = 1;
            }

            // --- END SPH NUM ---//

            // INVOICE NUM
            $lastinvoice = $InvoiceModel->orderBy('id', 'DESC')->first();

            $lastinv = "";
            if (!empty($lastinvoice)) {
                $dateinvx = date_create($lastinvoice['tahun']);
                $lastinv = date_format($dateinvx, 'Y');
            }

            // CREATE INVOICE DATA
            $amount = "";

            // DATE DATA
            if (!empty($lastinvoice)) {
                if ($lastinv <= $yearEnd) {
                    $amount = $lastinvoice['no_inv'] + 1;
                } elseif ($yearEnd > $lastinv) {
                    $amount = 1;
                } else {
                    $amount = 1;
                }
            } else {
                $amount = 1;
            }
            // END INVOICE NUM

            // Project Data
            $project = [
                'name'          => $input['name'],
                'clientid'      => $input['company'],
                'status'        => 1,
                // 'no_sph'        => $amountsph,
                'tahun'         => date('Y-m-d H:i:s'),
                'marketing'     => $input['marketing'],
            ];

            if (isset($input['designtype'])) {
                $project['type_design'] = 1;
                $project['ded'] = $input['design'];
            } else {
                $project['type_design'] = 0;
            }

            $ProjectModel->insert($project);

            $projectid = $ProjectModel->getInsertID();

            // INSERT INVOICE DATA
            $statusinv = [1, 2, 3, 4];
            foreach ($statusinv as $inv) {
                $datainv = [
                    'projectid' => $projectid,
                    'status'    => $inv,
                    // 'no_inv'    => $amount++,
                ];
                $InvoiceModel->save($datainv);
            }

            // INSERT BAST DATA
            $bast = $BastModel->where('projectid', $projectid)->where('status', "1")->first();
            if (empty($bast)) {
                $bastcreate = [
                    'projectid'     => $projectid,
                    'status'        => "1",
                ];
                $BastModel->save($bastcreate);
            }
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Membuat Proyek ' . $input['name']]);
            return redirect()->back()->with('message', "Data berhasil di simpan.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.edit', $this->data['uid']) || $this->data['authorize']->hasPermission('marketing.project.edit', $this->data['uid']) || $this->data['authorize']->hasPermission('production.project.edit', $this->data['uid']) || $this->data['authorize']->hasPermission('design.project.edit', $this->data['uid'])) {
            // Calling Model
            $ProjectModel       = new ProjectModel();
            $RabModel           = new RabModel();
            $DesignModel        = new DesignModel();
            $ProductionModel    = new ProductionModel();
            $BastModel          = new BastModel();
            $InvoiceModel       = new InvoiceModel();
            $CustomRabModel     = new CustomRabModel();
            $BuktiModel         = new BuktiModel();
            $LogModel           = new LogModel();

            // initialize
            $input  = $this->request->getPost();
            $pro    = $ProjectModel->find($id);
            // dd($input);

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
                } else {
                    $spk        = $pro['spk'];
                    $statusspk  = $pro['status_spk'];
                }
            } else {
                $spk        = $pro['spk'];
                $statusspk  = $pro['status_spk'];
            }

            $spknum = "";
            if (!empty($input['nospk'])) {
                $spknum = $input['nospk'];
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
                            if ((!empty($rab)) && ($rab['qty'] != $input['eqty' . $id][$paketid][$mdlid]) && $input['eqty' . $id][$paketid][$mdlid] != "0") {
                                if ($input['eqty' . $id][$paketid][$mdlid] != "0") {
                                    $productions = $ProductionModel->where('projectid', $id)->where('mdlid', $mdlid)->find();
                                    if ($rab['qty'] < $input['eqty' . $id][$paketid][$mdlid]) {
                                        $countDiff = (int)$input['eqty' . $id][$paketid][$mdlid] - (int)$rab['qty'];
                                        for ($n = 1; $n <= (int)$countDiff; $n++) {
                                            $dataProduction = [
                                                'mdlid'     => $mdlid,
                                                'projectid' => $id
                                            ];
                                            $ProductionModel->save($dataProduction);
                                        }
                                    } elseif ($rab['qty'] > $input['eqty' . $id][$paketid][$mdlid]) {
                                        $countDiff = (int)$rab['qty'] - (int)$input['eqty' . $id][$paketid][$mdlid];
                                        $productions = $ProductionModel->where('projectid', $id)->where('mdlid', $mdlid)->orderBy('id', 'DESC')->limit($countDiff)->find();
                                        foreach ($productions as $production) {
                                            $ProductionModel->delete($production['id']);
                                        }
                                    }
                                    $RabModel->save(['id' => $rab['id'], 'qty' => $qty]);
                                } else {
                                    $productions = $ProductionModel->where('projectid', $id)->where('mdlid', $mdlid)->find();
                                    foreach ($productions as $production) {
                                        $ProductionModel->delete($production['id']);
                                    }
                                    $RabModel->delete($rab);
                                }
                            } elseif (empty($rab)) {
                                if ($input['eqty' . $id][$paketid][$mdlid] != "0") {
                                    $datarab = [
                                        'mdlid'     => $mdlid,
                                        'projectid' => $id,
                                        'paketid'   => $paketid,
                                        'qty'       => $qty
                                    ];
                                    $RabModel->save($datarab);
                                    for ($n = 1; $n <= (int)$qty; $n++) {
                                        $dataProduction = [
                                            'mdlid'     => $mdlid,
                                            'projectid' => $id
                                        ];
                                        $ProductionModel->save($dataProduction);
                                    }
                                }
                            }
                        } else {
                            $rab = $RabModel->where('mdlid', $mdlid)->where('paketid', $paketid)->where('projectid', $id)->first();
                            if (!empty($rab)) {
                                $productions = $ProductionModel->where('projectid', $id)->where('mdlid', $mdlid)->find();
                                foreach ($productions as $production) {
                                    $ProductionModel->delete($production['id']);
                                }
                                $RabModel->delete($rab['id']);
                            }
                        }
                    }
                }
            }

            // Custom RAB Data
            if (!empty($input['customprice' . $id])) {
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
                        } else {
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
                        // 'revision'      => $input['submitted'],
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
                    $gambarkerjainput = [
                        'id'                => $prodid,
                        'gambar_kerja'      => $gambar,
                    ];
                    $ProductionModel->save($gambarkerjainput);
                }
            }

            // Mesin Awal
            if (isset($input['mesinawal' . $id])) {
                foreach ($input['mesinawal' . $id] as $prodid => $mesinawal) {
                    $mesinawalinput = [
                        'id'                => $prodid,
                        'mesin_awal'        => $mesinawal,
                    ];
                    $ProductionModel->save($mesinawalinput);
                }
            }

            // Tukang
            if (isset($input['tukang' . $id])) {
                foreach ($input['tukang' . $id] as $prodid => $tukang) {
                    $tukanginput = [
                        'id'                => $prodid,
                        'tukang'            => $tukang,
                    ];
                    $ProductionModel->save($tukanginput);
                }
            }

            // Mesin Lanjutan
            if (isset($input['mesinlanjutan' . $id])) {
                foreach ($input['mesinlanjutan' . $id] as $prodid => $mesinlanjutan) {
                    $mesinlanjutaninput = [
                        'id'                => $prodid,
                        'mesin_lanjutan'    => $mesinlanjutan,
                    ];
                    $ProductionModel->save($mesinlanjutaninput);
                }
            }

            // Finishing
            if (isset($input['finishing' . $id])) {
                foreach ($input['finishing' . $id] as $prodid => $finishing) {
                    $finishinginput = [
                        'id'                => $prodid,
                        'finishing'         => $finishing,
                    ];
                    $ProductionModel->save($finishinginput);
                }
            }

            // Packing
            if (isset($input['packing' . $id])) {
                foreach ($input['packing' . $id] as $prodid => $packing) {
                    $packinginput = [
                        'id'                => $prodid,
                        'packing'           => $packing,
                    ];
                    $ProductionModel->save($packinginput);
                }
            }

            // Pengiriman
            if (isset($input['pengiriman' . $id])) {
                foreach ($input['pengiriman' . $id] as $prodid => $pengiriman) {
                    $pengirimaninput = [
                        'id'                => $prodid,
                        'pengiriman'        => $pengiriman,
                    ];
                    $ProductionModel->save($pengirimaninput);
                }
            }

            // Setting
            if (isset($input['setting' . $id])) {
                foreach ($input['setting' . $id] as $prodid => $setting) {
                    $settinginput = [
                        'id'                => $prodid,
                        'setting'           => $setting,
                    ];
                    $ProductionModel->save($settinginput);
                }
            }

            // PIC Production
            $productionpic = $ProductionModel->where('projectid', $id)->find();
            foreach ($productionpic as $picprod) {
                if (isset($input['picpro'][$picprod['id']]) && ($input['picpro'][$picprod['id']] != $picprod['userid'])) {
                    $inputpropic = [
                        'id'                => $picprod['id'],
                        'userid'            => $input['picpro'][$picprod['id']],
                    ];
                    $ProductionModel->save($inputpropic);
                }
            }

            // JATUH TEMPO BAST
            $tgltempobast = "";
            if (!empty($input['jatuhtempobast' . $id])) {
                $datebast = $input['jatuhtempobast' . $id];
                $tgltempobast = date('Y-m-d H:i:s', strtotime($datebast));
            }

            // FINANCE

            // FUNCTION INVOICE
            $idinv1 = $InvoiceModel->where('projectid', $id)->where('status', '1')->first();
            $idinv2 = $InvoiceModel->where('projectid', $id)->where('status', '2')->first();
            $idinv3 = $InvoiceModel->where('projectid', $id)->where('status', '3')->first();
            $idinv4 = $InvoiceModel->where('projectid', $id)->where('status', '4')->first();

            // if (!empty($input['dateinvoice1' . $id]) && !empty($input['referensiinvoice1' . $id]) && !empty($input['pphinvoice1' . $id]) && !empty( $input['emailinvoice1' . $id]) && !empty($idinv1)){
            if (isset($input['dateinvoice1' . $id], $input['referensiinvoice1' . $id], $input['pphinvoice1' . $id], $input['emailinvoice1' . $id]) && !empty($idinv1) && !empty($input['dateinvoice1' . $id]) && !empty($input['referensiinvoice1' . $id]) && !empty($input['pphinvoice1' . $id]) && !empty($input['emailinvoice1' . $id])) {
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
                    'tahun'         => date('Y-m-d H:i:s'),
                    'no_inv'        => $input['noinv1' . $id],
                ];
                $InvoiceModel->save($invoice1);
            } elseif (isset($input['referensiinvoice1' . $id], $input['picinvoice1' . $id]) && !empty($input['dateinvoice1' . $id]) && !empty($input['emailinvoice1' . $id]) && !empty($input['pphinvoice1' . $id])) {

                $tahunini = date('Y-m-d H:i:s');
                $invoice1 = [
                    'projectid'     => $id,
                    'jatuhtempo'    => $input['dateinvoice1' . $id] . " 00:00:00",
                    'referensi'     => $input['referensiinvoice1' . $id],
                    'pph23'         => $input['pphinvoice1' . $id],
                    'email'         => $input['emailinvoice1' . $id],
                    'status'        => "1",
                    'pic'           => $input['picinvoice1' . $id],
                    'tahun'         => $tahunini,
                    'no_inv'        => $input['noinv1' . $id],
                ];
                $InvoiceModel->save($invoice1);
            }

            // INVOICE 2 UPDATE
            if (isset($input['dateinvoice2' . $id], $input['referensiinvoice2' . $id], $input['pphinvoice2' . $id], $input['emailinvoice2' . $id]) && !empty($idinv2) && !empty($input['dateinvoice2' . $id]) && !empty($input['referensiinvoice2' . $id]) && !empty($input['pphinvoice2' . $id]) && !empty($input['emailinvoice2' . $id])) {
                // if (!empty($input['dateinvoice2' . $id]) && !empty($input['referensiinvoice2' . $id]) && !empty($input['pphinvoice2' . $id]) && !empty( $input['emailinvoice2' . $id]) && !empty($idinv2)){
                $date2 = $input['dateinvoice2' . $id];
                $newDate2 = date('Y-m-d H:i:s', strtotime($date2));

                $invoice2 = [
                    'id'            => $idinv2['id'],
                    'projectid'     => $id,
                    'jatuhtempo'    => $newDate2, //date_format($date, 'm-d-Y H:i:s'),
                    'referensi'     => $input['referensiinvoice2' . $id],
                    'pph23'         => $input['pphinvoice2' . $id],
                    'email'         => $input['emailinvoice2' . $id],
                    'status'        => "2",
                    'pic'           => $input['picinvoice2' . $id],
                    'tahun'         => date('Y-m-d H:i:s'),
                    'no_inv'        => $input['noinv2' . $id],
                ];

                $InvoiceModel->save($invoice2);
            } elseif (isset($input['referensiinvoice2' . $id], $input['picinvoice2' . $id]) && !empty($input['dateinvoice2' . $id]) && !empty($input['referensiinvoice2' . $id]) && !empty($input['emailinvoice2' . $id]) && !empty($input['pphinvoice2' . $id])) {
                // } else {
                $tahunini = date('Y-m-d H:i:s');
                // $numinv = $invnum . "/DPSA/" . $client['rscode'] . "/" . $roman . "/" . $Year;
                $invoice2 = [
                    'projectid'     => $id,
                    'jatuhtempo'    => $input['dateinvoice2' . $id] . " 00:00:00",
                    'referensi'     => $input['referensiinvoice2' . $id],
                    'pph23'         => $input['pphinvoice2' . $id],
                    'email'         => $input['emailinvoice2' . $id],
                    'status'        => "2",
                    'pic'           => $input['picinvoice2' . $id],
                    'tahun'         => $tahunini,
                    'no_inv'        => $input['noinv2' . $id],
                ];
                $InvoiceModel->save($invoice2);
            }

            // INVOICE 3 UPDATE
            if (isset($input['dateinvoice3' . $id], $input['referensiinvoice3' . $id], $input['pphinvoice3' . $id], $input['emailinvoice3' . $id]) && !empty($idinv3) && !empty($input['dateinvoice3' . $id]) && !empty($input['referensiinvoice3' . $id]) && !empty($input['pphinvoice3' . $id]) && !empty($input['emailinvoice3' . $id])) {
                // if (!empty($input['dateinvoice3' . $id]) && !empty($input['referensiinvoice3' . $id]) && !empty($input['pphinvoice3' . $id]) && !empty( $input['emailinvoice3' . $id]) && !empty($idinv3)){
                $date3 = $input['dateinvoice3' . $id];
                $newDate3 = date('Y-m-d H:i:s', strtotime($date3));
                $invoice3 = [
                    'id'            => $idinv3['id'],
                    'projectid'     => $id,
                    'jatuhtempo'    => $newDate3 . " 00:00:00", //date_format($date, 'm-d-Y H:i:s'),
                    'referensi'     => $input['referensiinvoice3' . $id],
                    'pph23'         => $input['pphinvoice3' . $id],
                    'email'         => $input['emailinvoice3' . $id],
                    'status'        => "3",
                    'pic'           => $input['picinvoice3' . $id],
                    'tahun'         => date('Y-m-d H:i:s'),
                    'no_inv'        => $input['noinv3' . $id],
                ];
                $InvoiceModel->save($invoice3);
            } elseif (isset($input['referensiinvoice3' . $id], $input['picinvoice3' . $id]) && !empty($input['dateinvoice3' . $id]) && !empty($input['referensiinvoice3' . $id]) && !empty($input['emailinvoice3' . $id]) && !empty($input['pphinvoice3' . $id])) {
                // } else {
                $tahunini = date('Y-m-d H:i:s');
                // $numinv = $invnum . "/DPSA/" . $roman . "/" . $Year;
                $invoice3 = [
                    'projectid'     => $id,
                    'jatuhtempo'    => $input['dateinvoice3' . $id] . " 00:00:00",
                    'referensi'     => $input['referensiinvoice3' . $id],
                    'pph23'         => $input['pphinvoice3' . $id],
                    'email'         => $input['emailinvoice3' . $id],
                    'status'        => "3",
                    'pic'           => $input['picinvoice3' . $id],
                    'tahun'         => $tahunini,
                    'no_inv'        => $input['noinv3' . $id],
                ];
                $InvoiceModel->save($invoice3);
            }

            // INVOICE 4 UPDATE
            if (isset($input['dateinvoice4' . $id], $input['referensiinvoice4' . $id], $input['pphinvoice4' . $id], $input['emailinvoice4' . $id]) && !empty($idinv4) && !empty($input['dateinvoice4' . $id]) && !empty($input['referensiinvoice4' . $id]) && !empty($input['pphinvoice4' . $id]) && !empty($input['emailinvoice4' . $id])) {
                // if (!empty($input['dateinvoice4' . $id]) && !empty($input['referensiinvoice4' . $id]) && !empty($input['pphinvoice4' . $id]) && !empty( $input['emailinvoice4' . $id]) && !empty($idinv4)){
                $date4 = $input['dateinvoice3' . $id];
                $newDate4 = date('Y-m-d H:i:s', strtotime($date4));
                $invoice4 = [
                    'id'            => $idinv4['id'],
                    'projectid'     => $id,
                    'jatuhtempo'    => $newDate4 . " 00:00:00", //date_format($date, 'm-d-Y H:i:s'),
                    'referensi'     => $input['referensiinvoice4' . $id],
                    'pph23'         => $input['pphinvoice4' . $id],
                    'email'         => $input['emailinvoice4' . $id],
                    'status'        => "4",
                    'pic'           => $input['picinvoice4' . $id],
                    'tahun'         => date('Y-m-d H:i:s'),
                    'no_inv'        => $input['noinv4' . $id],
                ];
                $InvoiceModel->save($invoice4);
            } elseif (isset($input['referensiinvoice4' . $id], $input['picinvoice4' . $id]) && !empty($input['dateinvoice4' . $id]) && !empty($input['referensiinvoice4' . $id]) && !empty($input['emailinvoice4' . $id]) && !empty($input['pphinvoice4' . $id])) {
                // } else {
                $tahunini = date('Y-m-d H:i:s');
                // $numinv = $invnum . "/DPSA/" . $client['rscode'] . "/" . $roman . "/" . $Year;
                $invoice4 = [
                    'projectid'     => $id,
                    'jatuhtempo'    => $input['dateinvoice4' . $id] . " 00:00:00",
                    'referensi'     => $input['referensiinvoice4' . $id],
                    'pph23'         => $input['pphinvoice4' . $id],
                    'email'         => $input['emailinvoice4' . $id],
                    'status'        => "4",
                    'pic'           => $input['picinvoice4' . $id],
                    'tahun'         => $tahunini,
                    'no_inv'        => $input['noinv4' . $id],
                ];
                $InvoiceModel->save($invoice4);
            }
            // END NEW INVOICE FUNCTION

            $tanggalinv4 = "";
            if (!empty($tgltempobast)) {
                $day =  $tgltempobast;
                $date = date_create($day);
                $key = date_format($date, "Y-m-d");
                $hari = date_create($key);
                date_add($hari, date_interval_create_from_date_string('3 month'));
                $tanggalinv4 = date_format($hari, 'Y-m-d');
            }

            // TANGGAL BAST
            $bast = $BastModel->where('projectid', $id)->where('status', "1")->first();
            if (!empty($bast) && !empty($tgltempobast)) {
                $bastcreate = [
                    'id'            => $bast['id'],
                    'projectid'     => $id,
                    'tanggal_bast'  => $tgltempobast,
                ];
                $BastModel->save($bastcreate);
            }

            // Bukti Pengiriman Data
            if (!empty($input['buktipengiriman'])) {
                $senddate       = date_create();
                $tanggalkirim   = date_format($senddate, 'Y-m-d H:i:s');
                $databukti  = [
                    'projectid'     => $id,
                    'file'          => $input['buktipengiriman'],
                    'status'        => 1,
                    'created_at'    => $tanggalkirim,
                ];
                $BuktiModel->insert($databukti);
            }

            // Project Data
            $project = [
                'id'            => $id,
                'name'          => $name,
                'clientid'      => $client,
                'spk'           => $spk,
                'status_spk'    => $statusspk,
                'status'        => $status,
                'no_spk'        => $spknum,
                'no_sph'        => $input['nosph' . $id],
                'inv4'          => $tanggalinv4,
            ];
            $ProjectModel->save($project);


            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah data Proyek ' . $name]);
            return redirect()->back()->with('message', "Data berhasil di perbaharui.");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.delete', $this->data['uid'])) {
            // Calling Model
            $ProjectModel       = new ProjectModel();
            $RabModel           = new RabModel();
            $DesignModel        = new DesignModel();
            $BastModel          = new BastModel();
            $ProductionModel    = new ProductionModel();
            $CustomRabModel     = new CustomRabModel();
            $LogModel           = new LogModel();

            // Populating Data
            $rabs               = $RabModel->where('projectid', $id)->find();
            $design             = $DesignModel->where('projectid', $id)->first();
            $productions        = $ProductionModel->where('projectid', $id)->find();
            $basts              = $BastModel->where('projectid', $id)->find();
            $customrab          = $CustomRabModel->where('projectid', $id)->find();
            $project            = $ProjectModel->find($id);

            // Deleting Rab
            if (!empty($rab)) {
                foreach ($rabs as $rab) {
                    $RabModel->delete($rab['id']);
                }
            }

            // Deleting Custom Rab
            if (!empty($customrab)) {
                foreach ($customrab as $custom) {
                    $CustomRabModel->delete($custom['id']);
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
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus data Proyek ' . $project['name']]);
            $ProjectModel->delete($id);
            return redirect()->back()->with('error', "Data berhasil di hapus");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }


    public function sphprint($id)
    {
        // Calling models
        $UserModel      = new UserModel();
        $ProjectModel   = new ProjectModel;
        $CompanyModel   = new CompanyModel();
        $RabModel       = new RabModel();
        $PaketModel     = new PaketModel();
        $MdlModel       = new MdlModel();
        $GconfigModel   = new GconfigModel();
        $CustomRabModel = new CustomRabModel();
        $LogModel       = new LogModel();
        $MdlPaketModel  = new MdlPaketModel();

        $projects = $ProjectModel->find($id);
        $mark     = $UserModel->find($projects['marketing']);
        $client   = $CompanyModel->where('id', $projects['clientid'])->first();
        $picklien = $UserModel->find($client['pic']);
        $rabs     = $RabModel->where('projectid', $projects['id'])->find();
        $mdls     = $MdlModel->findAll();
        $pakets   = $PaketModel->findAll();
        $gconf    = $GconfigModel->first();
        $custrab  = $CustomRabModel->where('projectid', $projects['id'])->find();
        if (!empty($projects['marketing'])) {
            $mark     = $UserModel->find($projects['marketing']);
        } else {
            $mark     = $UserModel->where('parentid', $client['id'])->first();
        }
        if (!empty($client['pic'])) {
            $picklien = $UserModel->find($client['pic']);
        } else {
            $picklien =  $UserModel->where('parentid', $client['id'])->first();
        }
        $paketsdata = $PaketModel->where('parentid', "0")->find();

        // RAB
        $mdldata = [];
        $mdlid   = [];
        if (!empty($projects['id'])) {
            foreach ($rabs as $rab) {
                if ($rab['projectid'] === $projects['id']) {
                    foreach ($pakets as $paket) {
                        if ($paket['id'] === $rab['paketid']) {
                            foreach ($paketsdata as $kategori) {
                                if ($kategori['id'] === $paket['parentid']) {
                                    foreach ($mdls as $mdl) {
                                        if ($mdl['id'] === $rab['mdlid']) {
                                            $denom = "";
                                            $price = "";
                                            $total = [];
                                            if ($mdl['denomination'] === "1") {
                                                // $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "Unit";
                                            } elseif ($mdl['denomination'] === "2") {
                                                // $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "M";
                                            } elseif ($mdl['denomination'] === "3") {
                                                $luas   =   $mdl['height'] * $mdl['length'];
                                                // $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "M2";
                                            } elseif ($mdl['denomination'] === "4") {
                                                // $price  = $rab['qty'] * $mdl['price'];
                                                $denom  = "Set";
                                            }
                                            // $total[] = $rab['qty'] * $mdl['price'];
                                            $datamdlid[] = $mdl['id'];
                                            $mdldata[] = [
                                                'id'            => $paket['id'],
                                                'kategori'      => $kategori['name'],
                                                'name'          => $mdl['name'],
                                                'length'        => $mdl['length'],
                                                'width'         => $mdl['width'],
                                                'height'        => $mdl['height'],
                                                'volume'        => $mdl['volume'],
                                                'denom'         => $denom,
                                                'qty'           => $rab['qty'],
                                                'mdlprice'      => $mdl['price'],
                                                'price'         => $mdl['price'] * $rab['qty'],
                                                'keterangan'    => $mdl['keterangan'],
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $total = array_sum(array_column($mdldata, 'price'));

        // CUSTOM RAB
        $customrab = [];
        foreach ($custrab as $cusrab) {
            if (!empty($cusrab)) {
                $customrab[] = [
                    'name'  => $cusrab['name'],
                    'price' => $cusrab['price'],
                ];
            }
        }

        $totalrab = "";
        if (!empty($mdldata)) {
            $totalrab = array_sum(array_column($mdldata, 'mdlprice'));
        }

        $totalcustom = "";
        if (!empty($customrab)) {
            $totalcustom = array_sum(array_column($customrab, 'price'));
        }

        $ppn = "";
        if (!empty($gconf)) {
            $ppn = (int)$gconf['ppn'];
        }

        // END RAB DATA
        $ppnval   = ($ppn / 100) * ((int)$totalcustom + (int)$totalrab);
        $totalsph = (int)$totalcustom + (int)$totalrab + (int)$ppnval;

        // Terbilang
        function penyebut($nilai)
        {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                $temp = penyebut($nilai - 10) . " belas";
            } else if ($nilai < 100) {
                $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
            } else if ($nilai < 1000000000000000) {
                $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
            }
            return $temp;
        }

        function pembilang($nilai)
        {
            if ($nilai < 0) {
                $hasil = "minus " . trim(penyebut($nilai));
            } else {
                $hasil = trim(penyebut($nilai));
            }
            return $hasil;
        }

        $angka =  $totalsph;
        // End Terbilang 

        // MARKETING INITIAL
        $markname = "";
        if (!empty($mark)) {
            $markname = $mark->name;
        }
        $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", " ");
        $markname = str_replace($vowels, "", $markname);
        // END MARKETING INITIAL

        $datasph = [
            'nosph'     => "1",
            'proyek'    => $projects['name'],
            'lokasi'    => $client['rsname'],
            'tanggal'   => $projects['tahun'],
            'clientpic' => $picklien->name,
            'ppn'       => $gconf['ppn'],
            'ppnval'    => (int)$ppnval,
            'total'     => $total + (int)$totalcustom,
            'totalsph'  => $totalsph,
            'terbilang' => pembilang($angka) . " rupiah",
            'marketing' => strtoupper($markname),
            'direktur'  => $gconf['direktur'],
            'totcustom' => $totalcustom,
        ];

        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan Print SPH ' . $projects['name']]);

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = lang('Global.titleDashboard');
        $data['description']    = lang('Global.dashboardDescription');
        $data['projects']       = $projects;
        $data['client']         = $client;
        $data['custom']         = $customrab;
        $data['sphdata']        = $datasph;
        $data['sphrabs']        = $mdldata;

        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 5,
        ]);
        $mpdf->Image('./img/logo.png', 80, 0, 210, 297, 'png', '', true, false);
        $mpdf->showImageErrors = true;
        $mpdf->AddPage("L", "", "", "", "", "15", "15", "2", "15", "", "", "", "", "", "", "", "", "", "", "", "A4-L");

        $date = date_create($projects['created_at']);
        $filename = "LaporanSph" . $projects['name'] . " " . date_format($date, 'd-m-Y') . ".pdf";
        $html = view('Views/sphview', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
    }

    public function sphview($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) {
            // Calling models
            $UserModel      = new UserModel();
            $ProjectModel   = new ProjectModel;
            $CompanyModel   = new CompanyModel();
            $RabModel       = new RabModel();
            $PaketModel     = new PaketModel();
            $MdlModel       = new MdlModel();
            $MdlPaketModel  = new MdlPaketModel();
            $GconfigModel   = new GconfigModel();
            $CustomRabModel = new CustomRabModel();

            $projects = $ProjectModel->find($id);
            $mark     = $UserModel->find($projects['marketing']);
            $client   = $CompanyModel->where('id', $projects['clientid'])->first();
            $picklien = $UserModel->find($client['pic']);
            $rabs     = $RabModel->where('projectid', $projects['id'])->find();
            $mdls     = $MdlModel->findAll();
            $pakets   = $PaketModel->findAll();
            $gconf    = $GconfigModel->first();
            $custrab  = $CustomRabModel->where('projectid', $projects['id'])->find();
            if (!empty($projects['marketing'])) {
                $mark     = $UserModel->find($projects['marketing']);
            } else {
                $mark     = $UserModel->where('parentid', $client['id'])->first();
            }
            if (!empty($client['pic'])) {
                $picklien = $UserModel->find($client['pic']);
            } else {
                $picklien =  $UserModel->where('parentid', $client['id'])->first();
            }
            $paketsdata = $PaketModel->where('parentid', "0")->find();

            // RAB
            $mdldata = [];
            $mdlid   = [];
            if (!empty($projects['id'])) {
                foreach ($rabs as $rab) {
                    if ($rab['projectid'] === $projects['id']) {
                        foreach ($pakets as $paket) {
                            if ($paket['id'] === $rab['paketid']) {
                                foreach ($paketsdata as $kategori) {
                                    if ($kategori['id'] === $paket['parentid']) {
                                        foreach ($mdls as $mdl) {
                                            if ($mdl['id'] === $rab['mdlid']) {
                                                $denom = "";
                                                $price = "";
                                                $total = [];
                                                if ($mdl['denomination'] === "1") {
                                                    // $price  = $rab['qty'] * $mdl['price'];
                                                    $denom  = "Unit";
                                                } elseif ($mdl['denomination'] === "2") {
                                                    // $price  = $rab['qty'] * $mdl['price'];
                                                    $denom  = "M";
                                                } elseif ($mdl['denomination'] === "3") {
                                                    $luas   =   $mdl['height'] * $mdl['length'];
                                                    // $price  = $rab['qty'] * $mdl['price'];
                                                    $denom  = "M2";
                                                } elseif ($mdl['denomination'] === "4") {
                                                    // $price  = $rab['qty'] * $mdl['price'];
                                                    $denom  = "Set";
                                                }
                                                // $total[] = $rab['qty'] * $mdl['price'];
                                                $datamdlid[] = $mdl['id'];
                                                $mdldata[] = [
                                                    'id'            => $paket['id'],
                                                    'kategori'      => $kategori['name'],
                                                    'name'          => $mdl['name'],
                                                    'length'        => $mdl['length'],
                                                    'width'         => $mdl['width'],
                                                    'height'        => $mdl['height'],
                                                    'volume'        => $mdl['volume'],
                                                    'denom'         => $denom,
                                                    'qty'           => $rab['qty'],
                                                    'mdlprice'      => $mdl['price'],
                                                    'price'         => $mdl['price'] * $rab['qty'],
                                                    'keterangan'    => $mdl['keterangan'],
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $total = array_sum(array_column($mdldata, 'price'));

            // CUSTOM RAB
            $customrab = [];
            foreach ($custrab as $cusrab) {
                if (!empty($cusrab)) {
                    $customrab[] = [
                        'name'  => $cusrab['name'],
                        'price' => $cusrab['price'],
                    ];
                }
            }

            $totalrab = "";
            if (!empty($mdldata)) {
                $totalrab = array_sum(array_column($mdldata, 'mdlprice'));
            }

            $totalcustom = "";
            if (!empty($customrab)) {
                $totalcustom = array_sum(array_column($customrab, 'price'));
            }

            $ppn = "";
            if (!empty($gconf)) {
                $ppn = (int)$gconf['ppn'];
            }

            // END RAB DATA
            $ppnval   = ($ppn / 100) * ((int)$totalcustom + (int)$totalrab);
            $totalsph = (int)$totalcustom + (int)$totalrab + (int)$ppnval;

            // Terbilang
            function tersebut($nilai)
            {
                $nilai = abs($nilai);
                $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                $temp = "";
                if ($nilai < 12) {
                    $temp = " " . $huruf[$nilai];
                } else if ($nilai < 20) {
                    $temp = tersebut($nilai - 10) . " belas";
                } else if ($nilai < 100) {
                    $temp = tersebut($nilai / 10) . " puluh" . tersebut($nilai % 10);
                } else if ($nilai < 200) {
                    $temp = " seratus" . tersebut($nilai - 100);
                } else if ($nilai < 1000) {
                    $temp = tersebut($nilai / 100) . " ratus" . tersebut($nilai % 100);
                } else if ($nilai < 2000) {
                    $temp = " seribu" . tersebut($nilai - 1000);
                } else if ($nilai < 1000000) {
                    $temp = tersebut($nilai / 1000) . " ribu" . tersebut($nilai % 1000);
                } else if ($nilai < 1000000000) {
                    $temp = tersebut($nilai / 1000000) . " juta" . tersebut($nilai % 1000000);
                } else if ($nilai < 1000000000000) {
                    $temp = tersebut($nilai / 1000000000) . " milyar" . tersebut(fmod($nilai, 1000000000));
                } else if ($nilai < 1000000000000000) {
                    $temp = tersebut($nilai / 1000000000000) . " trilyun" . tersebut(fmod($nilai, 1000000000000));
                }
                return $temp;
            }

            function terbilang($nilai)
            {
                if ($nilai < 0) {
                    $hasil = "minus " . trim(tersebut($nilai));
                } else {
                    $hasil = trim(tersebut($nilai));
                }
                return $hasil;
            }

            $angka =  $totalsph;
            // End Terbilang 

            // MARKETING INITIAL
            $markname = "";
            if (!empty($mark)) {
                $markname = $mark->name;
            }
            $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", " ");
            $markname = str_replace($vowels, "", $markname);
            // END MARKETING INITIAL

            $datasph = [
                'nosph'     => "1",
                'proyek'    => $projects['name'],
                'lokasi'    => $client['rsname'],
                'tanggal'   => $projects['tahun'],
                'clientpic' => $picklien->name,
                'ppn'       => $gconf['ppn'],
                'ppnval'    => (int)$ppnval,
                'total'     => $total + (int)$totalcustom,
                'totalsph'  => $totalsph,
                'terbilang' => terbilang($angka) . " rupiah",
                'marketing' => strtoupper($markname),
                'direktur'  => $gconf['direktur'],
                'totcustom' => $totalcustom,
            ];

            // Parsing Data to View
            $data                   = $this->data;
            $data['title']          = lang('Global.titleDashboard');
            $data['description']    = lang('Global.dashboardDescription');
            $data['projects']       = $projects;
            $data['client']         = $client;
            $data['custom']         = $customrab;
            $data['sphdata']        = $datasph;
            $data['sphrabs']        = $mdldata;

            return view('sphview', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function invoice($id)
    {
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
        $CustomRabModel = new CustomRabModel();
        $BastModel      = new BastModel();
        $LogModel       = new LogModel();

        // PROJECT DATA
        $projects   = $ProjectModel->find($id);
        $gconf      = $GconfigModel->first();
        $alamat = "";

        if (!empty($gconf)) {
            $alamat = $gconf['alamat'];
        }
        if (!empty($rabcustom)) {
            $rabcustom  = $CustomRabModel->where('projectid', $projects['id'])->find();
        } else {
            $rabcustom  = [];
        }

        // INVOICE 
        $rabdata    = [];
        if (!empty($projects)) {
            $invoice1  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '1')->first();
            $invoice2  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '2')->first();
            $invoice3  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '3')->first();
            $invoice4  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '4')->first();

            // CLIENT DATA
            $client   = $CompanyModel->find($projects['clientid']);

            // RAB
            $rabs       = $RabModel->where('projectid', $projects['id'])->find();
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
        } else {

            $invoice1  = [];
            $invoice2  = [];
            $invoice3  = [];
            $invoice4  = [];
            $client    = [];
            $rabs      = [];
        }

        // BAST DATA
        if (!empty($id)) {
            $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
            $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();
        } else {
            $bast       = [];
            $sertrim    = [];
        }

        // TOTAL RAB PRICE
        $total = "";
        if (!empty($rabdata)) {
            $total = array_sum(array_column($rabdata, 'price'));
        }

        // RAB CUSTOM VALUE
        $rabcustotal = "";
        if (!empty($rabcustom)) {
            $rabcustotal = array_sum(array_column($rabcustom, 'price'));
        }

        // PPN
        $ppn = "";
        $ppnval = "";
        if (!empty($gconf)) {
            $ppn        = (int)$gconf['ppn'];
            $ppnval     = ($ppn / 100) * (int)$total;
        }

        // total value
        $totalvalue = (int)$total + (int)$rabcustotal + (int)$ppnval;

        // Invoice Data Array
        $termin     = "";
        $progress   = "";
        $nilaispk   = "";
        $dateinv    = "";
        $dateline   = "";
        $pph        = "";
        $referensi  = "";
        $email      = "";
        $status     = "";
        $pic        = "";
        $noinv      = "";
        $ppnvalue   = "";
        $pphvalue   = "";

        if (!empty($projects)) {
            // INVOICE I
            if ($projects['status_spk'] === "1" && !empty($invoice1) && !empty($projects['inv1'])) {
                $termin     = "30";
                $progress   = "30";
                $nilaispk   = ((int)$total - ((int)(70 / 100) * (int)$total)) + (int)$rabcustotal;
                $dateinv    = $projects['inv1'];
                $dateline   = $invoice1['jatuhtempo'];
                $pph        = (int)$invoice1['pph23'];
                $pphvalue   = ($pph / 100) * $nilaispk;
                $ppnvalue   = ($pph / 100) * $nilaispk;
                $referensi  = $invoice1['referensi'];
                $email      = $invoice1['email'];
                $status     = $invoice1['status'];
                $pic        = $invoice1['pic'];
                $noinv      = $invoice1['no_inv'];
            }

            // INVOICE II
            if (!empty($sertrim) && !empty($projects['inv2']) && !empty($invoice3)) {
                $termin     = "30";
                $progress   = "60";
                $nilaispk   = (int)$total - ((int)(70 / 100) * (int)$total) + (int)$rabcustotal;
                $dateinv    = $projects['inv2'];
                $dateline   = $invoice2['jatuhtempo'];
                $pph        = (int)$invoice2['pph23'];
                $pphvalue   = (($pph / 100) * $nilaispk);
                $ppnvalue   = ($ppn / 100) * $nilaispk;
                $referensi  = $invoice2['referensi'];
                $email      = $invoice2['email'];
                $status     = $invoice2['status'];
                $pic        = $invoice2['pic'];
                $noinv      = $invoice2['no_inv'];
            }

            // INVOICE III
            if (!empty($bast) && !empty($projects['inv3']) && !empty($invoice3)) {
                $termin     = "35";
                $progress   = "95";
                $nilaispk   = (int)$total - ((int)(65 / 100) * (int)$total) + (int)$rabcustotal;
                $dateinv    = $projects['inv3'];
                $dateline   = $invoice3['jatuhtempo'];
                $pph        = (int)$invoice3['pph23'];
                $pphvalue   = (($pph / 100) * $nilaispk);
                $ppnvalue   = ($ppn / 100) * $nilaispk;
                $referensi  = $invoice3['referensi'];
                $email      = $invoice3['email'];
                $status     = $invoice3['status'];
                $pic        = $invoice3['pic'];
                $noinv      = $invoice3['no_inv'];
            }

            // INVOCE 4 BAST 3 MONTH CONDITION
            $nowtime = "";
            $datelinebast = "";
            $bast = $BastModel->where('projectid', $projects['id'])->where('status', "1")->first();
            if (!empty($bast)) {
                if (!empty($bast['tanggal_bast'])) {
                    $day    = $bast['tanggal_bast'];
                    $date   = date_create($day);
                    $key    = date_format($date, "Y-m-d");
                    $hari   = date_create($key);
                    date_add($hari, date_interval_create_from_date_string('3 month'));
                    $datelinebast = date_format($hari, 'Y-m-d');

                    $now    = strtotime("now");
                    $nowtime = date("Y-m-d", $now);
                }
            }
            // INVOCE 4 BAST 3 MONTH CONDITION


            // INVOICE IV
            if (!empty($bast) && !empty($projects['inv4']) && !empty($invoice4) && $nowtime > $datelinebast) {
                $termin     = "5";
                $progress   = "100";
                $nilaispk   = (int)$total - ((95 / 100) * (int)$total) + (int)$rabcustotal;
                $dateinv    = $projects['inv4'];
                $dateline   = $invoice4['jatuhtempo'];
                $pph        = (int)$invoice4['pph23'];
                $pphvalue   = ($pph / 100) * $nilaispk;
                $ppnvalue   = ($ppn / 100) * $nilaispk;
                $referensi  = $invoice4['referensi'];
                $email      = $invoice4['email'];
                $status     = $invoice4['status'];
                $pic        = $invoice4['pic'];
                $noinv      = $invoice4['no_inv'];
            }

            // DATA REFERENSI
            $refdata    = "";
            $refname    = "";
            $refacc     = "";
            $refbank    = "";
            if (!empty($referensi)) {
                $refdata = $ReferensiModel->where('id', $referensi)->first();
                $refname    = $refdata['name'];
                $refacc     = $refdata['no_rek'];
                $refbank    = $refdata['bank'];
            }

            // DATA PIC
            $picdata = "";
            $picname = "";
            if (!empty($pic)) {
                $picdata    = $UserModel->where('id', $pic)->first();
                $picname    = $picdata->name;
            }

            // INVOICE FORMAT NUMBER
            $date = date_create($dateinv);
            $Year   = date_format($date, 'Y');
            $number = date_format($date, 'n');
            function invoicenumberview($number)
            {
                $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                $returnValue = '';
                while ($number > 0) {
                    foreach ($map as $roman => $int) {
                        if ($number >= $int) {
                            $number -= $int;
                            $returnValue .= $roman;
                            break;
                        }
                    }
                }
                return $returnValue;
            }
            $roman = invoicenumberview($number);

            $invnum = str_pad($noinv, 3, '0', STR_PAD_LEFT);

            $numinv = $invnum . "/DPSA/" . $roman . "/" . $Year;
            //---END OF INVOICE FORMAT NUMBER---//


            $terminval = "";
            if (!empty($termin)) {
                $terminval = (int)$total * ($termin / 100);
            }

            // PPN VALUE RUPIAH
            $terminvalue = "";
            if (!empty($ppn)) {
                $terminvalue = (int)$terminval * ($ppn / 100);
            }

            // PPH VALUE RUPIAH
            $pphtermin = "";
            if (!empty($pph)) {
                $pphtermin = (int)$terminval * ($pph / 100);
            }

            $invoicedata = [
                'termin'    => $termin,
                'progress'  => $progress,
                'nilai_spk' => $nilaispk,
                'dateinv'   => $dateinv,
                'dateline'  => $dateline,
                'total'     => (int)$total,
                'ppn'       => $ppn,
                'pph'       => $pph,
                'pphval'    => (int)$pphvalue,
                'referensi' => $refname,
                'refacc'    => $refacc,
                'refbank'   => $refbank,
                'email'     => $email,
                'pic'       => $picname,
                'noinv'     => $numinv,
                'direktur'  => $gconf['direktur'],
                'ppnval'    => (int)$ppnvalue,
                'no_spk'    => $projects['no_spk'],
                'alamat'    => $alamat,
                'totalterm' => (int)$terminvalue,
                'pphtermin' => (int)$pphtermin,
            ];
        } else {
            $invoicedata  = [];
        }

        //--- END NEW FUCTION ---//
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan Print Invoice ' . $projects['name']]);

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = lang('Global.titleDashboard');
        $data['description']    = lang('Global.dashboardDescription');
        $data['projects']       = $projects;
        $data['rabs']           = $rabdata;
        $data['rabcustom']      = $rabcustom;
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
        // $mpdf->AddPageByArray("L", "", "", "", "", "15", "15", "25", "15", "", "", "", "", "", "", "", "", "", "", "", "A4");
        $mpdf->SetWatermarkImage(
            './img/logo.png',
            0.1,
            '',
            // [50,50,50],
            // [50,50],
            [70, 40],
        );
        $mpdf->showWatermarkImage = true;
        // $mpdf->setFooter('{PAGENO} / {nb}');
        $date = date_create($projects['created_at']);
        $filename = "invoice" . $status . "-" . $projects['name'] . " " . date_format($date, 'd-m-Y') . ".pdf";
        $html = view('Views/invoice', $data);
        $mpdf->WriteHTML($html);

        $mpdf->Output($filename, 'D');
    }

    // INVOICE EXCEL
    public function invoiceexcel($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) {
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
            $CustomRabModel = new CustomRabModel();
            $BastModel      = new BastModel();

            // PROJECT DATA
            $projects   = $ProjectModel->find($id);
            $gconf      = $GconfigModel->first();
            $alamat = "";

            if (!empty($gconf)) {
                $alamat = $gconf['alamat'];
            }
            if (!empty($rabcustom)) {
                $rabcustom  = $CustomRabModel->where('projectid', $projects['id'])->find();
            } else {
                $rabcustom  = [];
            }

            // INVOICE 
            $rabdata    = [];
            if (!empty($projects)) {
                $invoice1  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '1')->first();
                $invoice2  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '2')->first();
                $invoice3  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '3')->first();
                $invoice4  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '4')->first();

                // CLIENT DATA
                $client   = $CompanyModel->find($projects['clientid']);

                // RAB
                $rabs       = $RabModel->where('projectid', $projects['id'])->find();
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
            } else {

                $invoice1  = [];
                $invoice2  = [];
                $invoice3  = [];
                $invoice4  = [];
                $client    = [];
                $rabs      = [];
            }

            // BAST DATA
            if (!empty($id)) {
                $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
                $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();
            } else {
                $bast       = [];
                $sertrim    = [];
            }

            // TOTAL RAB PRICE
            $total = "";
            if (!empty($rabdata)) {
                $total = array_sum(array_column($rabdata, 'price'));
            }

            // RAB CUSTOM VALUE
            $rabcustotal = "";
            if (!empty($rabcustom)) {
                $rabcustotal = array_sum(array_column($rabcustom, 'price'));
            }

            // PPN
            $ppn = "";
            $ppnval = "";
            if (!empty($gconf)) {
                $ppn        = (int)$gconf['ppn'];
                $ppnval     = ($ppn / 100) * (int)$total;
            }

            // total value
            $totalvalue = (int)$total + (int)$rabcustotal + (int)$ppnval;

            // Invoice Data Array
            $termin     = "";
            $progress   = "";
            $nilaispk   = "";
            $dateinv    = "";
            $dateline   = "";
            $pph        = "";
            $referensi  = "";
            $email      = "";
            $status     = "";
            $pic        = "";
            $noinv      = "";
            $ppnvalue   = "";
            $pphvalue   = "";

            if (!empty($projects)) {
                // INVOICE I
                if ($projects['status_spk'] === "1" && !empty($invoice1) && !empty($projects['inv1'])) {
                    $termin     = "30";
                    $progress   = "30";
                    $nilaispk   = ((int)$total - ((70 / 100) * (int)$total)) + (int)$rabcustotal;
                    $dateinv    = $projects['inv1'];
                    $dateline   = $invoice1['jatuhtempo'];
                    $pph        = (int)$invoice1['pph23'];
                    $pphvalue   = ($pph / 100) * $nilaispk;
                    $ppnvalue   = ($pph / 100) * $nilaispk;
                    $referensi  = $invoice1['referensi'];
                    $email      = $invoice1['email'];
                    $status     = $invoice1['status'];
                    $pic        = $invoice1['pic'];
                    $noinv      = $invoice1['no_inv'];
                    $npwpdpsa   = $gconf['npwp'];
                }

                // INVOICE II
                if (!empty($sertrim) && !empty($projects['inv2']) && !empty($invoice3)) {
                    $termin     = "30";
                    $progress   = "60";
                    $nilaispk   = (int)$total - ((70 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv2'];
                    $dateline   = $invoice2['jatuhtempo'];
                    $pph        = (int)$invoice2['pph23'];
                    $pphvalue   = (($pph / 100) * $nilaispk);
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice2['referensi'];
                    $email      = $invoice2['email'];
                    $status     = $invoice2['status'];
                    $pic        = $invoice2['pic'];
                    $noinv      = $invoice2['no_inv'];
                    $npwpdpsa   = $gconf['npwp'];
                }

                // INVOICE III
                if (!empty($bast) && !empty($projects['inv3']) && !empty($invoice3)) {
                    $termin     = "35";
                    $progress   = "95";
                    $nilaispk   = (int)$total - ((int)(65 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv3'];
                    $dateline   = $invoice3['jatuhtempo'];
                    // $priceppn   = ($gconf['ppn'] / 100) * $nilaispk;
                    $pph        = (int)$invoice3['pph23'];
                    $pphvalue   = (($pph / 100) * $nilaispk);
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice3['referensi'];
                    $email      = $invoice3['email'];
                    $status     = $invoice3['status'];
                    $pic        = $invoice3['pic'];
                    $noinv      = $invoice3['no_inv'];
                    $npwpdpsa   = $gconf['npwp'];
                }

                // INVOCE 4 BAST 3 MONTH CONDITION
                $nowtime = "";
                $datelinebast = "";
                $bast = $BastModel->where('projectid', $projects['id'])->where('status', "1")->first();
                if (!empty($bast)) {
                    if (!empty($bast['tanggal_bast'])) {
                        $day    = $bast['tanggal_bast'];
                        $date   = date_create($day);
                        $key    = date_format($date, "Y-m-d");
                        $hari   = date_create($key);
                        date_add($hari, date_interval_create_from_date_string('3 month'));
                        $datelinebast = date_format($hari, 'Y-m-d');

                        $now    = strtotime("now");
                        $nowtime = date("Y-m-d", $now);
                    }
                }
                // INVOCE 4 BAST 3 MONTH CONDITION

                // INVOICE IV
                if (!empty($bast) && !empty($projects['inv4']) && !empty($invoice4) && $nowtime > $datelinebast) {
                    $termin     = "5";
                    $progress   = "100";
                    $nilaispk   = (int)$total - ((95 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv4'];
                    $dateline   = $invoice4['jatuhtempo'];
                    $priceppn   = ($gconf['ppn'] / 100) * $nilaispk;
                    $pph        = (int)$invoice4['pph23'];
                    $pphvalue   = ($pph / 100) * $nilaispk;
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice4['referensi'];
                    $email      = $invoice4['email'];
                    $status     = $invoice4['status'];
                    $pic        = $invoice4['pic'];
                    $noinv      = $invoice4['no_inv'];
                    $npwpdpsa   = $gconf['npwp'];
                }

                // DATA REFERENSI
                $refdata    = "";
                $refname    = "";
                $refacc     = "";
                $refbank    = "";
                if (!empty($referensi)) {
                    $refdata = $ReferensiModel->where('id', $referensi)->first();
                    $refname    = $refdata['name'];
                    $refacc     = $refdata['no_rek'];
                    $refbank    = $refdata['bank'];
                }

                // DATA PIC
                $picdata = "";
                $picname = "";
                if (!empty($pic)) {
                    $picdata    = $UserModel->where('id', $pic)->first();
                    $picname    = $picdata->name;
                }

                // INVOICE FORMAT NUMBER
                $date = date_create($dateinv);
                $Year   = date_format($date, 'Y');
                $number = date_format($date, 'n');
                function invoicenumberview($number)
                {
                    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                    $returnValue = '';
                    while ($number > 0) {
                        foreach ($map as $roman => $int) {
                            if ($number >= $int) {
                                $number -= $int;
                                $returnValue .= $roman;
                                break;
                            }
                        }
                    }
                    return $returnValue;
                }
                $roman = invoicenumberview($number);

                $invnum = str_pad($noinv, 3, '0', STR_PAD_LEFT);

                $numinv = $invnum . "/DPSA/" . $roman . "/" . $Year;
                //---END OF INVOICE FORMAT NUMBER---//


                $terminval = "";
                if (!empty($termin)) {
                    $terminval = $total * ($termin / 100);
                }

                // PPN VALUE RUPIAH
                $terminvalue = "";
                if (!empty($ppn)) {
                    $terminvalue = (int)$terminval * ($ppn / 100);
                }

                // PPH VALUE RUPIAH
                $pphtermin = "";
                if (!empty($pph)) {
                    $pphtermin = (int)$terminval * ($pph / 100);
                }

                $invoicedata = [
                    'termin'    => $termin,
                    'progress'  => $progress,
                    'nilai_spk' => $nilaispk,
                    'dateinv'   => $dateinv,
                    'dateline'  => $dateline,
                    'total'     => (int)$total,
                    'ppn'       => $ppn,
                    'pph'       => $pph,
                    'pphval'    => (int)$pphvalue,
                    'referensi' => $refname,
                    'refacc'    => $refacc,
                    'refbank'   => $refbank,
                    'email'     => $email,
                    'pic'       => $picname,
                    // 'noinv'     => $numinv,
                    'noinv'     => $noinv,
                    'direktur'  => $gconf['direktur'],
                    'ppnval'    => (int)$ppnvalue,
                    'no_spk'    => $projects['no_spk'],
                    'alamat'    => $alamat,
                    'totalterm' => (int)$terminvalue,
                    'pphtermin' => (int)$pphtermin,
                    'npwpdpsa'  => $npwpdpsa,
                ];
            } else {
                $invoicedata  = [];
            }

            //--- END NEW FUCTION ---//

            // Parsing Data to View
            $data                   = $this->data;
            $data['title']          = lang('Global.titleDashboard');
            $data['description']    = lang('Global.dashboardDescription');
            $data['projects']       = $projects;
            $data['rabs']           = $rabdata;
            $data['rabcustom']      = $rabcustom;
            $data['pakets']         = $PaketModel->findAll();
            $data['mdls']           = $MdlModel->findAll();
            $data['client']         = $client;
            $data['invoice']        = $invoicedata;

            return view('invoiceexcel', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function invoiceview($id)
    {
        if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) {
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
            $CustomRabModel = new CustomRabModel();
            $BastModel      = new BastModel();

            // PROJECT DATA
            $projects   = $ProjectModel->find($id);
            $gconf      = $GconfigModel->first();
            $alamat = "";

            if (!empty($gconf)) {
                $alamat = $gconf['alamat'];
            }
            if (!empty($rabcustom)) {
                $rabcustom  = $CustomRabModel->where('projectid', $projects['id'])->find();
            } else {
                $rabcustom  = [];
            }

            // INVOICE 
            $rabdata    = [];
            if (!empty($projects)) {
                $invoice1  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '1')->first();
                $invoice2  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '2')->first();
                $invoice3  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '3')->first();
                $invoice4  = $InvoiceModel->where('projectid', $projects['id'])->where('status', '4')->first();

                // CLIENT DATA
                $client   = $CompanyModel->find($projects['clientid']);

                // RAB
                $rabs       = $RabModel->where('projectid', $projects['id'])->find();
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
            } else {

                $invoice1  = [];
                $invoice2  = [];
                $invoice3  = [];
                $invoice4  = [];
                $client    = [];
                $rabs      = [];
            }

            // BAST DATA
            if (!empty($id)) {
                $bast       = $BastModel->where('projectid', $id)->where('status', 1)->first();
                $sertrim    = $BastModel->where('projectid', $id)->where('status', 0)->first();
            } else {
                $bast       = [];
                $sertrim    = [];
            }

            // TOTAL RAB PRICE
            $total = "";
            if (!empty($rabdata)) {
                $total = array_sum(array_column($rabdata, 'price'));
            }

            // RAB CUSTOM VALUE
            $rabcustotal = "";
            if (!empty($rabcustom)) {
                $rabcustotal = array_sum(array_column($rabcustom, 'price'));
            }

            // PPN
            $ppn = "";
            $ppnval = "";
            if (!empty($gconf)) {
                $ppn        = (int)$gconf['ppn'];
                $ppnval     = ($ppn / 100) * (int)$total;
            }

            // total value
            $totalvalue = (int)$total + (int)$rabcustotal + (int)$ppnval;

            // Invoice Data Array
            $termin     = "";
            $progress   = "";
            $nilaispk   = "";
            $dateinv    = "";
            $dateline   = "";
            // $priceppn   = "";
            $pph        = "";
            $referensi  = "";
            $email      = "";
            $status     = "";
            $pic        = "";
            $noinv      = "";
            $ppnvalue   = "";
            $pphvalue   = "";

            if (!empty($projects)) {
                // INVOICE I
                if ($projects['status_spk'] === "1" && !empty($invoice1) && !empty($projects['inv1'])) {
                    $termin     = "30";
                    $progress   = "30";
                    $nilaispk   = ((int)$total - ((70 / 100) * (int)$total)) + (int)$rabcustotal;
                    $dateinv    = $projects['inv1'];
                    $dateline   = $invoice1['jatuhtempo'];
                    $pph        = (int)$invoice1['pph23'];
                    $pphvalue   = ($pph / 100) * $nilaispk;
                    $ppnvalue   = ($pph / 100) * $nilaispk;
                    $referensi  = $invoice1['referensi'];
                    $email      = $invoice1['email'];
                    $status     = $invoice1['status'];
                    $pic        = $invoice1['pic'];
                    $noinv      = $invoice1['no_inv'];
                }

                // INVOICE II
                if (!empty($sertrim) && !empty($projects['inv2']) && !empty($invoice3)) {
                    $termin     = "30";
                    $progress   = "60";
                    $nilaispk   = (int)$total - ((70 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv2'];
                    $dateline   = $invoice2['jatuhtempo'];
                    $pph        = (int)$invoice2['pph23'];
                    $pphvalue   = (($pph / 100) * $nilaispk);
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice2['referensi'];
                    $email      = $invoice2['email'];
                    $status     = $invoice2['status'];
                    $pic        = $invoice2['pic'];
                    $noinv      = $invoice2['no_inv'];
                }

                // INVOICE III
                if (!empty($bast) && !empty($projects['inv3']) && !empty($invoice3)) {
                    $termin     = "35";
                    $progress   = "95";
                    $nilaispk   = (int)$total - ((int)(65 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv3'];
                    $dateline   = $invoice3['jatuhtempo'];
                    // $priceppn   = ($gconf['ppn'] / 100) * $nilaispk;
                    $pph        = (int)$invoice3['pph23'];
                    $pphvalue   = (($pph / 100) * $nilaispk);
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice3['referensi'];
                    $email      = $invoice3['email'];
                    $status     = $invoice3['status'];
                    $pic        = $invoice3['pic'];
                    $noinv      = $invoice3['no_inv'];
                }

                // INVOCE 4 BAST 3 MONTH CONDITION
                $nowtime = "";
                $datelinebast = "";
                $bast = $BastModel->where('projectid', $projects['id'])->where('status', "1")->first();
                if (!empty($bast)) {
                    if (!empty($bast['tanggal_bast'])) {
                        $day    = $bast['tanggal_bast'];
                        $date   = date_create($day);
                        $key    = date_format($date, "Y-m-d");
                        $hari   = date_create($key);
                        date_add($hari, date_interval_create_from_date_string('3 month'));
                        $datelinebast = date_format($hari, 'Y-m-d');

                        $now    = strtotime("now");
                        $nowtime = date("Y-m-d", $now);
                    }
                }
                // INVOCE 4 BAST 3 MONTH CONDITION


                // INVOICE IV
                if (!empty($bast) && !empty($projects['inv4']) && !empty($invoice4) && $nowtime > $datelinebast) {
                    $termin     = "5";
                    $progress   = "100";
                    $nilaispk   = (int)$total - ((95 / 100) * (int)$total) + (int)$rabcustotal;
                    $dateinv    = $projects['inv4'];
                    $dateline   = $invoice4['jatuhtempo'];
                    $priceppn   = ($gconf['ppn'] / 100) * $nilaispk;
                    $pph        = (int)$invoice4['pph23'];
                    $pphvalue   = ($pph / 100) * $nilaispk;
                    $ppnvalue   = ($ppn / 100) * $nilaispk;
                    $referensi  = $invoice4['referensi'];
                    $email      = $invoice4['email'];
                    $status     = $invoice4['status'];
                    $pic        = $invoice4['pic'];
                    $noinv      = $invoice4['no_inv'];
                }

                // DATA REFERENSI
                $refdata    = "";
                $refname    = "";
                $refacc     = "";
                $refbank    = "";
                if (!empty($referensi)) {
                    $refdata = $ReferensiModel->where('id', $referensi)->first();
                    $refname    = $refdata['name'];
                    $refacc     = $refdata['no_rek'];
                    $refbank    = $refdata['bank'];
                }

                // DATA PIC
                $picdata = "";
                $picname = "";
                if (!empty($pic)) {
                    $picdata    = $UserModel->where('id', $pic)->first();
                    $picname    = $picdata->name;
                }

                // INVOICE FORMAT NUMBER
                $date = date_create($dateinv);
                $Year   = date_format($date, 'Y');
                $number = date_format($date, 'n');
                function invoicenumberview($number)
                {
                    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                    $returnValue = '';
                    while ($number > 0) {
                        foreach ($map as $roman => $int) {
                            if ($number >= $int) {
                                $number -= $int;
                                $returnValue .= $roman;
                                break;
                            }
                        }
                    }
                    return $returnValue;
                }
                $roman = invoicenumberview($number);

                $invnum = str_pad($noinv, 3, '0', STR_PAD_LEFT);

                $numinv = $invnum . "/DPSA/" . $roman . "/" . $Year;
                //---END OF INVOICE FORMAT NUMBER---//


                $terminval = "";
                if (!empty($termin)) {
                    $terminval = $total * ($termin / 100);
                }

                // PPN VALUE RUPIAH
                $terminvalue = "";
                if (!empty($ppn)) {
                    $terminvalue = (int)$terminval * ($ppn / 100);
                }

                // PPH VALUE RUPIAH
                $pphtermin = "";
                if (!empty($pph)) {
                    $pphtermin = (int)$terminval * ($pph / 100);
                }

                $invoicedata = [
                    'termin'    => $termin,
                    'progress'  => $progress,
                    'nilai_spk' => $nilaispk,
                    'dateinv'   => $dateinv,
                    'dateline'  => $dateline,
                    'total'     => (int)$total,
                    'ppn'       => $ppn,
                    'pph'       => $pph,
                    'pphval'    => (int)$pphvalue,
                    'referensi' => $refname,
                    'refacc'    => $refacc,
                    'refbank'   => $refbank,
                    'email'     => $email,
                    'pic'       => $picname,
                    'noinv'     => $numinv,
                    'direktur'  => $gconf['direktur'],
                    'ppnval'    => (int)$ppnvalue,
                    'no_spk'    => $projects['no_spk'],
                    'alamat'    => $alamat,
                    'totalterm' => (int)$terminvalue,
                    'pphtermin' => (int)$pphtermin,
                ];
            } else {
                $invoicedata  = [];
            }

            //--- END NEW FUCTION ---//

            // Parsing Data to View
            $data                   = $this->data;
            $data['title']          = lang('Global.titleDashboard');
            $data['description']    = lang('Global.dashboardDescription');
            $data['projects']       = $projects;
            $data['rabs']           = $rabdata;
            $data['rabcustom']      = $rabcustom;
            $data['pakets']         = $PaketModel->findAll();
            $data['mdls']           = $MdlModel->findAll();
            $data['client']         = $client;
            $data['invoice']        = $invoicedata;

            return view('invoice', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function removesertrim($id)
    {
        $BastModel      = new BastModel;
        $LogModel       = new LogModel;
        $ProjectModel   = new ProjectModel;

        $bast       = $BastModel->find($id);
        $project    = $ProjectModel->find($bast['projectid']);
        $filename   = $bast['file'];

        if (!empty($filename)) {
            if ($bast['status'] === "0") {
                unlink(FCPATH . 'img/sertrim/' . $filename);
            } elseif ($bast['status'] === "1") {
                unlink(FCPATH . 'img/bast/' . $filename);
            }
        }
        // $BastModel->delete($bast);
        if ($bast['status'] === "1") {
            $newbast = [
                'id'    => $bast['id'],
                'file'  => "",
            ];
            $BastModel->save($newbast);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus File Bast ' . $project['name']]);
        } else {
            $BastModel->delete($bast);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus File Sertrim ' . $project['name']]);
        }

        die(json_encode(array($filename)));
    }

    // public function inv4($id)
    // {
    //     $ProjectModel = new ProjectModel();
    //     $input = $this->request->getPost();

    //     $date = date_create($input['dateline']);
    //     $day = date_format($date, "Y-m-d H:i:s");

    //     $invoice = [
    //         'id'    => $id,
    //         'inv4'  => $day,
    //     ];
    //     $ProjectModel->save($invoice);

    //     die(json_encode($invoice));
    // }
}
