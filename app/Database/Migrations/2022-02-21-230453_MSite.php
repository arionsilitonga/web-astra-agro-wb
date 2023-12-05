<?php

namespace App\Database\Migrations;

use App\Models\MSiteModel;
use CodeIgniter\Database\Migration;

class CreateMSiteTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'sitecode' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'description' => [
				'type' => 'varchar',
				'constraint' => '100',
			],
			'order_no' => [
				'type' => 'INT',
				'unsigned' => true,
				'null' => false
			],
			/*'created_at' => [
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
			],*/
		]);
		$this->forge->addPrimaryKey('sitecode', true);
		$this->forge->createTable('m_site');

		$mSiteModel = new MSiteModel();
		$mSiteModel->insertBatch([
			['sitecode' => 'SAI1', 'description' => 'PT. SAWIT ASAHAN INDAH 1', 'order_no' => 0],
			['sitecode' => 'SLS1', 'description' => 'PT. SARI LEMBAH SUBUR 1', 'order_no' => 1],
		]);
	}

	public function down()
	{
		$this->forge->dropTable('m_site');
	}
}
