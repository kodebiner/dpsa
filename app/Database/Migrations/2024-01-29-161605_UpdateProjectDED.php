<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProjectDED extends Migration
{
    public function up()
    {
        $fields = [
            'type_design'           => ['type' => 'BOOLEAN', 'DEFAULT' => FALSE, 'after' => 'status_spk'],
            'ded'                   => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'type_design'],
        ];
        $this->forge->addColumn('project', $fields);
    }

    public function down()
    {
        $fields = [
            'type_design',
            'ded',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}