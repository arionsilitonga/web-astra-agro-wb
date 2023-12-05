<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MUnit extends Migration
{
    public function up()
    {
		$this->forge->addField([
			'unitcode' => [
				'type' => 'varchar',
				'constraint' => '20',
				//'null' => true,
			],
			'transportercode' => [
				'type' => 'varchar',
				'constraint' => '20',
				'null' => true,
			],
			'platenumber' => [
				'type' => 'varchar',
				'constraint' => '15',
			],
			'capacity' => [
				'type' => 'INT',
				'null' => true,
				'default' => 0,
			],
			'capacity_human' => [
				'type' => 'INT',
				'null' => true,
				'default' => 0,
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
		$this->forge->addPrimaryKey('unitcode');
		$this->forge->createTable('m_unit');
	}

    public function down()
    {
		$this->forge->dropTable('m_unit');
    }
}
