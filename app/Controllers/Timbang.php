<?php

namespace App\Controllers;

use App\Models\MCustomerModel;
use App\Models\MEmployeeModel;
use App\Models\MProductTransMapModel;
use App\Models\MSiteModel;
use App\Models\MTransporterModel;
use App\Models\MUnitModel;
use App\Models\ParameterValueModel;
use App\Models\TrCpoKernelQualityModel;
use App\Models\TrGradingModel;
use App\Models\TrKabModel;
use App\Models\TrWbModel;
use App\Libraries\PhpSerial;
use App\Models\MtransporterUnitModel;
use Config\Database;
use ZipArchive;
use Andre;
use Exception;

class Timbang extends BaseController
{
	
	public const produkTBS = [
		'value' => '400012100', // Fixed
		'text' => 'Tandan Buah Segar', //ambil name produk berdasar value
	];

	public const produkCpoKode = '501000001';

	public const produkKernelKode = '501010001';

	public const transaksiTBSInternal = [
		'value' => 'TBS Internal', // Fixed
		'text' => 'TBS INTERNAL', //ambil description berdasar value
	];

	public const transaksiTBSTitipOlah = [
		'value' => 'TBS Internal',
		'text' => 'TBS INTERNAL',
	];

	public const transaksiTBSExternal = [
		'value' => 'TBS External',
		'text' => 'TBS EXTERNAL',
	];

	public const transaksiTBSPlasma = [
		'value' => 'TBS Plasma',
		'text' => 'TBS Plasma',
	];

	public function nfcCancel()
	{
		$device = ParameterValueModel::getValue('READERCONFIG');
		exec("echo 'STOP' > " . $device);
		return $this->response->setJSON(['succes' => true]);
	}

	public function serialRead(){
		$serial = new PhpSerial;
		$serial->confBaudRate(2400);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->confFlowControl('min 1 time 5 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke');

		// Then we need to open it
		$serial->deviceOpen();
	}

	public function nfcRead()
	{
		$rawdata = $this->getRawNFCData();

		$data = explode(';', $rawdata);

		if (count($data) > 2) {
			if ($data[2] == 'ID_DT') {
				$idDT = $this->getDT($data);
				return $this->response->setJSON($idDT);
			} elseif ($data[2] == 'SKU') {
				$sku = $this->getSKU($data);
				return $this->response->setJSON($sku);
			} elseif ((count($data) >= 9) && ($data[6] == 4)) {
				// print_r($data);die;
				$bklReturn = $this->getKAB($data, $rawdata);
				return $this->response->setJSON($bklReturn);
			} elseif ($data[2] == 'TBS External') {
				//return $this->response->setJSON($data);
				return $this->response->setJSON($this->getKABExternal($data, $rawdata));
			}elseif ($data[2] == 'TBS Plasma') {
				//return $this->response->setJSON($data);
				return $this->response->setJSON($this->getKABPlasma($data, $rawdata));
			}
			
		} else if ($rawdata == "OK\r\n") {
			return $this->response->setJSON([
				'tipe' => 'Canceled',
			]);
		}

		return $this->response->setJSON([
			'tipe' => 'Unknown',
			'rawdata' => $rawdata,
		]);
	}

	protected function getRawNFCData()
	{
		//set_time_limit(20);
		//$device = '/dev/ttyUSB0';
		$device = ParameterValueModel::getValue('READERCONFIG');
		//  echo $device;die();
		$flowCtl = 'min 1 time 5 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke';

		$fd = fopen($device, 'rb+');
		$fd = fopen($device, 'rb+');
		exec('stty -F ' . $device . ' ' . $flowCtl);

		$welcome = fgets($fd);

		$command = 'READ';
		$hasil2 = fwrite($fd, $command);

		if (($confirm = fgets($fd)) == "OK\r\n") {
			session_write_close();
			//$rawdata = fgets($fd, 2400);			
			
			$rawdata = '';
			do {
				$new_rawdata = fgets($fd, 2400);
				$new_rawdata = trim($new_rawdata);
				$rawdata .= $new_rawdata;
				error_log("'$new_rawdata'");
			} while ((substr($new_rawdata, -3) != '#*E') && ($new_rawdata != 'OK') && ($new_rawdata != '== AAL Card Reader ==') && ($new_rawdata != ''));
			
			fwrite($fd, 'STOP');
		} else {
			$rawdata = null;
		}

		fclose($fd);

		return $rawdata;
	}

	protected function getDT(array $data)
	{
		$tipe = 'ID_DT';

		$unitTransporter = $this->getUnitTransporter($data[4], $data[6]);

		$data = [
			'unit_id' => $data[4],
			'plat_no' => $unitTransporter['unit']['text'],
			'tran_id' => $data[6],
			'tran_name' => $unitTransporter['transporter']['text'],
		];
		return [
			'tipe' => $tipe,
			'data' => $data,
		];
	}

	private function getUnitTransporter($unitcode, $transportercode = null)
	{
		$unitModel = new MUnitModel();
		if (($unit = $unitModel->find($unitcode)) == null) {
			$unit = [
				'unitcode' => $unitcode,
				'transportercode' => $transportercode,
				'platenumber' => '[New Record - ' . $unitcode . ']',
				'active' => 'Y',
			];
			$unitModel->insert($unit);
		}
		$transportercode = $unit['transportercode'];

		if ($transportercode != null) {
			$transporterModel = new MTransporterModel();
			if (($transporter = $transporterModel->find($transportercode)) == null) {
				$transporter = [
					'transportercode' => $transportercode,
					'name' => '[New Record - ' . $transportercode . ']',
					'transportertype' => 0,
					'active' => 'Y',
				];
				$transporterModel->insert($transporter);
			}
		} else {
			$transporter = [
				'transportercode' => null,
				'name' => null,
			];
		}

		return [
			'unit' => [
				'value' => $unit['unitcode'],
				'text' => $unit['platenumber'],
			],
			'transporter' => [
				'value' => $transporter['transportercode'],
				'text' => $transporter['name'],
			],
		];
	}

	private function getEmployee($npk, $name = null)
	{
		$employeeModel = new MEmployeeModel();
		if (($employee = $employeeModel->find($npk)) == null) {
			$employee = [
				'npk' => $npk,
				'name' => $name ?? ('[New Record - ' . $npk . ']'),
				'emp_type' => 'DRIVER / HELPER',
				'active' => 'Y',
			];
			$employeeModel->insert($employee);
		}
		return $employee;
	}

	protected function getSKU(array $data)
	{
		$tipe = 'SKU';
		$employee = $this->getEmployee(str_pad($data[3], 7, '0', STR_PAD_LEFT), $data[4]);
		return [
			'tipe' => $tipe,
			'data' => $employee,
		];
	}

	protected function getKAB(array $data, $rawdata)
	{
		//$kabraw = $data[3];

		$noc_decoded = base64_decode($data[3]);

		$rawnoc = $this->getRawNOC($noc_decoded);

		$count_noc = count($rawnoc);

		$noc = [];
		for ($i = 1; $i < $count_noc; $i++) {
			$raw = $rawnoc[$i];
			$arr = explode(';', $raw);
			if (!isset($customer_code)) {
				$customer_code = $arr[2];
			}
			$jjg = 0;
			for ($j = 16; $j < count($arr); $j += 2) {
				$jjg += (float)$arr[$j];
			}

			$noc[] = [
				'nocvalue' => $raw,
				'nocsite' => $arr[2],
				'nocdate' => $arr[3],
				'harvestdate' => $arr[10],
				'nocafd' => $arr[6],
				'nocblock' => $arr[7],
				'tgl_panen' => $arr[11],
				'jjg' => $jjg,
			];
		}

		$customerModel = new MCustomerModel();
		if (isset($customer_code) && (($cust = $customerModel->find($customer_code)) == null)) {
			$cust = [
				'customercode' => $customer_code,
				'name' => '[New Record - ' . $customer_code . ']',
				'active' => 'Y',
			];
			$customerModel->insert($cust);
		}
		$customer = [
			'value' => $customer_code ?? null,
			'text' => $cust['name'] ?? null,
		];

		$produk = static::produkTBS;

		$companyCode = ParameterValueModel::getValue('COMPANYCODE');
		if (isset($customer_code) && ($companyCode == $customer_code)) {
			$transaksi = static::transaksiTBSInternal;
		} else {
			//$transaksi = static::transaksiTBSExternal;
			$transaksi = static::transaksiTBSTitipOlah;
		}

		if ($count_noc > 0) {
			$siteCode = $noc[0]['nocsite'];
		} else {
			$siteCode = $companyCode;
		}
		
		$mSiteModel = new MSiteModel();
		if (($site = $mSiteModel->find($siteCode)) == null) {
			$site = [
				'sitecode' => $siteCode,
				'description' => '[New Site - '. $siteCode .']',
				'order_no' => ($mSiteModel->builder()->selectMax('order_no')->get()->getFirstRow()->order_no) + 1,
			];
			$mSiteModel->insert($site);
		}
		$site = [
			'value' => $siteCode,
			'text' => $site['description'],
		];

		$kabcode = $data[5];
		$trWbModel = new TrWbModel();
		$tr_kab = $trWbModel->where(['kabcode' => $kabcode])->first();

		$bklReturn = [
			'tipe' => 'KAB',
			'kab_type' => count($data) > 8 ? 'BOARDING' : 'NON-BOARDING',
			'sitecode' => $site,
			'customer' => $customer,
			'produk' => $produk,
			'transaksi' => $transaksi,
			'noc' => $noc,
			//'raw_noc' => $rawnoc,
			//'kab' => $data,
			'kabcode' => $kabcode,
			'chitnumber' => $tr_kab['chitnumber'] ?? '',
			'kabraw' => $rawdata,
			'status' =>$tr_kab['status'] ?? '',
			// 'driver_manual' => $tr_kab['driver_manual'],
			// 'keterangan' => $rawdata['keterangan'],
		];

		$kabKolom3 = explode(',', $data[2]);
		if (count($kabKolom3) >= 3) {
			$unitCode = $kabKolom3[2];
			$unitTransporter = $this->getUnitTransporter($unitCode);
			$bklReturn = array_merge($bklReturn, $unitTransporter);
		}
		if (count($kabKolom3) >= 4) {
			$driverID = $kabKolom3[3];
			$driverName = $this->getEmployee($driverID)['name'];
			$bklReturn['driver'] = [
				'value' => $driverID,
				'text' => $driverName,
			];
		}
		if (count($kabKolom3) >= 5) {
			$helper1ID = $kabKolom3[4];
			if ($helper1ID != '') {
				$helper1Name = $this->getEmployee($helper1ID)['name'];
				$bklReturn['helper1'] = [
					'value' => $helper1ID,
					'text' => $helper1Name,
				];
			}
		}
		if (count($kabKolom3) >= 6) {
			$helper2ID = $kabKolom3[5];
			if ($helper2ID != '') {
				$helper2Name = $this->getEmployee($helper2ID)['name'];
				$bklReturn['helper2'] = [
					'value' => $helper2ID,
					'text' => $helper2Name,
				];
			}
		}
		

		return $bklReturn;
	}

	protected function getKABExternal($data, $rawdata)
	{
		$trWbModel = new TrWbModel();
		//$tr_wb = $trWbModel->find($data[3]);

		$tr_wb = $trWbModel->where(['nomorticket' => $data[3]])->first();

		$bklReturn = [
			'tipe' => 'KAB External',
			'produk' => static::produkTBS,
			'transaksi' => static::transaksiTBSExternal,
			'nomorticket' => $data[3] ?? '',
			'gate_in' => $data[4] ?? '',
			'gate_out' => $data[5] ?? '',
			'boarding_in' => $data[6] ?? '',
			'jenis_unit' => $data[7] ?? '',
			'nomor_polisi' => $data[8] ?? '',
			'nama_driver' => $data[9] ?? '',
			'kode_supplier' => $data[10] ?? '',
			'nama_supplier' => $data[11] ?? '',
			'wilayah_asal_tbs' => $data[12] ?? '',
			'kab_prop' => $data[13] ?? '',
			'kab_createdate' => $data[14] ?? '',
			'kab_createby' => $data[15] ?? '',
			'supplier_group' => $data[16] ?? '',
			'supplier_group_description' => $data[17] ?? '',
			'kabraw' => $rawdata,

			'chitnumber' => $tr_wb['chitnumber'] ?? '',
		];
		return $bklReturn;
	}

	protected function getKABPlasma($data, $rawdata)
	{
		$trWbModel = new TrWbModel();
		//$tr_wb = $trWbModel->find($data[3]);

		$tr_wb = $trWbModel->where(['nomorticket' => $data[3]])->first();

		$bklReturn = [
			'tipe' => 'KAB External',
			'produk' => static::produkTBS,
			'transaksi' => static::transaksiTBSPlasma,
			'nomorticket' => $data[3] ?? '',
			'gate_in' => $data[4] ?? '',
			'gate_out' => $data[5] ?? '',
			'boarding_in' => $data[6] ?? '',
			'jenis_unit' => $data[7] ?? '',
			'nomor_polisi' => $data[8] ?? '',
			'nama_driver' => $data[9] ?? '',
			'kode_supplier' => $data[10] ?? '',
			'nama_supplier' => $data[11] ?? '',
			'wilayah_asal_tbs' => $data[12] ?? '',
			'kab_prop' => $data[13] ?? '',
			'kab_createdate' => $data[14] ?? '',
			'kab_createby' => $data[15] ?? '',
			'supplier_group' => $data[16] ?? '',
			'supplier_group_description' => $data[17] ?? '',
			'kabraw' => $rawdata,

			'chitnumber' => $tr_wb['chitnumber'] ?? '',
		];
		return $bklReturn;
	}



	/**
	 * Extract to file dan ambil text NOC
	 */
	protected function getRawNOC($noc_decoded)
	{
		$rawnoc = [];
		$tmp_folder = sys_get_temp_dir() . '/wbs';
		if (!file_exists($tmp_folder)) {
			mkdir($tmp_folder);
		}

		$file_name = tempnam($tmp_folder, 'noc');
		$file_name_extract = $file_name . '_ext';

		$fs = fopen($file_name, 'wb');
		fwrite($fs, $noc_decoded);
		fclose($fs);

		$zip = new ZipArchive();
		$x = $zip->open($file_name);
		if ($x === true) {
			$zip->extractTo($file_name_extract);
			$zip->close();

			$files = array_diff(scandir($file_name_extract), array('.', '..'));
			foreach ($files as $key => $file_dibuka) {
				$fs = fopen($file_name_extract . '/' . $file_dibuka, 'rb');
				while ($read = fgets($fs, 2400)) {
					$rawnoc[] = $read;
				}
				fclose($fs);
			}
			$this->rrmdir($file_name_extract);
		}
		unlink($file_name);

		return $rawnoc;
	}

	/*
	public function noc(){
		$raw_noc = [
			"1912031014,0111831",
			"54:A6:8D:25;;SDI1;0312191006;1;1;OB;020;K3016;V2AGLMB682004138;;031219;04B3;210B3;;379B3;16;119B3;12;253B3;15;358B3;25;383B3;4;55B3;3;386B3;2",
			"F1:DC:9A:05;;SDI1;0312191005;1;1;OB;017;K3026;V2AGLMB682005831;;031219;279B3;360B3;;53B3;11;42B3;12;334B3;7;122B3;5;22B3;9;24B3;3;11B3;2",
			"54:C4:69:DB;;SDI1;0312190859;1;1;OB;017;K3026;V2AGLMB682005831;;031219;279B3;360B3;;11B3;14;53B3;5;22B3;6;42B3;4;24B3;9;122B3;7;334B3;6",
			"84:2A:DB:25;;SDI1;0312190955;1;1;OB;014;K3014;V2AGLMC6C2406113;;031219;287B3;374B3;;111B3;4;143B3;9;19B3;9;354B3;8;284B3;4;280B3;2;319B3;2;281B3;2",
			"A0:A7:14:11;;SDI1;0312190851;1;1;OB;014;K3028;V2AGLMB682000791;;031219;01B3;337B3;;354B3;15;143B3;15;281B3;9;280B3;21;111B3;11",
			"54:F5:90:25;;SDI1;0312190902;1;1;OB;017;K3016;V2AGLMB682004138;;031219;04B3;210B3;;358B3;26;253B3;3;379B3;13;55B3;2;386B3;3;119B3;10",
			"EA:8D:93:27;;SDI1;0312190907;1;1;OB;014;K3014;V2AGLMC6C2406113;;031219;287B3;374B3;;284B3;26;111B3;7;319B3;10;280B3;6;281B3;7;354B3;10;143B3;6;19B3;11",
			"50:29:0E:11;;SDI1;0312190935;1;1;OB;017;K3028;V2AGLMB682000791;;031219;01B3;337B3;;281B3;1;19B3;3;280B3;11;383B3;13;143B3;12;386B3;4;358B3;5;354B3;3;319B3;5;55B3;1;253B3;9"
		];

		$count_noc = count($raw_noc);

		$noc = [];
		for ($i=1; $i < $count_noc; $i++) { 
			$raw = $raw_noc[$i];
			$arr = explode(';', $raw);
			if (!isset($customer_code)) {
				$customer_code = $arr[2];
			}
			$jjg = 0;
			for ($j = 16; $j < count($arr); $j += 2) {
				$jjg += (float)$arr[$j];
			}

			$noc[] = [
				'nocsite' => $arr[2],
				'nocdate' => $arr[3],
				'harvestdate' => $arr[10],
				'nocafd' => $arr[6],
				'nocblock' => $arr[7],
				'jjg' => $jjg,
				'arr' => $arr,
				'raw' => $raw,
			];
		}

		/*
		INSERT INTO TR_KAB(	CHITNUMBER, SABNO, NOCVALUE, NOCSITE, 			NOCDATE,			HARVESTDATE,		NOCAFD,				NOCBLOCK, 			JJG)
		VALUES (			CHITNUMBER, SABNO, NOCVALUE, NOCVALUE.KOLOM3,	NOCVALUE.kolom4,	NOCVALUE.kolom11,	NOCVALUE.kolom7,	NOCVALUE.kolom8,	NOCVALUE.kolom17+19+21+..)
		*

		return $this->response->setJSON([
			'customer_id' => $customer_code,
			'noc' => $noc,
			'raw_noc' => $raw_noc,
		]);
	}*/

	function rrmdir($dir)
	{
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
						$this->rrmdir($dir . DIRECTORY_SEPARATOR . $object);
					else
						unlink($dir . DIRECTORY_SEPARATOR . $object);
				}
			}
			rmdir($dir);
		}
	}

	public function writeKab($id)
	{
		$trWbModel = new TrWbModel();
		if (($trWb = $trWbModel->select([
			'chitnumber',
			'transactiontype',
			'kab_type',
			'productcode',
			'kabraw',
			'wb_in',
			'weight_in',
			'wb_out',
			'weight_out',
		])->find($id)) == null) {
			return $this->response->setJSON([
				'status' => 'fail',
				'messages' => 'Chitnumber not found',
			]);
		} else if ($trWb['productcode'] != static::produkTBS['value']) {
			$namaProdukTbs = static::produkTBS['text'];
			return $this->response->setJSON([
				'status' => 'fail',
				'messages' => "Product Code is not '$namaProdukTbs'",
			]);
		}

		if ($trWb['transactiontype'] == static::transaksiTBSExternal['value']) {
			$trWb['kab_type'] = 'NON-BOARDING';
		}
		if ($trWb['transactiontype'] == static::transaksiTBSPlasma['value']) {
			$trWb['kab_type'] = 'NON-BOARDING';
		}

		if ($trWb['kab_type'] == 'BOARDING' || $trWb['kab_type'] == 'NON-BOARDING') {

			$device = ParameterValueModel::getValue('READERCONFIG');
			$flowCtl = 'min 1 time 5 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke';
			exec('stty -F ' . $device . ' ' . $flowCtl);
			$fd = fopen($device, 'r+');
			$welcome = fgets($fd);

			if ($trWb['kab_type'] == 'NON-BOARDING') {
				/*$command = 'FORMATKAB';
				$hasil2 = fwrite($fd, $command);

				if (($confirm = fgets($fd)) == "OK\r\n") {
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					fwrite($fd, 'STOP');
					$afterFormatMessage[] = fgets($fd, 2400);
					//$confirmStop = fgets($fd, 2400);

					$bklReturn = [
						'status' => 'success',
						'messages' => 'Format KAB success',
						'respons' => $afterFormatMessage,
					];
				} else {
					$bklReturn = [
						'status' => 'fail',
						'messages' => 'Format KAB failed',
					];
				}*/
				$command = 'WRITEID';
				$hasil2 = fwrite($fd, $command);

				if (($confirm = fgets($fd)) == "Masukkan data:\r\n") {
					$kabraw = $trWb['kabraw'];
					$sourceArray = explode(';', $kabraw);
					$writeArray = [
						';;;;4',
					];
					$writeRaw = implode(';', $writeArray);
					sleep(1);
					$hasil3 = fwrite($fd, $writeRaw . "\r\n");
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);

					//$afterFormatMessage[] = $writeRaw;
					//sleep(5);
					$hasil4 = fwrite($fd, 'STOP');
					$afterFormatMessage[] = fgets($fd, 2400);
					//$afterFormatMessage[] = fgets($fd, 2400);

					$bklReturn = [
						'status' => 'success',
						'messages' => 'Write KAB success',
						'respons' => $afterFormatMessage,
					];
				} else {
					$bklReturn = [
						'status' => 'fail',
						'messages' => 'Write KAB failed',
						'confirm' => $confirm,
					];
				}
			} else if ($trWb['kab_type'] == 'BOARDING') {
				$command = 'WRITEID';
				$hasil2 = fwrite($fd, $command);
				//print_r($trWb);die();
				if (($confirm = fgets($fd)) == "Masukkan data:\r\n") {
					$kabraw = $trWb['kabraw'];
					$sourceArray = explode(';', $kabraw);
					$writeArray = [
						0,
						0,
						0,
						$sourceArray[5],
						4,
						$sourceArray[7],
						//$trWb['wb_in'] . ' ' . $trWb['weight_in'],
						date('dmYHis', strtotime($trWb['wb_in'])). ',' . $trWb['weight_in'],
						//$trWb['wb_out'] . ' ' . $trWb['weight_out'],
						date('dmYHis', strtotime($trWb['wb_out'])). ',' . $trWb['weight_out'],
					];
					$writeRaw = implode(';', $writeArray);
					sleep(1);
					$hasil3 = fwrite($fd, $writeRaw . "\r\n");
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);
					$afterFormatMessage[] = fgets($fd, 2400);

					//$afterFormatMessage[] = $writeRaw;
					//sleep(5);
					$hasil4 = fwrite($fd, 'STOP');
					$afterFormatMessage[] = fgets($fd, 2400);
					//$afterFormatMessage[] = fgets($fd, 2400);

					$bklReturn = [
						'status' => 'success',
						'messages' => 'Write KAB success',
						'respons' => $afterFormatMessage,
					];
				} else {
					$bklReturn = [
						'status' => 'fail',
						'messages' => 'Write KAB failed',
						'confirm' => $confirm,
					];
				}
			}

			fclose($fd);

			return $this->response->setJSON($bklReturn);
		} else {
			return $this->response->setJSON([
				'status' => 'fail',
				'messages' => 'Type KAB tidak terdefinisi',
			]);
		}
	}

	function clean($string) {
		$string = str_replace(' ', '-', trim($string)); // Replaces all spaces with hyphens.
 
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	 }

	public function weighRead()
	{
		$device = ParameterValueModel::getValue('WBPORTCONFIG');
		$datalengt = ParameterValueModel::getValue('WBDATALEN');

		$result = '1';
		$flowCtl = 'min 1 time 5 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke';

		$fd = fopen($device, 'rb+');
		$fd = fopen($device, 'rb+');
		exec('stty -F ' . $device . ' ' . $flowCtl);
		
		$serialPort = fopen($device, 'rb'); // Ganti '/dev/ttyS0' dengan nama port serial yang sesuai
		usleep(100000);
		//$serialPort = fopen($device, 'rbx');
		$line = '';

		while (true) {
			$char = fgetc($serialPort);

			if ($char === "\n") {
				// Karakter terminasi ditemukan, hentikan pembacaan
				break;
			}

			$line .= $char;
		}
		fclose($serialPort);
		//echo $line;
		$bakalReturn = [
			'device' => $device,
			'rawdata' => $line,
		];
		//echo $line;
		
		$value = intval(str_replace("-","",str_replace("kg","",str_replace("-99-","----",$this->clean($line)))));
		
		//echo $value;
		try {
			$bakalReturn['value'] = number_format($value, 0, ',', '.');
		} catch (\Throwable $th) {
			$bakalReturn['value'] = 1;
		}
		//print_r($bakalReturn);
		
		return $this->response->setJSON($bakalReturn);

	}


	public function getWeight()
	{
			$device = ParameterValueModel::getValue('WBPORTCONFIG');
			$datalengt = ParameterValueModel::getValue('WBDATALEN');

			$result = '1';
			$flowCtl = 'min 1 time 5 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke';

			$fd = fopen($device, 'rb+');
			$fd = fopen($device, 'rb+');
			exec('stty -F ' . $device . ' ' . $flowCtl);
			
			$serialPort = fopen($device, 'rb'); // Ganti '/dev/ttyS0' dengan nama port serial yang sesuai
			usleep(100000);
			//$serialPort = fopen($device, 'rbx');
			$line = '';

			while (true) {
					$char = fgetc($serialPort);

					if ($char === "\n") {
							// Karakter terminasi ditemukan, hentikan pembacaan
							break;
					}

					$line .= $char;
			}
			fclose($serialPort);
			//echo $line;
			$bakalReturn = [
					'device' => $device,
					'rawdata' => $line,
			];
			//echo $line;
			
			$value = intval(str_replace("-","",str_replace("kg","",str_replace("-99-","----",$this->clean($line)))));
			
			//echo $value;
			try {
					$bakalReturn['value'] = number_format($value, 0, ',', '.');
			} catch (\Throwable $th) {
					$bakalReturn['value'] = 1;
			}
			//print_r($bakalReturn);
			
			// return $this->response->setJSON($bakalReturn);
			try{
				//print_r($bakalReturn);
				return $this->response->setJSON($bakalReturn);
			}catch (\Throwable $th) {
				$bakalReturn['value'] = 1;
		}

	}


	// public function getWeight()
	// {
	// 	$device = ParameterValueModel::getValue('WBPORTCONFIG');
	// 	$datalengt = ParameterValueModel::getValue('WBDATALEN');

	// 	$result = '1';
	// 	$serialPort = fopen($device, 'r+'); // Ganti '/dev/ttyS0' dengan nama port serial yang sesuai
		
	// 	stream_set_timeout($serialPort, 20); // Mengatur timeout ke 1 detik (sesuaikan dengan kebutuhan Anda)

	// 	exec('stty -F '.$device.' 9600 cs8 -cstopb -parenb'); // Konfigurasi port serial

	// 	$startTime = microtime(true); // Waktu awal

	// 	while (true) {
	// 		$line = stream_get_line($serialPort, 32, "\n"); // Membaca satu baris dengan karakter EOL '\n'

	// 		if ($line !== false && strlen($line) ==$datalengt) {
	// 			$result = $line;
	// 			//echo $line . "\n"."panjang karakter :"; // Cetak satu baris
	// 			break;
	// 		}

	// 		// Periksa apakah sudah melebihi waktu tertentu, misalnya 10 detik
	// 		// $currentTime = microtime(true);
	// 		// if ($currentTime - $startTime > 0.5) {
	// 		// 	break; // Keluar dari perulangan setelah 10 detik
	// 		// }
	// 	}

	// 	fclose($serialPort); // Menutup port serial

	// 	return preg_replace('/[\x00-\x1F\x7F]/u', '', $result);

	// }

	// public function getWeight(){
	// 	$port = '/dev/ttyUSB0';  // Ganti port serial sesuai kebutuhan
	// 	$baudRate = 9600;  // Ganti kecepatan baud sesuai kebutuhan

	// 	// Membuka koneksi ke port serial
	// 	$serial = fopen($port, 'r+');
	// 	stream_set_blocking($serial, 0);
	// 	// stream_set_option($serial, \SOLVEDATA\SEMPACKLEN, '8'); // Ganti <data_length> dengan panjang data yang diinginkan
		

	// 	// Mengatur ukuran buffer menjadi 8192 byte
	// 	stream_set_chunk_size($serial, 8192);

	// 	// stream_set_timeout($serial, 5); // Mengatur timeout menjadi 5 detik (sesuaikan sesuai kebutuhan)
	// 	if (!$serial) {
	// 		die('Gagal membuka koneksi ke port serial.');
	// 	}

	// 	// Mengatur kecepatan baud
	// 	exec("stty -F $port $baudRate");

	// 	// Membaca satu baris data
		
	// 	$line = '';
	// 	$encoding = mb_detect_encoding($line, mb_detect_order(), true); // Mendeteksi pengkodean karakter
	// 	while (strlen($line) == 0 || $line[strlen($line) - 1] != "\n") {
	// 		$line .= fgets($serial);
			
	// 	}
	// 	usleep(500000);
	// 	// $line = fgets($serial);
	// 	// $encoding = mb_detect_encoding($line, mb_detect_order(), true); // Mendeteksi pengkodean karakter
	// 	// $line = iconv($encoding, 'UTF-8', $line); // Mengonversi pengkodean karakter
	// 	// $line = trim($line); // Menghapus karakter newline dan spasi di awal atau akhir baris
	// 	// echo $line;

	// 	// Menampilkan baris data
	// 	echo $line;

	// 	// Menutup koneksi serial
	// 	fclose($serial);
	// }

	public function getCurrentDate(){
		date_default_timezone_set('Asia/Jakarta');
		return date('Y-m-d H:i:s');
	}

	public function getWeightLength(){
		/*1.JIKA PAKAI TTY EXEC*/
		ob_flush();
		flush();
		$device = ParameterValueModel::getValue('WBPORTCONFIG');

		$result = '1';
		
	
		

		exec('stty -F '.$device.' 9600 cs8 -cstopb -parenb'); // Konfigurasi port serial
		$serialPort = fopen($device, 'r+'); // Ganti '/dev/ttyS0' dengan nama port serial yang sesuai
		stream_set_timeout($serialPort, 0.1); // Mengatur timeout ke 1 detik (sesuaikan dengan kebutuhan Anda)

		// $rawBerat = fgets($serialPort);
		// echo $rawBerat;

		while (true) {
			$line = stream_get_line($serialPort, 32, "\n"); // Membaca satu baris dengan karakter EOL '\n'

			if ($line !== false ) {
				$result = $line;
				echo $line . "\n"."panjang karakter : ".strlen($line); // Cetak satu baris
				break;
			}

			// Periksa apakah sudah melebihi waktu tertentu, misalnya 10 detik
			// $currentTime = microtime(true);
			// if ($currentTime - $startTime > 0.5) {
			// 	break; // Keluar dari perulangan setelah 10 detik
			// }
		}

		fclose($serialPort); // Menutup port serial


		/*1.JIKA PAKAI TTY EXEC*/
		
		
		/* JIKA PAKAI SHELL
		ob_implicit_flush(true);
		ob_end_flush();

		$cmd = "timeout 1 bash /var/www/astraagrolestari-weightbridge-v3/public/wbread/readlength.sh";

		$descriptorspec = array(
		0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
		2 => array("pipe", "w")    // stderr is a pipe that the child will write to
		);


		$process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());

		if (is_resource($process)) {

			while ($s = fgets($pipes[1])) {
				print $s."<br>";

			}
		} JIKA PAKAI SHELL*/
	}

	// public function weighRead(){
	// 	$raw = strval($this->getWeight());
		
		
	// 	$trim = trim($raw);
	// 	$regex = preg_replace('/[\x00-\x1F\x7F]/u', '', $trim);
		
	// 	$patterns = array();
	// 	$patterns[0] = '/ 99 /';
	// 	$patterns[1] = '/kg/';
	// 	$patterns[2] = '/g/';
	// 	$patterns[3] = '/./';
	// 	$replacements = array();
	// 	$replacements[0] = '';
	// 	$replacements[1] = '';
	// 	$replacements[2] = '';
	// 	$replacements[3] = '';
		
	// 	$formated = str_replace('g','',$regex );
	// 	$formated = str_replace('.','',$regex );
	// 	$formated = str_replace('kg','',$regex );
	// 	$formated = str_replace('KG','',$regex );
	// 	$formated = trim($formated );

	// 	try {	
			
	// 		$berat = intval($formated);
	// 		$bakalReturn['value'] = number_format($berat, 0, ',', '.');
	// 	} catch (Exception $e) {
			
	// 		$bakalReturn['error'] = 'Data length missmatch.';
	// 		$bakalReturn['value'] = 0;
	// 	}
		
	// 	$bakalReturn['raw'] = $raw;
	// 	$bakalReturn['lengthraw'] = strlen($raw);
	// 	$bakalReturn['formated'] = $formated;
	// 	$bakalReturn['regex'] = $regex;
	// 	$bakalReturn['regex'] = $regex;

	// 	return $this->response->setJSON($bakalReturn);
	// }

	public function readOuputWb($fd,$count = 0){
		$content = ""; $i = 0;

            if ($count !== 0)
            {
                do {
                    if ($i > $count) $content .= fread($fd, ($count - $i));
                    else $content .= fread($fd, 128);
                } while (($i += 128) === strlen($content));
            }
            else
            {
                do {
                    $content .= fread($fd, 128);
                } while (($i += 128) === strlen($content));
            }

            return $content;
	}

	public function index()
	{
		$mSiteModel = new MSiteModel();
		$mSites = $mSiteModel->orderBy('description', 'asc')
			->findAll();

		$customerModel = new MCustomerModel();
		$customers = $customerModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();

		$transporterModel = new MTransporterModel();
		$transporters = $transporterModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();

		$unitModel = new MUnitModel();
		$units = $unitModel->where(['deleted_at' => null])
			->orderBy('unitcode', 'asc')
			->where('active', 'Y')
			->findAll();

		$parameterValueModel = new ParameterValueModel($this->request);
		$parameter_values = $parameterValueModel->where('active', 'Y')
			->orderBy('description', 'asc')
			->findAll();
		$parameterValuesMap = [];
		$companyCode = '';
		foreach ($parameter_values as $value) {
			if (!isset($parameterValuesMap[$value['parameter_code']])) {
				$parameterValuesMap[$value['parameter_code']] = [
					$value['value'] => $value['description']
				];
			} else {
				$parameterValuesMap[$value['parameter_code']][$value['value']] = $value['description'];
			}
			if ($value['parameter_code'] == 'COMPANYCODE') {
				$companyCode = $value['value'];
			}
		}

		$employeeModel = new MEmployeeModel();
		$empleyees = $employeeModel->where(['deleted_at' => null])
			->where('active', 'Y')
			->orderBy('name', 'asc')
			->findAll();

		/** Cari Tr WB untuk edit pending transaction */
		if (($chitnumber = $this->request->getGet('id')) != null) {
			$trWbModel = new TrWbModel();
			$tr_wb = $trWbModel
				->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
				->select([
					'tr_wb.*',
					'm_unit.transportercode',
				])
				// ->where('status', '0')
				->find($chitnumber);
			$tr_wb['netto'] = abs(($tr_wb['weight_out'] ?? 0) - ($tr_wb['weight_in'] ?? 0));
			
			if($tr_wb['status']=='1'){
				echo "Transaksi ini Sudah Selesai,  (sudah melakukan Timbang Keluar).";die;
			}else{
				$trKabModel = new TrKabModel();
				// $tr_kab = $trKabModel->where('chitnumber', $chitnumber)
				// 	->findAll();

				$tr_kab = $trKabModel->where('chitnumber', $tr_wb['chitnumber'])
				->select([ 'tr_kab.chitnumber','tr_kab.sabno','tr_kab.nocvalue','tr_kab.nocsite','tr_kab.nocdate','tr_kab.harvestdate','tr_kab.nocafd','tr_kab.nocblock','tr_kab.jjg',])
				->groupBy(['tr_kab.chitnumber','tr_kab.sabno','tr_kab.nocvalue','tr_kab.nocsite','tr_kab.nocdate','tr_kab.harvestdate','tr_kab.nocafd','tr_kab.nocblock','tr_kab.jjg',])
				->findAll();
				/*
				$db = Database::connect();
				$lastQuery = $db->showLastQuery();
				return $lastQuery; die;
				*/

				$trGradingModel = new TrGradingModel();
				$tr_grading = $trGradingModel->find($chitnumber);

				$trQualityModel = new TrCpoKernelQualityModel();
				$tr_quality = $trQualityModel->find($chitnumber);
			}

		} else {
			$tr_wb = [
				'wbsitecode' => $companyCode,
				'sitecode' => $companyCode,
				'chitnumberpre'=>$this->generateNewChitNumber(),
			];
		}

		$preset = [
			'sites' => $mSites,
			'customers' => $customers,
			'transporters' => $transporters,
			'units' => $units,
			'parameter_values' => $parameterValuesMap,
			'employees' => $empleyees,

			'tr_wb' => $tr_wb,
			'tr_kab' => $tr_kab ?? [],
			'tr_grading' => $tr_grading ?? [],
			'tr_quality' => $tr_quality ?? [],
		];

		//return $this->response->setJSON($preset);die;

		return view('transaksi/timbang/index', $preset);
	}

	private function generateNewChitNumber()
	{
		$prefiks = 'SELECT DATE_FORMAT(NOW(), (SELECT `value` FROM m_parameter_values WHERE parameter_code = "CHITNUMBERCONFIG")) prefiks';
		$db = Database::connect();
		$prefiks = $db->query($prefiks)->getRow()->prefiks;
		$len = strlen($prefiks);
		$terakhir = "SELECT MAX(CAST(TRIM(RIGHT(`chitnumber`, LENGTH(`chitnumber`) - $len)) AS SIGNED)) cht FROM tr_wb WHERE `chitnumber` LIKE '$prefiks%'";
		$terakhir = $db->query($terakhir)->getRow()->cht ?? 0;
		$terakhir++;
		return $prefiks . sprintf('%03d', $terakhir);
	}

	public function getPending()
	{
		
		$post = $this->request->getPost();
		$statusTransaksi ="success";
		// /** Response */
		// return $this->response->setJSON([
		// 	'status' => 'success',
		// 	'new' => 'non-new',
		// 	'messages' => 'testing', // . ' API: ' .  ($api_response['messages'] ?? ''),
		// 	'chitnumber' => $post['chitnumber'],
		// 	'post' => $post,
		// ]);	
		$mSiteModel = new MSiteModel();
		$mSites = $mSiteModel->orderBy('description', 'asc')
			->findAll();

		$customerModel = new MCustomerModel();
		$customers = $customerModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();

		$transporterModel = new MTransporterModel();
		$transporters = $transporterModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();

		$unitModel = new MUnitModel();
		$units = $unitModel->where(['deleted_at' => null])
			->orderBy('unitcode', 'asc')
			->where('active', 'Y')
			->findAll();

		$parameterValueModel = new ParameterValueModel($this->request);
		$parameter_values = $parameterValueModel->where('active', 'Y')
			->orderBy('description', 'asc')
			->findAll();
		$parameterValuesMap = [];
		$companyCode = '';
		foreach ($parameter_values as $value) {
			if (!isset($parameterValuesMap[$value['parameter_code']])) {
				$parameterValuesMap[$value['parameter_code']] = [
					$value['value'] => $value['description']
				];
			} else {
				$parameterValuesMap[$value['parameter_code']][$value['value']] = $value['description'];
			}
			if ($value['parameter_code'] == 'COMPANYCODE') {
				$companyCode = $value['value'];
			}
		}

		$employeeModel = new MEmployeeModel();
		$empleyees = $employeeModel->where(['deleted_at' => null])
			->where('active', 'Y')
			->orderBy('name', 'asc')
			->findAll();

		/** Cari Tr WB untuk edit pending transaction */
		if (($chitnumber = $post['chitnumber']) != null) {
			$trWbModel = new TrWbModel();
			$tr_wb = $trWbModel
				->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
				->select([
					'tr_wb.*',
					'm_unit.transportercode',
				])
				// ->where('status', '0')
				->find($chitnumber);
			$tr_wb['netto'] = abs(($tr_wb['weight_out'] ?? 0) - ($tr_wb['weight_in'] ?? 0));
			
			if($tr_wb['status']=='1'){
				$statusTransaksi ='selesai';
;			}else{
				if ($tr_wb['transactiontype']=='TBS Internal' || $tr_wb['transactiontype']=='TBS Titip Olah'){
					$trKabModel = new TrKabModel();

					$tr_kab = $trKabModel->where('chitnumber', $tr_wb['chitnumber'])
					->select([ 'tr_kab.chitnumber','tr_kab.sabno','tr_kab.nocvalue','tr_kab.nocsite','tr_kab.nocdate','tr_kab.harvestdate','tr_kab.nocafd','tr_kab.nocblock','tr_kab.jjg',])
					->groupBy(['tr_kab.chitnumber','tr_kab.sabno','tr_kab.nocvalue','tr_kab.nocsite','tr_kab.nocdate','tr_kab.harvestdate','tr_kab.nocafd','tr_kab.nocblock','tr_kab.jjg',])
					->findAll();
					/*
					$db = Database::connect();
					$lastQuery = $db->showLastQuery();
					return $lastQuery; die;
					*/

					$trGradingModel = new TrGradingModel();
					$tr_grading = $trGradingModel->find($chitnumber);

					$trQualityModel = new TrCpoKernelQualityModel();
					$tr_quality = $trQualityModel->find($chitnumber);
					//echo "Transaksi ini Sudah Selesai,  (sudah melakukan Timbang Keluar).";die;
				}
					
			}

		} else {
			$tr_wb = [
				'wbsitecode' => $companyCode,
				'sitecode' => $companyCode,
				'chitnumberpre'=>$this->generateNewChitNumber(),
			];
		}

		// $preset = [
		// 	'sites' => $mSites,
		// 	'customers' => $customers,
		// 	'transporters' => $transporters,
		// 	'units' => $units,
		// 	'parameter_values' => $parameterValuesMap,
		// 	'employees' => $empleyees,

		// 	'tr_wb' => $tr_wb,
		// 	'tr_kab' => $tr_kab ?? [],
		// 	'tr_grading' => $tr_grading ?? [],
		// 	'tr_quality' => $tr_quality ?? [],
		// ];

		//return $this->response->setJSON($preset);die;

		// return view('transaksi/timbang/index', $preset);
		$preset = [
			'sites' => $mSites,
			'customers' => $customers,
			'transporters' => $transporters,
			'units' => $units,
			'parameter_values' => $parameterValuesMap,
			'employees' => $empleyees,
			'status' => $statusTransaksi,
			'tr_wb' => $tr_wb,
			'tr_kab' => $tr_kab ?? [],
			'tr_grading' => $tr_grading ?? [],
			'tr_quality' => $tr_quality ?? [],
		];

		return $this->response->setJSON($preset);

		// return view('transaksi/timbang/index', $preset);	
	}

	public function save()
	{
		/** Validasi Awal */
		if (! $this->validate([
			'productcode' => 'required',
			'transactiontype' => 'required',
		])) {
			return $this->returnErrorInvalidate();
		}

		$post = $this->request->getPost();

		if ($post['transactiontype'] == static::transaksiTBSExternal['value'] || $post['transactiontype'] == static::transaksiTBSPlasma['value']) {
			/*if (!$this->validate([
				//'nomorticket' => 'required',
				'customercode' => 'required',
			])) {
				return $this->returnErrorInvalidate();
			}*/
		}else {
			if (!$this->validate([
				'customercode'	 => 'required',
				'transportercode' => 'required',
				'unitcode' => 'required',
				// 'npk_driver' => 'required',
			])) {
				return $this->returnErrorInvalidate();
			}

			/** Jika Produk TBS, dan bukan TBS External */
			if ($post['productcode'] == static::produkTBS['value']) {
				/** Cek: TBS harus ada noc/kab detail */
				/*if (!$this->validate([
					'noc' => [
						'rules' => 'required',
						'errors' => [
							'required' => 'Data KAB / NOC belum di tap'
						]
					]
				])) {
					return $this->returnErrorInvalidate();
				}*/
			}
		}
		$tambahBaru = false;
		/** Cek: tambah baru atau edit data */
		if (!isset($post['chitnumber']) || $post['chitnumber'] == '') {
			if(!isset($post['nomor_polisi'])) {
				if($this->cekUnitBlumKeluar($post['unitcode']) != 0 ){
					return $this->returnErrorUnitBelumKeluar();
				}
			}
			
			/** Set new chitnumber */
			// $post['chitnumber'] = $this->generateNewChitNumber();
			$post['chitnumber'] = $post['chitnumber_pre'];

			/** mark tambah baru */
			$tambahBaru = true;

			if (key_exists('nomorticket', $post) && !key_exists('kabcode', $post)) {
				$post['kabcode'] = $post['nomorticket'];
			}
		}

		/** Set: sabno otomatis, jika kosong, untuk ProdukTBS dan bukan TransaksiTBSExternal */
		if ((!isset($post['sabno']) || $post['sabno'] == '') 
		&& ($post['productcode'] == static::produkTBS['value'])
		&& ($post['transactiontype'] == static::transaksiTBSInternal['value'])) {

			$prefiks = 'SELECT DATE_FORMAT(NOW(), (SELECT `value` FROM m_parameter_values WHERE parameter_code = "SABNOCONFIG")) prefiks';
			$db = Database::connect();
			$prefiks = $db->query($prefiks)->getRow()->prefiks;

			$len = strlen($prefiks);
			$terakhir = "SELECT MAX(CAST(TRIM(RIGHT(`sabno`, LENGTH(`sabno`) - $len)) as SIGNED)) sbn FROM tr_wb WHERE `sabno` LIKE '$prefiks%'";
			$terakhir = $db->query($terakhir)->getRow()->sbn ?? 0;
			$terakhir++;

			$sabno = $prefiks . sprintf('%03d', $terakhir);

			$post['sabno'] = $sabno;

		}

		/** Set: Clean dot (.) di weight_in dan weight_out */
		if (isset($post['weight_in']) && $post['weight_in'] != '') {
			$post['weight_in'] = str_replace('.', '', $post['weight_in']);
		}
		if (isset($post['weight_out']) && $post['weight_out'] != '') {
			$post['weight_out'] = str_replace('.', '', $post['weight_out']);
		}
	
		/** Set: status */
		if ((isset($post['weight_out']) && $post['weight_out'] != '' && intval($post['weight_out']) > 0)
		&& (isset($post['weight_in']) && $post['weight_in'] != '' && intval($post['weight_in']) > 0)
		) {
			$post['status'] = 1; // set status complete				
		} else {
			$post['status'] = 0; // set status pending
		}
	
		/** Set: Chitbumber dan SabNo di noc / kab detail */
		if (isset($post['noc'])) {
			foreach ($post['noc'] as $key => &$noc) {
				$noc['chitnumber'] = $post['chitnumber'];
				$noc['sabno'] = $post['sabno'] ?? '';
			}
		}
		
		/** Simpan Data */
		$trWbModel = new TrWbModel();					
		if ($tambahBaru) {
			// $this->delete($post['kabcode']);
			$post['operator_in'] = session()->get('name');
			$post['created_at'] = date('Y-m-d');
			$trWbModel->insert($post);

			if(isset($post['noc'])){
				$trKabModel = new TrKabModel();
				$trKabModel->insertBatch($post['noc']);
			}

			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Timbang', 'insert', $post);

			$post['sent'] = 0;
		} else {
			$post['operator_out'] = session()->get('name');
			$post['updated_at'] = date('Y-m-d');
			$where = array('chitnumber'=>$post['chitnumber'],'status'=>'0','weight_out'=>'0');
			$trWbModel->update($where, $post);

			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Pending Transaction', 'update', $post);
			
			$saved = $trWbModel->find($post['chitnumber']);
			$post['sent'] = $saved['sent'];
		}

		/** Simpan NOC / Detail KAB jika ada */
		/*if (isset($post['noc'])) {
			$trKabModel = new TrKabModel();
			if ($tambahBaru) {
				$trKabModel->insertBatch($post['noc']);
			} else {
				foreach ($post['noc'] as $kab) {
					$trKabModel->save($kab);
				}
			}
			
		}*/

		$this->simpanGrading($post);
		$this->simpanCpoKernelQuality($post);
		$this->updateUnitCode($post);

		/** Response */
		return $this->response->setJSON([
			'status' => 'success',
			'new' => $tambahBaru,
			'messages' => 'Data timbang berhasil di ' . ($tambahBaru ? 'simpan' : 'ubah'), // . ' API: ' .  ($api_response['messages'] ?? ''),
			'chitnumber' => $post['chitnumber'],
			'post' => $post,
			'saveAPI' => (($post['status'] == 1) && ($post['sent'] != 'Y')),
		]);		
	}

	public function saveAPI($chitnumber)
	{
		$cron = new Cron;
		return $this->response->setJSON($cron->scheduler($chitnumber));
	}

	private function returnErrorInvalidate()
	{
		$errors = $this->validator->getErrors();
		$inputErrors = implode(', ', array_keys($errors));
		return $this->response->setJSON([
			'status' => 'invalid',
			'messages' => "Terdapat input yang tidak valid.\n [$inputErrors]",
			'error' => $errors,
		]);
	}

	private function returnErrorUnitBelumKeluar()
	{
		return $this->response->setJSON([
			'status' => 'invalid',
			'messages' => "Terdapat input yang tidak valid.\n Kode Unit masih dalam proses Bongkar atau Muat ",
			'error' => 'Kode Unit masih dalam proses loading atau unloading',
		]);
	}

	private function simpanGrading(array $post)
	{
		if ((($post['grading']['jumlahsampling']) ?? '') != '') {
			$chitnumber = $post['chitnumber'];
			$model = new TrGradingModel();
			$grading = $post['grading'];
			$grading['chitnumber'] = $chitnumber;
			if ($model->find($chitnumber) == null) {
				$model->insert($grading);
			} else {
				$model->update($chitnumber, $grading);
			}
		}
	}

	private function simpanCpoKernelQuality(array $post)
	{
		if (((($post['kualitas']['ffa']) ?? '') != '') && ((($post['kualitas']['temperature']) ?? '') != '')) {
			$chitnumber = $post['chitnumber'];
			$kualitas = $post['kualitas'];
			$model = new TrCpoKernelQualityModel();
			$kualitas['chitnumber'] = $chitnumber;
			if ($model->find($chitnumber) == null) {
				$model->insert($kualitas);
			} else {
				$model->update($chitnumber, $kualitas);
			}
		}
	}

	private function updateUnitCode(array $post)
	{
		if (key_exists('unitcode', $post)) {
			$unitCodeModel = new MUnitModel();
			if (($unit = $unitCodeModel->find($post['unitcode'])) != null) {
				if ($unit['transportercode'] == null) {
					$unit['transportercode'] = $post['transportercode'];
				}
				$unitCodeModel->update($post['unitcode'], $unit);
				return true;
			}
		}
	}

	public function getTransaction($productCode)
	{
		$productTransMapModel = new MProductTransMapModel;
		$product_trans_map = $productTransMapModel->where("productcode = '$productCode'")->findAll();
		return $this->response->setJSON($product_trans_map);
	}

	// public function getTransporterMap($unitcode)
	// {
	// 	$transporterModel = new MtransporterUnitModel();
	// 	$transporter_unit_map = $transporterModel->where("unitcode = '$unitcode'")->findAll();
	// 	return $this->response->setJSON($transporter_unit_map);
	// 	// $productTransMapModel = new MProductTransMapModel;
	// 	// $product_trans_map = $productTransMapModel->where("productcode = '$unitcode'")->findAll();
	// 	// return $this->response->setJSON($product_trans_map);
	// }

	public function getTransporterMap()
	{	//$transporter_unit_model= new MUnitModel();
		// return $transporter_unit_model->db->showLastQuery(); die;
		$post = $this->request->getPost();
		$unitCode = $post['unitcode'];

		$transporter_unit_model= new MUnitModel();

		// if ( isset($post['unitCode'])) {
			
		// }
		$transporter_unit_map = $transporter_unit_model->where('m_unit.unitcode', $unitCode)
				->join('m_transporter trpt', 'trpt.transportercode = m_unit.transportercode', 'left')
				->select([
					'trpt.transportercode',
					'trpt.name',
					'm_unit.unitcode',
				])
				->findAll();
		
		return $this->response->setJSON($transporter_unit_map);

		// $productTransMapModel = new MProductTransMapModel;
		// $product_trans_map = $productTransMapModel->where("productcode = '$unitCode'")->findAll();
		// return $this->response->setJSON($product_trans_map);
	}

	
	public function getTransByUnit($unitcode)
	{	
		$db = Database::connect();
		$sql = "SELECT chitnumber FROM tr_wb WHERE `unitcode` = '$unitcode' and `status` ='0'";
		$chitnumber = $db->query($sql)->getRow()->chitnumber ?? "-";				
		return $chitnumber;

	}

	public function delete($wb_in){
		$model = new TrWbModel();
		try {
			$model->where([
				'wb_in' => $wb_in,
			])->delete();
		} catch (\Throwable $th) {
			throw $th;
		}
	
	}	

	public function cekChitNumberExists($chitnumber)
	{	
		$db = Database::connect();
		$sql = "SELECT COUNT(*) cek FROM tr_wb WHERE `chitnumber` = '$chitnumber'";
		$cek = $db->query($sql)->getRow()->cek ?? 0;
		// $db = Database::connect();
		// $trWbModel= new TrWbModel();
		// $cek = $trWbModel->where('tr_wb.chitnumber', $chitnumber)
		// 		->count_all_results();
		// // return $this->response->setJSON('cek'=$cek);
		// $count_all_query = $db->showLastQuery();
		// return $this->response->setJSON([
		// 	'status' => 'success',
		// 	'cek' => $cek,
		// ]);	die;
		// // return $this->response->setJSON($preset);
		// return $cek;
		return $this->response->setJSON([
			'status' => 'success',
			'cek' => $cek,
		]);

	}

	public function cekUnitBlumKeluar($unitcode)
	{	
		$db = Database::connect();
		$sql = "SELECT COUNT(*) cek FROM tr_wb WHERE `unitcode` = '$unitcode' and `status` ='0' ";
		$cek = $db->query($sql)->getRow()->cek ?? 0;
		return $cek;
	}
}