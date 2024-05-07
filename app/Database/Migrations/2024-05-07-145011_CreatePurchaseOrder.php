<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchase extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'clientid'          => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('purchaseorder', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('purchaseorder');
    }
}