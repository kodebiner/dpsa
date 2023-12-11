<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTypeMdl extends Migration
{
    public function up()
    {
        $fields = [
            'width'         => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'length'],
            'height'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'width'],
            'volume'        => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => true, 'after' => 'height'],
        ];
        $this->forge->dropColumn('mdl', 'width');
        $this->forge->dropColumn('mdl', 'height');
        $this->forge->dropColumn('mdl', 'volume');
        $this->forge->addColumn('mdl', $fields);
    }

    public function down()
    {
        $fields = [
            'width',
            'height',
            'volume',
        ];
        $this->forge->dropColumn('mdl', $fields);
    }
}