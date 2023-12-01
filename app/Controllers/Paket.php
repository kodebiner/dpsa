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
            $this->builder->orLike('paket.price', $input['search']);
        }

        // Populating data
        $mdls       = $MdlModel->findAll();

        $this->builder->select('paket.id as id, paket.name as name');
        $query      = $this->builder->get($perpage, $offset)->getResultArray();
        $total      = $this->builder->countAllResults();

        // Parsing Data to View
        $data                   = $this->data;
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

        // Finding Paket Detail
        $detail = $PaketDetailModel->where('paketid',$id)->find();

        // Delete Paket Detail
        $PaketDetailModel->delete($detail);

        // Delete Data
        $PaketModel->delete($id);

        // Return
        return redirect()->to('paket')->with('massage', 'Data telah dihapuskan');
    }
}
