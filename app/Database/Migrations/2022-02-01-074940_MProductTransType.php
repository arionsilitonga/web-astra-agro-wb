<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MProductTransType extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'id' => [
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			],
			'productcode' => [
				'type' => 'varchar',
				'constraint' => 50,
			],
			'transactioncode' => [
				'type' => 'varchar',
				'constraint' => 50,
			],
			'title' => [
				'type' => 'varchar',
				'constraint' => 100,
			],
		]);
		$this->forge->addPrimaryKey('id');
		$this->forge->addKey(['productcode', 'transactioncode']);
		$this->forge->createTable('m_product_trans_maps');
    }

    public function down()
    {
        $this->forge->dropTable('m_product_trans_maps');
    }
}
