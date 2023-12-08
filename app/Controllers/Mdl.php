<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\PaketModel;


class Mdl extends BaseController
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
        $pager      = \Config\Services::pager();

        // Calling Models
        $MdlModel   = new MdlModel();
        $PaketModel = new PaketModel();

        // Filter Input
        $input      = $this->request->getGet();

        if (isset($input['perpage'])) {
            $perpage = $input['perpage'];
        } else {
            $perpage = 10;
        }

        // Populating Data
        // List Paket
        if (isset($input['search']) && !empty($input['search'])) {
            $pakets = $PaketModel->like('name', $input['search'])->paginate($perpage, 'paket');
        } else {
            $pakets = $PaketModel->paginate($perpage, 'paket');
        }

        // List MDL In Paket
        $mdls = array();
        foreach ($pakets as $paket) {
            $mdls[$paket['id']] = $MdlModel->where('paketid', $paket['id'])->find();
        }

        // Parsing Data to View
        $data                   =   $this->data;
        $data['title']          =   "MDL";
        $data['description']    =   "Daftar MDL yang tersedia";
        $data['pakets']         =   $pakets;
        $data['mdls']           =   $mdls;
        $data['input']          =   $input;
        $data['pager']          =   $PaketModel->pager;

        // Return
        return view('mdl', $data);
    }

    public function create()
    {
        // Calling Models
        $PaketModel         = new PaketModel();

        // Get Data
        $input = $this->request->getPost();

        // Save Data
        $paket = [
            'name'          => $input['name'],
        ];

        // Insert Data Paket
        $PaketModel->insert($paket);

        // Return
        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function update($id)
    {
        // Calling Models
        $PaketModel = new PaketModel();

        // Get Data
        $input = $this->request->getPost();

        // Input Data
        $paketup = [
            'id'            => $id,
            'name'          => $input['name'],
        ];
        
        // Save Data Paket
        $PaketModel->save($paketup);

        // Return
        return redirect()->back()->with('message', 'Data Behasil Diperbaharui');
    }

    public function delete($id)
    {
        // Calling Models
        $PaketModel         = new PaketModel();
        $MdlModel           = new MdlModel();

        // Populating and Delete MDL
        $mdls               = $MdlModel->where('paketid', $id)->find();
        foreach ($mdls as $mdl) {
            $MdlModel->delete($mdl['id']);
        }

        // Delete Data
        $PaketModel->delete($id);

        // Return
        return redirect()->back()->with('message', 'Data Telah Dihapuskan');
    }

    public function createmdl($id)
    {
        // Calling Models
        $MdlModel = new MdlModel;

        // Get Data
        $input = $this->request->getPost();

        // Save Data
        if (($input['denomination'] === "2") || ($input['denomination'] === "3")) {
            $mdl = [
                'name'          => $input['name'],
                'length'        => $input['length'],
                'width'         => $input['width'],
                'height'        => $input['height'],
                'volume'        => NULL,
                'denomination'  => $input['denomination'],
                'price'         => $input['price'],
                'paketid'       => $id,
            ];
    
            if (! $this->validate([
                'name'      => "required|max_length[255]|is_unique[mdl.name]",
            ])) {
                    
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $MdlModel->save($mdl);
        } else {
            $mdl = [
                'name'          => $input['name'],
                'volume'        => NULL,
                'denomination'  => $input['denomination'],
                'price'         => $input['price'],
                'paketid'       => $id,
            ];
    
            if (! $this->validate([
                'name'      => "required|max_length[255]|is_unique[mdl.name]",
            ])) {
                    
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $MdlModel->save($mdl);
        }

        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function updatemdl($id)
    {
        // Populating Data
        $MdlModel = new MdlModel;

        // Get Data
        $input = $this->request->getPost();

        // Filter Condition Meters Or Unit
        if (($input['denomination'] === "2") || ($input['denomination'] === "3")) {
            $mdlup = [
                'id'            => $id,
                'name'          => $input['name'],
                'denomination'  => $input['denomination'],
                'length'        => $input['length'],
                'width'         => $input['width'],
                'height'        => $input['height'],
                'volume'        => NULL,
                'price'         => $input['price'],
                'paketid'       => $input['paketid'],
            ];
    
            if (! $this->validate([
                'name'      => "required|max_length[255]|is_unique[mdl.name]",
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Save Data MDL
            $MdlModel->save($mdlup);
        } else {
            $mdlup = [
                'id'            => $id,
                'name'          => $input['name'],
                'denomination'  => $input['denomination'],
                'length'        => NULL,
                'width'         => NULL,
                'height'        => NULL,
                'volume'        => NULL,
                'price'         => $input['price'],
                'paketid'       => $input['paketid'],
            ];
    
            if (! $this->validate([
                'name'      => "required|max_length[255]|is_unique[mdl.name]",
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            
            // Save Data MDL
            $MdlModel->save($mdlup);
        }

        // Return
        return redirect()->back()->with('message', lang('Global.updated'));
    }

    public function deletemdl($id)
    {
        // Populating Data
        $MdlModel = new MdlModel;

        // Delete Data MDL
        $MdlModel->delete($id);

        // Return
        return redirect()->back()->with('message', 'Data Telah Dihapuskan');
    }
}
