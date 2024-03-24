<?php namespace App\Models;

use CodeIgniter\Model;

class BuktiModel extends Model
{
    protected $allowedFields = [
        'projectid', 'file', 'note', 'status', 'created_at',
    ];
    protected $table            = 'bukti';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}