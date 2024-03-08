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
                $parents     = $PaketModel->like('name', $input['search'])->find();
            } else {
                $parents     = $PaketModel->where('parentid', 0)->paginate($perpage, 'parent');
            }

            // List Paket Auto Complete
            $autopakets     = $PaketModel->where('parentid !=', 0)->find();

            // List Paket
            $mdldata        = [];
            $mdlid          = [];
            if (!empty($parents)) {
                foreach ($parents as $parent) {
                    $mdldata[$parent['id']]['name']     = $parent['name'];
                    $paketdata                          = $PaketModel->where('parentid', $parent['id'])->find();
                    
                    if (!empty($paketdata)) {
                        foreach ($paketdata as $paket) {
                            $mdldata[$parent['id']]['paket'][$paket['id']]['id']        = $paket['id'];
                            $mdldata[$parent['id']]['paket'][$paket['id']]['parentid']  = $paket['parentid'];
                            $mdldata[$parent['id']]['paket'][$paket['id']]['name']      = $paket['name'];

                            // List MDL Paket
                            $mdlpaket   = $MdlPaketModel->where('paketid', $paket['id'])->find();
        
                            // List MDL
                            if (!empty($mdlpaket)) {
                                foreach ($mdlpaket as $mdlp) {
                                    $mdldata[$parent['id']]['paket'][$paket['id']]['mdl'][$mdlp['mdlid']]   = $MdlModel->find($mdlp['mdlid']);
                                    $mdlid[]                                                                = $mdlp['mdlid'];

                                    // List MDL Uncategories
                                    // $mdldata['mdluncate']                                                   = $MdlModel->where('id !=', $mdlp['mdlid'])->find();
                                }
                            } else {
                                $mdldata[$parent['id']]['paket'][$paket['id']]['mdl']                       = [];
                                $mdlid[]                                                                    = '';

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
                'text'  => $mdl['name']
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
        $MdlPaketModel = new MdlPaketModel();

        // Populating Data
        $input = $this->request->getPOST();
        $submit = [
            'mdlid'     => $input['mdlid'],
            'paketid'   => $input['paketid']
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
                'parentid'      => $input['parent'],
            ];

            // Insert Data Paket
            $PaketModel->insert($paket);

            // Recording Log
            $paketid = $PaketModel->getInsertID();
            $Paket = $PaketModel->find($paketid);
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
                'name'      => [
                    'label'     => 'Nama',
                    'rules'     => 'required|is_unique[mdl.name,mdl.id,' . $id . ']',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                    ],
                ],
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
                'photo'         => $input['keterangan'],
                'price'         => toInt($str),
            ];
            if (isset($input['photo'])) {
                $data['photo'] = $input['photo'];
            }
            $MdlModel->save($mdl);

            // Save Data MDL Paket
            $mdlid = $MdlModel->getInsertID();
            $datamdlpaket   = [
                'mdlid'     => $mdlid,
                'paketid'   => $id,
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
            $MdlModel = new MdlModel();

            // Get Data
            $input = $this->request->getPost();
            $str = $input['price'];

            function strupdate($str)
            {
                return (int)preg_replace("/\..+$/i", "", preg_replace("/[^0-9\.]/i", "", $str));
            }

            // Validation
            $rules = [
                'name'      => [
                    'label'     => 'Nama',
                    'rules'     => 'required|is_unique[mdl.name,mdl.id,' . $id . ']',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                    ],
                ],
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
            ];

            // Save Data MDL
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

            // initialize
            $input              = $this->request->getpost();

            // Populating Data
            if (!empty($input['paketid'])) {
                $mdlpaketdata   = $MdlPaketModel->where('paketid', $input['paketid'])->where('mdlid', $id)->delete();
            }

            // Delete Data MDL
            $MdlModel->delete($id);

            // Return
            return redirect()->back()->with('error', 'Data Telah Dihapuskan');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

	public function importExcel($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) {
        // Calling Models
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
            ];

            $MdlModel->insert($datamdl);

            // Save Data MDL Paket
            $mdlid = $MdlModel->getInsertID();
            $datamdlpaket   = [
                'mdlid'     => $mdlid,
                'paketid'   => $id,
            ];
            $MdlPaketModel->insert($datamdlpaket);

        }
        unlink(FCPATH . '/img/spk/' . $file_excel);
        
        return redirect()->back()->with('message', 'Berhasil Import Data');

        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function deleteallmdl($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.delete', $this->data['uid'])) {
            // Populating Data
            $MdlPaketModel   = new MdlPaketModel();

            // Delete Data MDL
            $MdlPaketModel->where('paketid', $id)->delete();

            // Return
            return redirect()->back()->with('error', 'Data Telah Dihapuskan');

        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
