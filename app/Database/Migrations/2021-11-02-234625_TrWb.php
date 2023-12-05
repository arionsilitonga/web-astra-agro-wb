<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrWb extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'wbsitecode' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'sitecode' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'chitnumber' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'sabno' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'customercode' => [
				'type' => 'varchar',
				'constraint' => '20',
				'null' => true,
			],
			'productcode' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'transactiontype' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'unitcode' => [
				'type' => 'varchar',
				'constraint' => '20',
				'null' => true,
			],
			'npk_driver' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'npk_helper1' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'npk_helper2' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'boarding' => [
				'type' => 'datetime',
				'null' => true,
			],
			'wb_in' => [
				'type' => 'datetime',
			],
			'wb_out' => [
				'type' => 'datetime',
				'null' => true,
			],
			'weight_in' => [
				'type' => 'integer',
			],
			'weight_out' => [
				'type' => 'integer',
				'null' => true,
			],
			'kab_type' => [
				'type' => 'varchar',
				'constraint' => '20',
			],
			'kabraw' => [
				'type' => 'longtext',
				'null' => true,
			],
			'operator_in' => [
				'type' => 'varchar',
				'constraint' => '50',
			],
			'terminal_in' => [
				'type' => 'datetime',
				'null' => true,
			],
			'terminal_out' => [
				'type' => 'datetime',
				'null' => true,
			],
			'operator_out' => [
				'type' => 'varchar',
				'constraint' => '50',
				'null' => true,
			],
			'status' => [
				'type' => 'char',
				'constraint' => '1',
				//'null' => true,
				'default' => 0
			],
			'sent' => [
				'type' => 'char',
				'constraint' => '1',
				//'null' => true,
				'default' => 0
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
		$this->forge->addPrimaryKey('chitnumber', true);
		$this->forge->createTable('tr_wb');
    }

    public function down()
    {
		$this->forge->dropTable('tr_wb');
    }
}
