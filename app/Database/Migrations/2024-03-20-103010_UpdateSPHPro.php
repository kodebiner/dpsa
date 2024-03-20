<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSPHPro extends Migration
{
    public function up()
    {
        $fields = [
            'sph'              => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addColumn('project', $fields);
    }
    
    public function down()
    {
        $fields = [
            'sph',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}