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

        // Return
        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function update($id)
    {
        // Calling Models
        $PaketModel = new PaketModel();

        // Get Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama Paket/Kategori',
                'rules'     => 'required|is_unique[paket.name,paket.id,'.$id.']',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama',
                'rules'     => 'required|is_unique[mdl.name]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
            'price'     => [
                'label'     => 'Harga',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save Data
        if ($input['denomination'] === "2") {

            // Validation
            $rules = [
                'length'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'width'      => [
                    'label'     => 'Lebar',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'height'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
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
                'price'         => $input['price'],
                'paketid'       => $id,
            ];

            // Save Data MDL
            $MdlModel->save($mdl);

        } elseif ($input['denomination'] === "3") {
            
            // Validation
            $rules = [
                'length'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'width'      => [
                    'label'     => 'Lebar',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'height'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
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
                'volume'        => (Int)$input['length'] * $input['height'],
                'denomination'  => $input['denomination'],
                'price'         => $input['price'],
                'paketid'       => $id,
            ];

            // Save Data MDL
            $MdlModel->save($mdl);

        } else {
            $mdl = [
                'name'          => $input['name'],
                'length'        => NULL,
                'width'         => NULL,
                'height'        => NULL,
                'volume'        => '1',
                'denomination'  => $input['denomination'],
                'price'         => $input['price'],
                'paketid'       => $id,
            ];

            // Save Data MDL
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

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama',
                'rules'     => 'required|is_unique[mdl.name,mdl.id,'.$id.']',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
            'price'     => [
                'label'     => 'Harga',
                'rules'     => 'required|decimal',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'decimal'       => '{field} hanya boleh berisi angka.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Filter Condition Meters Or Unit
        if ($input['denomination'] === "2") {
            
            // Validation
            $rules = [
                'length'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'width'      => [
                    'label'     => 'Lebar',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'height'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
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
                'price'         => $input['price'],
                'paketid'       => $input['paketid'],
            ];

            // Save Data MDL
            $MdlModel->save($mdlup);
        } elseif ($input['denomination'] === "3") {
            
            // Validation
            $rules = [
                'length'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'width'      => [
                    'label'     => 'Lebar',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
                    ],
                ],
                'height'      => [
                    'label'     => 'Panjang',
                    'rules'     => 'required|decimal',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'decimal'       => '{field} hanya boleh berisi angka.',
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
                'volume'        => (Int)$input['length'] * (Int)$input['height'],
                'price'         => $input['price'],
                'paketid'       => $input['paketid'],
            ];

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
                'volume'        => '1',
                'price'         => $input['price'],
                'paketid'       => $input['paketid'],
            ];
            
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
