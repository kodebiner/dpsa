<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCompanyBank extends Migration
{
    public function up()
    {
        $fields = [
            'bank'          => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'phone'],
            'no_rek'        => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'bank'],
            'pic'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'no_rek'],
            'rscode'        => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'rsname'],
        ];
        $this->forge->addColumn('company', $fields);
    }
    
    public function down()
    {
        $fields = [
            'bank',
            'no_rek',
            'pic',
            'rscode',
        ];
        $this->forge->dropColumn('company', $fields);
    }
}
