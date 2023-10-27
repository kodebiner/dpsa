<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\BarModel;


class Mdl extends BaseController
{
    public function index()
    {

        // Find Model
        $BarModel   = new BarModel;
        $MdlModel   = new MdlModel;

        // Populating Data
        $bars = $BarModel->find(1);
        $mdls = $MdlModel->findAll();

        // Data Quantiti
        $qty = $bars['qty'];
        
        $data = $this->data;
        $data['title']          =   lang('Global.titleDashboard');
        $data['description']    =   lang('Global.dashboardDescription');
        $data['qty']            =   $qty;
        $data['mdls']           =   $mdls;

        return view('Mdl', $data);
    }

    public function create()
    {
        // Calling Models
        $MdlModel = new MdlModel;

        // Get Data
        $input = $this->request->getPost();
        
        // Save Data
        $mdl = [
            'name'  => $input['name'],
            'price' => $input['price'],
        ];
        $MdlModel->save($mdl);

        return redirect()->to('mdl')->with('massage',lang('Global.saved'));
    }

    public function update($id)
    {
        $MdlModel = new MdlModel;

        $mdl = $MdlModel->find($id);

        $input = $this->request->getPost();

        $mdlup = [
            'id'    => $id,
            'name'  => $input['name'],
            'price' => $input['price'],
        ];
        $MdlModel->save($mdlup);

        return redirect()->to('mdl')->with('massage',lang('Global.updated'));
    }

    public function delete($id)
    {
        $MdlModel = new MdlModel;
        $mdl    = $MdlModel->find($id);
        $MdlModel->delete($id);

        return redirect()->to('mdl')->with('massage',lang('Global.deleted'));
    }

}
