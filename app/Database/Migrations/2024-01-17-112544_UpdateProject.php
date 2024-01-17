<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProject extends Migration
{
    public function up()
    {
        $fields = [
            'brief',
            'production',
        ];
        $this->forge->dropColumn('project', $fields);
    }

    public function down()
    {
        $fields = [
            'brief'                 => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'name'],
            'production'            => ['type' => 'INT', 'constraint' => 11, 'after' => 'status'],
        ];
        $this->forge->addColumn('project', $fields);
    }
}