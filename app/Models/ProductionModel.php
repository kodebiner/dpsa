<?php namespace App\Models;

use CodeIgniter\Model;

class ProductionModel extends Model
{
    protected $allowedFields = [
        'userid','mdlid', 'custrabid', 'projectid', 'gambar_kerja', 'mesin_awal', 'tukang', 'mesin_lanjutan', 'finishing', 'packing', 'pengiriman', 'setting',
    ];
    
    protected $table            = 'production';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}