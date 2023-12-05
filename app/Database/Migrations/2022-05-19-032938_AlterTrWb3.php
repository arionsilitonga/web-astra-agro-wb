<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTrWb3 extends Migration
{
    public function up()
    {
		$this->forge->addColumn('tr_wb', [
			'kab_prop' => [
				'type' => 'varchar',
				'constraint' => 200,
				'after' => 'nama_supplier',
			],
			'kab_createdate' => [
				'type' => 'varchar',
				'constraint' => 200,
				'after' => 'kab_prop',
			],
			'kab_createby' => [
				'type' => 'varchar',
				'constraint' => 200,
				'after' => 'kab_createdate',
			],
		]);
    }

    public function down()
    {
		$this->forge->dropColumn('tr_wb', [
			'kab_prop',
			'kab_createdate',
			'kab_createby',
		]);
    }
}
