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
use App\Models\BuktiModel;
use App\Models\GconfigModel;
use App\Models\NotificationModel;
use App\Models\InvoiceModel;
use App\Models\PembayaranModel;
use App\Models\VersionModel;

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
            $UserModel          = new UserModel();
            $ProjectModel       = new ProjectModel();
            $CompanyModel       = new CompanyModel();
            $RabModel           = new RabModel();
            $PaketModel         = new PaketModel();
            $MdlModel           = new MdlModel();
            $DesignModel        = new DesignModel();
            $BastModel          = new BastModel();
            $CustomRabModel     = new CustomRabModel();
            $PembayaranModel    = new PembayaranModel();
            $GconfigModel       = new GconfigModel();
            $InvoiceModel       = new InvoiceModel();

            // Populating data
            $input = $this->request->getGet();
            $gconf = $GconfigModel->first();

            // parsing data to view
            $data           = $this->data;
            // $data['input']  = $input;

            // Variable for pagination
            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            // Daterange Filter
            $inputdate = $this->request->getVar('daterange');
            if (!empty($inputdate)) {
                $daterange = explode(' - ', $inputdate);
                $startdate = $daterange[0];
                $enddate = $daterange[1];
            } else {
                $startdate = date('Y-m-1');
                $enddate = date('Y-m-t');
            }

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

                // Report Data Script
                $this->db       = \Config\Database::connect();
                $validation     = \Config\Services::validation();
                $proyek         = $this->builder  = $this->db->table('project');

                if ($startdate === $enddate) {
                    $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
                } else {
                    $this->builder->where('project.created_at >=', $startdate)->where('project.created_at <=', $enddate);
                }
                $this->builder->where('project.deleted_at ='.null);
                
                // Variable for pagination
                if (isset($input['perpagereport'])) {
                    $perpagereport = $input['perpagereport'];
                } else {
                    $perpagereport = 10;
                }
                
                $pagereport = (@$_GET['pagereport']) ? $_GET['pagereport'] : 1;
                $offsetreport = ($pagereport - 1) * $perpagereport;
                
                $this->builder->join('users', 'users.id = project.marketing');
                $this->builder->join('company', 'company.id = project.clientid');
                if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                    $this->builder->like('project.name', $input['searchreport']);
                    $this->builder->orLike('users.username', $input['searchreport']);
                    $this->builder->orLike('company.rsname', $input['searchreport']);
                }
                $this->builder->orderBy('id',"DESC");
                $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');

                $queryproject = $this->builder->get($perpagereport, $offsetreport)->getResultArray();

                // $totalpro = $ProjectModel->where('project.deleted_at ='.null)->where('project.created_at >=', $startdate)->where('project.created_at <=', $enddate)->find();
                // dd($totalpro);
                if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                    $totalpro = $ProjectModel
                        ->where('project.deleted_at ='.null)
                        ->join('company', 'company.id = project.clientid')
                        ->like('project.name', $input['searchreport'])
                        ->orLike('company.rsname', $input['searchreport'])
                        // ->where('created_at >=', $startdate)
                        // ->where('created_at <=', $enddate)
                        ->countAllResults();
                } else {
                    $totalpro = $ProjectModel
                    ->where('project.deleted_at ='.null)
                    ->where('project.created_at >=', $startdate)
                    ->where('project.created_at <=', $enddate)
                    ->countAllResults();
                }

                // Query Data Project
                $projectdata = [];
                foreach ($queryproject as $project) {

                    // Klien
                    $projectdata[$project['id']]['klien'] = $CompanyModel->where('id', $project['clientid'])->first();

                    // Marketing
                    $projectdata[$project['id']]['marketing'] = $UserModel->where('id', $project['marketing'])->first();

                    // RAB
                    $rabs       = $RabModel->where('projectid', $project['id'])->find();
                    if (!empty($rabs)) {
                        foreach ($rabs as $rab) {
                            $paketid[]  = $rab['paketid'];

                            // MDL RAB
                            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                            foreach ($rabmdl as $mdlr) {
                                if($mdlr['denomination'] === "2"){
                                    $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] * $mdlr['volume']);
                                }else{
                                    $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                                }
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
                                    'price'         => $hargamdl,
                                    'oriprice'      => (int)$mdlr['price'],
                                ];
                            }
                        }
                    }

                    // Get RAB data
                    $price = [];
                    if (!empty($projectdata[$project['id']]['rab'])) {
                        foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                            $price[] = [
                                'id'        => $mdldata['id'],
                                'proid'     => $mdldata['proid'],
                                'price'     => $mdldata['oriprice'],
                                'sumprice'  => $mdldata['price'],
                                'qty'       => $mdldata['qty']
                            ];
                        }
                    }

                    // Setrim
                    $projectdata[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();

                    // BAST
                    $projectdata[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                    $projectdata[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                    if (!empty($projectdata[$project['id']]['bastfile'])) {
                        $day =  $projectdata[$project['id']]['bastfile']['tanggal_bast'];
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

                    // Custom RAB
                    $projectdata[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->notLike('name', 'biaya pengiriman')->find();

                    // Shipping Cost
                    $projectdata[$project['id']]['shippingcost']    = $CustomRabModel->where('projectid', $project['id'])->like('name', 'biaya pengiriman')->first();

                    // All Custom RAB 
                    $allCustomRab = $CustomRabModel->where('projectid', $project['id'])->find();

                    // New Cust Rab Price
                    $allnewrabcust = [];
                    foreach($allCustomRab as $rabcust){
                        if($rabcust['denomination'] === "2"){
                            $hargacustrab = $rabcust['qty'] * ($rabcust['price'] * $rabcust['volume']);
                        }elseif($rabcust['denomination'] != "2" && !empty($rabcust['qty'])){
                            $hargacustrab = $rabcust['qty'] * $rabcust['price'];
                        }else{
                            $hargacustrab = $rabcust['price'];
                        }
                        $allnewrabcust[] = $hargacustrab;
                    }
                    $projectdatareport[$project['id']]['allcustomrab']    = array_sum($allnewrabcust);

                    // Pembayaran Value
                    $pembayaran = $PembayaranModel->where('projectid',$project['id'])->find();
                    $projectdata[$project['id']]['pembayaran'] = array_sum(array_column($pembayaran, 'qty'));

                    // Rab Sum Value
                    $projectdata[$project['id']]['rabvalue'] = 0;
                    if (!empty($price)) {
                        $projectdata[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                    }

                    // New Value ALL RAB & CUSTOM RAB + PPN
                    $projectdata[$project['id']]['rabvalueppn'] = 0;
                    if(!empty($price) || !empty($allnewrabcust)){
                        $allrab     =   array_sum($allnewrabcust) +  array_sum(array_column($price, 'sumprice')) ;

                        // Value I & II
                        $valuerab = $allrab - ($allrab * (70/100));
                        $ppnrabval = ((int)$valuerab * ((int)$gconf['ppn']/100));
                        $valrab1  = $valuerab + $ppnrabval;
                        $valrab12 = (int)$valrab1 * 2;

                        // Value III
                        $valuerab3 = $allrab - ($allrab * (65/100));
                        $ppnrabval3 = (((int)$gconf['ppn']/100) * (int)$valuerab3) + $valuerab3;

                        // Value IV
                        $valuerab4 = $allrab - ($allrab * (5/100));
                        $valrabres4 = $allrab - $valuerab4;
                        $ppnrabval4 = (((int)$gconf['ppn']/100) * (int)$valrabres4) + $valrabres4;

                        // PPH Value Configuration
                        $pphinvoice1 = $InvoiceModel->where('projectid',$project['id'])->where('status', 1)->first();
                        $pphinvoice2 = $InvoiceModel->where('projectid',$project['id'])->where('status', 2)->first();
                        $pphinvoice3 = $InvoiceModel->where('projectid',$project['id'])->where('status', 3)->first();
                        $pphinvoice4 = $InvoiceModel->where('projectid',$project['id'])->where('status', 4)->first();

                        $pph1 = (int)$valuerab * ((int)$pphinvoice1['pph23'] / 100); 
                        $pph2 = (int)$valuerab * ((int)$pphinvoice2['pph23'] / 100); 
                        $pph3 = (int)$valuerab3 * ((int)$pphinvoice3['pph23'] / 100); 
                        $pph4 = (int)$valuerab4 * ((int)$pphinvoice4['pph23'] / 100); 

                        $projectdata[$project['id']]['rabvalueppn'] = (int)$ppnrabval4 + (int)$ppnrabval3 + (int)$valrab12 + (int)$pph1 +  (int)$pph2 +  (int)$pph3 +  (int)$pph4;
                    }
                    // End New Value ALL RAB & CUSTOM RAB + PPN
                }

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['clients']        = $query;
                $data['rabs']           = $RabModel->findAll();
                $data['pakets']         = $PaketModel->findAll();
                $data['mdls']           = $MdlModel->findAll();
                $data['pagerpro']       = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
                $data['pagerreport']    = $pager->makeLinks($pagereport, $perpagereport, $totalpro, 'uikit_full2');
                // $data['pager']          = $this->builder->get($perpagereport, $offsetreport)->pager;
                // $data['pager']          = $clients->get($perpage, $offset);
                $data['input']          = $this->request->getGet('projectid');
                $data['projectdata']    = $projectdata;
                $data['projects']       = $queryproject;
                $data['input']          = $input;
                $data['total']          = count($queryproject);
                $data['startdate']      = strtotime($startdate);
                $data['enddate']        = strtotime($enddate);

                return view('dashboard-superuser', $data);

            } elseif ($this->data['role'] === 'client pusat') {

                // New Client Pusat Function
                $clients = array();
                $klienid = array();

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
                        $klienid []= $company['id'];
                    }
                    $branches = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at', null)->find();
                }

                foreach ($branches as $branch) {
                    $clients[] = [
                        'id'        => $branch['id'],
                        'rsname'    => $branch['rsname'],
                    ];
                    $klienid[] = $branch['id'];
                }
                $total = count($clients); 
                
                // Report Script
                $this->db       = \Config\Database::connect();
                $validation     = \Config\Services::validation();
                $proyek         = $this->builder  = $this->db->table('project');
                if ($startdate === $enddate) {
                    $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
                } else {
                    $this->builder->where('project.created_at >=', $startdate)->where('project.created_at <=', $enddate);
                }
                $this->builder->where('project.deleted_at ='.null);
                if(!empty($klienid)){
                    $this->builder->join('users', 'users.id = project.marketing');
                    $this->builder->join('company', 'company.id = project.clientid');
                    if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                        $this->builder->like('project.name', $input['searchreport']);
                        $this->builder->orLike('company.rsname', $input['searchreport']);
                    }
                    $this->builder->whereIn('project.clientid',$klienid);
                    $this->builder->orderBy('id',"DESC");
                    $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
                    $queryproject = $this->builder->get($perpage, $offset)->getResultArray();
                }else{
                    $queryproject = $this->builder->where('project.clientid',$this->data['account']->parentid)->get()->getResultArray();
                }
                
                // Variable for pagination
                if (isset($input['perpagereport'])) {
                    $perpagereport = $input['perpagereport'];
                } else {
                    $perpagereport = 10;
                }

                $pagereport = (@$_GET['pagereport']) ? $_GET['pagereport'] : 1;
                $offsetreport = ($pagereport - 1) * $perpagereport;

                if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                    $totalpro = $proyek
                        ->join('users', 'users.id = project.marketing')
                        ->join('company', 'company.id = project.clientid')
                        ->like('project.name', $input['searchreport'])
                        ->orLike('company.rsname', $input['searchreport'])
                        ->whereIn('project.clientid',$klienid)
                        ->where('project.deleted_at ='.null)
                        ->where('created_at >=', $startdate)
                        ->where('created_at <=', $enddate)
                        ->countAllResults();
                } else {
                    $totalpro = $proyek
                        ->where('project.deleted_at ='.null)
                        ->where('created_at >=', $startdate)
                        ->where('created_at <=', $enddate)
                        ->whereIn('project.clientid',$klienid)
                        ->countAllResults();
                }

                // Query Data Project
                $projectdata = [];
                foreach ($queryproject as $project) {

                    // Klien
                    $projectdata[$project['id']]['klien'] = $CompanyModel->where('id', $project['clientid'])->first();

                    // Marketing
                    $projectdata[$project['id']]['marketing'] = $UserModel->where('id', $project['marketing'])->first();

                    // RAB
                    $rabs       = $RabModel->where('projectid', $project['id'])->find();
                    if (!empty($rabs)) {
                        foreach ($rabs as $rab) {
                            $paketid[]  = $rab['paketid'];

                            // MDL RAB
                            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                            foreach ($rabmdl as $mdlr) {
                                if($mdlr['denomination'] === "2"){
                                    $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] *$mdlr['volume']);
                                }else{
                                    $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                                }
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
                                    'price'         => $hargamdl,
                                    'oriprice'      => (int)$mdlr['price'],
                                ];
                            }
                        }
                    }

                    // Get RAB data
                    $price = [];
                    if (!empty($projectdata[$project['id']]['rab'])) {
                        foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                            $price[] = [
                                'id'        => $mdldata['id'],
                                'proid'     => $mdldata['proid'],
                                'price'     => $mdldata['oriprice'],
                                'sumprice'  => $mdldata['price'],
                                'qty'       => $mdldata['qty']
                            ];
                        }
                    }

                    // Setrim
                    $projectdata[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();

                    // BAST
                    $projectdata[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                    $projectdata[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                    if (!empty($projectdata[$project['id']]['bastfile'])) {
                        $day =  $projectdata[$project['id']]['bastfile']['tanggal_bast'];
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

                    // Custom RAB
                    $projectdata[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->notLike('name', 'biaya pengiriman')->find();

                    // Shipping Cost
                    $projectdata[$project['id']]['shippingcost']    = $CustomRabModel->where('projectid', $project['id'])->like('name', 'biaya pengiriman')->first();

                    // All Custom RAB 
                    $allCustomRab = $CustomRabModel->where('projectid', $project['id'])->find();

                    // New Cust Rab Price
                    $allnewrabcust = [];
                    foreach($allCustomRab as $rabcust){
                        if($rabcust['denomination'] === "2"){
                            $hargacustrab = $rabcust['qty'] * ($rabcust['price'] * $rabcust['volume']);
                        }elseif($rabcust['denomination'] != "2" && !empty($rabcust['qty'])){
                            $hargacustrab = $rabcust['qty'] * $rabcust['price'];
                        }else{
                            $hargacustrab = $rabcust['price'];
                        }
                        $allnewrabcust[] = $hargacustrab;
                    }
                    $projectdatareport[$project['id']]['allcustomrab']    = array_sum($allnewrabcust);

                    // Pembayaran Value
                    $pembayaran = $PembayaranModel->where('projectid',$project['id'])->find();
                    $projectdata[$project['id']]['pembayaran'] = array_sum(array_column($pembayaran, 'qty'));

                    // Rab Sum Value
                    $projectdata[$project['id']]['rabvalue'] = 0;
                    if (!empty($price)) {
                        $projectdata[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                    }

                    // New Value ALL RAB & CUSTOM RAB + PPN
                    $projectdata[$project['id']]['rabvalueppn'] = 0;
                    if(!empty($price) || !empty($allnewrabcust)){
                        $allrab     =   array_sum($allnewrabcust) +  array_sum(array_column($price, 'sumprice')) ;

                        // Value I & II
                        $valuerab = $allrab - ($allrab * (70/100));
                        $ppnrabval = ((int)$valuerab * ((int)$gconf['ppn']/100));
                        $valrab1  = $valuerab + $ppnrabval;
                        $valrab12 = (int)$valrab1 * 2;

                        // Value III
                        $valuerab3 = $allrab - ($allrab * (65/100));
                        $ppnrabval3 = (((int)$gconf['ppn']/100) * (int)$valuerab3) + $valuerab3;

                        // Value IV
                        $valuerab4 = $allrab - ($allrab * (5/100));
                        $valrabres4 = $allrab - $valuerab4;
                        $ppnrabval4 = (((int)$gconf['ppn']/100) * (int)$valrabres4) + $valrabres4;

                        // PPH Value Configuration
                        $pphinvoice1 = $InvoiceModel->where('projectid',$project['id'])->where('status', 1)->first();
                        $pphinvoice2 = $InvoiceModel->where('projectid',$project['id'])->where('status', 2)->first();
                        $pphinvoice3 = $InvoiceModel->where('projectid',$project['id'])->where('status', 3)->first();
                        $pphinvoice4 = $InvoiceModel->where('projectid',$project['id'])->where('status', 4)->first();

                        $pph1 = (int)$valuerab * ((int)$pphinvoice1['pph23'] / 100); 
                        $pph2 = (int)$valuerab * ((int)$pphinvoice2['pph23'] / 100); 
                        $pph3 = (int)$valuerab3 * ((int)$pphinvoice3['pph23'] / 100); 
                        $pph4 = (int)$valuerab4 * ((int)$pphinvoice4['pph23'] / 100); 

                        $projectdata[$project['id']]['rabvalueppn'] = (int)$ppnrabval4 + (int)$ppnrabval3 + (int)$valrab12 + (int)$pph1 +  (int)$pph2 +  (int)$pph3 +  (int)$pph4;
                    }
                    // End New Value ALL RAB & CUSTOM RAB + PPN
                }

                $data['title']          = lang('Global.titleDashboard');
                $data['description']    = lang('Global.dashboardDescription');
                $data['rabs']           = $RabModel->findAll();
                $data['pakets']         = $PaketModel->findAll();
                $data['clients']        = array_slice($clients, $offset, $perpage);
                $data['pagerpro']       = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
                $data['pagerreport']    = $pager->makeLinks($pagereport, $perpagereport, $totalpro, 'uikit_full2');
                $data['input']          = $this->request->getGet('projectid');
                $data['projectdata']    = $projectdata;
                $data['projects']       = $queryproject;
                $data['input']          = $input;
                $data['total']          = count($queryproject);
                $data['startdate']      = strtotime($startdate);
                $data['enddate']        = strtotime($enddate);

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
                $BuktiModel         = new BuktiModel();
                $InvoiceModel       = new InvoiceModel();

                if (isset($input['search']) && !empty($input['search'])) {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at', null)->like('name', $input['search'])->paginate($perpage, 'projects');
                } else {
                    $projects = $ProjectModel->where('clientid', $this->data['parentid'])->where('deleted_at', null)->paginate($perpage, 'projects');
                }
                $this->builder->where('project.deleted_at ='.null);

                // Variable for pagination
                if (isset($input['perpage'])) {
                    $perpage = $input['perpage'];
                } else {
                    $perpage = 10;
                }

                $page = (@$_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $perpage;

                // Daterange Filter
                $inputdate = $this->request->getVar('daterange');
                if (!empty($inputdate)) {
                    $daterange = explode(' - ', $inputdate);
                    $startdate = $daterange[0];
                    $enddate = $daterange[1];
                } else {
                    $startdate = date('Y-m-1');
                    $enddate = date('Y-m-t');
                }

                if (isset($input['search']) && !empty($input['search'])) {
                    $totalclient = $ProjectModel
                        ->where('clientid', $this->data['parentid'])
                        ->join('company', 'company.id = project.clientid')
                        ->where('company.status !=', '0')
                        ->like('company.rsname', $input['search'])
                        ->orLike('company.rsname', $input['search'])
                        ->countAllResults();
                } else {
                    $totalclient = $ProjectModel
                        ->where('clientid', $this->data['parentid'])
                        ->join('company', 'company.id = project.clientid')
                        ->where('company.status !=', '0')
                        ->countAllResults();
                }

                $projectdata = [];
                $projectdesign = [];
                $client = "";

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
                                if($mdlr['denomination'] === "2"){
                                    $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] * (int)$mdlr['volume']);
                                }else{
                                    $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                                }
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
                                    'price'         => $hargamdl,
                                    'oriprice'      => (int)$mdlr['price'],
                                ];
                            }
                        }
    
                        // Custom RAB MODEL
                        $projectdata[$project['id']]['custrab']         = $CustomRabModel->where('projectid', $project['id'])->find();
                        $projectdata[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->find();
    
                        // bast
                        $projectdata[$project['id']]['sertrim']         = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                        $projectdata[$project['id']]['bast']            = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();
    
                        // Production
                        $productions                                    = $ProductionModel->where('projectid', $project['id'])->find();
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

                                    // Data Prodcution
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
                                        'pengiriman'        => $production['pengiriman'],
                                        'setting'           => $production['setting'],
                                        'percentages'       => array_sum($percentages) / 8 * 100,
                                    ];
                                }
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

                            // $progresdata = [];
                            foreach ($price as $progresval) {
                                $datamdlid[] = $progresval['id'];
                            }
                        }

                        // CUSTOM RAB PRODUCTION
                        if($this->data['authorize']->hasPermission('client.read', $this->data['uid'])){
                            $productionCustRabs = $ProductionModel->where('custrabid!=', NULL)->where('mdlid', 0)->where('projectid', $project['id'])->orderBy('mdlid', 'DESC')->find();
                        }

                        $projectdata[$project['id']]['productioncustrab']   = [];
                        if (!empty($productionCustRabs)) {
                            foreach ($productionCustRabs as $productionCustRab) {

                                // MDL Production
                                $cusrabproducts        = $CustomRabModel->where('id', $productionCustRab['custrabid'])->find();
                                $percentages    = [];
                                foreach ($cusrabproducts as $cusrabproduct) {
                                    
                                    // Percentage Production
                                    if ($productionCustRab['gambar_kerja'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['mesin_awal'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['tukang'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['mesin_lanjutan'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['finishing'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['packing'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['pengiriman'] == 1) {
                                        $percentages[]    = 1;
                                    }
                                    if ($productionCustRab['setting'] == 1) {
                                        $percentages[]    = 1;
                                    }

                                    $projectdata[$project['id']]['productioncustrab'][$productionCustRab['id']]  = [
                                        'id'                => $productionCustRab['id'],
                                        'userid'            => $productionCustRab['userid'],
                                        'custrabid'         => $productionCustRab['custrabid'],
                                        'name'              => $cusrabproduct['name'],
                                        'gambar_kerja'      => $productionCustRab['gambar_kerja'],
                                        'mesin_awal'        => $productionCustRab['mesin_awal'],
                                        'tukang'            => $productionCustRab['tukang'],
                                        'mesin_lanjutan'    => $productionCustRab['mesin_lanjutan'],
                                        'finishing'         => $productionCustRab['finishing'],
                                        'packing'           => $productionCustRab['packing'],
                                        'pengiriman'        => $productionCustRab['pengiriman'],
                                        'setting'           => $productionCustRab['setting'],
                                        'percentages'       => array_sum($percentages) / 8 * 100,
                                    ];
                                }

                                // $projectdata[$project['id']]['productioncustrab'][$productionCustRab['id']]['percentages']  = array_sum($percentages) / 8 * 100;
                            }
                        } else {
                            $cusrabproducts    = [];
                            $projectdata[$project['id']]['productioncustrab']   = [];
                        }
                        // END PRODUCTION CUSTOM RAB

                        // CUSTOM RAB PRODUCTION VALUE
                        $custrabid = [];
                        if (!empty($projectdata[$project['id']]['customrab'])) {
                            $custrabprice = [];
                            foreach ($projectdata[$project['id']]['customrab'] as $custrabdata) {
                                if($custrabdata['denomination'] === "2"){
                                    $hargacustrab = (int)$custrabdata['qty'] * ((int)$custrabdata['price'] *$custrabdata['volume']);
                                }else{
                                    $hargacustrab = (int)$custrabdata['qty'] * (int)$custrabdata['price'];
                                }
                                $custrabprice[] = [
                                    'id'            => $custrabdata['id'],
                                    'proid'         => $custrabdata['projectid'],
                                    'price'         => $custrabdata['price'],
                                    'totalprice'    => $hargacustrab,
                                    'qty'           => $custrabdata['qty']
                                ];
                            }
                                    
                            foreach ($custrabprice as $custrabprogresval) {
                                $custrabid[] = $custrabprogresval['id'];
                            }
                        }

                        // ARRAY DATA PRICE & PRECENTAGES
                        $pricetotalgroup   = [];

                        // MDL DATA
                        if(!empty($datamdlid)){
                            $dataMdlItems = $MdlModel->whereIn('id',$datamdlid)->find();
                            $mdlItemQty   = $RabModel->where('projectid',$project['id'])->whereIn('mdlid',$datamdlid)->find();
        
                            foreach ($dataMdlItems as $dataMdlItem) {
                                foreach ( $mdlItemQty as $qtyItem ) {
                                    if($qtyItem['mdlid'] === $dataMdlItem['id']){
        
                                        if($dataMdlItem['denomination'] === "2"){
                                            $mdlprice = ($dataMdlItem['price'] * $dataMdlItem['volume']) * $qtyItem['qty'];
                                        }else{
                                            $mdlprice = $dataMdlItem['price'] * $qtyItem['qty'];
                                        }
                                        $dataCalculatePresentage[$project['id']]['mdl'][$dataMdlItem['id']] = [
                                            'id'                => $project['id'],
                                            'mdlid'             => $dataMdlItem['id'],
                                            'mdlprice'          => $dataMdlItem['price'],
                                            'mdltotalprice'     => $mdlprice,
                                            'mdlqty'            => $qtyItem['qty'],
                                        ];
                                        $pricetotalgroup[] = $mdlprice;
        
                                    }
                                }
                            }
                        }
                        
                        // CUSTOM RAB DATA
                        $dataCustRabItems = [];
                        if (!empty($custrabid)) {
                            $dataCustRabItems = $CustomRabModel->where('projectid', $project['id'])->whereIn('id',$custrabid)->find();
                            foreach($dataCustRabItems as $dataCustRabItem){
                                if($dataCustRabItem['denomination'] === "2"){
                                    $hargacustrab = ($dataCustRabItem['price'] * $dataCustRabItem['volume']) * $dataCustRabItem['qty'];
                                }elseif($dataCustRabItem['denomination'] != "2" && !empty($dataCustRabItem['qty'])){
                                    $hargacustrab = $dataCustRabItem['price'] * $dataCustRabItem['qty'];
                                }else{
                                    $hargacustrab = $dataCustRabItem['price'];
                                }

                                $dataCalculatePresentage[$project['id']]['custrab'][$dataCustRabItem['id']] = [
                                    'id'                        =>  $project['id'],
                                    'custrabid'                 =>  $dataCustRabItem['id'],
                                    'custrabprice'              =>  $dataCustRabItem['price'],
                                    'custrabtotalprice'         =>  $hargacustrab,
                                    'custrabqty'                =>  $dataCustRabItem['qty'],
                                ];
                                $pricetotalgroup[] = $hargacustrab;
                                    
                            }
                        }

                        // ALL TOTAL PRICE
                        if(!empty($pricetotalgroup)){
                            $sumTotalpricevalue = array_sum($pricetotalgroup);
                        }

                        // MDL VALUE PER ITEMS
                        if(!empty($dataCalculatePresentage[$project['id']])){
                            if(!empty($datamdlid)){
                                foreach($dataMdlItems as $mdlitem){
                                    if(!empty($sumTotalpricevalue)) {
                                        if(!empty($dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']])){
                                            $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val'] = (((int)$dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['mdlprice'] / (int)$sumTotalpricevalue) * 65 ) / 8;
                                        }
                                    }
                                }
                            }
                        }
                            
                        // CUSTRAB VALUE PER ITEMS
                        if(!empty($custrabid)) {
                            foreach($dataCustRabItems as $custrabprice){
                                if(!empty($sumTotalpricevalue)) {
                                    $dataCalculatePresentage[$project['id']]['custrab'][$custrabprice['id']]['val'] = (((int)$dataCalculatePresentage[$project['id']]['custrab'][$custrabprice['id']]['custrabprice'] / $sumTotalpricevalue) * 65 ) / 8;
                                }
                            }
                        }

                        // PRODUCTION DATA
                        $newProduction = $ProductionModel->where('projectid',$project['id'])->find();

                        // CALCULATING VALUE DATA PERCENTAGE
                        $newAllProgress = [];
                        foreach($newProduction as $production) {

                            // CALCULATING DATA PERCENTAGE BY MDL
                            if(!empty($datamdlid)) {
                                foreach($dataMdlItems as $mdlitem) {
                                    if($mdlitem['id'] === $production['mdlid']){
                                        if ($production['gambar_kerja'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['mesin_awal'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['tukang'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['mesin_lanjutan'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['finishing'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['packing'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['pengiriman'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                        if ($production['setting'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                        }
                                    }
                                }
                            }

                            // CALCULATING DATA PERCENTAGE BY CUSTOM RAB
                            if(!empty($custrabid)) {
                                foreach($dataCustRabItems as $cusrabItem) {
                                    if($cusrabItem['id'] === $production['custrabid']){
                                        if ($production['gambar_kerja'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['mesin_awal'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['tukang'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['mesin_lanjutan'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['finishing'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['packing'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['pengiriman'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                        if ($production['setting'] === "1") {
                                            array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                        }
                                    }
                                }
                            }
                            
                        }

                        $projectdata[$project['id']]['progress']   = array_sum($newAllProgress);
                        // END NEW PRODUCTION CALCULATE PRESENTAGE

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
                        }else{
                            $projectdata[$project['id']]['dateline'] = '';
                            $projectdata[$project['id']]['now'] = '';
                        }

                        // PRODUCTION EMPLOYE EXIST
                        $employeproductions  = $ProductionModel->where('projectid', $project['id'])->find();
                        $employePro = [];
                        if(!empty($employeproductions)){
                            foreach($employeproductions as $employeProd){
                                if($employeProd['userid'] != null){
                                    $employePro[] = $employeProd['userid'];
                                }
                            }
                        }
                        $projectdata[$project['id']]['employeProduction'] = 'notexist';
                        if(!empty($employePro)){
                            $projectdata[$project['id']]['employeProduction'] = 'exist';
                        }
                        // END PRODUCTION EMPLOYE EXIST
    
                        // INVOICE
                        $projectdata[$project['id']]['invoice1'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '1')->first();
                        $projectdata[$project['id']]['invoice2'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '2')->first();
                        $projectdata[$project['id']]['invoice3'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '3')->first();
                        $projectdata[$project['id']]['invoice4'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '4')->first();
    
                        // Bukti Pembayaran
                        $projectdata[$project['id']]['buktipembayaran']     = $BuktiModel->where('projectid', $project['id'])->where('status', "0")->find();
    
                        // Bukti Pengiriman
                        $projectdata[$project['id']]['buktipengiriman']     = $BuktiModel->where('projectid', $project['id'])->where('status', "1")->find();
                    }
                }

                $company = $CompanyModel->whereIn('parentid', $this->data['parentid'])->where('deleted_at', null)->find();

                $clients    = array();
                $klienid    = array();
                foreach ($company as $comp) {
                    if (!empty($clients)) {
                        $clients[] = [
                            'id'  => $comp['id'],
                            'rsname' => $comp['rsname'],
                        ];
                        $klienid[] = $comp['id'];
                    }
                }
                
                // Report Script
                $this->db       = \Config\Database::connect();
                $validation     = \Config\Services::validation();
                $proyek         = $this->builder  = $this->db->table('project');

                // Variable for pagination
                if (isset($input['perpagereport'])) {
                    $perpagereport = $input['perpagereport'];
                } else {
                    $perpagereport = 10;
                }
                
                $pagereport = (@$_GET['pagereport']) ? $_GET['pagereport'] : 1;
                $offsetreport = ($pagereport - 1) * $perpagereport;

                if ($startdate === $enddate) {
                    $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
                } else {
                    $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate. ' 23:59:59');
                }
                $this->builder->where('project.clientid', $this->data['parentid']);
                $this->builder->where('project.deleted_at ='.null);
                $this->builder->join('users', 'users.id = project.marketing');
                $this->builder->join('company', 'company.id = project.clientid');
                if (isset($input['searchproyek']) && !empty($input['searchproyek'])) {
                    $this->builder->like('project.name', $input['searchproyek']);
                    $this->builder->orLike('company.rsname', $input['searchproyek']);
                    $this->builder->where('project.clientid', $this->data['parentid']);
                }
                $this->builder->orderBy('id',"DESC");
                $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
                // $queryproject = $this->builder->get($pagereport, $offsetreport)->getResultArray();
                $queryproject = $this->builder->get($perpagereport, $offsetreport)->getResultArray();
                
                if (isset($input['searchproyek']) && !empty($input['searchproyek'])) {
                    $totalpro = $proyek
                        ->like('project.name', $input['searchproyek'])
                        ->where('project.clientid', $this->data['parentid'])
                        ->where('project.deleted_at ='.null)
                        ->where('created_at >=', $startdate . ' 00:00:00')
                        ->where('created_at <=', $enddate . ' 23:59:59')
                        ->countAllResults();
                } else {
                    $totalpro = $proyek
                        ->where('clientid', $this->data['parentid'])
                        ->where('project.deleted_at ='.null)
                        ->where('created_at >=', $startdate . ' 00:00:00')
                        ->where('created_at <=', $enddate . '  23:59:59')
                        ->countAllResults();
                }

                // Query Data Project
                $projectdatareport = [];
                foreach ($queryproject as $project) {

                    // Klien
                    $projectdatareport[$project['id']]['klien'] = $CompanyModel->where('id', $project['clientid'])->first();

                    // Marketing
                    $projectdatareport[$project['id']]['marketing'] = $UserModel->where('id', $project['marketing'])->first();

                    // RAB
                    $rabs       = $RabModel->where('projectid', $project['id'])->find();
                    if (!empty($rabs)) {
                        foreach ($rabs as $rab) {
                            $paketid[]  = $rab['paketid'];

                            // MDL RAB
                            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                            foreach ($rabmdl as $mdlr) {
                                if($mdlr['denomination'] === "2"){
                                    $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] * (int)$mdlr['volume']);
                                }else{
                                    $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                                }
                                $projectdatareport[$project['id']]['rab'][$rab['id']]  = [
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
                                    'price'         => $hargamdl,
                                    'oriprice'      => (int)$mdlr['price'],
                                ];
                            }
                        }
                    }

                    // Get RAB data
                    $price = [];
                    if (!empty($projectdatareport[$project['id']]['rab'])) {
                        foreach ($projectdatareport[$project['id']]['rab'] as $mdldata) {
                            $price[] = [
                                'id'        => $mdldata['id'],
                                'proid'     => $mdldata['proid'],
                                'price'     => $mdldata['oriprice'],
                                'sumprice'  => $mdldata['price'],
                                'qty'       => $mdldata['qty']
                            ];
                        }
                    }

                    // Setrim
                    $projectdatareport[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();

                    // BAST
                    $projectdatareport[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                    $projectdatareport[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                    if (!empty($projectdatareport[$project['id']]['bastfile'])) {
                        $day =  $projectdatareport[$project['id']]['bastfile']['tanggal_bast'];
                        $date = date_create($day);
                        $key = date_format($date, "Y-m-d");
                        $hari = date_create($key);
                        date_add($hari, date_interval_create_from_date_string('3 month'));
                        $dateline = date_format($hari, 'Y-m-d');

                        $now = strtotime("now");
                        $nowtime = date("Y-m-d", $now);
                        $projectdatareport[$project['id']]['dateline'] = $dateline;
                        $projectdatareport[$project['id']]['now'] = $nowtime;
                    } else {
                        $projectdatareport[$project['id']]['dateline'] = "";
                        $projectdatareport[$project['id']]['now'] = "";
                    }

                    // Custom RAB
                    $projectdatareport[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->notLike('name', 'biaya pengiriman')->find();

                    // Shipping Cost
                    $projectdatareport[$project['id']]['shippingcost']    = $CustomRabModel->where('projectid', $project['id'])->like('name', 'biaya pengiriman')->first();

                    // All Custom RAB 
                    $allCustomRab = $CustomRabModel->where('projectid', $project['id'])->find();

                    // New Cust Rab Price
                    $allnewrabcust = [];
                    foreach($allCustomRab as $rabcust){
                        if($rabcust['denomination'] === "2"){
                            $hargacustrab = ($rabcust['price'] * $rabcust['volume']) * $rabcust['qty'];
                        }elseif($rabcust['denomination'] != "2" && !empty($rabcust['qty'])){
                            $hargacustrab = $rabcust['price'] * $rabcust['qty'];
                        }else{
                            $hargacustrab = $rabcust['price'];
                        }
                        $allnewrabcust[] = $hargacustrab;
                    }
                    $projectdatareport[$project['id']]['allcustomrab']    = array_sum($allnewrabcust);

                    // Pembayaran Value
                    $pembayaran = $PembayaranModel->where('projectid',$project['id'])->find();
                    $projectdatareport[$project['id']]['pembayaran'] = array_sum(array_column($pembayaran, 'qty'));

                    // Rab Sum Value
                    $projectdatareport[$project['id']]['rabvalue'] = 0;
                    if (!empty($price)) {
                        $projectdatareport[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                    }

                    // New Value ALL RAB & CUSTOM RAB + PPN
                    $projectdatareport[$project['id']]['rabvalueppn'] = 0;
                    if(!empty($price) || !empty($allnewrabcust)){
                        $allrab     =   array_sum($allnewrabcust) +  array_sum(array_column($price, 'sumprice')) ;

                        // Value I & II
                        $valuerab = $allrab - ($allrab * (70/100));
                        $ppnrabval = ((int)$valuerab * ((int)$gconf['ppn']/100));
                        $valrab1  = $valuerab + $ppnrabval;
                        $valrab12 = (int)$valrab1 * 2;

                        // Value III
                        $valuerab3 = $allrab - ($allrab * (65/100));
                        $ppnrabval3 = (((int)$gconf['ppn']/100) * (int)$valuerab3) + $valuerab3;

                        // Value IV
                        $valuerab4 = $allrab - ($allrab * (5/100));
                        $valrabres4 = $allrab - $valuerab4;
                        $ppnrabval4 = (((int)$gconf['ppn']/100) * (int)$valrabres4) + $valrabres4;

                        // PPH Value Configuration
                        $pphinvoice1 = $InvoiceModel->where('projectid',$project['id'])->where('status', 1)->first();
                        $pphinvoice2 = $InvoiceModel->where('projectid',$project['id'])->where('status', 2)->first();
                        $pphinvoice3 = $InvoiceModel->where('projectid',$project['id'])->where('status', 3)->first();
                        $pphinvoice4 = $InvoiceModel->where('projectid',$project['id'])->where('status', 4)->first();

                        $pph1 = (int)$valuerab * ((int)$pphinvoice1['pph23'] / 100); 
                        $pph2 = (int)$valuerab * ((int)$pphinvoice2['pph23'] / 100); 
                        $pph3 = (int)$valuerab3 * ((int)$pphinvoice3['pph23'] / 100); 
                        $pph4 = (int)$valuerab4 * ((int)$pphinvoice4['pph23'] / 100); 

                        $projectdatareport[$project['id']]['rabvalueppn'] = (int)$ppnrabval4 + (int)$ppnrabval3 + (int)$valrab12 + (int)$pph1 +  (int)$pph2 +  (int)$pph3 +  (int)$pph4;
                    }
                    // End New Value ALL RAB & CUSTOM RAB + PPN
                }


                // Parsing Data to View
                $data['title']              = lang('Global.titleDashboard');
                $data['description']        = lang('Global.dashboardDescription');
                $data['client']             = $client;
                $data['projects']           = $projects;
                $data['design']             = $DesignModel->findAll();
                $data['rabs']               = $RabModel->findAll();
                $data['pakets']             = $PaketModel->findAll();
                $data['mdls']               = $MdlModel->findAll();
                $data['pager']              = $pager->links('projects', 'uikit_full');
                $data['pagerpro']           = $pager->makeLinks($pagereport, $perpagereport, $totalpro, 'uikit_full2');
                $data['projectdata']        = $projectdata;
                $data['projectdesign']      = $projectdesign;
                $data['input']              = $this->request->getGet('projectid');
                $data['projectdatareport']  = $projectdatareport;
                $data['projectsreport']     = $queryproject;
                $data['total']              = count($queryproject);
                $data['startdate']          = strtotime($startdate);
                $data['enddate']            = strtotime($enddate);

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
            $UserModel          = new UserModel();
            $ProjectModel       = new ProjectModel;
            $CompanyModel       = new CompanyModel();
            $RabModel           = new RabModel();
            $PaketModel         = new PaketModel();
            $MdlModel           = new MdlModel();
            $DesignModel        = new DesignModel();
            $BastModel          = new BastModel();
            $ProductionModel    = new ProductionModel();
            $CustomRabModel     = new CustomRabModel();
            $InvoiceModel       = new InvoiceModel();
            $BuktiModel         = new BuktiModel();
            $PembayaranModel    = new PembayaranModel();
            $GconfigModel       = new GconfigModel();

            // Populating data
            $input  = $this->request->getGet();
            $gconf  = $GconfigModel->first();

            // Variable for pagination
            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            // Daterange Filter
            $inputdate = $this->request->getVar('daterange');
            if (!empty($inputdate)) {
                $daterange = explode(' - ', $inputdate);
                $startdate = $daterange[0];
                $enddate = $daterange[1];
            } else {
                $startdate = date('Y-m-1');
                $enddate = date('Y-m-t');
            }

            if (isset($input['perpagereport'])) {
                $perpagereport = (int)$input['perpagereport'];
            } else {
                $perpagereport = 10;
            }
            
            $pagereport = (@$_GET['pagereport']) ? $_GET['pagereport'] : 1;
            $offsetreport = ($pagereport - 1) * $perpagereport;

            // Report Script
            $this->db       = \Config\Database::connect();
            $validation     = \Config\Services::validation();
            $this->builder  = $this->db->table('project');
            $this->config   = config('Auth');
            $this->auth     = service('authentication');

            // Initialize
            $input = $this->request->getGet();
            $gconf = $GconfigModel->first();
            
            if ($startdate === $enddate) {
                $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
            } else {
                $this->builder->where('project.created_at >=', $startdate. ' 00:00:00')->where('project.created_at <=', $enddate. ' 23:59:59');
            }
            $this->builder->where('project.deleted_at ='.null);
            $this->builder->where('project.clientid', $id);
            $this->builder->join('users', 'users.id = project.marketing');
            $this->builder->join('company', 'company.id = project.clientid');
            if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                $this->builder->like('project.name', $input['searchreport']);
                $this->builder->where('project.clientid', $id);
            }
            $this->builder->orderBy('project.id',"DESC");
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
            // $queryproject = $this->builder->get($offsetreport, $perpagereport)->getResultArray();
            $queryproject = $this->builder->get($perpagereport, $offsetreport)->getResultArray();

            $totalpro = 0;
            if (isset($input['searchreport']) && !empty($input['searchreport'])) {
                $totalpro = $ProjectModel
                    ->like('project.name', $input['searchreport'])
                    ->where('project.deleted_at ='.null)
                    ->where('project.clientid', $id)
                    ->where('created_at >=', $startdate . '  00:00:00')
                    ->where('created_at <=', $enddate . '  23:59:59')
                    ->countAllResults();
            } else {
                $totalpro = $ProjectModel
                    ->where('project.deleted_at ='.null)
                    ->where('project.clientid', $id)
                    ->where('created_at >=', $startdate . '  00:00:00')
                    ->where('created_at <=', $enddate . '  23:59:59')
                    ->countAllResults();
            }

            $client = $CompanyModel->find($id);

            if (isset($input['search']) && !empty($input['search'])) {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at', null)->orderBy('id','DESC')->like('name', $input['search'])->paginate($perpage, 'projects');
            } else {
                $projects = $ProjectModel->where('clientid', $id)->where('deleted_at', null)->orderBy('id','DESC')->paginate($perpage, 'projects');
            }

            $projectdata        = [];
            $projectdesign      = [];
            $dataCalculatePresentage = [];

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
                            if($mdlr['denomination'] === "2"){
                                $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] * (int)$mdlr['volume']);
                            }else{
                                $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                            }
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
                                'price'         => $hargamdl,
                                'oriprice'      => (int)$mdlr['price'],
                            ];
                        }
                    }

                    // Custom RAB MODEL
                    $projectdata[$project['id']]['custrab']         = $CustomRabModel->where('projectid', $project['id'])->find();
                    $projectdata[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->find();

                    // bast
                    $projectdata[$project['id']]['sertrim']         = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                    $projectdata[$project['id']]['bast']            = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                    // Production
                    $productions                                    = $ProductionModel->where('projectid', $project['id'])->find();
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

                                // Data Prodcution
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
                                    'pengiriman'        => $production['pengiriman'],
                                    'setting'           => $production['setting'],
                                    'percentages'       => array_sum($percentages) / 8 * 100,
                                ];
                            }
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

                        // $progresdata = [];
                        foreach ($price as $progresval) {
                            $datamdlid[] = $progresval['id'];
                        }
                    }

                    // CUSTOM RAB PRODUCTION
                    if($this->data['authorize']->hasPermission('client.read', $this->data['uid'])){
                        $productionCustRabs = $ProductionModel->where('custrabid!=', NULL)->where('mdlid', 0)->where('projectid', $project['id'])->orderBy('mdlid', 'DESC')->find();
                    }

                    $projectdata[$project['id']]['productioncustrab']   = [];
                    if (!empty($productionCustRabs)) {
                        foreach ($productionCustRabs as $productionCustRab) {

                            // MDL Production
                            $cusrabproducts        = $CustomRabModel->where('id', $productionCustRab['custrabid'])->find();
                            $percentages    = [];
                            foreach ($cusrabproducts as $cusrabproduct) {
                                
                                // Percentage Production
                                if ($productionCustRab['gambar_kerja'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['mesin_awal'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['tukang'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['mesin_lanjutan'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['finishing'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['packing'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['pengiriman'] == 1) {
                                    $percentages[]    = 1;
                                }
                                if ($productionCustRab['setting'] == 1) {
                                    $percentages[]    = 1;
                                }

                                $projectdata[$project['id']]['productioncustrab'][$productionCustRab['id']]  = [
                                    'id'                => $productionCustRab['id'],
                                    'userid'            => $productionCustRab['userid'],
                                    'custrabid'         => $productionCustRab['custrabid'],
                                    'name'              => $cusrabproduct['name'],
                                    'gambar_kerja'      => $productionCustRab['gambar_kerja'],
                                    'mesin_awal'        => $productionCustRab['mesin_awal'],
                                    'tukang'            => $productionCustRab['tukang'],
                                    'mesin_lanjutan'    => $productionCustRab['mesin_lanjutan'],
                                    'finishing'         => $productionCustRab['finishing'],
                                    'packing'           => $productionCustRab['packing'],
                                    'pengiriman'        => $productionCustRab['pengiriman'],
                                    'setting'           => $productionCustRab['setting'],
                                    'percentages'       => array_sum($percentages) / 8 * 100,
                                ];
                            }

                        }
                    } else {
                        $cusrabproducts    = [];
                        $projectdata[$project['id']]['productioncustrab']   = [];
                    }
                    // END PRODUCTION CUSTOM RAB

                    // CUSTOM RAB PRODUCTION VALUE
                    $custrabid = [];
                    if (!empty($projectdata[$project['id']]['customrab'])) {
                        $custrabprice = [];
                        foreach ($projectdata[$project['id']]['customrab'] as $custrabdata) {
                            if($custrabdata['denomination'] === "2"){
                                $hargacustrab = (int)$custrabdata['qty'] * ((int)$custrabdata['price'] * (int)$custrabdata['volume']);
                            }elseif(!empty($custrabdata['qty']) && $custrabdata['qty']){
                                $hargacustrab = (int)$custrabdata['qty'] * (int)$custrabdata['price'];
                            }else{
                                $hargacustrab = (int)$custrabdata['price'];
                            }
                            $custrabprice[] = [
                                'id'            => $custrabdata['id'],
                                'proid'         => $custrabdata['projectid'],
                                'price'         => $custrabdata['price'],
                                'totalprice'    => $hargacustrab,
                                'qty'           => $custrabdata['qty']
                            ];
                        }
                                
                        foreach ($custrabprice as $custrabprogresval) {
                            $custrabid[] = $custrabprogresval['id'];
                        }
                    }

                    // ARRAY DATA PRICE & PRECENTAGES
                    $pricetotalgroup   = [];

                    // MDL DATA
                    if(!empty($datamdlid)){
                        $dataMdlItems = $MdlModel->whereIn('id',$datamdlid)->find();
                        $mdlItemQty   = $RabModel->where('projectid',$project['id'])->whereIn('mdlid',$datamdlid)->find();
    
                        foreach ($dataMdlItems as $dataMdlItem) {
                            foreach ( $mdlItemQty as $qtyItem ) {
                                if($qtyItem['mdlid'] === $dataMdlItem['id']){
                                    if($dataMdlItem['denomination'] === "2"){
                                        $hargamdl = (int)$qtyItem['qty'] * ((int)$dataMdlItem['price'] * (int)$dataMdlItem['volume']);
                                    }else{
                                        $hargamdl = (int)$qtyItem['qty'] * (int)$dataMdlItem['price'];
                                    }
                                    $dataCalculatePresentage[$project['id']]['mdl'][$dataMdlItem['id']] = [
                                        'id'                => $project['id'],
                                        'mdlid'             => $dataMdlItem['id'],
                                        'mdlprice'          => $dataMdlItem['price'],
                                        'mdltotalprice'     => $hargamdl,
                                        'mdlqty'            => $qtyItem['qty'],
                                    ];
                                    $pricetotalgroup[] = $hargamdl;
    
                                }
                            }
                        }
                    }
                    
                    // CUSTOM RAB DATA
                    $dataCustRabItems = [];
                    if (!empty($custrabid)) {
                        $dataCustRabItems = $CustomRabModel->where('projectid', $project['id'])->whereIn('id',$custrabid)->find();
                        foreach($dataCustRabItems as $dataCustRabItem){

                            if($dataCustRabItem['denomination'] === "2"){
                                $hargacustrab = (int)$dataCustRabItem['qty'] * ((int)$dataCustRabItem['price'] * (int)$dataCustRabItem['volume']);
                            }elseif(!empty($dataCustRabItem['qty']) && $dataCustRabItem['denomination'] != "2"){
                                $hargacustrab = (int)$dataCustRabItem['qty'] * (int)$dataCustRabItem['price'];
                            }else{
                                $hargacustrab = (int)$dataCustRabItem['price'];
                            }

                            $dataCalculatePresentage[$project['id']]['custrab'][$dataCustRabItem['id']] = [
                                'id'                        =>  $project['id'],
                                'custrabid'                 =>  $dataCustRabItem['id'],
                                'custrabprice'              =>  $dataCustRabItem['price'],
                                'custrabtotalprice'         =>  $hargacustrab,
                                'custrabqty'                =>  $dataCustRabItem['qty'],
                            ];
                            $pricetotalgroup[] = $hargacustrab;
                                
                        }
                    }

                    // ALL TOTAL PRICE
                    if(!empty($pricetotalgroup)){
                        $sumTotalpricevalue = array_sum($pricetotalgroup);
                    }

                    // MDL VALUE PER ITEMS
                    if(!empty($dataCalculatePresentage[$project['id']])){
                        if(!empty($datamdlid)){
                            foreach($dataMdlItems as $mdlitem){
                                if(!empty($sumTotalpricevalue)) {
                                    if(!empty($dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']])){
                                        $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val'] = (((int)$dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['mdlprice'] / (int)$sumTotalpricevalue) * 65 ) / 8;
                                    }
                                }
                            }
                        }
                    }
                        
                    // CUSTRAB VALUE PER ITEMS
                    if(!empty($custrabid)) {
                        foreach($dataCustRabItems as $custrabprice){
                            if(!empty($sumTotalpricevalue)) {
                                $dataCalculatePresentage[$project['id']]['custrab'][$custrabprice['id']]['val'] = (((int)$dataCalculatePresentage[$project['id']]['custrab'][$custrabprice['id']]['custrabprice'] / $sumTotalpricevalue) * 65 ) / 8;
                            }
                        }
                    }

                    // PRODUCTION DATA
                    $newProduction = $ProductionModel->where('projectid',$project['id'])->find();

                    // CALCULATING VALUE DATA PERCENTAGE
                    $newAllProgress = [];
                    foreach($newProduction as $production) {

                        // CALCULATING DATA PERCENTAGE BY MDL
                        if(!empty($datamdlid)) {
                            foreach($dataMdlItems as $mdlitem) {
                                if($mdlitem['id'] === $production['mdlid']){
                                    if ($production['gambar_kerja'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['mesin_awal'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['tukang'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['mesin_lanjutan'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['finishing'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['packing'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['pengiriman'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                    if ($production['setting'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['mdl'][$mdlitem['id']]['val']);
                                    }
                                }
                            }
                        }

                        // CALCULATING DATA PERCENTAGE BY CUSTOM RAB
                        if(!empty($custrabid)) {
                            foreach($dataCustRabItems as $cusrabItem) {
                                if($cusrabItem['id'] === $production['custrabid']){
                                    if ($production['gambar_kerja'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['mesin_awal'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['tukang'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['mesin_lanjutan'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['finishing'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['packing'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['pengiriman'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                    if ($production['setting'] === "1") {
                                        array_push($newAllProgress, $dataCalculatePresentage[$project['id']]['custrab'][$cusrabItem['id']]['val']);
                                    }
                                }
                            }
                        }
                        
                    }

                    $projectdata[$project['id']]['progress']   = array_sum($newAllProgress);
                    // END NEW PRODUCTION CALCULATE PRESENTAGE

                    // PRODUCTION EMPLOYE EXIST
                    $employeproductions  = $ProductionModel->where('projectid', $project['id'])->find();
                    $employePro = [];
                    if(!empty($employeproductions)){
                        foreach($employeproductions as $employeProd){
                            if($employeProd['userid'] != null){
                                $employePro[] = $employeProd['userid'];
                            }
                        }
                    }
                    $projectdata[$project['id']]['employeProduction'] = 'notexist';
                    if(!empty($employePro)){
                        $projectdata[$project['id']]['employeProduction'] = 'exist';
                    }
                    // END PRODUCTION EMPLOYE EXIST

                    // BAST DATE
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
                    }else{
                        $projectdata[$project['id']]['dateline'] = '';
                        $projectdata[$project['id']]['now'] = '';
                    }
                    // END BAST DATE

                    // INVOICE
                    $projectdata[$project['id']]['invoice1'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '1')->first();
                    $projectdata[$project['id']]['invoice2'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '2')->first();
                    $projectdata[$project['id']]['invoice3'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '3')->first();
                    $projectdata[$project['id']]['invoice4'] = $InvoiceModel->where('projectid', $project['id'])->where('status', '4')->first();

                    // Bukti Pembayaran
                    $projectdata[$project['id']]['buktipembayaran']     = $BuktiModel->where('projectid', $project['id'])->where('status', "0")->find();

                    // Bukti Pengiriman
                    $projectdata[$project['id']]['buktipengiriman']     = $BuktiModel->where('projectid', $project['id'])->where('status', "1")->find();
                    
                }

                // Query Data Project
                $projectdatareport = [];
                foreach ($queryproject as $project) {

                    // Klien
                    $projectdatareport[$project['id']]['klien'] = $CompanyModel->where('id', $project['clientid'])->first();

                    // Marketing
                    $projectdatareport[$project['id']]['marketing'] = $UserModel->where('id', $project['marketing'])->first();

                    // RAB
                    $rabs       = $RabModel->where('projectid', $project['id'])->find();
                    if (!empty($rabs)) {
                        foreach ($rabs as $rab) {
                            $paketid[]  = $rab['paketid'];

                            // MDL RAB
                            $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                            foreach ($rabmdl as $mdlr) {
                                if($mdlr['denomination'] === "2"){
                                    $hargamdl = (int)$rab['qty'] * ((int)$mdlr['price'] * (int)$mdlr['volume']);
                                }else{
                                    $hargamdl = (int)$rab['qty'] * (int)$mdlr['price'];
                                }
                                $projectdatareport[$project['id']]['rab'][$rab['id']]  = [
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
                                    'price'         => $hargamdl,
                                    'oriprice'      => (int)$mdlr['price'],
                                ];
                            }
                        }
                    }

                    // Get RAB data
                    $price = [];
                    if (!empty($projectdatareport[$project['id']]['rab'])) {
                        foreach ($projectdatareport[$project['id']]['rab'] as $mdldata) {
                            $price[] = [
                                'id'        => $mdldata['id'],
                                'proid'     => $mdldata['proid'],
                                'price'     => $mdldata['oriprice'],
                                'sumprice'  => $mdldata['price'],
                                'qty'       => $mdldata['qty']
                            ];
                        }
                    }

                    // Setrim
                    $projectdatareport[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();

                    // BAST
                    $projectdatareport[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                    $projectdatareport[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                    if (!empty($projectdatareport[$project['id']]['bastfile'])) {
                        $day =  $projectdatareport[$project['id']]['bastfile']['tanggal_bast'];
                        $date = date_create($day);
                        $key = date_format($date, "Y-m-d");
                        $hari = date_create($key);
                        date_add($hari, date_interval_create_from_date_string('3 month'));
                        $dateline = date_format($hari, 'Y-m-d');

                        $now = strtotime("now");
                        $nowtime = date("Y-m-d", $now);
                        $projectdatareport[$project['id']]['dateline'] = $dateline;
                        $projectdatareport[$project['id']]['now'] = $nowtime;
                    } else {
                        $projectdatareport[$project['id']]['dateline'] = "";
                        $projectdatareport[$project['id']]['now'] = "";
                    }

                    // Custom RAB
                    $projectdatareport[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->notLike('name', 'biaya pengiriman')->find();

                    // Shipping Cost
                    $projectdatareport[$project['id']]['shippingcost']    = $CustomRabModel->where('projectid', $project['id'])->like('name', 'biaya pengiriman')->first();

                    // All Custom RAB 
                    $allCustomRab = $CustomRabModel->where('projectid', $project['id'])->find();

                    $allnewrabcust = [];
                    // New Cust Rab Price
                    foreach($allCustomRab as $rabcust){
                        if($rabcust['denomination'] === "2"){
                            $harga = ($rabcust['price'] * $rabcust['volume']) * $rabcust['qty'];
                        }elseif(!empty($rabcust['qty']) && $rabcust['denomination'] !="2"){
                            $harga = $rabcust['price'] * $rabcust['qty'];
                        }else{
                            $harga = $rabcust['price'];
                        }
                        $allnewrabcust[] = $harga;
                    }

                    $projectdatareport[$project['id']]['allcustomrab']    = array_sum($allCustomRab);

                    // Pembayaran Value
                    $pembayaran = $PembayaranModel->where('projectid',$project['id'])->find();
                    $projectdatareport[$project['id']]['pembayaran'] = array_sum(array_column($pembayaran, 'qty'));

                    // Rab Sum Value
                    $projectdatareport[$project['id']]['rabvalue'] = 0;
                    if (!empty($price)) {
                        $projectdatareport[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                    }

                    // New Value ALL RAB & CUSTOM RAB + PPN
                    $projectdatareport[$project['id']]['rabvalueppn'] = 0;
                    if(!empty($price) || !empty($allnewrabcust)){
                        $allrab     =   array_sum($allnewrabcust) +  array_sum(array_column($price, 'sumprice')) ;

                        // Value I & II
                        $valuerab = $allrab - ($allrab * (70/100));
                        $ppnrabval = ((int)$valuerab * ((int)$gconf['ppn']/100));
                        $valrab1  = $valuerab + $ppnrabval;
                        $valrab12 = (int)$valrab1 * 2;

                        // Value III
                        $valuerab3 = $allrab - ($allrab * (65/100));
                        $ppnrabval3 = (((int)$gconf['ppn']/100) * (int)$valuerab3) + $valuerab3;

                        // Value IV
                        $valuerab4 = $allrab - ($allrab * (5/100));
                        $valrabres4 = $allrab - $valuerab4;
                        $ppnrabval4 = (((int)$gconf['ppn']/100) * (int)$valrabres4) + $valrabres4;

                        // PPH Value Configuration
                        $pphinvoice1 = $InvoiceModel->where('projectid',$project['id'])->where('status', 1)->first();
                        $pphinvoice2 = $InvoiceModel->where('projectid',$project['id'])->where('status', 2)->first();
                        $pphinvoice3 = $InvoiceModel->where('projectid',$project['id'])->where('status', 3)->first();
                        $pphinvoice4 = $InvoiceModel->where('projectid',$project['id'])->where('status', 4)->first();

                        $pph1 = (int)$valuerab * ((int)$pphinvoice1['pph23'] / 100); 
                        $pph2 = (int)$valuerab * ((int)$pphinvoice2['pph23'] / 100); 
                        $pph3 = (int)$valuerab3 * ((int)$pphinvoice3['pph23'] / 100); 
                        $pph4 = (int)$valuerab4 * ((int)$pphinvoice4['pph23'] / 100); 

                        $projectdatareport[$project['id']]['rabvalueppn'] = (int)$ppnrabval4 + (int)$ppnrabval3 + (int)$valrab12 + (int)$pph1 + (int)$pph2 + (int)$pph3 + (int)$pph4;
                    }
                    // End New Value ALL RAB & CUSTOM RAB + PPN
                }
            }

            if(!isset($projectdatareport)){
                $projectdatareport = [];
            }

            if(!isset($queryproject)){
                $queryproject = [];
            }
            // End Empty Project Condition


            // Parsing Data to View
            $data                       = $this->data;
            $data['input']              = $input;
            $data['title']              = lang('Global.titleDashboard');
            $data['description']        = lang('Global.dashboardDescription');
            $data['client']             = $client;
            $data['projects']           = $projects;
            $data['design']             = $DesignModel->findAll();
            $data['rabs']               = $RabModel->findAll();
            $data['pakets']             = $PaketModel->findAll();
            $data['mdls']               = $MdlModel->findAll();
            $data['pager']              = $pager->links('projects', 'uikit_full');
            $data['pagerpro']           = $pager->makeLinks($pagereport, $perpagereport, $totalpro, 'uikit_full2');
            $data['projectdata']        = $projectdata;
            $data['projectdesign']      = $projectdesign;
            $data['input']              = $input;
            $data['projectdatareport']  = $projectdatareport;
            $data['projectsreport']     = $queryproject;
            $data['total']              = count($queryproject);
            $data['startdate']          = strtotime($startdate);
            $data['enddate']            = strtotime($enddate);


            return view('dashboard', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function acc($id)
    {
        if (($this->data['authorize']->hasPermission('client.auth.holding', $this->data['uid'])) || ($this->data['authorize']->hasPermission('client.auth.branch', $this->data['uid']))) {
            $DesignModel        = new DesignModel();
            $UserModel          = new UserModel();
            $ProjectModel       = new ProjectModel();
            $LogModel           = new LogModel();
            $NotifikasiModel    = new NotificationModel();
            $input              = $this->request->getPost('status');

            $design = $DesignModel->find($id);
            $project = $ProjectModel->find($design['projectid']);

            // Users Notifikasi ACC
            $this->builder = $this->db->table('users');
            $this->builder->where('deleted_at', null);
            $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            $this->builder->where('users.id !=', $this->data['uid']);
            $this->builder->where('auth_groups.name =', 'admin');
            $this->builder->orWhere('auth_groups.name =', 'design');
            $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
            $users = $this->builder->get()->getResult();

            foreach($users as $user){
                $datanotifikasi = [
                    'userid'        => $user->id,
                    'keterangan'    => 'Design '.$project['name'].' telah di disetujui client',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];
                $NotifikasiModel->insert($datanotifikasi);
            }

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

    public function notif($id)
    {
        $NotifikasiModel    = new NotificationModel();
        $input              = $this->request->getPost('status');

        $datanotifikasi = [
            'id'            => $id,
            'status'        => "1",
        ];
        $NotifikasiModel->save($datanotifikasi);

        $data = $this->data;
        die(json_encode(array($datanotifikasi)));
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
        $ProjectModel       = new ProjectModel();
        $DesignModel        = new DesignModel();
        $LogModel           = new LogModel();
        $NotifikasiModel    = new NotificationModel();
        $VersionModel       = new VersionModel();

        $input = $this->request->getPost();
        $project = $ProjectModel->find($id);

        // Users
        $this->builder = $this->db->table('users');
        $this->builder->where('deleted_at', null);
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id !=', $this->data['uid']);
        $this->builder->where('auth_groups.name =', 'design');
        $this->builder->orWhere('auth_groups.name =', 'admin');
        $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
        $users = $this->builder->get()->getResult();

        foreach($users as $user){
            $datanotifikasi = [
                'userid'        => $user->id,
                'keterangan'    => 'Revisi design '.$project['name'].' telah di upload client',
                'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                'status'        => 0,
            ];
            $NotifikasiModel->insert($datanotifikasi);
        }

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
            return redirect()->to('/')->withInput()->with('errors', $this->validator->getErrors());
        }

        if (isset($input['revisi'])) {
            $project = $ProjectModel->find($id);
            $design = $DesignModel->where('projectid', $id)->first();
            if (!empty($design)) {
                // if (!empty($design['revision'])) {
                //     unlink(FCPATH . '/img/revisi/' . $design['revision']);
                // }
                $datadesign = [
                    'id'            => $design['id'],
                    'projectid'     => $id,
                    'revision'      => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->save($datadesign);

                
                // Insert revisi Version (Arsip)
                $revisionversion = [
                    'projectid'     => $id,
                    'file'          => $input['revisi'],
                    'type'          => 2,
                ];
                $VersionModel->insert($revisionversion);
                // End Insert revisi Version

                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload revisi ' . $project['name']]);
            } else {
                $datadesign = [
                    'projectid'     => $id,
                    'revision'      => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->save($datadesign);

                // Insert revisi Version (Arsip)
                $revisionversion = [
                    'projectid'     => $id,
                    'file'          => $input['revisi'],
                    'type'          => 2,
                ];
                $VersionModel->insert($revisionversion);
                // End Insert revisi Version

                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah revisi ' . $project['name']]);
            }
        }

        return redirect()->to('/')->with('message', 'Revisi telah tekirim');
    }

    public function removerevisi()
    {
        // Removing File
        $input = $this->request->getPost('revisi');
        unlink(FCPATH . 'img/revisi/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function buktipembayaran($id)
    {
        // Calling Models
        $BuktiModel         = new BuktiModel();
        $ProjectModel       = new ProjectModel();
        $NotifikasiModel    = new NotificationModel();
        $LogModel           = new LogModel();

        $input      = $this->request->getPost();
        $project    = $ProjectModel->find($id);

        // Validation Rules
        $rules = [
            'buktipembayaran' => [
                'label'  => 'Bukti Pembayaran',
                'rules'  => 'required',
                'errors' => [
                    'required'      => '{field} Belum Di Unggah',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/')->withInput()->with('errors', $this->validator->getErrors());
        }

        $date       = date_create();
        $tanggal    = date_format($date, 'Y-m-d H:i:s');
        $databukti  = [
            'projectid'     => $id,
            'file'          => $input['buktipembayaran'],
            'status'        => 0,
            'created_at'    => $tanggal,
        ];
        $BuktiModel->insert($databukti);

        // Users
        $this->builder = $this->db->table('users');
        $this->builder->where('deleted_at', null);
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id !=', $this->data['uid']);
        $this->builder->orWhere('auth_groups.name =', 'admin');
        $this->builder->where('auth_groups.name =', 'marketing');
        $this->builder->orWhere('auth_groups.name =', 'finance');
        $this->builder->select('users.id as id, users.username as name, users.active as status, users.firstname as firstname, users.lastname as lastname, users.email as email, users.parentid as parent, auth_groups.id as group_id, auth_groups.name as role');
        $users = $this->builder->get()->getResult();

        foreach($users as $user){
            $datanotifikasi = [
                'userid'        => $user->id,
                'keterangan'    => 'Bukti pembayaran '.$project['name'].' telah di Kirim klien',
                'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                'status'        => 0,
            ];
            $NotifikasiModel->insert($datanotifikasi);
        }

        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan Upload Bukti Pembayaran ' . $project['name']]);


        return redirect()->to('/')->with('message', 'Bukti telah tekirim');
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
        $authorize->createPermission('finance.project.edit', 'Merubah data finance.');
        $authorize->createPermission('ppic.project.edit', 'Merubah data PPIC.');

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
        $authorize->createGroup('finance', 'Divisi finance.');
        $authorize->createGroup('ppic', 'Divisi PPIC.');

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
        $authorize->addPermissionToGroup('admin.project.read', 'finance');
        $authorize->addPermissionToGroup('finance.project.edit', 'finance');
        $authorize->addPermissionToGroup('client.read', 'finance');
        $authorize->addPermissionToGroup('finance.project.edit', 'superuser');
        $authorize->addPermissionToGroup('production.project.edit', 'admin');
        $authorize->addPermissionToGroup('marketing.project.edit', 'admin');
        $authorize->addPermissionToGroup('design.project.edit', 'admin');
        $authorize->addPermissionToGroup('finance.project.edit', 'admin');
        $authorize->addPermissionToGroup('admin.project.read', 'ppic');
        $authorize->addPermissionToGroup('ppic.project.edit', 'ppic');
        $authorize->addPermissionToGroup('admin.mdl.read', 'client pusat');
        $authorize->addPermissionToGroup('admin.mdl.read', 'client cabang');

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
            return redirect()->to('/')->with('errors', $this->validator->getErrors());
        }

        // Vaalidating Password
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/')->withInput()->with('errors', $this->validator->getErrors());
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
        // $authorize = service('authorization');
        // $authorize->removeUserFromGroup(13, 3);
        // $authorize->addUserToGroup(13, 1);
        echo "There's nothing to see here";
    }

    public function information()
    {
        // echo "There's nothing to see here";
        phpinfo();
    }
}
