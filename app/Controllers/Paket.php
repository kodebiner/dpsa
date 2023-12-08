<?php

namespace App\Controllers;

use App\Models\PaketModel;
use App\Models\PaketDetailModel;
use App\Models\MdlModel;


class Paket extends BaseController
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
        // Calling Services
        $pager = \Config\Services::pager();

        // Calling Models
        $MdlModel   = new MdlModel;

        // Filter Input
        $input = $this->request->getGet();

        if (isset($input['perpage'])) {
            $perpage = $input['perpage'];
        } else {
            $perpage = 10;
        }

        $page = (@$_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $perpage;

        if (isset($input['search']) && !empty($input['search'])) {
            $this->builder->like('paket.name', $input['search']);
        }

        // Populating data
        $mdls       = $MdlModel->findAll();

        $this->builder->select('paket.id as id, paket.name as name');
        $query      = $this->builder->get($perpage, $offset)->getResultArray();
        $total      = $this->builder->countAllResults();

        // Parsing Data to View
        $data                   =   $this->data;
        $data['title']          =   "Paket";
        $data['description']    =   "Daftar Paket yang tersedia";
        $data['pakets']         =   $query;
        $data['mdls']           =   $mdls;
        $data['input']          =   $input;
        $data['pager']          =   $pager->makeLinks($page, $perpage, $total, 'uikit_full');

        // Return
        return view('paket', $data);
    }

    public function create()
    {
        // Calling Models
        $PaketModel         = new PaketModel;
        $PaketDetailModel   = new PaketDetailModel;

        // Get Data
        $input = $this->request->getPost();

        // Save Data
        $paket = [
            'name'          => $input['name'],
        ];

        // Insert Data Paket
        $PaketModel->insert($paket);

        // Get Paket ID
        $paketId = $PaketModel->getInsertID();

        // Creating Paket Detail
        foreach ($input['mdlid'] as $mdl) {
            $detail = [
                'paketid'   => $paketId,
                'mdlid'     => $mdl
            ];

            // Insert Data Paket Detail
            $PaketDetailModel->insert($detail);
        }

        // Return
        return redirect()->to('paket')->with('massage', "Data Tersimpan");
    }

    public function update($id)
    {
        // Calling Models
        $PaketModel = new PaketModel;

        $input = $this->request->getPost();

        // Input Data
        $paketup = [
            'id'            => $id,
            'name'          => $input['name'],
        ];

        // Save Data Paket
        $PaketModel->save($paketup);

        // Return
        return redirect()->to('paket')->with('massage', 'Data Behasil Di Ubah');
    }

    public function delete($id)
    {
        // Calling Models
        $PaketModel         = new PaketModel;
        $PaketDetailModel   = new PaketDetailModel;

        // Delete Paket Detail
        $PaketDetailModel->where('paketid', $id)->delete();

        // Delete Data
        $PaketModel->delete($id);

        // Return
        return redirect()->to('paket')->with('massage', 'Data telah dihapuskan');
    }

    public function indexdetail($id)
    {
        // Connecting Databse
        $db         = \Config\Database::connect();

        // Calling Services
        $pager = \Config\Services::pager();

        // Calling Models
        $MdlModel   = new MdlModel;
        $PaketModel = new PaketModel;

        // Filter Input
        $input = $this->request->getGet();

        if (isset($input['perpage'])) {
            $perpage = $input['perpage'];
        } else {
            $perpage = 10;
        }

        $page = (@$_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $perpage;

        // Populating data
        $detailbuilder      =   $db->table('paketdetail');

        $detailsbuilder     =   $detailbuilder->select('paketdetail.mdlid as id, paketdetail.paketid as paketid, mdl.name as name, mdl.price as price');
        $detailsbuilder     =   $detailbuilder->join('mdl', 'paketdetail.mdlid = mdl.id', 'left');
        $detailsbuilder     =   $detailbuilder->where('paketdetail.paketid', $id);

        if (isset($input['search']) && !empty($input['search'])) {
            $detailsbuilder     =   $detailbuilder->like('name', $input['search'])->where('paketdetail.paketid', $id);
            $detailsbuilder     =   $detailbuilder->orLike('price', $input['search'])->where('paketdetail.paketid', $id);
        }

        // Paginate MDL
        $query              =   $detailsbuilder->get($perpage, $offset)->getResultArray();
        $total              =   $detailsbuilder->countAllResults();
        
        // Not Paginate MDL
        $detailsbuilder     =   $detailbuilder->where('paketdetail.paketid', $id);
        $mdlarray           =   $detailsbuilder->get()->getResultArray();

        // Get MDL Array
        $mdl                =   array();
        foreach ($mdlarray as $mdlarr) {
            $mdl[]          =   $mdlarr['mdlid'];
        }

        $mdls               =   $MdlModel->whereNotIn('id', $mdl)->find();

        // Parsing Data to View
        $data                   =   $this->data;
        $data['title']          =   "Detail Paket";
        $data['description']    =   "Daftar Detail Paket yang tersedia";
        $data['paketdetails']   =   $query;
        $data['input']          =   $input;
        $data['mdls']           =   $mdls;
        $data['pager']          =   $pager->makeLinks($page, $perpage, $total, 'uikit_full');
        $data['pakets']         =   $PaketModel->find($id);

        // Return
        return view('paketdetail', $data);
    }

    public function createdetail($id)
    {
        // Calling Models
        $PaketDetailModel   = new PaketDetailModel;

        // Get Data
        $input = $this->request->getPost();

        // Creating Paket Detail
        $detail = [
            'paketid'   => $id,
            'mdlid'     => $input['mdlid'],
        ];

        // Insert Data Paket Detail
        $PaketDetailModel->insert($detail);

        // Return
        return redirect()->back()->with('massage', "Data Tersimpan");
    }

    public function deletedetail($id)
    {
        // Calling Models
        $PaketDetailModel   =   new PaketDetailModel;

        // Get Data
        $input = $this->request->getPost();
        
        // Deleting Paket Detail Data
        $PaketDetailModel->where('paketid', $input['paketid'])->where('mdlid', $id)->delete();

        // Return
        return redirect()->back()->with('massage', 'Data telah dihapuskan');
    }
}
