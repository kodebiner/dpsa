<?php

namespace App\Controllers;

use App\Models\DesignModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;

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
            $input->move(FCPATH . '/img/design/', $truename.'.'.$ext);

            // Getting True Filename
            $returnFile = $truename.'.'.$ext;

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
        // die(json_encode($this->request->getPost()));

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
            $input->move(FCPATH . '/img/spk/', $filename);

            // Removing uploaded if it's not the same filename
            if ($filename != $truename . '.pdf') {
                unlink(FCPATH . '/img/spk/' . $filename);
            }

            // Getting True Filename
            $returnFile = $truename . '.pdf';

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
                    'required'      => 'File SPK {field} Belum Di Unggah',
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
        // Populating Data
        $MdlModel   = new MdlModel();
        $input      = $this->request->getFile('uploads');

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

        die('Berhasil Import Data MDL');
    }
}
