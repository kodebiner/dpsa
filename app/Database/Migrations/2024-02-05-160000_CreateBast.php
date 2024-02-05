<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBast extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'file'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'            => ['type' => 'BOOLEAN'],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bast', true);
    }

    public function down()
    {
        $this->forge->dropTable('bast');
    }
}
