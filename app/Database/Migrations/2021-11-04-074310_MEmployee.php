<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MEmployee extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'npk' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'name' => [
				'type' => 'varchar',
				'constraint' => '100',
			],
			'emp_type' => [
				'type' => 'varchar',
				'constraint' => '50',
			],
			'active' => [
				'type' => 'CHAR',
				'default' => 'Y'
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
		$this->forge->addPrimaryKey('npk');
		$this->forge->createTable('m_employee');
	}

	public function down()
	{
		$this->forge->dropTable('m_employee');
	}
}