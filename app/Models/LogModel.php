<?php
namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model{
	protected $table = 'tr_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $useTimestamps = false;
    
    protected $allowedFields = ['username', 'menu', 'action', 'form_data', 'created_at'];
}