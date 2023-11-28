<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMDL extends Migration
{
    public function up()
    {
        $fields = [
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'length'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true],
            'width'         => ['type' => 'INT', 'constraint' => 11, 'NULL' => true],
            'height'        => ['type' => 'INT', 'constraint' => 11, 'NULL' => true],
            'volume'        => ['type' => 'INT', 'constraint' => 11, 'NULL' => true],
            'denomination'  => ['type' => 'INT', 'constraint' => 11],
            'price'         => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('mdl', true);
    }

    public function down()
    {
        $this->forge->dropTable('mdl');
    }
}