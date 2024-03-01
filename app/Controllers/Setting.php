<?php

namespace App\Controllers;

use App\Models\GconfigModel;
use App\Models\ReferensiModel;

class Setting extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $config;
    protected $data;
    
    public function index()
    {
        // Calling Models
        $GconfigModel       = new GconfigModel();
        $ReferensiModel     = new ReferensiModel();

        // Filter Input
        $input              = $this->request->getGet();

        if (isset($input['perpage'])) {
            $perpage        = $input['perpage'];
        } else {
            $perpage        = 10;
        }

        // Search Engine
        if (isset($input['search']) && !empty($input['search'])) {
            $references     = $ReferensiModel->like('name', $input['search'])->find();
        } else {
            $references     = $ReferensiModel->paginate($perpage, 'referensi');
        }

        // Checking data availability
        $checkConfig    = $GconfigModel->find('1');

        // Populating data
        if (!$checkConfig) {
            $newConfig = [
                'ppn'               => '0',
                'alamat'            => '',
                'direktur'          => '',
                'npwp'              => '',
                
            ];
            $GconfigModel->insert($newConfig);
        }

        $gConfig = $GconfigModel->find('1');

        // Parsing data to view
        $data                   = $this->data;
        $data['title']          = 'Pengaturan Umum';
        $data['description']    = 'Pengaturan Umum untuk Aplikasi DPSA';
        $data['gconfig']        = $gConfig;
        $data['references']     = $references;

        // Rendering view
        return view('Views/setting', $data);
    }

    public function gconfig()
    {
        // Calling Models
        $GconfigModel       = new GconfigModel();

        // Get Data
        $input = $this->request->getPost();
        
        $gConfig = [
            'id'                => '1',
            'ppn'               => $input['ppn'],
            'direktur'          => $input['direktur'],
            'alamat'            => $input['alamat'],
            'npwp'              => $input['npwp']
        ];
        $GconfigModel->save($gConfig);

        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function createreferensi()
    {
        // Calling Models
        $ReferensiModel       = new ReferensiModel();

        // Get Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama Yang Bersangkutan',
                'rules'     => 'required|is_unique[referensi.name]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name'              => $input['name'],
            'bank'              => $input['bank'],
            'no_rek'            => $input['no_rek'],
        ];
        $ReferensiModel->insert($data);
        
        return redirect()->back()->with('message', "Data Tersimpan");
    }

    public function updatereferensi($id)
    {
        // Calling Models
        $ReferensiModel       = new ReferensiModel();

        // Get Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'name'      => [
                'label'     => 'Nama Yang Bersangkutan',
                'rules'     => 'required|is_unique[referensi.name,referensi.id,' . $id . ']',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'id'                => $id,
            'name'              => $input['name'],
            'bank'              => $input['bank'],
            'no_rek'            => $input['no_rek'],
        ];
        $ReferensiModel->save($data);
        
        return redirect()->back()->with('message', 'Data Behasil Diperbaharui');
    }

    public function deletereferensi($id)
    {
        // Calling Models
        $ReferensiModel         = new ReferensiModel();

        // Deleting Referensi Data
        $ReferensiModel->delete($id);
        
        return redirect()->back()->with('error', 'Data Telah Dihapuskan');
    }
}
