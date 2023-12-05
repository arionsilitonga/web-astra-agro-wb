<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTrWb2 extends Migration
{
    public function up()
    {
		$this->db->query('ALTER TABLE `tr_wb` ADD COLUMN `nomorticket` VARCHAR(50) AFTER `operator_out`, ADD INDEX(`nomorticket`)');
    }

    public function down()
    {
        $this->forge->dropColumn('tr_wb', [
			'nomorticket',
		]);
    }
}
