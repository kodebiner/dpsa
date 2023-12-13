<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRAB extends Migration
{
    public function up()
    {
        $fields = [
            'paketid'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'mdlid'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ];
        $this->forge->dropColumn('rab', 'qty_deliver');
        $this->forge->dropColumn('rab', 'qty_complete');
        $this->forge->addColumn('rab', $fields);
    }

    public function down()
    {
        $fields = [
            'qty_deliver'       => ['type' => 'INT', 'constraint' => 11],
            'qty_complete'      => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->dropColumn('rab', 'paketid');
        $this->forge->dropColumn('rab', 'created_at');
        $this->forge->dropColumn('rab', 'updated_at');
        $this->forge->dropColumn('rab', 'deleted_at');
        $this->forge->addColumn('rab', $fields);
    }
}