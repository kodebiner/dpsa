<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProyekTanggalSPk extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_spk'     => ['type' => 'datetime', 'null' => true, 'after' => 'no_spk'],
            'batas_produksi'  => ['type' => 'datetime', 'null' => true, 'after' => 'tanggal_spk'],
        ];
        $this->forge->addColumn('project', $fields);
    }
    
    public function down()
    {
        $fields = [
            'tanggal_spk',
            'batas_produksi',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}