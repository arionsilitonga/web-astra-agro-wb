<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTrWb extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tr_wb', [
			'gate_in' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'operator_out',
			],
			'gate_out' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'gate_in',
			],
			'boarding_in' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'gate_out',
			],
			'jenis_unit' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'boarding_in',
			],
			'nomor_polisi' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'jenis_unit',
			],
			'nama_driver' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'nomor_polisi',
			],
			'kode_supplier' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'nama_driver',
			],
			'nama_supplier' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'kode_supplier',
			],
			'wilayah_asal_tbs' => [
				'type' => 'varchar',
				'constraint' => 150,
				'after' => 'kode_supplier',
			],
		]);
    }

    public function down()
    {
        $this->forge->dropColumn('tr_wb', [
			'gate_in',
			'gate_out',
			'boarding_in',
			'jenis_unit',
			'nomor_polisi',
			'nama_driver',
			'kode_supplier',
			'nama_supplier',
			'wilayah_asal_tbs',
		]);
    }
}
