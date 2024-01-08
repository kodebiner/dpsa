<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSPK extends Migration
{
    public function up()
    {
        $fields = [
            'spk'               => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'clientid'],
            'status_spk'        => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after' => 'spk'],
        ];
        $this->forge->addColumn('project', $fields);
    }

    public function down()
    {
        $fields = [
            'spk',
            'status_spk',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}