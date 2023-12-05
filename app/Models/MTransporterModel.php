<?php
namespace App\Models;

use CodeIgniter\Model;

class MTransporterModel extends MyBaseModel{
	protected $table = 'm_transporter';
	protected $primaryKey = 'transportercode';

	protected $fields = [
		'transportercode' => [
			'type' => 'varchar',
			'constraint' => '20',
			'list' => true,
			'order' => true,
			'search' => true,
		],
		'name' => [
			'type' => 'varchar',
			'constraint' => '100',
			'list' => true,
			'order' => true,
			'search' => true,
		],
		'address' => [
			'type' => 'varchar',
			'constraint' => '255',
			'null' => true,
			//'list' => true,
			//'order' => true,
			//'search' => true,
		],
		'phone' => [
			'type' => 'varchar',
			'constraint' => '35',
			'null' => true,
			'list' => true,
			'order' => true,
			'search' => true,
		],
		'active' => [
			'type' => [
				'Y' => 'Ya (Yes)',
				'N' => 'Tidak (No)',
			],
			'default' => 'Y',
			'search' => true,
			'list' => true,
			'order' => true,
			'search' => true,
		],
		'transportertype' => [
			'type' => [
				'INTERNAL' => 'INTERNAL',
				'EXTERNAL' => 'EXTERNAL',
			], /** 0 => internal, 1 => external */
			'constraint' => '20',
			'unsigned' => true,
			'list' => true,
			'order' => true,
			'search' => true,
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
		'transportercode' => 'trim|required|alpha_numeric',
		'name' => 'trim|required',
		'address' => 'trim',
		'phone' => 'trim',
		'active' => 'trim',
		'transportertype' => 'trim',
	];
}