<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFileInvoice extends Migration
{
    public function up()
    {
        $fields = [
            'file'      => ['type' => 'varchar', 'constraint' => 255 , 'null' => true, 'after' => 'no_inv'],
        ];
        $this->forge->addColumn('invoice', $fields);
    }
    
    public function down()
    {
        $fields = [
            'file',
        ];
        $this->forge->dropColumn('invoice', $fields);
    }
}