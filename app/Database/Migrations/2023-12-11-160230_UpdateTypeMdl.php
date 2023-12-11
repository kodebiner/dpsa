<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTypeMdl extends Migration
{
    public function up()
    {
        $fields = [
            'width' => [
                'name'          => 'width',
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'height' => [
                'name'          => 'height',
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'volume' => [
                'name'          => 'volume',
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
                'name'          => 'width',
                'type'          => 'INT',
                'constraint'    => 11,
            ],
            'height' => [
                'name'          => 'height',
                'type'          => 'INT',
                'constraint'    => 11,
            ],
            'volume' => [
                'name'          => 'volume',
                'type'          => 'INT',
                'constraint'    => 11,
            ],
        ];
        $this->forge->modifyColumn('mdl', $fields);
    }
}