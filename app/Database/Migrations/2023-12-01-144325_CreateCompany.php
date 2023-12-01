<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompany extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'rsname'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'ptname'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'address'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'npwp'              => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'parentid'          => ['type' => 'INT', 'constraint' => 11],
            'status'            => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('company', true);
    }

    public function down()
    {
        $this->forge->dropTable('company');
    }
}