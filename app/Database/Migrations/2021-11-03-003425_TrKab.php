<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrKab extends Migration
{
    public function up()
    {
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'chitnumber' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'sabno' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'nocvalue' => [
				'type' => 'text',
			],
			'nocsite' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'nocdate' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'harvestdate' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'nocafd' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'nocblock' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'tgl_panen' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'jjg' => [
				'type' => 'integer',
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
		//$this->forge->addUniqueKey(['chitnumber', 'sabno', 'nocvalue']);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->createTable('tr_kab');
    }

    public function down()
    {
		$this->forge->dropTable('tr_kab');
    }
}