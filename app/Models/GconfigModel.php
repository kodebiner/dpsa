<?php

namespace App\Models;

use CodeIgniter\Model;

class GconfigModel extends Model
{
    protected $allowedFields = [
        'ppn', 'direktur', 'alamat', 'npwp',
    ];
    protected $table            = 'gconfig';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}
