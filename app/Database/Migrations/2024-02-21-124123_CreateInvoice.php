<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoice extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'jatuhtempo'        => ['type' => 'DATETIME', 'null' => true],
            'referensi'         => ['type' => 'INT', 'constraint' => 11],
            'pph23'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'            => ['type' => 'INT', 'constraint' => 11],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('invoice', true);
    }
    
    public function down()
    {
        $this->forge->dropTable('invoice');
    }
}
