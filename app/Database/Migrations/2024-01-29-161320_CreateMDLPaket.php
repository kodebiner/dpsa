<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMDLPaket extends Migration
{
    public function up()
    {
        $fields = [
            'mdlid'             => ['type' => 'INT', 'constraint' => 11],
            'paketid'           => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addField($fields);
        $this->forge->createTable('mdl_paket', true);
    }

    public function down()
    {
        $this->forge->dropTable('mdl_paket');
    }
}