<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserMarketingCode extends Migration
{
    public function up()
    {
        $fields = [
            'kode_marketing'              => ['type' => 'VARCHAR', 'constraint' => 255],
        ];
        $this->forge->addColumn('users', $fields);
    }
    
    public function down()
    {
        $fields = [
            'kode_marketing',
        ];
        $this->forge->dropColumn('users', $fields);
    }
}