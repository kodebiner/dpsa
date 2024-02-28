<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProyekSpk extends Migration
{
    public function up()
    {
        $fields = [
            'no_sph'    => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'ded'],
            'tahun'     => ['type' => 'datetime', 'null' => true, 'after' => 'no_sph'],
        ];
        $this->forge->addColumn('project', $fields);
    }
    
    public function down()
    {
        $fields = [
            'no_sph',
            'tahun',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}
