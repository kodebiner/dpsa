<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPOdetail extends Migration
{
    public function up()
    {
        $this->forge->dropTable('purchaseorderdetail');
    }
    
    public function down()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'purchaseid'        => ['type' => 'INT', 'constraint' => 11],
            'clientid'          => ['type' => 'INT', 'constraint' => 11],
            'mdlid'             => ['type' => 'INT', 'constraint' => 11],
            'paketid'           => ['type' => 'INT', 'constraint' => 11],
            'qty'               => ['type' => 'INT', 'constraint' => 11],

        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('purchaseorderdetail', true);
    }
}