<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCustomRabQty extends Migration
{
    public function up()
    {
        $fields = [
            'qty'              => ['type' => 'INT', 'constraint' => 11],
        ];
        $this->forge->addColumn('customrab', $fields);
    }
    
    public function down()
    {
        $fields = [
            'qty',
        ];
        $this->forge->dropColumn('customrab', $fields);
    }
}