<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProyekMarketing extends Migration
{
    public function up()
    {
        $fields = [
            'marketing'    => ['type' => 'INT', 'constraint' => 11, 'after' => 'clientid'],
        ];
        $this->forge->addColumn('project', $fields);
    }
    
    public function down()
    {
        $fields = [
            'marketing',
        ];
        $this->forge->dropColumn('project', $fields);
    }
}
