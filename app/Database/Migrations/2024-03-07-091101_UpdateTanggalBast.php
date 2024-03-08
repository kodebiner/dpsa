<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTanggalBast extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_bast'      => ['type' => 'datetime', 'null' => true, 'after' => 'status'],
        ];
        $this->forge->addColumn('bast', $fields);
    }
    
    public function down()
    {
        $fields = [
            'tanggal_bast',
        ];
        $this->forge->dropColumn('bast', $fields);
    }
}