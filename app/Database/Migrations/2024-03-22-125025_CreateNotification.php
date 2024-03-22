<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotification extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'userid'            => ['type' => 'INT', 'constraint' => 11],
            'keterangan'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'url'               => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'            => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('notification', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('notification');
    }
}
