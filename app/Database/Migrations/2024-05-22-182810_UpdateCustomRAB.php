<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateCustomRAB extends Migration
{
    public function up()
    {
        $fields = [
            'length'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'name'],
            'width'         => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'length'],
            'height'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'width'],
            'volume'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'height'],
            'denomination'  => ['type' => 'INT', 'constraint' => 11, 'after' => 'volume'],
        ];
        $this->forge->addColumn('customrab', $fields);
    }
    
    public function down()
    {
        $fields = [
            'length',
            'width',
            'height',
            'volume',
            'denomination',
        ];
        $this->forge->dropColumn('customrab', $fields);
    }
}