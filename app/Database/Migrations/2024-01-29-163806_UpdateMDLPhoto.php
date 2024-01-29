<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMDLPhoto extends Migration
{
    public function up()
    {
        // Photo
        $photo = [
            'photo'             => ['type' => 'VARCHAR', 'constraint' => 255, 'NULL' => TRUE, 'after' => 'keterangan'],
        ];

        // Delete Paketid
        $paketid = [
            'paketid',
        ];
        $this->forge->addColumn('mdl', $photo);
        $this->forge->dropColumn('mdl', $paketid);
    }

    public function down()
    {
        // Delete Photo
        $photo = [
            'photo',
        ];

        // Rollback Paketid
        $paketid = [
            'paketid'           => ['type' => 'INT', 'constraint' => 11, 'after' => 'price'],
        ];
        $this->forge->dropColumn('mdl', $photo);
        $this->forge->addColumn('mdl', $paketid);
    }
}