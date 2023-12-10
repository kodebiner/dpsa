<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\UserModel;

class ClientRegister extends BaseController
{
    protected $auth;
    protected $config;
    protected $request;
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
        $this->config = config('Auth');
        $this->auth   = service('authentication');
    }

    public function index()
    {
        // Calling Models
        $CompanyModel   = new CompanyModel();

        // Populating Data
        $pusat  = $CompanyModel->where('parentid', '0')->where('status', '1')->find();

        // Parsing Data to View
        $data                   = $this->data;
        $data['title']          = 'Pendaftaran Akun Client';
        $data['description']    = '';
        $data['pusat']          = $pusat;

        // Rendering View
        return view('Views/Client/registerindex', $data);
    }

    public function submit()
    {
        // Calling Services
        $authorize = service('authorization');

        // Calling Models & Entities
        $UserModel = new UserModel();
        $CompanyModel = new CompanyModel();
        $NewUser = new \App\Entities\User();

        // Populating Data
        $input = $this->request->getPost();

        // Validation
        $rules = [
            'firstname' => [
                'label'     => 'Nama Depan',
                'rules'     => 'required|alpha_space',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'alpha_space'   => '{field} hanya boleh berisi karakter alfabet dan spasi.',
                ],
            ],
            'lastname' => [
                'label'     => 'Nama Belakang',
                'rules'     => 'required|alpha_space',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'alpha_space'   => '{field} hanya boleh berisi karakter alfabet dan spasi.',
                ],
            ],
            'username' => [
                'label'     => 'Nama Pengguna',
                'rules'     => 'required|is_unique[users.username]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'is_unique'     => '{field} {{value}} sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
            'email' => [
                'label'     => 'Email',
                'rules'     => 'permit_empty|valid_email|is_unique[users.email]',
                'errors'    => [
                    'valid_email'   => '{field} harus menggunakan email yang valid.',
                    'is_unique'     => '{field} {{value}} sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
            'password' => [
                'label'     => 'Password',
                'rules'     => 'required|min_length[8]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'min_length'    => '{field} harus lebih panjang atau sama dengan {param}.',
                ],
            ],
            'pass_confirm' => [
                'label'     => 'Pengulangan Password',
                'rules'     => 'required|matches[password]',
                'errors'    => [
                    'required'      => '{field} wajib diisi.',
                    'matches'       => '{field} harus sama dengan {param}.',
                ],
            ],
            'phone' => [
                'label'     => 'No. Telp',
                'rules'     => 'permit_empty|decimal|is_unique[company.phone]',
                'errors'    => [
                    'decimal'       => '{field} harus berupa format nomor telp yang tepat.',
                    'is_unique'     => '{field} {{value}} sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($input['type'] === '0') {
            if (empty($input['companyid'])) {
                $rules = [
                    'ptname' => [
                        'label'     => 'Nama PT',
                        'rules'     => 'required|is_unique[company.ptname]',
                        'errors'    => [
                            'required'      => '{field} wajib diisi.',
                            'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                        ],
                    ],
                    'rsname' => [
                        'label'     => 'Nama Alias',
                        'rules'     => 'required|is_unique[company.rsname]',
                        'errors'    => [
                            'required'      => '{field} wajib diisi.',
                            'is_unique'     => '{field} <b>{value}</b> sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                        ],
                    ],
                    'position' => [
                        'label'     => 'Jabatan / Peran di Perusahaan',
                        'rules'     => 'required',
                        'errors'    => [
                            'required'      => '{field} wajib diisi.',
                        ],
                    ],
                ];

                if (!$this->validate($rules)) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }
            }
        } else {
            $rules = [
                'ptname' => [
                    'label'     => 'Nama PT',
                    'rules'     => 'required|is_unique[company.ptname]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'is_unique'     => '{field} {{value}} sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                    ],
                ],
                'rsname' => [
                    'label'     => 'Nama Rumah Sakit',
                    'rules'     => 'required|is_unique[company.rsname]',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                        'is_unique'     => '{field} {{value}} sudah digunakan. Silahkan gunakan {field} yang lainnya.',
                    ],
                ],
                'address' => [
                    'label'     => 'Alamat Rumah Sakit',
                    'rules'     => 'required',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                    ],
                ],
                'npwp' => [
                    'label'     => 'NPWP Perusahaan',
                    'rules'     => 'required',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                    ],
                ],
                'phone' => [
                    'label'     => 'No. Telp',
                    'rules'     => 'required',
                    'errors'    => [
                        'required'      => '{field} wajib diisi.',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        // Processing Data
        if ($input['type'] === '0') {
            if (!empty($input['companyid'])) {
                $NewUser->parentid = $input['companyid'];
            } else {
                $company = [
                    'ptname'    => $input['ptname'],
                    'rsname'    => $input['rsname'],
                    'address'   => $input['address'],
                    'npwp'      => $input['npwp'],
                    'phone'     => $input['phone'],
                    'parentid'  => '0',
                    'status'    => '0',
                ];
                $CompanyModel->insert($company);

                $companyid = $CompanyModel->getInsertID();

                $NewUser->firstname = $input['firstname'];
                $NewUser->lastname = $input['lastname'];
                $NewUser->username = $input['username'];
                if (!empty($input['email'])) {
                    $NewUser->email = $input['email'];
                }
                $NewUser->active = '0';
                $NewUser->password = $input['password'];
                $NewUser->parentid = $companyid;
                $NewUser->position = $input['position'];
            }
            $UserModel->insert($NewUser);

            $userId = $UserModel->getInsertID();

            $authorize->addUserToGroup($userId, 'Client Pusat');

            return redirect()->to('login')->with('message', 'Pendaftaran berhasil dilakukan. Admin kami akan melakukan verifikasi dan aktivasi akun anda.');
        } else {
            $company = [
                'ptname'    => $input['ptname'],
                'rsname'    => $input['rsname'],
                'address'   => $input['address'],
                'npwp'      => $input['npwp'],
                'phone'     => $input['phone'],
                'parentid'  => $input['companyid'],
                'status'    => '0',
            ];
            $CompanyModel->insert($company);

            $companyid = $CompanyModel->getInsertID();

            $NewUser->firstname = $input['firstname'];
            $NewUser->lastname = $input['lastname'];
            $NewUser->username = $input['username'];
            $NewUser->password = $input['password'];
            $NewUser->active = '0';
            $NewUser->parentid = $companyid;
            if (!empty($input['email'])) {
                $NewUser->email = $input['email'];
            }
            $UserModel->insert($NewUser);

            $userId = $UserModel->getInsertID();

            $authorize->addUserToGroup($userId, 'Client Cabang');

            return redirect()->to('login')->with('message', 'Pendaftaran berhasil dilakukan. Admin kami akan melakukan verifikasi dan aktivasi akun anda.');
        }
    }
}