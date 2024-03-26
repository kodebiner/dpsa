<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaran extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'date'              => ['type' => 'DATETIME'],
            'qty'               => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pembayaran', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('pembayaran');
    }
}