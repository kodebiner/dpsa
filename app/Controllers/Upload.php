<?php

namespace App\Controllers;

use App\Models\BastModel;
use App\Models\DesignModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\LogModel;

class Upload extends BaseController
{

    protected $data;

    public function designcreate()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/macbinary,application/mac-binary,application/octet-stream,application/x-binary,application/x-macbinary,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/design/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removedesigncreate()
    {
        // Removing File
        $input = $this->request->getPost('submitted');
        unlink(FCPATH . 'img/design/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function revisi()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf]',
        ];

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/revisi/', $filename);

            // Removing uploaded if it's not the same filename
            if ($filename != $truename . '.pdf') {
                unlink(FCPATH . '/img/revisi/' . $filename);
            }

            // Getting True Filename
            $returnFile = $truename . '.pdf';

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    // public function saverevisi($id)
    // {
    //     $DesignModel    = new DesignModel();
    //     $LogModel       = new LogModel();
    //     $ProjectModel   = new ProjectModel();

    //     $input = $this->request->getPost();
    //     // Design Data
    //     if (isset($input['revisi'])) {
    //         $project = $ProjectModel->find($id);
    //         $design = $DesignModel->where('projectid', $id)->first();
    //         if (!empty($design)) {
    //             if(!empty($design['revision'])){
    //                 unlink(FCPATH . '/img/revisi/' . $design['revision']);
    //             }
    //             $datadesign = [
    //                 'id'            => $design['id'],
    //                 'projectid'     => $id,
    //                 'revision'      => $input['revisi'],
    //                 'status'        => 1,
    //             ];
    //             $DesignModel->insert($datadesign);
    //             $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload revisi'.$project['name']]);
    //         } else {
    //             $datadesign = [
    //                 'projectid'     => $id,
    //                 'revision'      => $input['revisi'],
    //                 'status'        => 1,
    //             ];
    //             $DesignModel->save($datadesign);
    //             $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah revisi'.$project['name']]);
    //         }
    //     }

    //     return redirect()->back()->with('message', 'Revisi terkirim');
    // }

    public function removerevisi()
    {
        // Removing File
        $input = $this->request->getPost('revisi');
        unlink(FCPATH . 'img/revisi/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function spk()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/spk/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function savespk($id)
    {
        $ProjectModel   = new ProjectModel();
        $LogModel       = new LogModel();
        $input          = $this->request->getPost();
        $project        = $ProjectModel->find($id);

        // Validation Rules
        $rules = [
            'spk' => [
                'label'  => 'SPK',
                'rules'  => 'required',
                'errors' => [
                    'required'      => 'File {field} Belum Di Unggah',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Design Data
        if (isset($input['spk'])) {
            $spk = $ProjectModel->find($id);
            if (empty($spk)) {
                $dataspk = [
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                    'inv1'          => date("Y-m-d H:i:s"),
                ];
                $ProjectModel->save($dataspk);
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload SPK '.$project['name']]);
            } else {
                if(!empty($spk['spk'])){
                    unlink(FCPATH . '/img/spk/' . $spk['spk']);
                }
                $dataspk = [
                    'id'            => $spk['id'],
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                    'inv1'          => date("Y-m-d H:i:s"),
                ];
                $ProjectModel->save($dataspk);
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Malakukan upload SPK '.$project['name']]);
            }
        }

        return redirect()->back()->with('message', 'SPK terkirim');
    }

    public function removespk()
    {
        // Removing File
        $input = $this->request->getPost('spk');
        unlink(FCPATH . 'img/spk/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function mdl($id)
    {
        // Calling Models
        $LogModel       = new LogModel();
        $MdlModel       = new MdlModel();
        $MdlPaketModel  = new MdlPaketModel();
        $input          = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]',
        ];

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        $ext = $input->getClientExtension();
        if ($ext == 'xls') {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
        $spreadsheet = $render->load($input);

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
            $idmdl = $MdlModel->getInsertID();
            $datamdlpaket   = [
                'mdlid'     => $idmdl,
                'paketid'   => $id,
            ];
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Menambahkan Mdl']);
            $MdlPaketModel->insert($datamdlpaket);
        }

        die('Berhasil Import Data MDL');
    }

    public function layout()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Check Directory
            if(!file_exists('/var/www/html/dpsa/public/design')){
                mkdir('/var/www/html/dpsa/public/design', 0777, true);
            }
            
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/design/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removelayout()
    {
        // Removing File
        $input = $this->request->getPost('design');
        unlink(FCPATH . 'img/design/' . $input);

        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function photomdl()
    {
        $image      = \Config\Services::image();
        $input      = $this->request->getFile('uploads');
        $ext        = $input->getClientExtension();

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,image/png,image/jpeg,image/pjpeg]',
        ];

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/mdl/', $filename);

            // Removing uploaded if it's not the same filename
            if ($filename != $truename.'.'. $ext) {
                unlink(FCPATH . '/img/mdl/' . $filename);
            }

            // Getting True Filename
            $returnFile = $truename.'.'. $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removephotomdl()
    {
        // Removing File
        $input = $this->request->getPost('photo');
        unlink(FCPATH . 'img/mdl/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function sertrim($id)
    {
        $image          = \Config\Services::image();
        $validation     = \Config\Services::validation();
        $BastModel      = new BastModel();
        $ProjectModel   = new ProjectModel();
        $LogModel       = new LogModel();
        $input          = $this->request->getFile('uploads');
        $project        = $ProjectModel->find($id);

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            // $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);

            if (!empty($input)) {
                function random_string($filename)
                {
                    $key = '';
                    $keys = array_merge(range(0, 9), range('a', 'z'));

                    for ($i = 0; $i < $filename; $i++) {
                        $key .= $keys[array_rand($keys)];
                    }

                    return $key;
                }
                $truename = random_string(20);
            }

            $input->move(FCPATH . '/img/sertrim/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $sertrim = [
                'projectid' => $id,
                'file'      => $returnFile,
                'status'    => 0,
            ];
            $BastModel->save($sertrim);
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload Sertrim '.$project['name']]);
            $idBast = $BastModel->getInsertID();

            $inv2date = [
                'id'    => $id,
                'inv2'  => date("Y-m-d H:i:s"),
            ];
            $ProjectModel->save($inv2date);

            $retunarr = [
                'id'    => $idBast,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($retunarr));
        }
    }

    public function bast($id)
    {
        $image          = \Config\Services::image();
        $validation     = \Config\Services::validation();
        $BastModel      = new BastModel();
        $ProjectModel   = new ProjectModel();
        $LogModel       = new LogModel();
        $input          = $this->request->getFile('uploads');
        $project        = $ProjectModel->find($id);


        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();

            function random_string($filename)
            {
                $key = '';
                $keys = array_merge(range(0, 9), range('a', 'z'));

                for ($i = 0; $i < $filename; $i++) {
                    $key .= $keys[array_rand($keys)];
                }

                return $key;
            }
            $truename = random_string(20);
            $input->move(FCPATH . '/img/bast/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $bastId="";
            if (!empty($returnFile)) {
                $bast = $BastModel->where('projectid',$id)->where('status','1')->first();
                if (empty($bast)) {
                    // unlink(FCPATH . '/img/bast/' . $returnFile);
                    $databast = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $BastModel->save($databast);
                    $bastId = $BastModel->getInsertID();
                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload Bast '.$project['name']]);
                } else {
                    // $filebast = FCPATH . '/img/bast/' . $bast['file'];
                    // if (file_exists(FCPATH . '/img/bast/' . $bast['file'])) { 
                    //     unlink(FCPATH . '/img/bast/' . $bast['file']);
                    // }
                    if (!empty($bast['file'])) { 
                        unlink(FCPATH . '/img/bast/' . $bast['file']);
                    }
                    // unlink(FCPATH . '/img/bast/' . $bast['file']);
                    $databast = [
                        'id'            => $bast['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $BastModel->save($databast);
                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah Bast '.$project['name']]);
                    $bastId = $bast['id'];
                }
            }

            $inv3date = [
                'id'    => $id,
                'inv3'  => date("Y-m-d H:i:s"),
            ];
            $ProjectModel->save($inv3date);
           
            $returnBast = [
                'id'    => $bastId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returnBast));
        }
    }

    public function buktipembayaran()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/macbinary,application/mac-binary,application/octet-stream,application/x-binary,application/x-macbinary,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/bukti/pembayaran/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removebuktipembayaran()
    {
        // Removing File
        $input = $this->request->getPost('buktipembayaran');
        unlink(FCPATH . 'img/bukti/pembayaran/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function buktipengiriman()
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/macbinary,application/mac-binary,application/octet-stream,application/x-binary,application/x-macbinary,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = $input->getClientExtension();

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/bukti/pengiriman/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removebuktipengiriman()
    {
        // Removing File
        $input = $this->request->getPost('buktipengiriman');
        unlink(FCPATH . 'img/bukti/pengiriman/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }
}
