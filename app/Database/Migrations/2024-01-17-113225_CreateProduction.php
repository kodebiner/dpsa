<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduction extends Migration
{
    public function up()
    {
        $fields = [
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'mdlid'             => ['type' => 'INT', 'constraint' => 11],
            'projectid'         => ['type' => 'INT', 'constraint' => 11],
            'gambar_kerja'      => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'mesin_awal'        => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'tukang'            => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'mesin_lanjutan'    => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'finishing'         => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'packing'           => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
            'setting'           => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('production', true);
    }

    public function down()
    {
        $this->forge->dropTable('production');
    }
}