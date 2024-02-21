<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomRAB extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'name'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'price'             => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('customrab', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('customrab');
    }
}
