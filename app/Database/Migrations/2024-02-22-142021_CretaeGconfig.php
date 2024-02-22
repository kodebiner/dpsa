<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGconfig extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ppn'               => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gconfig', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('gconfig');
    }
}
