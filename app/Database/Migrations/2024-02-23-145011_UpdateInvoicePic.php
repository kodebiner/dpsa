<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePicInvoice extends Migration
{
    public function up()
    {
        $fields = [
            'pic'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'status'],
        ];
        $this->forge->addColumn('invoice', $fields);
    }
    
    public function down()
    {
        $fields = [
            'pic',
        ];
        $this->forge->dropColumn('invoice', $fields);
    }
}