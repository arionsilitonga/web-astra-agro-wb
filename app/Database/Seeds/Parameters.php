<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Parameters extends Seeder
{
	public function run()
	{
		$parameters_data = [
			[
				'parameter_code' => 'COMPANYCODE',
				'description' => 'KODE PT / PABRIK',
				'form_code' => 'NAMA PT',
				'data_type' => 'VARCHAR',
			],
			[
				'parameter_code' => 'TRANSACTIONTYPE',
				'description' => 'JENIS TRANSAKSI TIMBANG',
				'form_code' => 'JENIS TRANSAKSI',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'PRODUCTCODE',
				'description' => 'JENIS PRODUK ANGKUTAN',
				'form_code' => 'KODE PRODUK',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'TRANSPORTERTYPE',
				'description' => 'JENIS ANKUTAN BERDASARKAN PEMILIK',
				'form_code' => 'JENIS ANGKUTAN',
				'data_type' => 'VARCHAR',
			],
			[
				'parameter_code' => 'APICONFIG',
				'description' => 'CONFIG ALAMAT API',
				'form_code' => 'ALAMAT BASE API',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'CHITNUMBERCONFIG',
				'description' => 'KODE PREFIX NOMOR TRANSAKSI',
				'form_code' => 'KODE NOMOR CHIT',
				'data_type' => 'VARCHAR',
			],
			[
				'parameter_code' => 'SABNOCONFIG',
				'description' => 'KODE PRFIX NOMOR SURAT ANTAR BUAT',
				'form_code' => 'KODE NOMOR SAB',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'READERCONFIG',
				'description' => 'PORT NUMBER OF NFC READER',
				'form_code' => 'PORT NFC READER',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'WBPORTCONFIG',
				'description' => 'PORT NUMBER OF INDICATOR WB',
				'form_code' => 'PORT INDIKATOR',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'WBOUTPUTCONFIG',
				'description' => 'POLA OUTPUT SERIAL DARI WB',
				'form_code' => 'POLA SERIAL WB',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'DATEFORMATCONFIG',
				'description' => 'FORMAT TANGGAL',
				'form_code' => 'FORMAT TANGGAL',
				'data_type' => 'VARCHAR'
			],
			[
				'parameter_code' => 'ADJUSTWEIGHT',
				'description' => 'PENYESUAIAN MANUAL BERAT TIMBANG',
				'form_code' => 'WEIGHT ADJUST FORM',
				'data_type' => 'VARCHAR',
			]
		];

		foreach ($parameters_data as $key => $data) {
			$this->db->table('m_parameters')->insert($data);
		}

		$parameter_values_data = [
			['parameter_code' => 'COMPANYCODE',	'value' => 'SAI1', 'description' => 'PT.SAWIT ASAHAN INDAH', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS Internal', 'description' => 'TBS INTI AAL', 'order_number' => 0, 'active' => 'Y',],
			//['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS Titip Olah', 'description' => 'TBS AAL diluar kebun Pabrik', 'order_number' => 1, 'active' => 'Y'],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS External', 'description' => 'TBS EXTERNAL AAL', 'order_number' => 2, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS Plasma/KKPA', 'description' => 'TBS PLASMA/KPA', 'order_number' => 3, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS Titip Olah', 'description' => 'TBS Titip Olah dari sesama Grup AAL', 'order_number' => 4, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TTG non TBS', 'description' => 'TTG non TBS', 'order_number' => 5, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Non TBS Titip Olah', 'description' => 'Non TBS Titip Olah', 'order_number' => 6, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'SPBJ', 'description' => 'SPBJ', 'order_number' => 7, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Surat Jalan', 'description' => 'Surat Jalan', 'order_number' => 8, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Nota Kirim', 'description' => 'Nota Kirim', 'order_number' => 9, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Nota Terima', 'description' => 'Nota Terima', 'order_number' => 10, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Others', 'description' => 'Others', 'order_number' => 11, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '111030377', 'description' => 'Solar', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '151000134', 'description' => 'Garam', 'order_number' => 1, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000001', 'description' => 'BORATE/BORON', 'order_number' => 2, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000002', 'description' => 'GROMAX', 'order_number' => 3, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000003', 'description' => 'HUMEGA', 'order_number' => 4, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000004', 'description' => 'HUMEGA CRUMBLE', 'order_number' => 5, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000005', 'description' => 'KIESERITTE', 'order_number' => 6, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000006', 'description' => 'MOP', 'order_number' => 7, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000007', 'description' => 'NPK 12.12.7.2', 'order_number' => 8, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000008', 'description' => 'NPK 15.15.6.4', 'order_number' => 9, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000009', 'description' => 'O S T', 'order_number' => 10, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000010', 'description' => 'PUPUK HAYATI EMAS', 'order_number' => 11, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000011', 'description' => 'PUPUK HAYATI OST', 'order_number' => 12, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000012', 'description' => 'PUPUK SP-36', 'order_number' => 13, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000013', 'description' => 'PUPUK TABLET PMLT', 'order_number' => 14, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000014', 'description' => 'ROCK PHOSPHAT	ROCK PHOSPHAT', 'order_number' => 15, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '161000042', 'description' => 'PHOSPHATE GUANO 28%', 'order_number' => 16, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '400012100', 'description' => 'TBS (Tandan Buah Segar)', 'order_number' => 17, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '501000001', 'description' => 'CPO (Crude Palm Oil)', 'order_number' => 18, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '501000004', 'description' => 'Sludge Oil', 'order_number' => 19, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '501010001', 'description' => 'Kernel', 'order_number' => 20, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '501020001', 'description' => 'Palm Kernel Expeller (PKE)', 'order_number' => 21, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '501020002', 'description' => 'Palm Kernel Oil (PKO)', 'order_number' => 22, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '511000001', 'description' => 'RBN Olein', 'order_number' => 23, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '511000002', 'description' => 'RBD Palm Oil', 'order_number' => 24, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '511010001', 'description' => 'Palm Fatty Acid Distilated (PFAD)', 'order_number' => 25, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '511020001', 'description' => 'RBD Starein', 'order_number' => 26, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000001', 'description' => 'Cap Sendok Botol Satu Liter', 'order_number' => 27, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000002', 'description' => 'Cap Sendok Botol Dua Liter', 'order_number' => 28, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000003', 'description' => 'Cap Sendok Jerigen 20 Liter', 'order_number' => 29, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000004', 'description' => 'Cap Sendok Jerigen Lima Liter', 'order_number' => 30, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000005', 'description' => 'Cap Sendok S Pouch 1 Liter', 'order_number' => 31, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '521000006', 'description' => 'Cap Sendok S Pouch 2 Liter', 'order_number' => 32, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '900000001', 'description' => 'Cangkang', 'order_number' => 33, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '900000002', 'description' => 'Tankos (Tandan Kosong)', 'order_number' => 34, 'active' => 'Y',],
			['parameter_code' => 'PRODUCTCODE',	'value' => '910000001', 'description' => 'Others', 'order_number' => 35, 'active' => 'Y',],
			['parameter_code' => 'TRANSPORTERTYPE',	'value' => 'INTERNAL', 'description' => 'UNIT ANGKUT INTERNAL AAL', 'order_number' => 1, 'active' => 'Y',],
			['parameter_code' => 'TRANSPORTERTYPE', 'value' => 'EKSTERNAL', 'description' => 'UNIT ANGKUT EKSTERNAL atau KONTRAKTOR', 'order_number' => 2, 'active' => 'Y',],
			['parameter_code' => 'APICONFIG', 'value' => 'http://pims.astra-agro.co.id/api', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'CHITNUMBERCONFIG', 'value' => 'SAI1P1%y%m%d', 'description' => 'COMPANYCODE+P1+YYMMDD', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'SABNOCONFIG', 'value' => 'TTBSAI1%y%m%d', 'description' => 'TTB+D26+COMPANYCODE+YYMMDD', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'READERCONFIG', 'value' => '/dev/ttyUSB0', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'WBPORTCONFIG', 'value' => '/dev/ttyACM0', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'WBOUTPUTCONFIG', 'value' => '====XXXXX====', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'DATEFORMATCONFIG', 'value' => 'DD-MM-YYYY HH24:MI:SS', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'DATEFORMATCONFIG', 'value' => 'DD-MM-YYYY', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'DATEFORMATCONFIG', 'value' => 'YYYY-MM-DD', 'description' => '', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'ADJUSTWEIGHT', 'value' => 'N', 'description' => 'Weight dapat diubah manual', 'order_number' => 0, 'active' => 'Y'],
		];
		foreach ($parameter_values_data as $key => $data) {
			$this->db->table('m_parameter_values')->insert($data);
		}
	}
}
