<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVersion extends Migration
{
    public function up()
    {
        $fields = [
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'projectid'     => ['type' => 'INT', 'constraint' => 11],
            'file'          => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true],
            'type'          => ['type' => 'INT', 'constraint' => 11],

        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('version', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('version');
    }
}