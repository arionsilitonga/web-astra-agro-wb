<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Demo extends Seeder
{
	public function run()
	{
		$customer = [
			[
				'customercode' => 'CV. MGP',
				'name' => 'CV. MANDIRI GILANG PERKASA',
				'active' => 'Y',
			],
			[
				'customercode' => 'SAI1',
				'name' => 'PT. SAWIT ASAHAN INDAH',
				'active' => 'Y',
			],
		];
		$this->db->table('m_customer')->insertBatch($customer);

		$employes = [
			['npk' => "9231245", 'name' => 'ARION', 'emp_type' => 'SKU'],
			['npk' => "2454544", 'name' => 'ELIOT', 'emp_type' => 'Mandor'],
			['npk' => "3234325", 'name' => 'BEDU', 'emp_type' => 'Driver'],
			['npk' => "3234320", 'name' => 'SAIPUL', 'emp_type' => 'Helper'],
		];
		$this->db->table('m_employee')->insertBatch($employes);

		$transporter = [
			[
				'transportercode' => 'TBPP',
				'name' => 'PT. TRI BHAKTI PRIMA PERKASA',
				'active' => 'Y',
				'transportertype' => 1, //External
			],
			[
				'transportercode' => 'SAI1',
				'name' => 'PT. SAWIT ASAHAN INDAH',
				'active' => 'Y',
				'transportertype' => 0, //Internal
			],
		];
		$this->db->table('m_transporter')->insertBatch($transporter);

		$units = [
			[
				'unitcode' => "SAIDT001",
				'platenumber' => 'BA 2343 KCL',
				'transportercode' => 'SAI1',
				'active' => 'Y',
			],
			[
				'unitcode' => "SAIDT002",
				'platenumber' => 'BA 2345 KCL',
				'transportercode' => 'SAI1',
				'active' => 'Y',
			],
			[
				'unitcode' => "XSDIDA001",
				'platenumber' => 'BK 2223 DD',
				'transportercode' => 'TBPP',
				'active' => 'Y',
			],
			[
				'unitcode' => "XSAIDT001",
				'platenumber' => 'BK 2223 DD',
				'transportercode' => 'TBPP',
				'active' => 'Y',
			],
		];
		$this->db->table('m_unit')->insertBatch($units);

	}
}
