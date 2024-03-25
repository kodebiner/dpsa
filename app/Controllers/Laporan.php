<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupUserModel;
use App\Models\PermissionModel;
use Myth\Auth\Models\GroupModel;
use App\Models\CompanyModel;
use App\Models\CustomRabModel;
use App\Models\ProjectModel;
use App\Models\ProjectTempModel;
use App\Models\LogModel;
use App\Models\MdlModel;
use App\Models\RabModel;
use App\Models\BastModel;

class Laporan extends BaseController
{

    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $helpers = ['form'];
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('project');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.user.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Calling Model
            $ProjectModel              = new ProjectModel();
            $CompanyModel              = new CompanyModel();
            $UserModel                 = new UserModel();
            $RabModel                  = new RabModel();
            $MdlModel                  = new MdlModel();
            $CustomRabModel            = new CustomRabModel();
            $BastModel                 = new BastModel();


            // Populating data
            // $projects = $ProjectModel->findAll();
            $companys = $CompanyModel->findAll();
            // $users    = $UserModel->where('parentid !=', null)->find();

            // Initialize
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            $this->builder->join('users', 'users.id = project.marketing');
            // $this->builder->where('deleted_at', null);
            // $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
            // $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('project.name', $input['search']);
                $this->builder->orLike('users.username', $input['search']);
                // $this->builder->orLike('company.address', $input['search']);
            }
            // if (isset($input['rolesearch']) && !empty($input['rolesearch']) && ($input['rolesearch'] != '0')) {
            //     if ($input['rolesearch'] === "1") {
            //         $this->builder->where('company.parentid', "0");
            //     } elseif ($input['rolesearch'] === "2") {
            //         $this->builder->where('company.parentid !=', "0");
            //     }
            // }
            $this->builder->select('project.id as id, project.name as name, project.clientid as clientid, project.marketing as marketing, project.created_at as created_at, users.username as username');
            $query = $this->builder->get($perpage, $offset)->getResultArray();

            // $total = $this->builder->countAllResults();
            $total = count($query);

            $projects = $query;

            $projectdata = [];
            foreach ($projects as $project) {

                // Klien
                $projectdata[$project['id']]['klien'] = $CompanyModel->where('id', $project['clientid'])->first();

                // Marketing
                $projectdata[$project['id']]['marketing'] = $UserModel->where('id', $project['marketing'])->first();

                // RAB
                $rabs       = $RabModel->where('projectid', $project['id'])->find();
                if (!empty($rabs)) {
                    foreach ($rabs as $rab) {
                        $paketid[]  = $rab['paketid'];

                        // MDL RAB
                        $rabmdl     = $MdlModel->where('id', $rab['mdlid'])->find();
                        foreach ($rabmdl as $mdlr) {
                            $projectdata[$project['id']]['rab'][$rab['id']]  = [
                                'id'            => $mdlr['id'],
                                'proid'         => $project['id'],
                                'name'          => $mdlr['name'],
                                'length'        => $mdlr['length'],
                                'width'         => $mdlr['width'],
                                'height'        => $mdlr['height'],
                                'volume'        => $mdlr['volume'],
                                'denomination'  => $mdlr['denomination'],
                                'keterangan'    => $mdlr['keterangan'],
                                'qty'           => $rab['qty'],
                                'price'         => (int)$rab['qty'] * (int)$mdlr['price'],
                                'oriprice'      => (int)$mdlr['price'],
                            ];
                        }
                    }
                }

                // Get RAB data
                $price = [];
                if (!empty($projectdata[$project['id']]['rab'])) {
                    foreach ($projectdata[$project['id']]['rab'] as $mdldata) {
                        $price[] = [
                            'id'        => $mdldata['id'],
                            'proid'     => $mdldata['proid'],
                            'price'     => $mdldata['oriprice'],
                            'sumprice'  => $mdldata['price'],
                            'qty'       => $mdldata['qty']
                        ];
                    }
                }

                // Setrim
                $projectdata[$project['id']]['sertrim']     = $BastModel->where('projectid', $project['id'])->where('status', "0")->first();
                // BAST
                $projectdata[$project['id']]['bast']        = $BastModel->where('projectid', $project['id'])->where('file !=', "")->find();
                $projectdata[$project['id']]['bastfile']    = $BastModel->where('projectid', $project['id'])->where('status', "1")->first();

                if (!empty($projectdata[$project['id']]['bastfile'])) {
                    $day =  $projectdata[$project['id']]['bastfile']['tanggal_bast'];
                    $date = date_create($day);
                    $key = date_format($date, "Y-m-d");
                    $hari = date_create($key);
                    date_add($hari, date_interval_create_from_date_string('3 month'));
                    $dateline = date_format($hari, 'Y-m-d');

                    $now = strtotime("now");
                    $nowtime = date("Y-m-d", $now);
                    $projectdata[$project['id']]['dateline'] = $dateline;
                    $projectdata[$project['id']]['now'] = $nowtime;
                } else {
                    $projectdata[$project['id']]['dateline'] = "";
                    $projectdata[$project['id']]['now'] = "";
                }

                // Custom RAB
                $projectdata[$project['id']]['customrab']       = $CustomRabModel->where('projectid', $project['id'])->notLike('name', 'biaya pengiriman')->find();

                // Shipping Cost
                $projectdata[$project['id']]['shippingcost']    = $CustomRabModel->where('projectid', $project['id'])->like('name', 'biaya pengiriman')->first();

                // All Custom RAB 
                $allCustomRab = $CustomRabModel->where('projectid', $project['id'])->find();
                $projectdata[$project['id']]['allcustomrab']    = array_sum(array_column($allCustomRab, 'price'));

                // Rab Sum Value
                $projectdata[$project['id']]['rabvalue'] = 0;
                if (!empty($price)) {
                    $projectdata[$project['id']]['rabvalue']    = array_sum(array_column($price, 'sumprice'));
                }
            }

            // Parsing data to view
            $data                   = $this->data;
            $data['title']          = 'Laporan';
            $data['description']    = lang('Global.clientListDesc');
            $data['roles']          = $CompanyModel->where('deleted_at', null)->find();
            $data['company']        = $query;
            $data['total']          = $total;
            $data['pager']          = $pager->makeLinks($page, $perpage, $total, 'uikit_full');
            $data['input']          = $input;
            $data['projectdata']    = $projectdata;
            $data['projects']       = $projects;

            return view('laporan', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
