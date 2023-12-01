<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaketDetail extends Migration
{
    public function up()
    {
        $fields = [
            'paketid'           => ['type' => 'INT', 'constraint' => 11],
            'mdlid'             => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addField($fields);
        $this->forge->createTable('paketdetail', true);
    }

    public function down()
    {
        $this->forge->dropTable('paketdetail');
    }
}