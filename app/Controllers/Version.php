<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\ProjectModel;
use App\Models\MdlModel;
use App\Models\MdlPaketModel;
use App\Models\PaketModel;
use App\Models\RabModel;
use App\Models\DesignModel;
use App\Models\ProductionModel;
use App\Models\BastModel;
use App\Models\InvoiceModel;
use App\Models\ReferensiModel;
use App\Models\UserModel;
use App\Models\CustomRabModel;
use App\Models\GconfigModel;
use App\Models\BuktiModel;
use App\Models\NotificationModel;
use App\Models\PembayaranModel;
use App\Models\LogModel;
use App\Models\VersionModel;

class Version extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->db       = \Config\Database::connect();
        $validation     = \Config\Services::validation();
        $this->builder  = $this->db->table('versions');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
        $pager          = \Config\Services::pager();
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.project.read', $this->data['uid'])) {
            // Calling Model
            $ProjectModel           = new ProjectModel();
            $BastModel              = new BastModel();
            $CompanyModel           = new CompanyModel();
            $MdlModel               = new MdlModel();
            $MdlPaketModel          = new MdlPaketModel();
            $PaketModel             = new PaketModel();
            $RabModel               = new RabModel();
            $DesignModel            = new DesignModel();
            $ProductionModel        = new ProductionModel();
            $InvoiceModel           = new InvoiceModel();
            $ReferensiModel         = new ReferensiModel();
            $CustomRabModel         = new CustomRabModel();
            $BuktiModel             = new BuktiModel();
            $UserModel              = new UserModel();
            $NotificationModel      = new NotificationModel();
            $PembayaranModel        = new PembayaranModel();
            $VersionModel           = new VersionModel();
            $LogModel               = new LogModel();

            // Calling Services
            $pager = \Config\Services::pager();
            // Initilize
            $input = $this->request->getGet();

            // $version    = $VersionModel->where('projectid',$input['project'])->where('type',$input['type'])->find();
            $projects   = $ProjectModel->find($input['project']);
            $company    = $CompanyModel->find($projects['clientid']);

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if(isset($_GET['page'])){
                $i = (($_GET['page']-1) * 10) + 1;
            }else{
                $i = 1;
            }

            // New Searh Company Project System
            $this->builder  = $this->db->table('version');
            $this->builder->where('projectid',$input['project'])->where('type',$input['type']);
            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('version.file', $input['search']);
            }
            $this->builder->orderBy('id','ASC');
            $this->builder->select('version.id as id, version.file as file, version.type as type, version.projectid as projectid');
            $versions = $this->builder->get($perpage, $offset)->getResultArray();

            if (isset($input['search']) && !empty($input['search'])) {
                $totalprolist       = $VersionModel
                    ->where('projectid',$input['project'])
                    ->where('type',$input['type'])
                    ->countAllResults();
            } else {
                $totalprolist     = $VersionModel
                    ->where('projectid',$input['project'])
                    ->where('type',$input['type'])
                    ->countAllResults();
            }

            // Parsing Data To View
            $data                   = $this->data;
            $data['title']          = "Arsip File";
            $data['description']    = "Data Proyek";
            $data['projects']       = $projects;
            $data['company']        = $company;
            $data['versions']       = $versions;
            $data['pager']          = $pager->makeLinks($page, $perpage, $totalprolist, 'uikit_full');
            $data['inputpage']      = $this->request->getVar();
            $data['id']             = $input['project'];
            $data['typefile']       = $input['type'];
            $data['number']         = $i;

            return view('version', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

}
