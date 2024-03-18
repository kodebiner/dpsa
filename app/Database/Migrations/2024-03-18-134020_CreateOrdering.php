<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdering extends Migration
{
    public function up()
    {
        $fields = [
            'ordering'      => ['type' => 'INT', 'constraint' => 11,],
        ];
        $this->forge->addColumn('mdl_paket', $fields);
        $this->forge->addColumn('paket', $fields);
    }
    
    public function down()
    {
        $fields = [
            'ordering',
        ];
        $this->forge->dropColumn('mdl_paket', $fields);
        $this->forge->dropColumn('paket', $fields);
    }
}