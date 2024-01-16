<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMDL extends Migration
{
    public function up()
    {
        $fields = [
            'keterangan'               => ['type' => 'TEXT', 'null' => true, 'after' => 'paketid'],
        ];
        $this->forge->addColumn('mdl', $fields);
    }

    public function down()
    {
        $fields = [
            'keterangan',
        ];
        $this->forge->dropColumn('mdl', $fields);
    }
}