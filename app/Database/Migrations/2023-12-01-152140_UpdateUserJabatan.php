<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserJabatan extends Migration
{
    public function up()
    {
        $fields = [
            'jabatan'         => ['type' => 'varchar', 'constraint' => 255],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = ['jabatan'];
        $this->forge->dropColumn('users', $fields);
    }
}