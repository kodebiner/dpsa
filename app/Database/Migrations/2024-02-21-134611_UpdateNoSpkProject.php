<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateNoSpkProject extends Migration
{
    public function up()
    {
        $fields = [
            'no_spk'             => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => TRUE, 'after' => 'spk'],
        ];
        $this->forge->addColumn('project', $fields);
    }
    
    public function down()
    {
        $fields = [
            'no_spk',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}
