<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'email' => [
				'type' => 'varchar',
				'constraint' => 150,
			],
			'password' => [
				'type' => 'varchar',
				'constraint' => 150,
			],
			'name' => [
				'type' => 'varchar',
				'constraint' => 100,
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
		$this->forge->addPrimaryKey('email', true);
		$this->forge->createTable('users', true);
    }

    public function down()
    {
		$this->forge->dropTable('users');
    }
}
