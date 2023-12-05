<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTrWB0 extends Migration
{
    public function up()
    {
        /*$this->forge->addColumn('tr_wb', [
			'kabcode' => [
				'type' => 'varchar',
				'constraint' => '150',
				'after' => 'kab_type',
			],
			'INDEX(kabcode)',
		]);*/
		$this->db->query('ALTER TABLE tr_wb ADD COLUMN kabcode VARCHAR(150) AFTER `kab_type`, ADD INDEX(kabcode)');
    }

    public function down()
    {
		$this->forge->dropColumn('tr_wb', 'kabcode');
    }
}
