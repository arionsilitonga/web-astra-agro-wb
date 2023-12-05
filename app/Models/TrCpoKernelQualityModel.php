<?php
namespace App\Models;

use CodeIgniter\Model;

class TrCpoKernelQualityModel extends Model {
	protected $table = 'tr_cpo_kernel_quality';
	protected $primaryKey = 'chitnumber';
	
	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $allowedFields = [
		'chitnumber',
		'ffa',
		'temperature',
		'moist',
		'dirt',
		'kernel_pecah',
		'seal_number',
	];
}
?>