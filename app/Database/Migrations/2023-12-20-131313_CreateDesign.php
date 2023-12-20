<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesign extends Migration
{
    public function up()
    {
        $this->forge->dropTable('design', true);
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'submitted'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'revision'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'            => ['type' => 'INT', 'constraint' => 11],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('design', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('design');
    }
}
