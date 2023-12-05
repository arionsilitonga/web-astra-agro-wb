<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends MyBaseModel{
	protected $table = 'users';
	protected $primaryKey = 'email';

	protected $fields = [
		'email' => [
			'type' => 'varchar',
			'constraint' => 150,
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'password' => [
			'type' => 'hidden',
			'constraint' => 150,
		],
		'name' => [
			'type' => 'varchar',
			'constraint' => 100,
			'search' => true,
			'order' => true,
			'list' => true,
		],
	];

	/*
	protected $allowedFields = [
		'email',
		'password',
		'name',
	];
	*/

	protected $validationRules = [
		'email' => 'required',
		'name' => 'required',
	];
}