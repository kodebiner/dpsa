<?php

namespace App\Controllers;

use App\Models\MdlModel;
use App\Models\BarModel;


class Mdl extends BaseController
{
    public function index()
    {

        // Find Model
        $MdlModel   = new MdlModel;

        // Populating Data
        $mdls = $MdlModel->findAll();
        
        $data = $this->data;
        $data['title']          =   "MDL";
        $data['description']    =   "Daftar MDL yang tersedia";
        $data['mdls']           =   $mdls;

        return view('mdl', $data);
    }

    public function create()
    {
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

        return redirect()->to('mdl')->with('massage',"Data Tersimpan");
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
