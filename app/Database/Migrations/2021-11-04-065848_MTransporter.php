<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MTransporter extends Migration
{
    public function up()
    {
		$this->forge->addField([
			'transportercode' => [
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
			'transportertype' => [
				'type' => 'varchar', /** 0 => internal, 1 => external */
				'constraint' => '15',
				'default' => 'INTERNAL',
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
		$this->forge->addPrimaryKey('transportercode');
		$this->forge->createTable('m_transporter');
    }

    public function down()
    {
		$this->forge->dropTable('m_transporter');
    }
}
