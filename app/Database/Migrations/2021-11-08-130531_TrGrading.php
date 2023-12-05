<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrGrading extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'chitnumber' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'jumlahsampling' => [
				'type' => 'int',
			],
			'bb' => [
				'type' => 'int',
			],
			'bm' => [
				'type' => 'int',
			],
			'tp' => [
				'type' => 'int',
			],
			'or' => [
				'type' => 'int',
			],
			'tks' => [
				'type' => 'int',
			],
			'adjustweight' => [
				'type' => 'int',
			],
			'created_at' => [
				'type' => 'datetime',
				'null' => true,
			],
			'updated_at' => [
				'type' => 'datetime',
				'null' => true
			],
			'deleted_at' => [
				'type' => 'datetime',
				'null' => true
			],
		]);
		$this->forge->addPrimaryKey('chitnumber');
		$this->forge->createTable('tr_grading');
	}

	public function down()
	{
		$this->forge->dropTable('tr_grading');
	}
}
?>