<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAuth extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'email' => [
				'type' => 'varchar',
				'constraint' => 150,
			],
			'auth_to' => [
				'type' => 'varchar',
				'constraint' => '255',
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
		$this->forge->addPrimaryKey('id');
		$this->forge->createTable('user_auth');
	}

	public function down()
	{
		$this->forge->dropTable('user_auth');
	}
}