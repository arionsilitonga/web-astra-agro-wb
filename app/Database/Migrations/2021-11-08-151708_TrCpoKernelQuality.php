<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrCpoKernelQuality extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'chitnumber' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'ffa' => [
				'type' => 'int',
			],
			'temperature' => [
				'type' => 'int',
			],
			'moist' => [
				'type' => 'int',
			],
			'dirt' => [
				'type' => 'int',
			],
			'kernel_pecah' => [
				'type' => 'int',
			],
			'seal_number' => [
				'type' => 'varchar',
				'constraint' => '15',
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
		$this->forge->createTable('tr_cpo_kernel_quality');
    }

    public function down()
    {
		$this->forge->dropTable('tr_cpo_kernel_quality');
    }
}
