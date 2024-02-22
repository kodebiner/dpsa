<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferensi extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'bank'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'no_rek'            => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('referensi', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('referensi');
    }
}
