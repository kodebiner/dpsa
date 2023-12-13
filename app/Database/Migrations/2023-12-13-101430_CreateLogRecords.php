<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogRecords extends Migration
{
    public function up()
    {
        $fields = [
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uid'           => ['type' => 'INT', 'constraint' => 11],
            'record'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('logrecords', true);
    }

    public function down()
    {
        $this->forge->dropTable('logrecords');
    }
}