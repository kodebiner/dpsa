<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\PaketModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseDetailModel;
use App\Models\LogModel;
use App\Models\RabModel;
use \phpoffice\PhpOffice\PhpSpreadsheet;

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
        if ($this->data['authorize']->hasPermission('admin.mdl.read', $this->data['uid'])) {
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
                $perpage    = $input['perpage'];
            } else {
                $perpage    = 10;
            }

            // Search Engine
            if (isset($input['search']) && !empty($input['search'])) {
                $parents     = $PaketModel->like('name', $input['search'])->orderBy('ordering', 'ASC')->find();
            } else {
                $parents     = $PaketModel->where('parentid', 0)->orderBy('ordering', 'ASC')->paginate($perpage, 'parent');
            }

            // Purchase List
            $clientid = $this->data['account']->parentid;
            if($this->data['parentid'] === null){
                $purchases = $PurchaseModel->findAll();
            }else{
                $purchases = $PurchaseModel->where('clientid', $clientid)->find();
            }

            // Purchase Data
            $purchasedata = [];
            foreach ($purchases as $purchase){
                if($this->data['parentid'] === null){
                    $purchasedetail = $PurchaseDetailModel->findAll();
                }else{
                    $purchasedetail = $PurchaseDetailModel->where('clientid',$clientid)->find();
                }
                $purchasesdata[$purchase['id']['purchasedetail']] =  $purchasedetail;
            }

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

                                    // List MDL Uncategories
                                    // $mdldata['mdluncate']                                                   = $MdlModel->where('id !=', $mdlp['mdlid'])->find();
                                }
                            } else {
                                $mdldata[$parent['id']]['paket'][$paket['id']]['mdl']                                       = [];
                                $mdlid[]                                                                                    = '';

                                // List MDL Uncategories
                                // $mdldata['mdluncate']                                                       = $MdlModel->findAll();
                            }
                        }
                    } else {
                        $mdlpaket                           = [];
                        $mdldata[$parent['id']]['paket']    = [];
                        $mdlid[]                            = '';

                        // List MDL Uncategories
                        // $mdldata['mdluncate']               = $MdlModel->findAll();
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
            $data['input']          =   $input;
            $data['pager']          =   $PaketModel->pager;
            $data['idmdl']          =   $this->request->getGet('mdlid');
            $data['idpaket']        =   $this->request->getGet('paketid');
            $data['idparent']       =   $this->request->getGet('parentid');
            $data['purchasedata']   =   $purchasedata;

            // Return
            return view('purchase', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function datapaket()
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.read', $this->data['uid'])) {
            // Calling Model
            $MDLModel       = new MdlModel();
            $MDLPaketModel  = new MdlPaketModel();

            // Populating Data
            $input          = $this->request->getGET();
            $MdlPaket       = $MDLPaketModel->where('paketid', $input['paketid'])->find();

            $exclude = [];

            foreach ($MdlPaket as $paket) {
                $exclude[] = $paket['mdlid'];
            }

            if (!empty($exclude)) {
                $MDL = $MDLModel->like('name', $input['search']['term'])->whereNotIn('id', $exclude)->find();
            } else {
                $MDL = $MDLModel->like('name', $input['search']['term'])->find();
            }

            $return     = [];

            foreach ($MDL as $mdl) {
                $return[] = [
                    'id'    => $mdl['id'],
                    'text'  => $mdl['name'].' || '.$mdl['keterangan'],
                ];
            }

            die(json_encode($return));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function createpurchase()
    {
        // Calling Models
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();
        $MdlModel               = new MdlModel();

        // Get Data
        $input = $this->request->getPost();
        $mdl    = $MdlModel->find($input['id']);

        // Purchase
        $purchase  = [
            'clientid' => $this->data['account']->parentid,
        ];

        $data = [
            'mdl'           => $input['id'],
            'paket'         => $input['paket'],
            'name'          => $mdl['name'],
            'width'         => $mdl['width'],
            'heigth'        => $mdl['height'],
            'length'        => $mdl['length'],
            'volume'        => $mdl['volume'],
            'denomination'  => $mdl['denomination'],
            'price'         => $mdl['price'],
            'keterangan'    => $mdl['keterangan'],
            'photo'         => $mdl['photo'],
        ];

        die(json_encode(array($data)));
        
    }

    public function insertpurchase()
    {
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();

        // Get Data
        $input = $this->request->getPost();
        dd($input);
    }

    public function createpurchasedetail()
    {
        // Calling Models
        $PurchaseModel          = new PurchaseModel();
        $PurchaseDetailModel    = new PurchaseDetailModel();

    }

    public function submitcat()
    {
        // Calling Models
        $MdlPaketModel  = new MdlPaketModel();

        // Populating Data
        $input          = $this->request->getPOST();
        $lastOrder      = $MdlPaketModel->where('paketid', $input['paketid'])->orderBy('ordering', 'DESC')->first();
        if (!empty($lastOrder)) {
            $order = $lastOrder['ordering'] + 1;
        } else {
            $order = '1';
        }

        $submit = [
            'mdlid'     => $input['mdlid'],
            'paketid'   => $input['paketid'],
            'ordering'  => $order
        ];
        $exist = $MdlPaketModel->where('mdlid', $input['mdlid'])->where('paketid', $input['paketid'])->find();

        //Processing Data
        if (empty($exist)) {
            $MdlPaketModel->save($submit);
        }

        die(json_encode($submit));
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) {
            

            // Return
            return redirect()->back()->with('message', "Data Tersimpan");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
