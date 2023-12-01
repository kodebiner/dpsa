<?php namespace App\Models;

use CodeIgniter\Model;

class PaketDetailModel extends Model
{
    protected $allowedFields = [
        'paketid', 'mdlid',
    ];
    
    protected $returnType       = 'array';
    protected $table            = 'paketdetail';
}