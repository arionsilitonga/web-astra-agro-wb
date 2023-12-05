<?php
namespace App\Models;

class MUnitModel extends MyBaseModel{
	protected $table = 'm_unit';
	protected $primaryKey = 'unitcode';

	protected $useSoftDeletes = true;
	protected $deletedField  = 'deleted_at';

	protected $fields = [
		'unitcode' => [
			'type' => 'varchar',
			'constraint' => '20',
			//'null' => true,
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'transportercode' => [
			'type' => 'dropdown',
			'source' => [
				'table' => 'm_transporter',
				'key' => 'transportercode',
				'text' => 'name',
			],
			'constraint' => '20',
			'null' => true,
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'platenumber' => [
			'type' => 'varchar',
			'constraint' => '15',
			'search' => true,
			'order' => true,
			'list' => true,
		],
		'capacity' => [
			'type' => 'int',
			//'search' => true,
			'order' => true,
			'list' => true,
		],
		'capacity_human' => [
			'type' => 'int',
			//'search' => true,
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
		/*
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
		*/
	];

	protected $validationRules = [
		'unitcode' => 'trim|required',
		'transportercode' => 'trim',
		'platenumber' => 'trim|required',
		'active' => 'trim',
	];
}
?>