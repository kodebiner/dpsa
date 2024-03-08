<?php namespace App\Models;

use CodeIgniter\Model;

class BastModel extends Model
{
    protected $allowedFields = [
        'projectid', 'file', 'status','tanggal_bast', 'created_at', 'updated_at',
    ];
    protected $table            = 'bast';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useTimestamps    = true;
    protected $returnType       = 'array';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}