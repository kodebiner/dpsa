<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePengirimanProduksi extends Migration
{
    public function up()
    {
        $fields = [
            'pengiriman'        => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE, 'after' => 'packing'],
            'userid'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'id'],
        ];
        $this->forge->addColumn('production', $fields);
    }
    
    public function down()
    {
        $fields = [
            'pengiriman',
            'userid',
        ];
        $this->forge->dropColumn('production', $fields);
    }
}