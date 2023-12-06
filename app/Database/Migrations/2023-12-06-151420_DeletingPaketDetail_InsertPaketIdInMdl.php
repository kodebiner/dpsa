<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DeletingPaketDetailInsertPaketIdInMdl extends Migration
{
    public function up()
    {
        $this->forge->dropTable('paketdetail', true);

        $fields = [
            'paketid'           => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addColumn('mdl', $fields);
    }

    public function down()
    {
        $fields = ['paketid'];
        $this->forge->dropColumn('mdl', $fields);
    }
}