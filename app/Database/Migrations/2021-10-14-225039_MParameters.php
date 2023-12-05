<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MParameters extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'parameter_code' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'description' => [
				'type' => 'varchar',
				'constraint' => 50,
			],
			'form_code' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'data_type' => [
				'type' => 'varchar',
				'constraint' => 10,
			],
		]);
		$this->forge->addPrimaryKey('parameter_code', true);
		$this->forge->createTable('m_parameters', true);
    }

    public function down()
    {
		$this->forge->dropTable('m_parameters');
    }
}
