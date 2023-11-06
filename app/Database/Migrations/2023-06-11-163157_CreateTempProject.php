<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTempProject extends Migration
{
    public function up()
    {
        $fields = [
            'id'            => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'varchar', 'constraint' => 255],
            'brief'         => ['type' => 'varchar', 'constraint' => 255],
            'status'        => ['type' => 'int', 'constraint' => 11],
            'production'    => ['type' => 'int', 'constraint' => 11],
            'clientid'      => ['type' => 'int', 'constraint' => 11],
        ];
        $this->forge->addKey('id', true);
        $this->forge->createTable('temp_project', true);
    }

    public function down()
    {
        $this->forge->dropTable('temp_project');
    }
}