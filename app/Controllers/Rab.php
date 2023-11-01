<?php

namespace App\Controllers;

use App\Models\BarModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\MdlModel;
use App\Models\RabModel;


class Rab extends BaseController
{
    protected $db, $builder;
    protected $auth;
    protected $config;


    public function index()
    {
        // Find Model
        $ProjectModel   = new ProjectModel;
        $UserModel      = new UserModel;
        $MdlModel       = new MdlModel;
        $RabModel       = new RabModel;

        // Populating Data
        $projects   = $ProjectModel->findAll();
        $users      = $UserModel->findAll();
        $rabs       = $RabModel->findAll();
        
        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['clients']        =   $users;
        $data['projects']       =   $projects;
        $data['mdls']           =   $MdlModel->findAll();
        $data['rabs']           =   $RabModel->findAll();

        return view('rab', $data);
    }

    public function create()
    {
        $RabModel = new RabModel;

        $input  =   $this->request->getPost();

        foreach ($input['qty'] as $id => $qty){
            $rabdat [] = [
                'projectid'     => $input['pro'],
                'mdlid'         => $id,
                'qty'           => $qty,
                'qty_deliver'   => $input['qtydeliv'][$id],
                'qty_complete'  => $input['completed'][$id],
            ];
        }
        foreach ($rabdat as $rab){
            $data = [
                'projectid'     => $rab['projectid'],
                'mdlid'         => $rab['mdlid'],
                'qty'           => $rab['qty'],
                'qty_deliver'   => $rab['qty_deliver'],
                'qty_complete'  => $rab['qty_complete'],
            ];
            $RabModel->save($data);
        }
        
        return redirect()->to('rab')->with('massage', lang('Global.saved'));
    }

    public function update($id)
    {
        $RabtModel = new RabtModel;

        // initialisation
        $input = $this->request->getPost();
        $time   = date('Y-m-d H:i:s');

        $rab = [
            'id'                => $id,
            'qty'               => $input['qty'],
            'qty_deliver'       => $input['qtydeliv'],
            'qty_complete'      => $input['qtycomp'],
            'projectid'         => $input['pro'],
            'mdlid'             => $input['mdl'],
        ];
        $RabModel->save($rab);
        
        return redirect()->to('project')->with('massage', lang('Global.saved'));
    }

    public function delete($id){

        $RabModel = new RabModel;

        $rab = $RabModel->find($id);
        $RabModel->delete($rab);

        return redirect()->to('rab')->with('massage', lang('Global.deleted'));
    }

    public function approve(){

    }

}
