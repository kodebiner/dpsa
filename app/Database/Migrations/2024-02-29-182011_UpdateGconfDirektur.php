<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGconfDirektur extends Migration
{
    public function up()
    {
        $fields = [
            'direktur'      => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'ppn'],
            'alamat'        => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'direktur'],
        ];
        $this->forge->addColumn('gconfig', $fields);
    }
    
    public function down()
    {
        $fields = [
            'direktur',
            'alamat',
        ];
        $this->forge->dropColumn('gconfig', $fields);
    }
}
