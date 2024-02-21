<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProjectInvoice extends Migration
{
    public function up()
    {
        $fields = [
            'inv1'        => ['type' => 'datetime', 'null' => true],
            'inv2'        => ['type' => 'datetime', 'null' => true],
            'inv3'        => ['type' => 'datetime', 'null' => true],
            'inv4'        => ['type' => 'datetime', 'null' => true],
        ];
        $this->forge->addColumn('project', $fields);
    }

    public function down()
    {
        $fields = [
            'inv1',
            'inv2',
            'inv3',
            'inv4',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}
