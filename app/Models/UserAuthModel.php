<?php
namespace App\Models;

use CodeIgniter\Model;

class UserAuthModel extends Model{
	protected $table = 'user_auth';
	protected $primaryKey = 'id';

	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $allowedFields = [
		'email',
		'auth_to',
	];
}