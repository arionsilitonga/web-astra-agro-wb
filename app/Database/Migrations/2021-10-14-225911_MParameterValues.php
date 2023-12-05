<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MParameterValues extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'id' => [
				'type' => 'INT',
				//'constraint' => 5,
				'unsigned' => true,
				'auto_increment' => true
			],
			'parameter_code' => [
				'type' => 'varchar',
				'constraint' => 20,
			],
			'value' => [
				'type' => 'varchar',
				'constraint' => 50,
			],
			'description' => [
				'type' => 'varchar',
				'constraint' => 50,
			],
			'order_number' => [
				'type' => 'INT',
				'unsigned' => true,
				'null' => false,
			],
			'active' => [
				'type' => 'CHAR',
				'default' => 'Y'
			],
		]);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->addUniqueKey(['parameter_code', 'value']);
		$this->forge->createTable('m_parameter_values');
    }

    public function down()
    {
        $this->forge->dropTable('m_parameter_values');
    }
}
