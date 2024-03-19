<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBukti extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'file'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'            => ['type' => 'INT', 'constraint' => 11],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bukti', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('bukti');
    }
}
