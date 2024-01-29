<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\PaketModel;
use App\Models\LogModel;

use \phpoffice\PhpOffice\PhpSpreadsheet;

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
        $MdlModel       = new MdlModel();
        $PaketModel     = new PaketModel();
        $MdlPaketModel  = new MdlPaketModel();

        // Filter Input
        $input          = $this->request->getGet();

        if (isset($input['perpage'])) {
            $perpage    = $input['perpage'];
        } else {
            $perpage    = 10;
        }

        // Populating Data
        // List Parent
        $parents        = $PaketModel->where('parentid', 0)->paginate($perpage, 'parent');

        // List Parent Auto Complete
        $autoparent     = $PaketModel->where('parentid', 0)->find();

        // LAST UPDATE HERE
        // List Paket
        // $mdldata        = [];
        // $paketdata      = [];
        // $mdlpaketdata   = [];
        // foreach ($parents as $parent) {
        //     $paketdata[]    = $PaketModel->where('parentid', $parent['id'])->find();
            
        //     foreach ($paketdata as $paket) {
        //         $mdlpaket   = $MdlPaketModel->where('paketid', $paket['id'])->find();

        //         if (!empty($mdlpaket)) {
        //             foreach ($mdlpaket as $mdlp) {
        //                 $mdlpaketdata[] = $MdlModel->find($mdlp['mdlid']);
        //             }
        //         } else {
        //             $mdlpaketdata   = '';
        //         }
        //     }
        // }
        // $mdldata['paket']   = $paketdata;
        // $mdldata['mdl']     = $mdlpaketdata;
        // dd($mdldata);

        // List MDL Paket
        // $mdls   = [];

        // List MDL In Paket
        // $mdls = array();
        // foreach ($pakets as $paket) {
        //     $mdls[$paket['id']] = $MdlModel->where('paketid', $paket['id'])->find();
        // }

        // Search Engine
        // if (isset($input['search']) && !empty($input['search'])) {
        //     $pakets     = $PaketModel->like('name', $input['search'])->paginate($perpage, 'paket');
        // } else {
        //     $pakets     = $PaketModel->paginate($perpage, 'paket');
        // }

        // Parsing Data to View
        $data                   =   $this->data;
        $data['title']          =   "MDL";
        $data['description']    =   "Daftar MDL yang tersedia";
        $data['pakets']         =   $pakets;
        $data['autoparent']     =   $autoparent;
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
        $LogModel           = new LogModel();

        // Get Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama Paket/Kategori',
                'rules'     => 'required|is_unique[paket.name]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save Data
        $paket = [
            'name'          => $input['name'],
        ];

        // Insert Data Paket
        $PaketModel->insert($paket);

        // Recording Log
        $paketid = $PaketModel->getInsertID();
        $Paket = $PaketModel->find($paketid);
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan paket MDL ' . $Paket['name']]);

        // Return
        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function update($id)
    {
        // Calling Models
        $PaketModel = new PaketModel();
        $LogModel = new LogModel();

        // Get Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama Paket/Kategori',
                'rules'     => 'required|is_unique[paket.name,paket.id,' . $id . ']',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Recording Log
        $Paket = $PaketModel->find($id);
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Merubah paket MDL ' . $Paket['name'] . ' menjadi ' . $input['name']]);

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
        $LogModel           = new LogModel();

        // Populating Data
        $Paket              = $PaketModel->find($id);
        $mdls               = $MdlModel->where('paketid', $id)->find();

        // Record Log
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus paket MDL ' . $Paket['name'] . ' dan seluruh item MDL di dalamnya.']);

        // Delete MDL
        foreach ($mdls as $mdl) {
            $MdlModel->delete($mdl['id']);
        }

        // Delete Data
        $PaketModel->delete($id);

        // Return
        return redirect()->back()->with('error', 'Data Telah Dihapuskan');
    }

    public function createmdl($id)
    {
        // Calling Models
        $MdlModel = new MdlModel();
        $PaketModel = new PaketModel();
        $LogModel = new LogModel();

        // Get Data
        $input = $this->request->getPost();
        $str = $input['price'];

        function toInt($str)
        {
            return (int)preg_replace("/\..+$/i", "", preg_replace("/[^0-9\.]/i", "", $str));
        }

        // Validation
        $rules = [
            'length'      => [
                'label'     => 'Panjang',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
            'width'      => [
                'label'     => 'Lebar',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
            'height'      => [
                'label'     => 'Panjang',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $mdl = [
            'name'          => $input['name'],
            'length'        => $input['length'],
            'width'         => $input['width'],
            'height'        => $input['height'],
            'volume'        => $input['length'],
            'denomination'  => $input['denomination'],
            'keterangan'    => $input['keterangan'],
            'price'         => toInt($str),
            'paketid'       => $id,
        ];

        // Save Data MDL
        $MdlModel->save($mdl);

        // Record Log
        $Paket = $PaketModel->find($id);
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan item ' . $input['name'] . ' kedalam paket MDL ' . $Paket['name']]);

        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function updatemdl($id)
    {
        // Populating Data
        $MdlModel = new MdlModel;

        // Get Data
        $input = $this->request->getPost();
        $str = $input['price'];

        function strupdate($str)
        {
            return (int)preg_replace("/\..+$/i", "", preg_replace("/[^0-9\.]/i", "", $str));
        }

        // Validation
        $rules = [
            'length'      => [
                'label'     => 'Panjang',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
            'width'      => [
                'label'     => 'Lebar',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
            'height'      => [
                'label'     => 'Panjang',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka desimal (koma "," desimal menggunakan titik ".").',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $mdlup = [
            'id'            => $id,
            'name'          => $input['name'],
            'denomination'  => $input['denomination'],
            'length'        => $input['length'],
            'width'         => $input['width'],
            'height'        => $input['height'],
            'volume'        => $input['length'],
            'keterangan'    => $input['keterangan'],
            'price'         => strupdate($str),
            'paketid'       => $input['paketid'],
        ];

        // Save Data MDL
        $MdlModel->save($mdlup);

        // Return
        return redirect()->back()->with('message', 'Data Behasil Diperbaharui');
    }

    public function deletemdl($id)
    {
        // Populating Data
        $MdlModel = new MdlModel;

        // Delete Data MDL
        $MdlModel->delete($id);

        // Return
        return redirect()->back()->with('error', 'Data Telah Dihapuskan');
    }

	public function importExcel($id)
    {
        // Populating Data
        $MdlModel = new MdlModel();

        // Get Data
        $file_excel = $this->request->getFile('fileexcel');
        $ext = $file_excel->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($file_excel);
        dd($ext);

        $data = $spreadsheet->getActiveSheet()->toArray();
        foreach($data as $x => $row) {
            if ($x == 0) {
                continue;
            }

            // Define Row
            $mdlid          = $row[0];
            $mdlname        = $row[1];
            $length         = $row[2];
            $width          = $row[3];
            $height         = $row[4];
            $volume         = $row[5];
            if ($row[6] == 'Unit') {
                $denomination     = 1;
            } elseif ($row[6] == 'Meter Lari') {
                $denomination     = 2;
            } elseif ($row[6] == 'Meter Persegi') {
                $denomination     = 3;
            } elseif ($row[6] == 'Set') {
                $denomination     = 4;
            }
            $keterangan     = $row[7];
            $price          = $row[8];

            $datamdl = [
                'name'          => $mdlname,
                'length'        => $length,
                'width'         => $width,
                'height'        => $height,
                'volume'        => $volume,
                'denomination'  => $denomination,
                'keterangan'    => $keterangan,
                'price'         => $price,
                'paketid'       => $id,
            ];

            $MdlModel->insert($datamdl);
        }
        unlink(FCPATH . '/img/spk/' . $file_excel);
        
        return redirect()->back()->with('message', 'Berhasil Import Data');
    }

    public function deleteallmdl($id)
    {
        // Populating Data
        $MdlModel   = new MdlModel();

        // Delete Data MDL
        $MdlModel->where('paketid', $id)->delete();

        // Return
        return redirect()->back()->with('error', 'Data Telah Dihapuskan');
    }
}
