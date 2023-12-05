<?php
namespace App\Models;

class MEmployeeModel extends MyBaseModel{
	protected $table = 'm_employee';
	protected $primaryKey = 'npk';

	protected $fields = [
		'npk' => [
			'type' => 'varchar',
			'constraint' => '20',
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'name' => [
			'type' => 'varchar',
			'constraint' => '100',
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'emp_type' => [
			'type' => 'varchar',
			'constraint' => '50',
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'active' => [
			'type' => [
				'Y' => 'Ya (Yes)',
				'N' => 'Tidak (No)',
			],
			'default' => 'Y',
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'created_at' => [
			'type' => 'hidden',
			'null' => true,
		],
		'updated_at' => [
			'type' => 'hidden',
			'null' => true
		],
		'deleted_at' => [
			'type' => 'hidden',
			'null' => true
		],
	];

	protected $validationRules = [
		'npk' => 'required',
		'name' => 'required',
		'emp_type' => 'required',
	];
}
?>