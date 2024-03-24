<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateNoteBukti extends Migration
{
    public function up()
    {
        $fields = [
            'note'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'file'],
        ];
        $this->forge->addColumn('bukti', $fields);
    }
    
    public function down()
    {
        $fields = [
            'note',
        ];
        $this->forge->dropColumn('bukti', $fields);
    }
}