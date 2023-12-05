<?php

namespace App\Models;

use CodeIgniter\Model;

class TrGradingModel extends Model{
	protected $table = "tr_grading";
	protected $primaryKey = 'chitnumber';
	
	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $allowedFields = [
		'chitnumber',
		'jumlahsampling',
		'bb',
		'bm',
		'tp',
		'or',
		'tks',
		'adjustweight',
	];

}