<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTypeMdl extends Migration
{
    public function up()
    {
        $fields = [
            'width' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'height' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'volume' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
        ];
        $this->forge->modifyColumn('mdl', $fields);
    }

    public function down()
    {
        $fields = [
            'width' => [
                'type'          => 'INT',
                'constraint'    => 11,
            ],
            'height' => [
                'type'          => 'INT',
                'constraint'    => 11,
            ],
            'volume' => [
                'type'          => 'INT',
                'constraint'    => 11,
            ],
        ];
        $this->forge->modifyColumn('mdl', $fields);
    }
}