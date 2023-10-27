<?php

namespace App\Controllers;

use App\Models\BarModel;

class Bar extends BaseController
{
    public function index()
    {
        $BarModel = new BarModel;

        $bars = $BarModel->find(1);

        $qty = $bars['qty'];

        $data                   = $this->data;
        $data['title']          =  lang('Global.titleBar');
        $data['description']    =  lang('Global.barDescription');
        $data['qty']            = $qty;

        return view('bar', $data);
    }

    public function create (){

        $BarModel = new BarModel;

        $input = $this->request->getPost();

        $progress = [
            'qty' => $input['qty'],
        ];
        $BarModel->save($progress);

        $qty = '';
        foreach ($bars as $bar){
            $qty = $bar['qty'];
        }

        $data                   =   $this->data;
        $data['title']          =   lang('Global.titleBar');
        $data['description']    =   lang('Global.barDescription');

        return view('bar', $data);

    }

    public function update($id){

        $BarModel = new BarModel;

        $input = $this->request->getPost();
        $bars = $BarModel->find($id);

        $progress = [
            'id'    => $id,
            'qty'   => (int)$bars['qty'] + (int)$input['qty'],
        ];
        $BarModel->save($progress);

        $qty = $bars['qty'];

        $data                   =   $this->data;
        $data['title']          =   lang('Global.titleBar');
        $data['description']    =   lang('Global.barDescription');
        $data['qty']            =   $qty;

        return redirect()->back();
    }

}
