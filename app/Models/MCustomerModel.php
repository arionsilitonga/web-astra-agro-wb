<?php
namespace App\Models;

class MCustomerModel extends MyBaseModel{
	protected $table = 'm_customer';
	protected $primaryKey = 'customercode';

	protected $fields = [
		'customercode' => [
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
		'address' => [
			'type' => 'varchar',
			'constraint' => '255',
			'null' => true,
			'search' => true,
			'order' => true,
		],
		'phone' => [
			'type' => 'varchar',
			'constraint' => '35',
			'null' => true,
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
			'type' => 'hidden', //'datetime',
			'null' => true,
			'order' => true,
		],
		'updated_at' => [
			'type' => 'hidden', //'datetime',
			'null' => true,
			'order' => true,
		],
		'deleted_at' => [
			'type' => 'hidden', //'datetime',
			'null' => true,
			'order' => true,
		],
	];

	protected $validationRules = [
		'customercode' => 'trim|required|regex_match[/^[A-Z0-9.]+$/]',
		'name' => 'trim|required',
		'address' => 'trim',
		'phone' => 'trim|regex_match[/^[0-9 -]*$/]',
	];
}