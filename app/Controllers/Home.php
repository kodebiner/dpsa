<?php

namespace App\Controllers;

use App\Entities\User;
use App\Models\BastModel;
use App\Models\CompanyModel;
use App\Models\CustomRabModel;
use App\Models\UserModel;
use App\Models\ProjectModel;
use App\Models\RabModel;
use App\Models\PaketModel;
use App\Models\MdlModel;
use App\Models\DesignModel;
use App\Models\LogModel;
use App\Models\ProductionModel;

class Home extends BaseController
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
        if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Models
            $UserModel      = new UserModel();
            $ProjectModel   = new ProjectModel();
            $CompanyModel   = new CompanyModel();
            $RabModel       = new RabModel();
            $PaketModel     = new PaketModel();
            $MdlModel       = new MdlModel();
            $DesignModel    = new DesignModel();


            // Populating data
            $input = $this->request->getGet();

            // parsing data to view
            $data           = $this->data;
            $data['input']  = $input;

            // Variable for pagination
            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if (($this->data['role'] === 'superuser') || ($this->data['role'] === 'owner')) {

                // New Client Data 
                $clients = $this->db->table('company')->where('deleted_at', null);
                $clients->select('company.id as id, company.rsname as rsname, company.ptname as ptname, company.address as address');
                $clients->where('company.status !=', '0');
                if (isset($input['search']) && !empty($input['search'])) {
                    $clients->like('company.rsname', $input['search']);
                    $clients->orLike('company.rsname', $input['search']);
                }
                $query = $clients->get($perpage, $offset)->getResultArray();

                if (isset($input['search']) && !empty($input['search'])) {
                    $total = $clients
                        ->where('company.status !=', '0')
                        ->like('company.rsname', $input['search'])
                        ->orLike('company.rsname', $input['search'])
                        ->countAllResults();
                } else {
                    $total = $clients
                        ->where('company.status !=', '0')
                        ->countAllResults();
                }

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['clients']        = $query;
                $data['rabs']           = $RabModel->findAll();
                $data['pakets']         = $PaketModel->findAll();
                $data['mdls']           = $MdlModel->findAll();
                $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');

                return view('dashboard-superuser', $data);
            } elseif ($this->data['role'] === 'client pusat') {

                // New Client Pusat Function
                $clients = array();

                if (isset($input['search']) && !empty($input['search'])) {
                    $branches = $CompanyModel->where('parentid', $this->data['parentid'])->where('deleted_at', null)->like('rsname', $input['search'])->orLike('ptname', $input['search'])->find();
                } else {
                    $projectpusat = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at', null)->find();
                    if (!empty($projectpusat)) {
                        $company = $CompanyModel->where('id', $this->data['parentid'])->first();
                        $clients[] = [
                            'id'        => $company['id'],
                            'rsname'    => $company['rsname'],
                        ];
                    }
                    $branches = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at', null)->find();
                }

                foreach ($branches as $branch) {
                    $clients[] = [
                        'id'        => $branch['id'],
                        'rsname'    => $branch['rsname'],
                    ];
                }

                $total = count($clients);

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['rabs']           = $RabModel->findAll();
                $data['pakets']         = $PaketModel->findAll();
                $data['clients']        = array_slice($clients, $offset, $perpage);
                $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');

                return view('dashboard-superuser', $data);
            } elseif ($this->data['role'] === 'client cabang') {
                // Client Cabang function
                $ProjectModel       = new ProjectModel;
                $CompanyModel       = new CompanyModel();
                $RabModel           = new RabModel();
                $PaketModel         = new PaketModel();
                $MdlModel           = new MdlModel();
                $DesignModel        = new DesignModel();
                $BastModel          = new BastModel();
                $ProductionModel    = new ProductionModel();
                $CustomRabModel     = new CustomRabModel();

                if (isset($input['search']) && !empty($input['search'])) {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at', null)->like('name', $input['search'])->paginate($perpage, 'projects');
                } else {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at', null)->paginate($perpage, 'projects');
                }


                $projectdata = [];
                $projectdesign = [];
                $client = "";
                if (!empty($projects)) {
                    foreach ($projects as $project) {
                        $client = $CompanyModel->find($project['clientid']);
                        $projectdata[$project['id']]['project']     = $ProjectModel->where('id', $project['id'])->first();
                        $projectdesign[$project['id']]['design']    = $DesignModel->where('projectid', $project['id'])->first();

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

                        // Custom RAB MODEL
                        $projectdata[$project['id']]['custrab']        = $CustomRabModel->where('projectid', $project['id'])->find();

                        // bast
                        $projectdata[$project['id']]['sertrim']        = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                        $projectdata[$project['id']]['bast']           = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

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

                        // Production Proggres
                        if (!empty($projectdata[$project['id']]['rab'])) {
                            $price = [];
                            foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                                $price[] = [
                                    'id'        => $mdldata['id'],
                                    'proid'     => $mdldata['proid'],
                                    'price'     => $mdldata['oriprice'],
                                    'sumprice'  => $mdldata['price'],
                                    'qty'       => $mdldata['qty'],
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

                            $productval = $ProductionModel->where('projectid', $project['id'])->whereIn('mdlid', $datamdlid)->find();

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
                            $projectdata[$project['id']]['progress']    = array_sum($progress);

                            if (!empty($projectdata[$project['id']]['bast'])) {
                                $day =  $projectdata[$project['id']]['bast']['tanggal_bast'];
                                $date = date_create($day);
                                $key = date_format($date, "Y-m-d");
                                $hari = date_create($key);
                                date_add($hari, date_interval_create_from_date_string('3 month'));
                                $dateline = date_format($hari, 'Y-m-d');

                                $now = strtotime("now");
                                $nowtime = date("Y-m-d", $now);
                                $projectdata[$project['id']]['dateline'] = $dateline;
                                $projectdata[$project['id']]['now'] = $nowtime;
                            }
                        } else {
                            $projectdata[$project['id']]['dateline'] = '';
                            $projectdata[$project['id']]['now'] = '';
                        }
                    }
                }

                $company = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at', null)->find();

                $clients = array();
                foreach ($company as $comp) {
                    if (!empty($clients)) {
                        $clients[] = [
                            'id'  => $comp['id'],
                            'rsname' => $comp['rsname'],
                        ];
                    }
                }

                // Parsing Data to View
                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['client']         = $client;
                $data['projects']       = $projects;
                $data['design']         = $DesignModel->findAll();
                $data['rabs']           = $RabModel->findAll();
                $data['pakets']         = $PaketModel->findAll();
                $data['mdls']           = $MdlModel->findAll();
                $data['pager']          = $pager->links('projects', 'uikit_full');
                $data['projectdata']    = $projectdata;
                $data['projectdesign']  = $projectdesign;

                return view('dashboard', $data);
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function clientdashboard($id)
    {
        if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) {

            // Calling Services
            $pager = \Config\Services::pager();
            // Calling models
            $ProjectModel       = new ProjectModel;
            $CompanyModel       = new CompanyModel();
            $RabModel           = new RabModel();
            $PaketModel         = new PaketModel();
            $MdlModel           = new MdlModel();
            $DesignModel        = new DesignModel();
            $BastModel          = new BastModel();
            $ProductionModel    = new ProductionModel();
            $CustomRabModel     = new CustomRabModel();

            // Populating Data
            $input = $this->request->getGet();

            if (isset($input['perpage']) && !empty($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $client = $CompanyModel->find($id);

            if (isset($input['search']) && !empty($input['search'])) {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at', null)->like('name', $input['search'])->paginate($perpage, 'projects');
            } else {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at', null)->paginate($perpage, 'projects');
            }

            $projectdata        = [];
            $projectdesign      = [];
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    $projectdata[$project['id']]['project']     = $ProjectModel->where('id', $project['id'])->first();
                    $projectdesign[$project['id']]['design']    = $DesignModel->where('projectid', $project['id'])->first();

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

                    // Custom RAB MODEL
                    $projectdata[$project['id']]['custrab']        = $CustomRabModel->where('projectid', $project['id'])->find();

                    // bast
                    $projectdata[$project['id']]['sertrim']        = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                    $projectdata[$project['id']]['bast']           = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

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

                    // Production Proggres
                    if (!empty($projectdata[$project['id']]['rab'])) {
                        $price = [];
                        foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                            $price[] = [
                                'id'        => $mdldata['id'],
                                'proid'     => $mdldata['proid'],
                                'price'     => $mdldata['oriprice'],
                                'sumprice'  => $mdldata['price'],
                                'qty'       => $mdldata['qty'],
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

                        $productval = $ProductionModel->where('projectid', $project['id'])->whereIn('mdlid', $datamdlid)->find();

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
                        $projectdata[$project['id']]['progress']    = array_sum($progress);

                        if (!empty($projectdata[$project['id']]['bast'])) {
                            $day =  $projectdata[$project['id']]['bast']['tanggal_bast'];
                            $date = date_create($day);
                            $key = date_format($date, "Y-m-d");
                            $hari = date_create($key);
                            date_add($hari, date_interval_create_from_date_string('3 month'));
                            $dateline = date_format($hari, 'Y-m-d');

                            $now = strtotime("now");
                            $nowtime = date("Y-m-d", $now);
                            $projectdata[$project['id']]['dateline'] = $dateline;
                            $projectdata[$project['id']]['now'] = $nowtime;
                        }
                    } else {
                        $projectdata[$project['id']]['dateline'] = '';
                        $projectdata[$project['id']]['now'] = '';
                    }
                }
            }


            // Parsing Data to View
            $data                   = $this->data;
            $data['title']          = lang('Global.titleDashboard');
            $data['description']    = lang('Global.dashboardDescription');
            $data['client']         = $client;
            $data['projects']       = $projects;
            $data['design']         = $DesignModel->findAll();
            $data['rabs']           = $RabModel->findAll();
            $data['pakets']         = $PaketModel->findAll();
            $data['mdls']           = $MdlModel->findAll();
            $data['pager']          = $pager->links('projects', 'uikit_full');
            $data['projectdata']    = $projectdata;
            $data['projectdesign']  = $projectdesign;

            return view('dashboard', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function acc($id)
    {
        if (($this->data['authorize']->hasPermission('client.auth.holding', $this->data['uid'])) || ($this->data['authorize']->hasPermission('client.auth.branch', $this->data['uid']))) {
            $DesignModel = new DesignModel();
            $ProjectModel = new ProjectModel();
            $LogModel    = new LogModel();
            $input = $this->request->getPost('status');

            $design = $DesignModel->find($id);

            $status = [
                'id'        => $id,
                'status'    => $input,
            ];
            $DesignModel->save($status);

            $project = $ProjectModel->find($design['projectid']);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menyetujui Revisi Desain ' . $project['name']]);
            $data = $this->data;
            die(json_encode(array($project['name'])));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function revisi()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/macbinary,application/mac-binary,application/octet-stream,application/x-binary,application/x-macbinary,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/revisi/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function saverevisi($id)
    {
        $ProjectModel   = new ProjectModel();
        $DesignModel    = new DesignModel();
        $LogModel       = new LogModel();

        $input = $this->request->getPost();
        $project = $ProjectModel->find($id);

        // Validation Rules
        $rules = [
            'revisi' => [
                'label'  => 'Revisi',
                'rules'  => 'required',
                'errors' => [
                    'required'      => '{field} Belum Di Unggah',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (isset($input['revisi'])) {
            $project = $ProjectModel->find($id);
            $design = $DesignModel->where('projectid', $id)->first();
            if (!empty($design)) {
                if(!empty($design['revision'])){
                    unlink(FCPATH . '/img/revisi/' . $design['revision']);
                }
                $datadesign = [
                    'id'            => $design['id'],
                    'projectid'     => $id,
                    'revision'      => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->save($datadesign);
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload revisi '.$project['name']]);
            } else {
                $datadesign = [
                    'projectid'     => $id,
                    'revision'      => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->save($datadesign);
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah revisi '.$project['name']]);
            }
        }

        return redirect()->back()->with('message', 'Revisi telah tekirim');
    }

    public function removerevisi()
    {
        // Removing File
        $input = $this->request->getPost('revisi');
        unlink(FCPATH . 'img/revisi/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function installation()
    {
        // Database Migration
        command('migrate --all');

        // Calling Libraries and Services
        $authorize = service('authorization');

        // Calling Models
        $UserModel = new UserModel();

        // Remove All Users
        $users = $UserModel->findAll();
        if (!empty($users)) {
            $uids = array();
            foreach ($users as $user) {
                $uids[] = $user->id;
            }
            $UserModel->delete($uids);
            $UserModel->purgeDeleted();
        }

        // Remove Old Permission
        $permissions = $authorize->permissions();
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                $authorize->deletePermission($permission['id']);
            }
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
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $authorize->deleteGroup($group->id);
            }
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

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Vaalidating Password
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
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

    public function addfinance()
    {
        // Calling Libraries and Services
        $authorize = service('authorization');

        // Creating Permissions
        $authorize->createPermission('finance.project.edit', 'Merubah data finance.');

        // Creating Prebuild Groups
        $authorize->createGroup('finance', 'Divisi finance.');

        // Prebuild permissions
        $authorize->addPermissionToGroup('admin.project.read', 'finance');
        $authorize->addPermissionToGroup('finance.project.edit', 'finance');
        $authorize->addPermissionToGroup('client.read', 'finance');
        $authorize->addPermissionToGroup('finance.project.edit', 'superuser');

        // Redirect to Login
        return redirect()->to('login')->with('message', 'Aplikasi berhasil diperbarui. Silahkan melakukan Login');
    }

    public function logedin()
    {
        $data = $this->data;

        if (($data['role'] === 'client pusat') || ($data['role'] === 'client cabang')) {
            return redirect()->to('');
        } else {
            return redirect()->to('project');
        }
    }

    public function trial()
    {
        $authorize = service('authorization');
        $authorize->removeUserFromGroup(3, 9);
    }

    public function information()
    {
        phpinfo();
    }
}
