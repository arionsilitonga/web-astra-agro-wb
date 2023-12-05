<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WBParameters extends Seeder
{
    public function run()
    {
		//Baudrate
		$this->db->table('m_parameters')->insert([
			'parameter_code' => 'WBBAUDRATE',
			'description' => 'PENYESUAIAN BAUDRATE TIMBANGAN',
			'form_code' => 'WEIGHT CONFIG FORM',
			'data_type' => 'VARCHAR',
		]);
		$this->db->table('m_parameter_values')->insert([
			'parameter_code' => 'WBBAUDRATE',
			'value' => '2400',
			'description' => '',
			'order_number' => 0,
			'active' => 'Y',
		]);

		//parity
		$this->db->table('m_parameters')->insert([
			'parameter_code' => 'WBPARITY',
			'description' => 'PENYESUAIAN PARITY TIMBANGAN',
			'form_code' => 'WEIGHT CONFIG FORM',
			'data_type' => 'VARCHAR',
		]);
		$this->db->table('m_parameter_values')->insert([
			'parameter_code' => 'WBPARITY',
			'value' => 'even',
			'description' => '[none | even | odd]',
			'order_number' => 0,
			'active' => 'Y',
		]);

		//DataLen
		$this->db->table('m_parameters')->insert([
			'parameter_code' => 'WBDATALEN',
			'description' => 'PENYESUAIAN DATALEN TIMBANGAN',
			'form_code' => 'WEIGHT CONFIG FORM',
			'data_type' => 'VARCHAR',
		]);
		$this->db->table('m_parameter_values')->insert([
			'parameter_code' => 'WBDATALEN',
			'value' => '7',
			'description' => '',
			'order_number' => 0,
			'active' => 'Y',
		]);

		//StopBits
		$this->db->table('m_parameters')->insert([
			'parameter_code' => 'WBSTOPBITS',
			'description' => 'PENYESUAIAN STOPBITS TIMBANGAN',
			'form_code' => 'WEIGHT CONFIG FORM',
			'data_type' => 'VARCHAR',
		]);
		$this->db->table('m_parameter_values')->insert([
			'parameter_code' => 'WBSTOPBITS',
			'value' => '2',
			'description' => '',
			'order_number' => 0,
			'active' => 'Y',
		]);

		//Browser Refresh Rate
		$this->db->table('m_parameters')->insert([
			'parameter_code' => 'WBBROWSERREFRESH',
			'description' => 'PENYESUAIAN BROWSERREFRESH TIMBANGAN',
			'form_code' => 'WEIGHT CONFIG FORM',
			'data_type' => 'VARCHAR',
		]);
		$this->db->table('m_parameter_values')->insert([
			'parameter_code' => 'WBBROWSERREFRESH',
			'value' => '2000',
			'description' => '',
			'order_number' => 0,
			'active' => 'Y',
		]);

    }
}
