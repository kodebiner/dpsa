<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCompany extends Migration
{
    public function up()
    {
        $fields = [
            'created_at'        => ['type' => 'datetime', 'null' => true],
            'updated_at'        => ['type' => 'datetime', 'null' => true],
            'deleted_at'        => ['type' => 'datetime', 'null' => true],
        ];
        $this->forge->addColumn('company', $fields);
    }

    public function down()
    {
        $fields = [
            'created_at',
            'updated_at',
            'deleted_at',
        ];
        $this->forge->dropColumn('company', $fields);
    }
}