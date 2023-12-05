<?php
namespace App\Models;

use CodeIgniter\Model;

class MSiteModel extends MyBaseModel
{
	protected $useSoftDeletes = false;
	
	protected $table = 'm_site';
	protected $primaryKey = 'sitecode';

	protected $fields = [
		'sitecode' => [
			'type' => 'varchar',
			'constraint' => 20,
			'search' => true,
			'order' => true,
			'list' => true,	
		],
		'description' => [
			'type' => 'varchar',
			'constraint' => 100,
			'search' => true,
			'order' => true,
			'list' => true,	
		],
		'order_no' => [
			'type' => 'integer',
			'search' => true,
			'order' => true,
			'list' => true,	
		],
	];

	/* protected $allowedFields = [
		'sitecode',
		'description',
		'order_no',
	]; */

	protected $validationRules = [
		'sitecode' => 'trim|required',
		'description' => 'trim|required',
		'order_no' => 'required',
	];
}