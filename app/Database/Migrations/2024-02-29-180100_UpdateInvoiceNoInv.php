<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateNoInvoice extends Migration
{
    public function up()
    {
        $fields = [
            'no_inv'    => ['type' => 'VARCHAR', 'constraint' => 255, 'after' => 'pic'],
            'tahun'     => ['type' => 'datetime', 'null' => true, 'after' => 'no_inv'],
        ];
        $this->forge->addColumn('invoice', $fields);
    }
    
    public function down()
    {
        $fields = [
            'no_inv',
            'tahun',
        ];
        $this->forge->dropColumn('invoice', $fields);
    }
}
