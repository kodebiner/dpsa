<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\BarModel;


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
        $this->builder  = $this->db->table('mdl');
        $this->config   = config('Auth');
        $this->auth     = service('authentication');
    }

    public function index()
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.read', $this->data['uid'])) {
            // Calling Services
            $pager = \Config\Services::pager();

            // Populating data
            $input = $this->request->getGet();

            if (isset($input['perpage'])) {
                $perpage = $input['perpage'];
            } else {
                $perpage = 10;
            }

            $page = (@$_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $perpage;

            if (isset($input['search']) && !empty($input['search'])) {
                $this->builder->like('mdl.name', $input['search']);
                $this->builder->orLike('mdl.price', $input['search']);
            }
            $this->builder->select('mdl.id as id, mdl.name as name, mdl.length as length, mdl.height as height, mdl.width as width, mdl.volume as volume, mdl.denomination as denomination, mdl.price as price');
            $query =   $this->builder->get($perpage, $offset)->getResultArray();

            $total = $this->builder->countAllResults();

            $data = $this->data;
            $data['title']          =   "MDL";
            $data['description']    =   "Daftar MDL yang tersedia";
            $data['mdls']           =   $query;
            $data['input']          =   $input;
            $data['pager']          =   $pager->makeLinks($page, $perpage, $total, 'uikit_full');

            return view('mdl', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function create()
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.create', $this->data['uid'])) {
            // Calling Models
            $MdlModel = new MdlModel;

            // Get Data
            $input = $this->request->getPost();

            // Save Data
            if (($input['denomination'] === "2") || ($input['denomination'] === "3")) {
                $mdl = [
                    'name'          => $input['name'],
                    'length'        => $input['length'],
                    'width'         => $input['width'],
                    'height'        => $input['height'],
                    'volume'        => $input['volume'],
                    'denomination'  => $input['denomination'],
                    'price'         => $input['price'],
                ];
                $MdlModel->save($mdl);
            } else {
                $mdl = [
                    'name'          => $input['name'],
                    'denomination'  => $input['denomination'],
                    'price'         => $input['price'],
                ];
                $MdlModel->save($mdl);
            }

            return redirect()->to('mdl')->with('massage', "Data Tersimpan");
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function update($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.edit', $this->data['uid'])) {
            $MdlModel = new MdlModel;

            $input = $this->request->getPost();

            // Filter Condition Meters Or Unit
            if (($input['denomination'] === "2") || ($input['denomination'] === "3")) {

                $mdlup = [
                    'id'            => $id,
                    'name'          => $input['name'],
                    'denomination'  => $input['denomination'],
                    'length'        => $input['length'],
                    'width'         => $input['width'],
                    'height'        => $input['height'],
                    'volume'        => $input['volume'],
                    'price'         => $input['price'],
                ];
                $MdlModel->save($mdlup);
            } else {

                $mdlup = [
                    'id'            => $id,
                    'name'          => $input['name'],
                    'denomination'  => $input['denomination'],
                    'length'        => Null,
                    'width'         => Null,
                    'height'        => Null,
                    'volume'        => Null,
                    'price'         => $input['price'],
                ];
                $MdlModel->save($mdlup);
            }
            return redirect()->to('mdl')->with('massage', lang('Global.updated'));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function delete($id)
    {
        if ($this->data['authorize']->hasPermission('admin.mdl.delete', $this->data['uid'])) {
            $MdlModel = new MdlModel;
            $MdlModel->delete($id);
            return redirect()->to('mdl')->with('massage', lang('Global.deleted'));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
