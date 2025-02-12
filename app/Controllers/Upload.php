<?php

namespace App\Controllers;

use App\Models\BastModel;
use App\Models\DesignModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\LogModel;
use App\Models\InvoiceModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\VersionModel;

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
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/pdf]',
        ];

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/design/', $filename);

            // Getting True Filename
            $returnFile = $truename . '.pdf';

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removedesigncreate()
    {
        // Removing File
        $input = $this->request->getPost('submitted');
        // unlink(FCPATH . 'img/design/' . $input);

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

            // Getting True Filename
            $returnFile = $truename . '.pdf';

            // Returning Message
            die(json_encode($returnFile));
        }
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
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . '/img/spk/', $filename);

            // Getting True Filename
            $returnFile = $truename . '.pdf';

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function savespk($id)
    {
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getPost();
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

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
            return redirect()->to('/')->withInput()->with('errors', $this->validator->getErrors());
        }

        // SPK Data
        if (isset($input['spk'])) {
            $spk = $ProjectModel->find($id);
            if (empty($spk)) {
                $dataspk = [
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                    // 'inv1'          => date("Y-m-d H:i:s"),
                ];
                $ProjectModel->save($dataspk);

                $projectid = $ProjectModel->getInsertID();

                // Insert SPK Version (Arsip)
                $spkversion = [
                    'projectid'     => $projectid,
                    'file'          => $input['spk'],
                    'type'          => 4,
                ];
                $VersionModel->insert($spkversion);
                // End Insert SPK Version

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'SPK baru telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'SPK baru telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'SPK baru telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload SPK ' . $project['name']]);
            } else {
                // if (!empty($spk['spk'])) {
                //     unlink(FCPATH . '/img/spk/' . $spk['spk']);
                // }
                $dataspk = [
                    'id'            => $spk['id'],
                    'spk'           => $input['spk'],
                    'status_spk'    => 0,
                    // 'inv1'          => date("Y-m-d H:i:s"),
                ];
                $ProjectModel->save($dataspk);

                // Insert SPK Version (Arsip)
                $spkversion = [
                    'projectid'     => $spk['id'],
                    'file'          => $input['spk'],
                    'type'          => 4,
                ];
                $VersionModel->insert($spkversion);
                // End Insert SPK Version

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'SPK baru telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'SPK baru telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'SPK baru telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
                $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Malakukan upload SPK ' . $project['name']]);
            }
        }

        return redirect()->to('/')->with('message', 'SPK terkirim');
    }

    public function removespk()
    {
        // Removing File
        $input = $this->request->getPost('spk');
        unlink(FCPATH . 'img/spk/' . $input);

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    // public function spkclient()
    // {
    //     $image      = \Config\Services::image();
    //     $validation = \Config\Services::validation();
    //     $input      = $this->request->getFile('uploads');
    //     $file       = $input->getName();

    //     if ($input->isValid() && !$input->hasMoved()) {
    //         $input->move(FCPATH . '/img/spkclient/', $file);

    //         // Returning Message
    //         die(json_encode($file));
    //     }
    // }

    // public function removespkclient()
    // {
    //     // Removing File
    //     $input = $this->request->getPost('spk');
    //     unlink(FCPATH . 'img/spkclient/' . $input);

    //     // Return Message
    //     die(json_encode(array('errors', 'Data berhasil di hapus')));
    // }

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
            $lastOrder  = $MdlPaketModel->where('paketid', $id)->orderBy('ordering', 'DESC')->first();
            if (!empty($lastOrder)) {
                $order  = $lastOrder['ordering'] + 1;
            } else {
                $order  = '1';
            }
            $datamdlpaket   = [
                'mdlid'     => $idmdl,
                'paketid'   => $id,
                'ordering'  => $order
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
        $file       = $input->getName();

        if ($input->isValid() && !$input->hasMoved()) {
            $input->move(FCPATH . '/img/design/', $file);

            // Returning Message
            die(json_encode($file));
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

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,image/png,image/x-png,image/jpeg,image/pjpeg]',
        ];

        $ext        = strtolower($input->getClientExtension());

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $input->move(FCPATH . 'img/mdl/', $filename);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            // Returning Message
            die(json_encode($returnFile));
        }
    }

    public function removephotomdl()
    {
        // Removing File
        $input = $this->request->getPost('photo');

        if(file_exists(FCPATH . 'img/mdl/' . $input)){
            unlink(FCPATH . 'img/mdl/' . $input);
        }

        // Return Message
        die(json_encode(array('errors', 'Data berhasil di hapus')));
    }

    public function sertrim($id)
    {
        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $BastModel          = new BastModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

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

            // if (!empty($input)) {
            //     function random_string($filename)
            //     {
            //         $key = '';
            //         $keys = array_merge(range(0, 9), range('a', 'z'));

            //         for ($i = 0; $i < $filename; $i++) {
            //             $key .= $keys[array_rand($keys)];
            //         }

            //         return $key;
            //     }
            //     $truename = random_string(20);
            // }

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

            // Insert Sertim Version (Arsip)
            $sertrimversion = [
                'projectid'     => $id,
                'file'          => $returnFile,
                'type'          => 5,
            ];
            $VersionModel->insert($sertrimversion);
            // End Insert Sertim Version

            // Insert Log Notification
            $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload Sertrim ' . $project['name']]);

            $inv2date = [
                'id'    => $id,
                'inv2'  => date("Y-m-d H:i:s"),
            ];
            $ProjectModel->save($inv2date);

            // Notif Marketing
            $notifmarketing  = [
                'userid'        => $marketings->id,
                'keterangan'    => 'Bukti Serah Terima telah diterbitkan',
                'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                'status'        => 0,
            ];

            $NotificationModel->insert($notifmarketing);

            // Notif Admin
            foreach ($admins as $admin) {
                $notifadmin  = [
                    'userid'        => $admin['id'],
                    'keterangan'    => 'Bukti Serah Terima telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifadmin);
            }

            // Notif Client
            foreach ($clients as $client) {
                $notifclient  = [
                    'userid'        => $client->id,
                    'keterangan'    => 'Bukti Serah Terima telah diterbitkan',
                    'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifclient);
            }

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
        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $BastModel          = new BastModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();


        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/vnd.ms-excel,application/xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
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
            // function random_string($filename)
            // {
            //     $key = '';
            //     $keys = array_merge(range(0, 9), range('a', 'z'));

            //     for ($i = 0; $i < $filename; $i++) {
            //         $key .= $keys[array_rand($keys)];
            //     }

            //     return $key;
            // }
            // $truename = random_string(20);
            $input->move(FCPATH . '/img/bast/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $bastId = "";
            if (!empty($returnFile)) {
                $bast = $BastModel->where('projectid', $id)->where('status', '1')->first();
                if (empty($bast)) {
                    $databast = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $BastModel->save($databast);
                    $bastId = $BastModel->getInsertID();

                    // Insert BAST Version (Arsip)
                    $sertrimversion = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 6,
                    ];
                    $VersionModel->insert($sertrimversion);
                    // End Insert BAST Version

                    // Insert Log Notification
                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload Bast ' . $project['name']]);

                    // Notif Marketing
                    $notifmarketing  = [
                        'userid'        => $marketings->id,
                        'keterangan'    => 'Bast telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifmarketing);

                    // Notif Admin
                    foreach ($admins as $admin) {
                        $notifadmin  = [
                            'userid'        => $admin['id'],
                            'keterangan'    => 'Bast telah diterbitkan',
                            'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                            'status'        => 0,
                        ];

                        $NotificationModel->insert($notifadmin);
                    }

                    // Notif Client
                    foreach ($clients as $client) {
                        $notifclient  = [
                            'userid'        => $client->id,
                            'keterangan'    => 'Bast telah diterbitkan',
                            'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                            'status'        => 0,
                        ];

                        $NotificationModel->insert($notifclient);
                    }
                } else {
                    // if (!empty($bast['file'])) {
                    //     unlink(FCPATH . '/img/bast/' . $bast['file']);
                    // }
                    $databast = [
                        'id'            => $bast['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $BastModel->save($databast);

                    // Insert BAST Version (Arsip)
                    $sertrimversion = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 6,
                    ];
                    $VersionModel->insert($sertrimversion);
                    // End Insert BAST Version

                    $bastId = $bast['id'];
                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah Bast ' . $project['name']]);

                    // Notif Marketing
                    $notifmarketing  = [
                        'userid'        => $marketings->id,
                        'keterangan'    => 'Bast telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifmarketing);

                    // Notif Admin
                    foreach ($admins as $admin) {
                        $notifadmin  = [
                            'userid'        => $admin['id'],
                            'keterangan'    => 'Bast telah diterbitkan',
                            'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                            'status'        => 0,
                        ];

                        $NotificationModel->insert($notifadmin);
                    }

                    // Notif Client
                    foreach ($clients as $client) {
                        $notifclient  = [
                            'userid'        => $client->id,
                            'keterangan'    => 'Bast telah diterbitkan',
                            'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                            'status'        => 0,
                        ];

                        $NotificationModel->insert($notifclient);
                    }
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

    public function sph($id)
    {
        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();


        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/vnd.ms-excel,application/xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
        ];

        // Get Extention
        $ext = strtolower($input->getClientExtension());

        // Validating
        if (!$this->validate($rules)) {
            http_response_code(400);
            die(json_encode(array('message' => $this->validator->getErrors())));
        }

        if ($input->isValid() && !$input->hasMoved()) {
            // Saving uploaded file
            $filename = $input->getRandomName();
            $truename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);

            // function random_string($filename)
            // {
            //     $key = '';
            //     $keys = array_merge(range(0, 9), range('a', 'z'));

            //     for ($i = 0; $i < $filename; $i++) {
            //         $key .= $keys[array_rand($keys)];
            //     }

            //     return $key;
            // }
            // $truename = random_string(20);
            $input->move(FCPATH . '/img/sph/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $sphId = "";
            if (!empty($returnFile)) {
                $sph = $ProjectModel->where('id', $id)->first();
                if (empty($sph)) {
                    $datasph = [
                        'id'            => $id,
                        'sph'           => $returnFile,
                    ];
                    $ProjectModel->save($datasph);
                    $sphId = $ProjectModel->getInsertID();

                    // Insert SPH Version (Arsip)
                    $sphversion = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 3,
                    ];
                    $VersionModel->insert($sphversion);
                    // End Insert SPH Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload sph ' . $project['name']]);
                } else {
                    // if (!empty($sph['sph'])) {
                    //     unlink(FCPATH . '/img/sph/' . $sph['sph']);
                    // }
                    $datasph = [
                        'id'            => $sph['id'],
                        'sph'           => $returnFile,
                    ];
                    $ProjectModel->save($datasph);

                    // Insert SPH Version (Arsip)
                    $sphversion = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 3,
                    ];
                    $VersionModel->insert($sphversion);
                    // End Insert SPH Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah sph ' . $project['name']]);
                    $sphId = $sph['id'];
                }

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'SPH telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'SPH telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'SPH telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
            }

            $returnBast = [
                'id'    => $sphId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returnBast));
        }
    }

    public function invoice($id)
    {

        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $InvoiceModel       = new InvoiceModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Finance
        $finances   = $authorize->usersInGroup('finance');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/vnd-xls,application/x-xls,application/vnd.ms-excel,application/xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
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
            $input->move(FCPATH . '/img/invoice/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $invoiceId = "";
            if (!empty($returnFile)) {
                $invoice = $InvoiceModel->where('projectid', $id)->where('status', '1')->first();
                if (empty($invoice)) {
                    $datainvoice = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $InvoiceModel->save($datainvoice);
                    $invoiceId = $InvoiceModel->getInsertID();

                    // Insert INVOICE I Version (Arsip)
                    $sertrimversion = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 7,
                    ];
                    $VersionModel->insert($sertrimversion);
                    // End Insert INVOICE I Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload invoice I' . $project['name']]);
                } else {
                    // if (!empty($invoice['file'])) {
                    //     unlink(FCPATH . '/img/invoice/' . $invoice['file']);
                    // }
                    $datainvoice = [
                        'id'            => $invoice['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 1,
                    ];
                    $InvoiceModel->save($datainvoice);

                    // Insert INVOICE I Version (Arsip)
                    $invoice1version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 7,
                    ];
                    $VersionModel->insert($invoice1version);
                    // End Insert INVOICE I Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah invoice I' . $project['name']]);
                    $invoiceId = $invoice['id'];
                }

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'Invoice I telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'Invoice I telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Finance
                foreach ($finances as $finance) {
                    $notiffinance  = [
                        'userid'        => $finance['id'],
                        'keterangan'    => 'Invoice I telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notiffinance);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'Invoice I telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
            }

            $returninvoice = [
                'id'    => $invoiceId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returninvoice));
        }
    }

    public function invoice2($id)
    {

        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $InvoiceModel       = new InvoiceModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Finance
        $finances   = $authorize->usersInGroup('finance');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
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

            // function random_string($filename)
            // {
            //     $key = '';
            //     $keys = array_merge(range(0, 9), range('a', 'z'));

            //     for ($i = 0; $i < $filename; $i++) {
            //         $key .= $keys[array_rand($keys)];
            //     }

            //     return $key;
            // }
            // $truename = random_string(20);
            $input->move(FCPATH . '/img/invoice/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $invoiceId = "";
            if (!empty($returnFile)) {
                $invoice = $InvoiceModel->where('projectid', $id)->where('status', '2')->first();
                if (empty($invoice)) {
                    $datainvoice = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 2,
                    ];
                    $InvoiceModel->save($datainvoice);
                    $invoiceId = $InvoiceModel->getInsertID();

                    // Insert INVOICE II Version (Arsip)
                        $invoice2version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 8,
                    ];
                    $VersionModel->insert($invoice2version);
                    // End Insert INVOICE II Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload invoice II' . $project['name']]);
                } else {
                    // if (!empty($invoice['file'])) {
                    //     unlink(FCPATH . '/img/invoice/' . $invoice['file']);
                    // }
                    $datainvoice = [
                        'id'            => $invoice['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 2,
                    ];
                    $InvoiceModel->save($datainvoice);

                    // Insert INVOICE II Version (Arsip)
                    $invoice2version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 8,
                    ];
                    $VersionModel->insert($invoice2version);
                    // End Insert INVOICE II Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah invoice II' . $project['name']]);
                    $invoiceId = $invoice['id'];
                }

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'Invoice II telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'Invoice II telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Finance
                foreach ($finances as $finance) {
                    $notiffinance  = [
                        'userid'        => $finance['id'],
                        'keterangan'    => 'Invoice II telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notiffinance);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'Invoice II telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
            }

            $returninvoice = [
                'id'    => $invoiceId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returninvoice));
        }
    }

    public function invoice3($id)
    {

        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $InvoiceModel       = new InvoiceModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);

        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Finance
        $finances   = $authorize->usersInGroup('finance');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
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

            // function random_string($filename)
            // {
            //     $key = '';
            //     $keys = array_merge(range(0, 9), range('a', 'z'));

            //     for ($i = 0; $i < $filename; $i++) {
            //         $key .= $keys[array_rand($keys)];
            //     }

            //     return $key;
            // }
            // $truename = random_string(20);
            $input->move(FCPATH . '/img/invoice/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $invoiceId = "";
            if (!empty($returnFile)) {
                $invoice = $InvoiceModel->where('projectid', $id)->where('status', '3')->first();
                if (empty($invoice)) {
                    $datainvoice = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 3,
                    ];
                    $InvoiceModel->save($datainvoice);
                    $invoiceId = $InvoiceModel->getInsertID();

                    // Insert INVOICE III Version (Arsip)
                    $invoice3version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 9,
                    ];
                    $VersionModel->insert($invoice3version);
                    // End Insert INVOICE III Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload invoice III' . $project['name']]);
                } else {
                    // if (!empty($invoice['file'])) {
                    //     if (file_exists(FCPATH . '/img/invoice/' . $invoice['file'])) {
                    //         unlink(FCPATH . '/img/invoice/' . $invoice['file']);
                    //     }
                    // }
                    $datainvoice = [
                        'id'            => $invoice['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 3,
                    ];
                    $InvoiceModel->save($datainvoice);

                    // Insert INVOICE III Version (Arsip)
                    $invoice3version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 9,
                    ];
                    $VersionModel->insert($invoice3version);
                    // End Insert INVOICE III Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah invoice III' . $project['name']]);
                    $invoiceId = $invoice['id'];
                }

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'Invoice III telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'Invoice III telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Finance
                foreach ($finances as $finance) {
                    $notiffinance  = [
                        'userid'        => $finance['id'],
                        'keterangan'    => 'Invoice III telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notiffinance);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'Invoice III telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
            }

            $returninvoice = [
                'id'    => $invoiceId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returninvoice));
        }
    }

    public function invoice4($id)
    {

        $image              = \Config\Services::image();
        $validation         = \Config\Services::validation();
        $InvoiceModel       = new InvoiceModel();
        $ProjectModel       = new ProjectModel();
        $LogModel           = new LogModel();
        $UserModel          = new UserModel();
        $NotificationModel  = new NotificationModel();
        $VersionModel       = new VersionModel();
        $input              = $this->request->getFile('uploads');
        $project            = $ProjectModel->find($id);
        $authorize  = $auth = service('authorization');

        // Notification
        // User Admin
        $admins     = $authorize->usersInGroup('admin');

        // User Finance
        $finances   = $authorize->usersInGroup('finance');

        // User Marketing
        $marketings = $UserModel->find($project['marketing']);

        // User Client
        $clients    = $UserModel->where('parentid', $project['clientid'])->find();

        // Validation Rules
        $rules = [
            'uploads'   => 'uploaded[uploads]|mime_in[uploads,application/xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/pdf,application/octet-stream,image/png,image/jpeg,image/pjpeg]',
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

            // function random_string($filename)
            // 
            //     $key = '';
            //     $keys = array_merge(range(0, 9), range('a', 'z'));

            //     for ($i = 0; $i < $filename; $i++) {
            //         $key .= $keys[array_rand($keys)];
            //     }

            //     return $key;
            // }
            // $truename = random_string(20);
            $input->move(FCPATH . '/img/invoice/', $truename . '.' . $ext);

            // Getting True Filename
            $returnFile = $truename . '.' . $ext;

            $invoiceId = "";
            if (!empty($returnFile)) {
                $invoice = $InvoiceModel->where('projectid', $id)->where('status', '4')->first();
                if (empty($invoice)) {
                    $datainvoice = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 4,
                    ];
                    $InvoiceModel->save($datainvoice);
                    $invoiceId = $InvoiceModel->getInsertID();

                    // Insert INVOICE IV Version (Arsip)
                    $invoice4version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 10,
                    ];
                    $VersionModel->insert($invoice4version);
                    // End Insert INVOICE IV Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Melakukan upload invoice IV' . $project['name']]);
                } else {
                    // if (!empty($invoice['file'])) {
                    //     unlink(FCPATH . '/img/invoice/' . $invoice['file']);
                    // }
                    $datainvoice = [
                        'id'            => $invoice['id'],
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'status'        => 4,
                    ];
                    $InvoiceModel->save($datainvoice);

                    // Insert INVOICE IV Version (Arsip)
                    $invoice4version = [
                        'projectid'     => $id,
                        'file'          => $returnFile,
                        'type'          => 10,
                    ];
                    $VersionModel->insert($invoice4version);
                    // End Insert INVOICE IV Version

                    $LogModel->save(['uid' => $this->data['uid'], 'record' => 'Mengubah invoice IV' . $project['name']]);
                    $invoiceId = $invoice['id'];
                }

                // Notif Marketing
                $notifmarketing  = [
                    'userid'        => $marketings->id,
                    'keterangan'    => 'Invoice IV telah diterbitkan',
                    'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                    'status'        => 0,
                ];

                $NotificationModel->insert($notifmarketing);

                // Notif Admin
                foreach ($admins as $admin) {
                    $notifadmin  = [
                        'userid'        => $admin['id'],
                        'keterangan'    => 'Invoice IV telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifadmin);
                }

                // Notif Finance
                foreach ($finances as $finance) {
                    $notiffinance  = [
                        'userid'        => $finance['id'],
                        'keterangan'    => 'Invoice IV telah diterbitkan',
                        'url'           => 'project/listprojectclient/'.$project['clientid'].'?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notiffinance);
                }

                // Notif Client
                foreach ($clients as $client) {
                    $notifclient  = [
                        'userid'        => $client->id,
                        'keterangan'    => 'Invoice IV telah diterbitkan',
                        'url'           => 'dashboard/' . $project['clientid'] . '?projectid=' . $project['id'],
                        'status'        => 0,
                    ];

                    $NotificationModel->insert($notifclient);
                }
            }

            $returninvoice = [
                'id'    => $invoiceId,
                'file'  => $returnFile,
                'proid' => $id,
            ];

            // Returning Message
            die(json_encode($returninvoice));
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
