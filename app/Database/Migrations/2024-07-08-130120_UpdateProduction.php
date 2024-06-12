<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProductionCustrab extends Migration
{
    public function up()
    {
        $fields = [
            'custrabid'              => ['type' => 'INT', 'constraint' => 11, 'after' => 'mdlid'],
        ];
        $this->forge->addColumn('production', $fields);
    }
    
    public function down()
    {
        $fields = [
            'custrabid',
        ];
        $this->forge->dropColumn('production', $fields);
    }
}