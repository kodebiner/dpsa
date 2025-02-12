<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\InvoiceModel;
use App\Models\BastModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\PaketModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseDetailModel;
use App\Models\LogModel;
use App\Models\RabModel;
use App\Models\ProductionModel;

class Purchase extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('paket');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('client.read', $this->data['uid'])) {
            // Calling Services
            $pager      = \Config\Services::pager();

            // Calling Models
            $MdlModel               = new MdlModel();
            $PaketModel             = new PaketModel();
            $MdlPaketModel          = new MdlPaketModel();
            $PurchaseModel          = new PurchaseModel();
            $PurchaseDetailModel    = new PurchaseDetailModel();

            // Filter Input
            $input          = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->db       = \Config\Database::connect();
            $validation     = \Config\Services::validation();
            $this->builder  = $this->db->table('paket');
            $this->config   = config('Auth');
            $this->auth     = service('authentication');

            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('paket.name', $input['search'])->where('ordering >=', 1)->orderBy('ordering', 'ASC');
            } else {
                $this->builder->where('parentid', 0)->where('ordering >=', 1)->orderBy('ordering', 'ASC');
            }
            $this->builder->select('paket.id as id, paket.name as name, paket.ordering as ordering, paket.parentid as parentid,');
            $parents = $this->builder->get($perpage, $offset)->getResultArray();

            if (isset($input['search']) && !empty($input['search'])) {
                $totalParent = $PaketModel
                ->like('paket.name', $input['search'])
                ->where('parentid', 0)
                ->countAllResults();
            } else { 
                $totalParent = $PaketModel
                ->where('parentid', 0)
                ->countAllResults();
            }

            // Purchase Data
            $purchasedata = [];

            // List Paket Auto Complete
            $autopakets     = $PaketModel->where('parentid !=', 0)->find();

            // List Paket
            $mdldata        = [];
            $mdlid          = [];
            if (!empty($parents)) {
                foreach ($parents as $parent) {
                    $mdldata[$parent['id']]['name']     = $parent['name'];
                    $paketdata                          = $PaketModel->where('parentid', $parent['id'])->orderBy('ordering', 'ASC')->find();

                    if (!empty($paketdata)) {
                        foreach ($paketdata as $paket) {
                            $mdldata[$parent['id']]['paket'][$paket['id']]['id']        = $paket['id'];
                            $mdldata[$parent['id']]['paket'][$paket['id']]['parentid']  = $paket['parentid'];
                            $mdldata[$parent['id']]['paket'][$paket['id']]['name']      = $paket['name'];
                            $mdldata[$parent['id']]['paket'][$paket['id']]['ordering']  = $paket['ordering'];

                            // List MDL Paket
                            $mdlpaket   = $MdlPaketModel->where('paketid', $paket['id'])->orderBy('ordering', 'ASC')->find();

                            // List MDL
                            if (!empty($mdlpaket)) {
                                foreach ($mdlpaket as $mdlp) {
                                    $mdldata[$parent['id']]['paket'][$paket['id']]['mdl'][$mdlp['mdlid']]                   = $MdlModel->find($mdlp['mdlid']);
                                    $mdldata[$parent['id']]['paket'][$paket['id']]['mdl'][$mdlp['mdlid']]['ordering']       = $mdlp['ordering'];
                                    $mdlid[]                                                                                = $mdlp['mdlid'];
                                }
                            } else {
                                $mdldata[$parent['id']]['paket'][$paket['id']]['mdl']                                       = [];
                                $mdlid[]                                                                                    = '';
                            }
                        }
                    } else {
                        $mdlpaket                           = [];
                        $mdldata[$parent['id']]['paket']    = [];
                        $mdlid[]                            = '';
                    }

                    // List Parent Auto Complete
                    $autoparents    = $PaketModel->where('parentid', 0)->where('id !=', $parent['id'])->find();
                }

                // List MDL Uncategories
                $mdldata['mdluncate']                                                   = $MdlModel->whereNotIn('id', $mdlid)->find();
            } else {
                $paketdata              = [];
                $autoparents            = [];
                $mdldata['mdluncate']   = [];
            }


            // Parsing Data to View
            $data                   =   $this->data;
            $data['title']          =   "Pesanan Klien";
            $data['description']    =   "Daftar Pesanan Pembelian";
            $data['mdldata']        =   $mdldata;
            $data['parents']        =   $parents;
            $data['countparents']   =   count($parents);
            $data['autoparents']    =   $autoparents;
            $data['autopakets']     =   $autopakets;
            $data['inputpage']      =   $input;
            $data['pager']          =   $pager->makeLinks($page, $perpage, $totalParent, 'uikit_full');
            $data['idmdl']          =   $this->request->getGet('mdlid');
            $data['idpaket']        =   $this->request->getGet('paketid');
            $data['idparent']       =   $this->request->getGet('parentid');
            $data['purchasedata']   =   $purchasedata;
            $data['mdls']           =   $MdlModel->findAll();
            $data['input']          =   $this->request->getGet('companyid');

            // Return
            // return view('purchase', $data);
            return view('newPurchase', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function createpurchase()
    {
        // Calling Models
        $MdlModel               = new MdlModel();

        // Get Data
        $input = $this->request->getPost();
        $mdl    = $MdlModel->find($input['id']);

        $data = [
            'mdl'           => $input['id'],
            // 'paket'         => $input['paket'],
            'name'          => $mdl['name'],
            'width'         => $mdl['width'],
            'heigth'        => $mdl['height'],
            'length'        => $mdl['length'],
            'volume'        => $mdl['volume'],
            'denomination'  => $mdl['denomination'],
            'price'         => $mdl['price'],
            'keterangan'    => $mdl['keterangan'],
            'photo'         => $mdl['photo'],
            'qty'           => '',
        ];

        if(empty($data['qty'])){
            $data['qty'] = 1;
        }

        if (!isset($_SESSION['item_purchase'])) {
            $_SESSION['item_purchase'] = array();
        }
        array_push($_SESSION['item_purchase'],$data);


        die(json_encode(array($data)));
        
    }

    public function createQtySession()
    {
        $input = $this->request->getPost();
        
        $newQtyItem = array();
        // unset($_SESSION['item_purchase']);
        foreach($_SESSION['item_purchase'] as $item){
            if($item['mdl'] === $input['id']){
                if(!empty($input['qtyVal'])){
                    $item['qty'] = $input['qtyVal'];
                }
                array_push($newQtyItem,$item);
            }else{
                array_push($newQtyItem,$item);
            }
        }
        unset($_SESSION['item_purchase']);
        
        $_SESSION['item_purchase'] = $newQtyItem;

        die(json_encode($_SESSION['item_purchase']));
    }

    public function unsetSessionItem()
    {
        $input = $this->request->getPost();

        $newItem = array();

        foreach($_SESSION['item_purchase'] as $item){
            if($item['mdl'] === $input['id']){
                unset($_SESSION['item_purchase'][$item['mdl']]);
            }else{
                array_push($newItem,$item);
            }
        }

        unset($_SESSION['item_purchase']);
        
        $_SESSION['item_purchase'] = $newItem;

        die(json_encode($_SESSION['item_purchase']));
    }

    public function unsetSession()
    {
        unset($_SESSION['item_purchase']);
        die(json_encode("Item Berhasil Dihapus"));
    }

    public function insertpurchase()
    {
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();
        $MdlPaketModel          = new MdlPaketModel();
        $UserModel              = new UserModel();
        $NotificationModel      = new NotificationModel();
        $LogModel               = new LogModel();
        $UserModel              = new UserModel();
        $CompanyModel           = new CompanyModel();

        $user = $UserModel->find($this->data['uid']);
        $company = $CompanyModel->find($this->data['account']->parentid);
        
        $quantity = $this->request->getPost('qty');

        if (empty($this->data['account']->parentid)) {
            unset($_SESSION['item_purchase']);
            return redirect()->to('pesanan')->withInput()->with('errors', array('Pesan Hanya Bisa Dilakukan Oleh Klien'));
        }

        // Check Current Client Id Order
        $currentOrderId = $PurchaseModel->where('clientid',$this->data['account']->parentid)->first();
        // $currentOrderId = $PurchaseModel->where('clientid',1)->first();

        if(empty($currentOrderId)){
            $purchase = [
                'clientid' => $this->data['account']->parentid,
                // 'clientid' => 1,
            ];
            $PurchaseModel->insert($purchase);
            $purchaseid = $PurchaseModel->getInsertID();

        }elseif(!empty($currentOrderId)){
            $purchaseid = $currentOrderId['id'];
        }

        foreach($quantity as $key => $qty){
            // Get Paket Data
            $paket = $MdlPaketModel->where('mdlid', $key)->first();

            // Check Data If Get Same Order
            $currentOrder = $PurchaseDetailModel->where('clientid',$this->data['account']->parentid)->where('mdlid',$key)->where('paketid',$paket['paketid'])->first();
            // $currentOrder = $PurchaseDetailModel->where('clientid',1)->where('mdlid',$key)->where('paketid',$paket['paketid'])->first();
            if(!empty($currentOrder)){
                $podetail = [
                    'id'            => $currentOrder['id'],
                    'purchaseid'    => $purchaseid,
                    'clientid'      => $this->data['account']->parentid,
                    // 'clientid'      => 1,
                    'mdlid'         => $key,
                    'paketid'       => $paket['paketid'],
                    'qty'           => (int)$qty,
                ];
                $PurchaseDetailModel->save($podetail);
            }else{
                $podetail = [
                    'purchaseid'    => $purchaseid,
                    'clientid'      => $this->data['account']->parentid,
                    // 'clientid'      => 1,
                    'mdlid'         => $key,
                    'paketid'       => $paket['paketid'],
                    'qty'           => $qty,
                ];
                $PurchaseDetailModel->insert($podetail);
            }
        }

        // Notification

        // Get Data
        $authorize  = $auth = service('authorization');

        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Marketing
        $marketings = $authorize->usersInGroup('marketing');


        // Notif Marketing
        foreach ($marketings as $marketing){

            $notifmarketing  = [
                'userid'        => $marketing['id'],
                'keterangan'    => $company['rsname']. ' baru saja menambahkan pesanan baru ',
                'url'           => 'pesanmasuk?companyid=' . $this->data['account']->parentid,
                'status'        => 0,
            ];
            $NotificationModel->insert($notifmarketing);
        }

        // Notif Admin
        foreach ($admins as $admin) {
            $notifadmin  = [
                'userid'        => $admin['id'],
                'keterangan'    => $company['rsname']. ' baru saja menambahkan pesanan baru ',
                'url'           => 'pesanmasuk?companyid=' . $this->data['account']->parentid,
                'status'        => 0,
            ];
            $NotificationModel->insert($notifadmin);
        }

        $LogModel->save(['uid' => $this->data['uid'], 'record' => $user->username.' dari '.$company['rsname'].' baru saja menambahkan pesanan Baru ']);
        
        // End Notifications
        
        // Unset Item Order
        unset($_SESSION['item_purchase']);

        return redirect()->to('pesanan')->with('message','Pesanan Telah Dikirimkan Mohon Tunggu Konfirmasi Dari DPSA');
    }

    public function orderlist()
    {
        // Calling Services
        $pager      = \Config\Services::pager();

        // Calling Models
        $MdlModel               = new MdlModel();
        $PaketModel             = new PaketModel();
        $MdlPaketModel          = new MdlPaketModel();
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();
        $CompanyModel           = new CompanyModel();
        // $companyclient          = $this->builder  = $this->db->table('company');

        // All Company 
        $allcompany = $CompanyModel->findAll();

        // Filter Input
        $input          = $this->request->getGet();

        // Report Data Script
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $purchases      = $this->builder  = $this->db->table('purchaseorder');
        
        if (isset($input['perpage'])) {
            $perpage    = $input['perpage'];
        } else {
            $perpage    = 10;
        }

        $page = (@$_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $perpage;

        $purchases->join('company', 'company.id = purchaseorder.clientid');
        if (isset($input['search']) && !empty($input['search'])) {
            $purchases->like('company.rsname', $input['search']);
        }
        $purchases->orderBy('id',"DESC");
        $purchases->select('purchaseorder.id as id, purchaseorder.clientid as clientid, company.rsname as name');
        $querypurchase = $purchases->get($perpage, $offset)->getResultArray();

        // Search Company Order
        if (isset($input['search']) && !empty($input['search'])) {
            $totalpro       = $purchases
                ->join('company', 'company.id = purchaseorder.clientid')
                ->like('company.rsname', $input['search'])
                ->countAllResults();
        } else {
            $totalpro     = $purchases
                ->join('company', 'company.id = purchaseorder.clientid')
                ->countAllResults();
        }

        // Purchase Data
        $purchasedata       = [];
        $purchasedetails    = [];
        $quantity           = [];
       
        foreach ($querypurchase as $purchase){
            $purchasedetails[$purchase['clientid']]['mdls']  = $PurchaseDetailModel->where('clientid',$purchase['clientid'])->find();
            foreach($purchasedetails[$purchase['clientid']]['mdls'] as $purdet){
                $mdls[] = $MdlModel->where('id',$purdet['mdlid'])->first();
                $quantity[]  = [
                            'id'    => $purdet['id'],
                            'mdlid' => $purdet['mdlid'],
                            'qty'   => $purdet['qty'],
                        ];
                foreach($mdls as $mdl){
                    if($purdet['mdlid'] === $mdl['id']){
                        foreach($quantity as $qty){
                            if($qty['mdlid'] === $mdl['id']){
                                $purchasedata[$purchase['clientid']]['purdet'][$mdl['id']]  = [
                                    'id'            => $mdl['id'],  
                                    'name'          => $mdl['name'],
                                    'length'        => $mdl['length'],
                                    'width'         => $mdl['width'],
                                    'height'        => $mdl['height'],
                                    'volume'        => $mdl['volume'],
                                    'denomination'  => $mdl['denomination'],
                                    'photo'         => $mdl['photo'],
                                    'keterangan'    => $mdl['keterangan'],
                                    'qty'           => (int)$qty['qty'],
                                    'price'         => (int)$qty['qty'] * (int)$mdl['price'],
                                    'oriprice'      => (int)$mdl['price'],
                                ];
                            }
                        }
                    }
                   
                }
            }
        }


       // Parsing Data to View
       $data                   =   $this->data;
       $data['title']          =   "Daftar Pesanan Klien";
       $data['description']    =   "Daftar Pesanan Klien";
       $data['items']          =    $purchasedata;
       $data['purchases']      =    $querypurchase;
       $data['pager']          =    $pager->makeLinks($page, $perpage, $totalpro, 'uikit_full');
       $data['input']          =    $this->request->getGet('companyid');
       $data['inputpage']      =    $this->request->getVar();
       $data['allCompany']     =    $allcompany;

       // Return
       return view('purchaseorderlist', $data);
    }

    public function confirm($id){

        if ($this->data['authorize']->hasPermission('admin.project.create', $this->data['uid'])) {

            // Calling Model
            $ProjectModel           = new ProjectModel();
            $InvoiceModel           = new InvoiceModel();
            $BastModel              = new BastModel();
            $LogModel               = new LogModel();
            $NotificationModel      = new NotificationModel();
            $UserModel              = new UserModel();
            $PurchaseModel          = new PurchaseModel();
            $PurchaseDetailModel    = new PurchaseDetailModel();
            $RabModel               = new RabModel();
            $ProductionModel        = new ProductionModel();

            // initialize
            $input      = $this->request->getPost();
            $authorize  = $auth = service('authorization');

            // User Admin
            $admins     = $authorize->usersInGroup('admin');

            // User Designer
            $designers  = $authorize->usersInGroup('design');

            // User Marketing
            $marketings = $UserModel->find($this->data['account']->id);

            // User Client
            $clients    = $UserModel->where('parentid', $id)->find();

            // Purchase Order Data
            $purchases       = $PurchaseModel->where('clientid',$id)->find();
            $purchasedetails = $PurchaseDetailModel->where('clientid',$id)->find();

            // Validation Rules
            $rules = [
                'name' => [
                    'label'  => 'Nama Proyek',
                    'rules'  => 'required',
                    'errors' => [
                        'required'      => '{field} wajib diisi',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->to('project')->withInput()->with('errors', $this->validator->getErrors());
            }

            // Project Data
            $project = [
                'name'          => $input['name'],
                'clientid'      => $id,
                'status'        => 1,
                'tahun'         => date('Y-m-d H:i:s'),
                'marketing'     => $this->data['account']->id,
            ];

            if (isset($input['designtype'])) {
                $project['type_design'] = 1;
                $project['ded']         = $input['design'];
            } else {
                $project['type_design'] = 0;
            }

            $ProjectModel->insert($project);

            $projectid = $ProjectModel->getInsertID();

            // Data Notification
            if (isset($input['designtype'])) {
                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $this->data['account']->id,
                    'keterangan'    => $marketings->firstname . ' ' . $marketings->lastname . ' baru saja menambahkan Proyek Baru (' . $input['name'] . ') dengan desain ' . $input['design'],
                    'url'           => "project/listprojectclient/".$id."?projectid=" . $projectid,
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => $marketings->firstname . ' ' . $marketings->lastname . ' baru saja menambahkan Proyek Baru (' . $input['name'] . ') dengan desain ' . $input['design'],
                        'url'           => "project/listprojectclient/".$id."?projectid=" . $projectid,
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Designer
                foreach ($designers as $designer) {
                    $notifdesigner  = [
                        'userid'        => $designer['id'],
                        'keterangan'    => $marketings->firstname . ' ' . $marketings->lastname . ' baru saja menambahkan Proyek Baru (' . $input['name'] . ') dengan desain ' . $input['design'],
                        'url'           => "project/listprojectclient/".$id."?projectid=" . $projectid,
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifdesigner);
                }
            } else {
                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $this->data['account']->id,
                    'keterangan'    => $marketings->firstname . ' ' . $marketings->lastname . ' baru saja menambahkan Proyek Baru (' . $input['name'] . ')',
                    'url'           => "project/listprojectclient/".$id."?projectid=" . $projectid,
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => $marketings->firstname . ' ' . $marketings->lastname . ' baru saja menambahkan Proyek Baru (' . $input['name'] . ')',
                        'url'           => "project/listprojectclient/".$id."?projectid=" . $projectid,
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }
            }
            
            // Replace Data From PO to RAB
            $rab = [];
            foreach($purchasedetails as $podetail){
                $datarab = [
                    'projectid' => $projectid,
                    'mdlid'     => $podetail['mdlid'],
                    'paketid'   => $podetail['paketid'],
                    'qty'       => $podetail['qty'],
                ];
                $RabModel->insert($datarab);

                $rab[$podetail['mdlid']] = $podetail['qty'];

                // Delete PO Data
                $PurchaseDetailModel->delete($podetail['id']);
            }

            // Production Tracking
            foreach($rab as $mdl => $qty){
                for ($x = 0; $x < $qty; $x++){
                    $dataproduction = [
                        'projectid' => $projectid,
                        'mdlid'     => $mdl,
                    ];
                    $ProductionModel->insert($dataproduction);
                }
            }

            // Delete Purchase Data
            foreach($purchases as $purchase){
                $PurchaseModel->delete($purchase['id']);
            }

            // INSERT INVOICE DATA
            $statusinv = [1, 2, 3, 4];
            foreach ($statusinv as $inv) {
                $datainv = [
                    'projectid' => $projectid,
                    'status'    => $inv,
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

            return redirect()->to('pesanmasuk')->with('message', "Data berhasil di konfirmasi dan telah dibuat sebagai proyek". $input['name']);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deletepurchase()
    {
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();
        $LogModel               = new LogModel();
        $UserModel              = new UserModel();
        $CompanyModel           = new CompanyModel();

        $user = $UserModel->find($this->data['uid']);
        
        $input = $this->request->getPost('id');
        
        $company = $CompanyModel->find($input);
        $LogModel->save(['uid' => $this->data['uid'], 'record' => $user->username. ' baru saja menghapus pesanan dari '.$company['rsname']]);
        
        $purchases = $PurchaseModel->where('clientid',$input)->find();
        $Purchasedetails = $PurchaseDetailModel->where('clientid',$input)->find();

        foreach($Purchasedetails as $purchasedet){
            $PurchaseDetailModel->delete($purchasedet['id']);
        }

        foreach($purchases as $purchase){
            $PurchaseModel->delete($purchase['id']);
        }

        
        die(json_encode('Data Berhasil Dihapus'));
    }
}
