<?php namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $allowedFields = [
        'userid', 'keterangan', 'url', 'status',
    ];
    protected $table            = 'notification';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
}