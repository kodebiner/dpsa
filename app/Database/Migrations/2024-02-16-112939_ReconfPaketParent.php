<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReconfPaketParent extends Migration
{
    public function up()
    {
        $fields = [
            'parentid',
        ];
        $parent = [
            'parentid'                 => ['type' => 'INT', 'constraint' => 11, 'after' => 'id', 'default' => 0],
        ];
        $this->forge->dropColumn('paket', $fields);
        $this->forge->addColumn('paket', $parent);
    }

    public function down()
    {
        $fields = [
            'parentid',
        ];
        $parent = [
            'parentid'                 => ['type' => 'INT', 'constraint' => 11, 'after' => 'id'],
        ];
        $this->forge->dropColumn('paket', $fields);
        $this->forge->addColumn('paket', $parent);
    }
}
