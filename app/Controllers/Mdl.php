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
        if ($this->data['authorize']->hasPermission('admin.mdl.read', $this->data['uid'])) {
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
            // $parents        = $PaketModel->where('parentid', 0)->paginate($perpage, 'parent');

            // Search Engine
            if (isset($input['search']) && !empty($input['search'])) {
                $parents     = $PaketModel->like('name', $input['search'])->orderBy('ordering', 'ASC')->find();
            } else {
                $parents     = $PaketModel->where('parentid', 0)->orderBy('ordering', 'ASC')->paginate($perpage, 'parent');
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
            $data['title']          =   "MDL";
            $data['description']    =   "Daftar MDL yang tersedia";
            $data['mdldata']        =   $mdldata;
            $data['parents']        =   $parents;
            $data['countparents']   =   count($parents);
            $data['autoparents']    =   $autoparents;
            $data['autopakets']     =   $autopakets;
            $data['input']          =   $input;
            $data['pager']          =   $PaketModel->pager;

            // Return
            return view('mdl', $data);
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
            // Calling Models
            $PaketModel         = new PaketModel();
            $LogModel           = new LogModel();

            // Get Data
            $input = $this->request->getPost();

            // Validation
            // $rules = [
            //     'name'      => [
            //         'label'     => 'Nama Paket/Kategori',
            //         'rules'     => 'required|is_unique[paket.name]',
            //         'errors'    => [
            //             'required'      => '{field} wajib diisi.',
            //             'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
            //         ],
            //     ],
            // ];
            // if (!$this->validate($rules)) {
            //     return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            // }

            // Save Data
            $lastOrder      = $PaketModel->where('parentid', $input['parent'])->orderBy('ordering', 'DESC')->first();
            if (!empty($lastOrder)) {
                $order = $lastOrder['ordering'] + 1;
            } else {
                $order = '1';
            }

            $paket = [
                'name'          => $input['name'],
                'parentid'      => $input['parent'],
                'ordering'      => $order
            ];

            // Insert Data Paket
            $PaketModel->insert($paket);

            // Recording Log
            $paketid    = $PaketModel->getInsertID();
            $Paket      = $PaketModel->find($paketid);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan paket MDL ' . $Paket['name']]);

            // Return
            return redirect()->back()->with('message', "Data Tersimpan");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.edit', $this->data['uid'])) {
            // Calling Models
            $PaketModel = new PaketModel();
            $LogModel = new LogModel();

            // Get Data
            $input = $this->request->getPost();

            // Validation
            // $rules = [
            //     'name'      => [
            //         'label'     => 'Nama Paket/Kategori',
            //         'rules'     => 'required|is_unique[paket.name,paket.id,' . $id . ']',
            //         'errors'    => [
            //             'required'      => '{field} wajib diisi.',
            //             'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
            //         ],
            //     ],
            // ];
            // if (!$this->validate($rules)) {
            //     return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            // }

            // Recording Log
            $Paket = $PaketModel->find($id);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Merubah paket MDL ' . $Paket['name'] . ' menjadi ' . $input['name']]);

            // Input Data
            $paketup = [
                'id'            => $id,
                'name'          => $input['name'],
            ];
            if (isset($input['parent'])) {
                $paketup['parentid'] = $input['parent'];
            }

            // Save Data Paket
            $PaketModel->save($paketup);

            // Return
            return redirect()->back()->with('message', 'Data Behasil Diperbaharui');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.delete', $this->data['uid'])) {
            // Calling Models
            $PaketModel         = new PaketModel();
            $MdlModel           = new MdlModel();
            $MdlPaketModel      = new MdlPaketModel();
            $LogModel           = new LogModel();

            // Populating Data
            $Paket              = $PaketModel->find($id);

            // Record Log
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus paket MDL ' . $Paket['name'] . ' dan seluruh item MDL di dalamnya.']);

            // Delete MDL
            $MdlPaketModel->where('paketid', $id)->delete();
            // foreach ($mdls as $mdl) {
            //     $MdlPaketModel->delete($mdl['id']);
            // }

            // Delete Data
            $PaketModel->delete($id);

            // Return
            return redirect()->back()->with('error', 'Data Telah Dihapuskan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function createmdl($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) {
            // Calling Models
            $MdlModel       = new MdlModel();
            $MdlPaketModel  = new MdlPaketModel();
            $PaketModel     = new PaketModel();
            $LogModel       = new LogModel();

            // Get Data
            $input = $this->request->getPost();
            $str = $input['price'];

            function toInt($str)
            {
                return (int)preg_replace("/\..+$/i", "", preg_replace("/[^0-9\.]/i", "", $str));
            }

            // Validation
            $rules = [
                // 'name'      => [
                //     'label'     => 'Nama',
                //     'rules'     => 'required|is_unique[mdl.name,mdl.id,' . $id . ']',
                //     'errors'    => [
                //         'required'      => '{field} wajib diisi.',
                //         'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                //     ],
                // ],
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

            // Save Data MDL
            $mdl = [
                'name'          => $input['name'],
                'length'        => $input['length'],
                'width'         => $input['width'],
                'height'        => $input['height'],
                'volume'        => $input['length'],
                'denomination'  => $input['denomination'],
                'keterangan'    => $input['keterangan'],
                'photo'         => $input['photo'],
                'price'         => toInt($str),
            ];
            if (isset($input['photo'])) {
                $data['photo'] = $input['photo'];
            }
            $MdlModel->save($mdl);

            // Save Data MDL Paket
            $mdlid      = $MdlModel->getInsertID();
            $lastOrder  = $MdlPaketModel->where('paketid', $id)->orderBy('ordering', 'DESC')->first();
            if (!empty($lastOrder)) {
                $order  = $lastOrder['ordering'] + 1;
            } else {
                $order  = '1';
            }
            $datamdlpaket   = [
                'mdlid'     => $mdlid,
                'paketid'   => $id,
                'ordering'  => $order,
            ];
            $MdlPaketModel->insert($datamdlpaket);

            // Record Log
            $Paket = $PaketModel->find($id);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan item ' . $input['name'] . ' kedalam paket MDL ' . $Paket['name']]);

            return redirect()->back()->with('message', "Data Tersimpan");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function updatemdl($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.edit', $this->data['uid'])) {
            // Populating Data
            $MdlModel   = new MdlModel();
            $LogModel   = new LogModel();

            // Get Data
            $input = $this->request->getPost();
            $mdls  = $MdlModel->find($id);
            $str = $input['price'];

            function strupdate($str)
            {
                return (int)preg_replace("/\..+$/i", "", preg_replace("/[^0-9\.]/i", "", $str));
            }

            // Validation
            // if ($input['name'] === $mdls['name']) {
            //     $is_unique =  '';
            // } else {
            //     $is_unique =  'is_unique[mdl.name]';
            // }

            $rules = [
                // 'name'      => [
                //     'label'     => 'Nama',
                //     'rules'     => 'required|'.$is_unique,
                //     'errors'    => [
                //         'required'      => '{field} wajib diisi.',
                //         'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                //     ],
                // ],
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
                'photo'         => $input['photo'],
                'price'         => strupdate($str),
            ];

            // Save Data MDL
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah MDL ' . $input['name']]);
            $MdlModel->save($mdlup);

            // Return
            return redirect()->back()->with('message', 'Data Behasil Diperbaharui');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deletemdl($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.delete', $this->data['uid'])) {
            // Calling Models
            $MdlModel           = new MdlModel();
            $MdlPaketModel      = new MdlPaketModel();
            $LogModel           = new LogModel();

            // initialize
            $input              = $this->request->getpost();
            $mdl                = $MdlModel->find($id);

            // Delete Data MDL
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus MDL ' . $mdl['name']]);
            $MdlPaketModel->where('paketid', $input['paketid'])->where('mdlid', $id)->delete();

            // Return
            return redirect()->back()->with('error', 'Data Telah Dihapuskan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deletemdluncategories($id)
    {
        // Calling Models
        $MdlModel           = new MdlModel();
        $LogModel           = new LogModel();

        // initialize
        $mdl                = $MdlModel->find($id);

        // Delete Data MDL
        $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus MDL ' . $mdl['name']]);
        $MdlModel->delete($id);

        // Return
        return redirect()->back()->with('error', 'Data Telah Dihapuskan');
    }

    public function importExcel($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) {
            // Calling Models
            $LogModel       = new LogModel();
            $MdlModel       = new MdlModel();
            $MdlPaketModel  = new MdlPaketModel();

            // Populating Data
            $file_excel = $this->request->getFile('fileexcel');
            $ext = $file_excel->getClientExtension();
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($file_excel);

            $data = $spreadsheet->getActiveSheet()->toArray();
            foreach ($data as $x => $row) {
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
                ];

                $MdlModel->insert($datamdl);

                // Save Data MDL Paket
                $mdlid      = $MdlModel->getInsertID();
                $lastOrder  = $MdlPaketModel->where('paketid', $id)->orderBy('ordering', 'DESC')->first();
                if (!empty($lastOrder)) {
                    $order  = $lastOrder['ordering'] + 1;
                } else {
                    $order  = '1';
                }
                $datamdlpaket   = [
                    'mdlid'     => $mdlid,
                    'paketid'   => $id,
                    'ordering'  => $order
                ];
                $MdlPaketModel->insert($datamdlpaket);
            }
            unlink(FCPATH . '/img/spk/' . $file_excel);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan Import MDL']);
            return redirect()->back()->with('message', 'Berhasil Import Data');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deleteallmdl($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.delete', $this->data['uid'])) {
            // Populating Data
            $PaketModel     = new PaketModel();
            $MdlPaketModel  = new MdlPaketModel();
            $LogModel       = new LogModel();
            $paket          = $PaketModel->find($id);

            // Delete Data MDL
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menghapus Mdl paket '.$paket['name']]);
            $MdlPaketModel->where('paketid', $id)->delete();

            // Return
            return redirect()->back()->with('error', 'Data Telah Dihapuskan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function orderingpaket()
    {
        // Calling Models
        $PaketModel     = new PaketModel();
        $MdlPaketModel  = new MdlPaketModel();

        // Populating Data
        $parents        = $PaketModel->where('parentid', 0)->find();

        $count = 1;
        foreach ($parents as $parent) {
            $orderingparent = [
                'id'        => $parent['id'],
                'ordering'  => $count++,
            ];

            $PaketModel->save($orderingparent);

            $pakets     = $PaketModel->where('parentid', $parent['id'])->find();

            $order = 1;
            foreach ($pakets as $paket) {
                $orderingpaket = [
                    'id'        => $paket['id'],
                    'ordering'  => $order++,
                ];
    
                $PaketModel->save($orderingpaket);

                $mdlpakets  = $MdlPaketModel->where('paketid', $paket['id'])->find();

                $mdl = 1;
                foreach ($mdlpakets as $mdlpak) {
                    $orderingmdl = [
                        'mdlid'     => $mdlpak['mdlid'],
                        'paketid'   => $mdlpak['paketid'],
                        'ordering'  => $mdl++,
                    ];
        
                    $MdlPaketModel->save($orderingmdl);
                }
            }
        }

        $MdlPaketModel->where('ordering', null)->delete();

        // Return
        return redirect()->back()->with('message', 'Data Telah Diurutkan');
    }

    public function reorderingparent()
    {
        // Calling Models
        $PaketModel     = new PaketModel();
        $MdlPaketModel  = new MdlPaketModel();

        // Populating Data
        $input          = $this->request->getPOST();

        $parent         = $PaketModel->find($input['id']);

        //Processing Data
        if ($parent['ordering'] < $input['order']) {
            $upperParent = $PaketModel->where('parentid', '0')->where('ordering <=', $input['order'])->where('ordering >', $parent['ordering'])->find();
            foreach ($upperParent as $upper) {
                $upperSubmit = [
                    'id'        => $upper['id'],
                    'ordering'  => $upper['ordering'] - 1
                ];
                $PaketModel->save($upperSubmit);
            }
        } else {
            $lowerParent = $PaketModel->where('parentid', '0')->where('ordering >=', $input['order'])->where('ordering <', $parent['ordering'])->find();
            foreach ($lowerParent as $lower) {
                $lowerSubmit = [
                    'id'        => $lower['id'],
                    'ordering'  => $lower['ordering'] + 1
                ];
                $PaketModel->save($lowerSubmit);
            }
        }
        $currentSubmit = [
            'id'        => $input['id'],
            'ordering'  => $input['order']
        ];
        $PaketModel->save($currentSubmit);

        die(json_encode($currentSubmit));
    }

    public function reorderingpaket()
    {
        // Calling Models
        $PaketModel     = new PaketModel();
        $MdlPaketModel  = new MdlPaketModel();

        // Populating Data
        $input          = $this->request->getPOST();

        $paket          = $PaketModel->find($input['id']);
        // $mdl            = $MdlPaketModel->where('id', $input['id'])->where('mdlid', $input['mdlid'])->first();

        //Processing Data
        if ($paket['ordering'] < $input['order']) {
            $upperPaket = $PaketModel->where('parentid', $input['parent'])->where('ordering <=', $input['order'])->where('ordering >', $paket['ordering'])->find();
            foreach ($upperPaket as $upper) {
                $upperSubmit = [
                    'id'        => $upper['id'],
                    'ordering'  => $upper['ordering'] - 1
                ];
                $PaketModel->save($upperSubmit);
            }
        } else {
            $lowerPaket = $PaketModel->where('parentid', $input['parent'])->where('ordering >=', $input['order'])->where('ordering <', $paket['ordering'])->find();
            foreach ($lowerPaket as $lower) {
                $lowerSubmit = [
                    'id'        => $lower['id'],
                    'ordering'  => $lower['ordering'] + 1
                ];
                $PaketModel->save($lowerSubmit);
            }
        }
        $currentSubmit = [
            'id'        => $input['id'],
            'parentid'  => $input['parent'],
            'ordering'  => $input['order']
        ];
        $PaketModel->save($currentSubmit);

        die(json_encode($currentSubmit));
    }

    public function reorderingmdl()
    {
        // Calling Models
        $MdlPaketModel  = new MdlPaketModel();

        // Populating Data
        $input          = $this->request->getPOST();

        $mdl            = $MdlPaketModel->where('mdlid', $input['id'])->where('paketid', $input['paket'])->first();

        //Processing Data
        if ($mdl['ordering'] < $input['order']) {
            $upperMdl = $MdlPaketModel->where('paketid', $input['paket'])->where('ordering <=', $input['order'])->where('ordering >', $mdl['ordering'])->find();
            foreach ($upperMdl as $upper) {
                $upperOrder = $upper['ordering'] - 1;
                $MdlPaketModel->where('paketid', $input['paket'])->where('mdlid', $upper['mdlid'])->delete();
                $upperSubmit = [
                    'mdlid'     => $upper['mdlid'],
                    'paketid'   => $input['paket'],
                    'ordering'  => $upperOrder
                ];
                $MdlPaketModel->save($upperSubmit);
            }
        } else {
            $lowerMdl = $MdlPaketModel->where('paketid', $input['paket'])->where('ordering >=', $input['order'])->where('ordering <', $mdl['ordering'])->find();
            foreach ($lowerMdl as $lower) {
                $lowerOrder = $lower['ordering'] + 1;
                $MdlPaketModel->where('paketid', $input['paket'])->where('mdlid', $lower['mdlid'])->delete();
                $lowerSubmit = [
                    'mdlid'     => $lower['mdlid'],
                    'paketid'   => $input['paket'],
                    'ordering'  => $lowerOrder
                ];
                $MdlPaketModel->save($lowerSubmit);
            }
        }
        $MdlPaketModel->where('paketid', $input['paket'])->where('mdlid', $input['id'])->delete();
        $currentSubmit = [
            'mdlid'     => $input['id'],
            'paketid'   => $input['paket'],
            'ordering'  => $input['order']
        ];
        $MdlPaketModel->save($currentSubmit);

        die(json_encode($currentSubmit));
    }
}
