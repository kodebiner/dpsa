<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserPosition extends Migration
{
    public function up()
    {
        $fields = [
            'position'         => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = ['position'];
        $this->forge->dropColumn('users', $fields);
    }
}