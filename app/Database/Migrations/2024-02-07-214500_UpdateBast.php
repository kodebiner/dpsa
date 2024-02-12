<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBast extends Migration
{
    public function up()
    {
        $fields = [
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
        ];
        $this->forge->addColumn('bast', $fields);
    }

    public function down()
    {
        $fields = [
            'created_at',
            'updated_at',
        ];
        $this->forge->dropColumn('bast', $fields);
    }
}