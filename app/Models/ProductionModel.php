<?php namespace App\Models;

use CodeIgniter\Model;

class ProductionModel extends Model
{
    protected $allowedFields = [
        'id', 'mdlid', 'projectid', 'gambar_kerja', 'mesin_awal', 'tukang', 'mesin_lanjutan', 'finishing', 'packing', 'setting',
    ];
    
    protected $table            = 'production';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}