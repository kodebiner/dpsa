<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DesignModel;

class Upload extends BaseController
{
    public function designcreate()
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
        if (! $this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && ! $input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH.'/img/design/', $filename);
            
            // Removing uploaded if it's not the same filename
            if ($filename != $truename.'.pdf') {
                unlink(FCPATH.'/img/design/'.$filename);
            }

            // Getting True Filename
            $returnFile = $truename.'.pdf';

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removedesigncreate()
    {
        // Removing File
        $input = $this->request->getPost('submitted');
        unlink(FCPATH.'img/design/'.$input);

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
        if (! $this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && ! $input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH.'/img/revisi/', $filename);
            
            // Removing uploaded if it's not the same filename
            if ($filename != $truename.'.pdf') {
                unlink(FCPATH.'/img/revisi/'.$filename);
            }

            // Getting True Filename
            $returnFile = $truename.'.pdf';

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
}
