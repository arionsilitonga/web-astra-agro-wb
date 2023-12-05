<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MCustomer extends Migration
{
    public function up()
    {
		$this->forge->addField([
			'customercode' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'name' => [
				'type' => 'varchar',
				'constraint' => '100',
			],
			'address' => [
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
			],
			'phone' => [
				'type' => 'varchar',
				'constraint' => '35',
				'null' => true,
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
		$this->forge->addPrimaryKey('customercode');
		$this->forge->createTable('m_customer');
    }

    public function down()
    {
		$this->forge->dropTable('m_customer');
    }
}
