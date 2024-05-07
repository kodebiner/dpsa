<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseDetail extends Migration
{
    public function up()
    {
        $fields = [
            'clientid'          => ['type' => 'INT', 'constraint' => 11],
            'mdlid'             => ['type' => 'INT', 'constraint' => 11],
            'paketid'           => ['type' => 'INT', 'constraint' => 11],
            'qty'               => ['type' => 'INT', 'constraint' => 11],

        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('purchaseorderdetail', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('purchaseorderdetail');
    }
}