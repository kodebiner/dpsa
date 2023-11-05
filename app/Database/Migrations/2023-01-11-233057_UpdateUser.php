<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUser extends Migration
{
    public function up()
    {
        $fields = [
            'firstname'         => ['type' => 'varchar', 'constraint' => 255],
            'lastname'          => ['type' => 'varchar', 'constraint' => 255],
            'parentid'          => ['type' => 'int', 'constraint' => 11],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = ['firstname', 'lastname', 'parentid'];
        $this->forge->dropColumn('users', $fields);
    }
}