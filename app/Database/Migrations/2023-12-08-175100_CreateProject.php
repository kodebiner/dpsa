<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProject extends Migration
{
    public function up()
    {
        $fields = [
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 255],
            'brief'            => ['type' => 'varchar', 'constraint' => 255],
            'status'           => ['type' => 'int', 'constraint' => 11],
            'production'       => ['type' => 'int', 'constraint' => 11],
            'clientid'         => ['type' => 'int', 'constraint' => 11],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('project', true);
        $this->forge->dropTable('temp_project', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('project');
    }
}
