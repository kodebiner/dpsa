<?php

namespace App\Controllers;

use App\Models\BastModel;
use App\Models\DesignModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;

class Upload extends BaseController
{
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

    public function saverevisi($id)
    {
        $DesignModel = new DesignModel();

        $input = $this->request->getPost();
        // Design Data
        if (isset($input['revisi'])) {
            $design = $DesignModel->where('projectid', $id)->first();
            if (empty($design)) {
                unlink(FCPATH . '/img/revisi/' . $design['revision']);
                $datadesign = [
                    'projectid'     => $id,
                    'revision'     => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->insert($datadesign);
            } else {
                $datadesign = [
                    'id'            => $design['id'],
                    'projectid'     => $id,
                    'revision'      => $input['revisi'],
                    'status'        => 1,
                ];
                $DesignModel->save($datadesign);
            }
        }
        return redirect()->back()->with('message', 'Revisi terkirim');
    }

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
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf,application/octet-stream]',
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
        $ProjectModel = new ProjectModel();

        $input = $this->request->getPost();

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
                unlink(FCPATH . '/img/spk/' . $spk['spk']);
                $dataspk = [
                    'projectid'     => $id,
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                ];
                $ProjectModel->insert($dataspk);
            } else {
                $dataspk = [
                    'id'            => $spk['id'],
                    'projectid'     => $id,
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                ];
                $ProjectModel->save($dataspk);
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

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function photomdl()
    {
        $image      = \Config\Services::image();
        $input = $this->request->getFile('uploads');

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|is_image[uploads]|max_size[uploads,2048]|ext_in[uploads,png,jpg,jpeg]',
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
            if ($filename != $truename . '.jpg') {
                unlink(FCPATH . '/img/mdl/' . $filename);
            }

            // Getting True Filename
            $returnFile = $truename . '.jpg';

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
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $BastModel  = new BastModel();
        $input      = $this->request->getFile('uploads');
        // $id         = $this->request->getPost('id');

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

            $idBast = $BastModel->getInsertID();

            $retunarr = [
                'id'    => $idBast,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($retunarr));
        }
    }

    // public function fileser($id)
    // {
    //     $BastModel = new BastModel();
    //     $bast = $BastModel->where('projectid', $id)->find();
    //     return $this->response->setJSON($bast);
    // }

    public function bast($id)
    {
        $image      = \Config\Services::image();
        $validation = \Config\Services::validation();
        $input      = $this->request->getFile('uploads');
        $BastModel  = new BastModel();

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

            if (!empty($returnFile)) {
                $bast = $BastModel->where('projectid',$id)->first();
                $bastId="";
                if (empty($bast)) {
                    // unlink(FCPATH . '/img/bast/' . $returnFile);
                    $databast = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'    => 1,
                    ];
                    $BastModel->save($databast);
                    $bastId = $BastModel->getInsertID();
                } else {
                    unlink(FCPATH . '/img/bast/' . $bast['file']);
                    $databast = [
                        'id'            => $bast['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $BastModel->save($databast);
                    $bastId = $bast['id'];
                }
            }
           

            $returnBast = [
                'id'    => $bastId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returnBast));
        }
    }
}
