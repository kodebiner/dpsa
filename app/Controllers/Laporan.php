<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CompanyModel;
use App\Models\CustomRabModel;
use App\Models\ProjectModel;
use App\Models\ProjectTempModel;
use App\Models\LogModel;
use App\Models\MdlModel;
use App\Models\RabModel;
use App\Models\BastModel;
use App\Models\GconfigModel;
use App\Models\InvoiceModel;
use App\Models\PembayaranModel;

class Laporan extends BaseController
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
        $this->builder  = $this->db->table('project');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid']) || $this->data['authorize']->hasPermission('finance.project.edit', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $ProjectModel              = new ProjectModel();
            $CompanyModel              = new CompanyModel();
            $UserModel                 = new UserModel();
            $RabModel                  = new RabModel();
            $MdlModel                  = new MdlModel();
            $CustomRabModel            = new CustomRabModel();
            $BastModel                 = new BastModel();
            $PembayaranModel           = new PembayaranModel();
            $GconfigModel              = new GconfigModel();
            $InvoiceModel              = new InvoiceModel();

            // Populating data
            $input = $this->request->getVar('daterange');
            if (!empty($input)) {
                $daterange = explode(' - ', $input);
                $startdate = $daterange[0];
                $enddate = $daterange[1];
            } else {
                $startdate = date('Y-m-1');
                $enddate = date('Y-m-t');
            }

            // Initialize
            $input = $this->request->getGet();
            $gconf = $GconfigModel->first();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if ($startdate === $enddate) {
                $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
            } else {
                $this->builder->where('project.created_at >=', $startdate)->where('project.created_at <=', $enddate);
            }
            $this->builder->where('project.deleted_at ='.null);
            $this->builder->join('users', 'users.id = project.marketing');
            $this->builder->join('company', 'company.id = project.clientid');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('project.name', $input['search']);
                $this->builder->orLike('users.username', $input['search']);
                $this->builder->orLike('company.rsname', $input['search']);
            }
            $this->builder->orderBy('project.id',"DESC");
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
            $query = $this->builder->get($perpage, $offset)->getResultArray();
            $total = count($query);

            if (isset($input['search']) && !empty($input['search'])) {
                $totalLaporan = $ProjectModel
                    ->like('project.name', $input['search'])
                    ->countAllResults();
            } else {
                $totalLaporan = $ProjectModel
                    ->countAllResults();
            }

            $projects = $query;

            $projectdata = [];
            foreach ($projects as $project) {

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
                $projectdata[$project['id']]['allcustomrab']    = array_sum(array_column($allCustomRab, 'price'));

                // Pembayaran Value
                $pembayaran = $PembayaranModel->where('projectid',$project['id'])->find();
                $projectdata[$project['id']]['pembayaran'] = array_sum(array_column($pembayaran, 'qty'));

                // Rab Sum Value
                $projectdata[$project['id']]['rabvalue'] = 0;
                if (!empty($price)) {
                    $projectdata[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                }

                // New Value ALL RAB & CUSTOM RAB + PPN + PPH
                $projectdata[$project['id']]['rabvalueppn'] = 0;
                if(!empty($price) || !empty($allCustomRab)){
                    $allrab     =   array_sum(array_column($allCustomRab, 'price')) +  array_sum(array_column($price, 'sumprice')) ;

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


            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = 'Laporan';
            $data['description']    = lang('Global.clientListDesc');
            $data['total']          = $total;
            $data['input']          = $input;
            $data['projectdata']    = $projectdata;
            $data['projects']       = $projects;
            $data['pager']          = $pager->makeLinks($page, $perpage, $totalLaporan, 'uikit_full');
            $data['startdate']      = strtotime($startdate);
            $data['enddate']        = strtotime($enddate);

            return view('laporan', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function excel()
    {
        
        // Calling Model
        $ProjectModel              = new ProjectModel();
        $CompanyModel              = new CompanyModel();
        $UserModel                 = new UserModel();
        $RabModel                  = new RabModel();
        $MdlModel                  = new MdlModel();
        $CustomRabModel            = new CustomRabModel();
        $BastModel                 = new BastModel();
        $PembayaranModel           = new PembayaranModel();
        $GconfigModel              = new GconfigModel();
        $InvoiceModel              = new InvoiceModel();
        

        // Populating data
        $inputDate = $this->request->getGet('daterange');
        if (!empty($inputDate)) {
            $daterange = explode(' - ', $inputDate);
            $startdate = $daterange[0];
            $enddate = $daterange[1];
        } else {
            $startdate = date('Y-m-1');
            $enddate = date('Y-m-t');
        }
        
        // Initialize
        $input = $this->request->getGet();
        $gconf = $GconfigModel->first();

        // if (isset($input['perpage'])) {
        //     $perpage = $input['perpage'];
        // } else {
        //     $perpage = 10;
        // }

        // $page = (@$_GET['page']) ? $_GET['page'] : 1;
        // $offset = ($page - 1) * $perpage;

        if ($startdate === $enddate) {
            $this->builder->where('project.created_at >=', $startdate . ' 00:00:00')->where('project.created_at <=', $enddate . ' 23:59:59');
        } else {
            $this->builder->where('project.created_at >=', $startdate)->where('project.created_at <=', $enddate);
        }
        $this->builder->where('project.deleted_at ='.null);

        if($this->data['role'] === "client cabang"){
            
            $this->builder->where('project.clientid',$this->data['account']->parentid);
            $this->builder->join('users', 'users.id = project.marketing');
            $this->builder->join('company', 'company.id = project.clientid');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('project.name', $input['search']);
                $this->builder->orLike('users.username', $input['search']);
                $this->builder->orLike('company.rsname', $input['search']);
            }
            $this->builder->orderBy('id',"DESC");
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
            // $query = $this->builder->get($perpage, $offset)->getResultArray();
            $query = $this->builder->get()->getResultArray();
            $total = count($query);
            $projects = $query;

        }elseif($this->data['role'] === "client pusat"){

            $this->builder->join('users', 'users.id = project.marketing');
            $this->builder->join('company', 'company.id = project.clientid');
            $this->builder->where('company.id',$this->data['account']->parentid);
            $this->builder->orWhere('company.parentid',$this->data['account']->parentid);
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('project.name', $input['search']);
                $this->builder->orLike('users.username', $input['search']);
                $this->builder->orLike('company.rsname', $input['search']);
            }
            $this->builder->orderBy('id',"DESC");
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
            // $query = $this->builder->get($perpage, $offset)->getResultArray();
            $query = $this->builder->get()->getResultArray();
            $total = count($query);
            $projects = $query;

        }else{
           
            $this->builder->join('company', 'company.id = project.clientid');
            $this->builder->join('users', 'users.id = project.marketing');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('project.name', $input['search']);
                $this->builder->orLike('users.username', $input['search']);
                $this->builder->orLike('company.rsname', $input['search']);
            }
            $this->builder->orderBy('id',"DESC");
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, company.rsname as rsname, project.marketing as marketing, project.created_at as created_at, users.username as username');
            // $query = $this->builder->get($perpage, $offset)->getResultArray();
            $query = $this->builder->get()->getResultArray();
            $total = count($query);
            $projects = $query;
            
        }

        $projectdata = [];
        foreach ($projects as $project) {

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
            $projectdata[$project['id']]['allcustomrab']    = array_sum(array_column($allCustomRab, 'price'));

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
            if(!empty($price) || !empty($allCustomRab)){
                $allrab     =   array_sum(array_column($allCustomRab, 'price')) +  array_sum(array_column($price, 'sumprice')) ;

                // PPN Value Configuration

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

        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = 'Laporan';
        $data['description']    = "Data Laporan";
        $data['company']        = $query;
        $data['total']          = $total;
        $data['input']          = $input;
        $data['projectdata']    = $projectdata;
        $data['projects']       = $projects;
        $data['startdate']      = strtotime($startdate);
        $data['enddate']        = strtotime($enddate);

        return view('laporanview', $data);
    }
}
