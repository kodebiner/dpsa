<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePaketParent extends Migration
{
    public function up()
    {
        $fields = [
            'parentid'                 => ['type' => 'INT', 'constraint' => 11, 'after' => 'id'],
        ];
        $this->forge->addColumn('paket', $fields);
    }

    public function down()
    {
        $fields = [
            'parentid'
        ];
        $this->forge->dropColumn('paket', $fields);
    }
}