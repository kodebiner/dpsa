<?php namespace App\Models;

use CodeIgniter\Model;

class MdlPaketModel extends Model
{
    protected $allowedFields = [
        'mdlid', 'paketid',
    ];
    protected $table            = 'mdl_paket';
    protected $returnType       = 'array';
}