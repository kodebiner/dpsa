<?php namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $allowedFields = [
        'id', 'clientid',
    ];
    
    protected $table            = 'purchaseorder';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}