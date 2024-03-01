<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGconfNpwp extends Migration
{
    public function up()
    {
        $fields = [
            'npwp'      => ['type' => 'INT', 'constraint' => 11, 'after' => 'ppn'],
        ];
        $this->forge->addColumn('gconfig', $fields);
    }
    
    public function down()
    {
        $fields = [
            'npwp',
        ];
        $this->forge->dropColumn('gconfig', $fields);
    }
}
