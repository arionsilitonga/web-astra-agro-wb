<?php
namespace App\Controllers;

use App\Models\TrCpoKernelQualityModel;
use App\Models\TrGradingModel;
use App\Models\TrKabModel;
use App\Models\TrWbModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Database as DatabaseDatabase;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use App\Models\ParameterValueModel;
use CodeIgniter\HTTP\Response;
use PDO;

class Cron extends Controller {

	public const webAPIEndpoint = '/api-wb/SendWb/sendWbIn';
	public const updateAPIEndpoint = '/api-wb/SendWb/sendWbOut';

	public function scheduler($chitnumber = null){
		$TrWbModel = new TrWbModel();
		$TrKabModel = new TrKabModel();
		//$TrGradingModel = new TrGradingModel();
		//$TrCpoKernelQualityModel = new TrCpoKernelQualityModel();

		$tr_wb_data = $TrWbModel->where('(status = 1) AND (sent != "Y") AND (sent != "P") AND (tr_wb.deleted_at IS NULL)')
			->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
			->join('tr_grading', 'tr_grading.chitnumber = tr_wb.chitnumber', 'left')
			->join('tr_cpo_kernel_quality AS qwt', 'qwt.chitnumber = tr_wb.chitnumber', 'left')
			->select([
				'tr_wb.wbsitecode',
				'tr_wb.sitecode',
				'tr_wb.chitnumber',
				'tr_wb.customercode',
				'tr_wb.productcode',
				'tr_wb.transactiontype',
				'tr_wb.wb_in',
				'tr_wb.weight_in',
				'tr_wb.wb_out',
				'tr_wb.weight_out',
				'tr_wb.unitcode',
				'm_unit.transportercode',
				'tr_wb.npk_driver',
				'tr_wb.npk_helper1',
				'tr_wb.npk_helper2',
				'tr_wb.driver_manual as npk_helper3',
				'tr_wb.boarding',
				'tr_wb.terminal_in',
				'tr_wb.terminal_out',
				'tr_wb.operator_in',
				'tr_wb.operator_out',
				'tr_wb.kabraw',
				'tr_grading.jumlahsampling',
				'tr_grading.bb',
				'tr_grading.bm',
				'tr_grading.tp',
				'tr_grading.or AS overripe',
				'tr_grading.tks',
				'tr_grading.adjustweight AS adjustweigh',
				'qwt.ffa',
				'qwt.temperature',
				'qwt.moist',
				'qwt.dirt',
				'qwt.kernel_pecah',
				'qwt.seal_number',
				'tr_wb.sabno',
				'tr_wb.nomorticket',
				'tr_wb.gate_in',
				'tr_wb.gate_out',
				'tr_wb.boarding_in AS boarding_ext',
				'tr_wb.jenis_unit',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.kode_supplier',
				'tr_wb.nama_supplier',
				'tr_wb.wilayah_asal_tbs',
				'tr_wb.kab_prop',
				'tr_wb.kab_createdate',
				'tr_wb.kab_createby',
				'tr_wb.jjg_ext',
			]);
		
		if ($chitnumber != null) {
			$tr_wb_data = $tr_wb_data->where(['tr_wb.chitnumber' => $chitnumber]);
		}
		
		$tr_wb_data = $tr_wb_data->findAll(5);
		
		$terproses = 0;
		$success_count = 0;
		$errors = [];
		foreach ($tr_wb_data as $tr_wb) {
			# code...
			$tr_wb['wb_in'] = date('d-m-Y H:i:s', strtotime($tr_wb['wb_in']));
			$tr_wb['wb_out'] = date('d-m-Y H:i:s', strtotime($tr_wb['wb_out']));
			//$tr_wb['npk_helper3'] = '';

			/*
			if (($grading = $TrGradingModel->find($tr_wb['chitnumber'])) != null) {
				$tr_wb['jumlahsampling'] = $grading['jumlahsampling'];
				$tr_wb['bb'] = $grading['bb'];
				$tr_wb['bm'] = $grading['bm'];
				$tr_wb['tp'] = $grading['tp'];
				$tr_wb['overripe'] = $grading['or'];
				$tr_wb['tks'] = $grading['tks'];
				$tr_wb['adjustweigh'] = $grading['adjustweight'];	
			}
			*/

			/*
			if (($kualitas = $TrCpoKernelQualityModel->find($tr_wb['chitnumber'])) != null) {
				$tr_wb['ffa'] = $kualitas['ffa'];
				$tr_wb['temperature'] = $kualitas['temperature'];
				$tr_wb['moist'] = $kualitas['moist'];
				$tr_wb['dirt'] = $kualitas['dirt'];
				$tr_wb['kernel_pecah'] = $kualitas['kernel_pecah'];
				$tr_wb['seal_number'] = $kualitas['seal_number'];
			}
			*/

			//$noc_data = $TrKabModel->where('chitnumber', $tr_wb['chitnumber'])->findAll();
			$noc_data = $TrKabModel->where('chitnumber', $tr_wb['chitnumber'])
					->select(['tr_kab.chitnumber',
							'tr_kab.nocvalue'],
					)
					->groupBy([
						'tr_kab.chitnumber',
						'tr_kab.nocvalue',
					])
					->findAll();
			$noclist = '';
			foreach ($noc_data as $noc) {
				if ($noclist != '') $noclist .= '|';
				$noclist .= $noc['nocvalue'];
			}
			$tr_wb['noclist'] = $noclist;
			//unset($tr_wb['created_at']);
			//unset($tr_wb['updated_at']);
			//unset($tr_wb['deleted_at']);
			
			$api_response = $this->postAPIWb($tr_wb);
			echo $tr_wb['chitnumber'] . ' => ' . PHP_EOL;
			echo print_r($api_response);
			
			if ($api_response['success']) {
				$TrWbModel->update($tr_wb['chitnumber'], ['sent' => 'Y']);
				$success_count++;
			} else {
				/*
				$messages = $api_response['messages'];
				$errors[$tr_wb['chitnumber']] = [
					'messages' => $api_response['messages'],
					'data' => $tr_wb,
				];
				if ($messages == 'data sudah ada') {
					$TrWbModel->update($tr_wb['chitnumber'], ['sent' => 'Y']);
				} else {*/
					$TrWbModel->update($tr_wb['chitnumber'], ['sent' => '0']);
				//}
				//echo json_encode($tr_wb) . PHP_EOL;
				//echo $api_response['messages'] . PHP_EOL;
				print_r($tr_wb); //die();
			}
			$terproses++;
		}

		$bklReturn = PHP_EOL
			. '---- Summary ------' . PHP_EOL 
		 	. $terproses . ' data' . PHP_EOL
			. $success_count . ' sukses terkirim' . PHP_EOL;
		if (count($errors) > 0) {
			$bklReturn .= 'Errors :' . PHP_EOL .  print_r($errors, true);
		}

		if ($chitnumber == null) {
			helper("filesystem");
			
			$folderCron = WRITEPATH . 'cron/';
			if (file_exists($folderCron)) {
				mkdir($folderCron);
			}

			$filename = $folderCron . date('Y-m-d h.i.s a') .'.txt'; 
			write_file($filename, $bklReturn);
			
			return $filename . PHP_EOL . $bklReturn . PHP_EOL;
		} else {
			return [
				'success' => (count($errors) > 0 ? false : true),
				'terproses' => $terproses,
				'terkirim' => $success_count,
				'errors' => $errors,
			];
		}
	}

	public function sendWbIn($chitnumber = null){
		$TrWbModel = new TrWbModel();
		$TrKabModel = new TrKabModel();

		$tr_wb_data = $TrWbModel->where('sent ="0"')
			->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
			->join('tr_grading', 'tr_grading.chitnumber = tr_wb.chitnumber', 'left')
			->join('tr_cpo_kernel_quality AS qwt', 'qwt.chitnumber = tr_wb.chitnumber', 'left')
			->select([
				'tr_wb.wbsitecode',
				'tr_wb.sitecode',
				'tr_wb.chitnumber',
				'tr_wb.customercode',
				'tr_wb.productcode',
				'tr_wb.transactiontype',
				'tr_wb.wb_in',
				'tr_wb.weight_in',
				'tr_wb.wb_out',
				'tr_wb.weight_out',
				'tr_wb.unitcode',
				'm_unit.transportercode',
				'tr_wb.npk_driver',
				'tr_wb.npk_helper1',
				'tr_wb.npk_helper2',
				'tr_wb.driver_manual as npk_helper3',
				'tr_wb.boarding',
				'tr_wb.terminal_in',
				'tr_wb.terminal_out',
				'tr_wb.operator_in',
				'tr_wb.operator_out',
				'tr_wb.kabraw',
				'tr_grading.jumlahsampling',
				'tr_grading.bb',
				'tr_grading.bm',
				'tr_grading.tp',
				'tr_grading.or AS overripe',
				'tr_grading.tks',
				'tr_grading.adjustweight AS adjustweigh',
				'qwt.ffa',
				'qwt.temperature',
				'qwt.moist',
				'qwt.dirt',
				'qwt.kernel_pecah',
				'qwt.seal_number',
				'tr_wb.sabno',
				'tr_wb.nomorticket',
				'tr_wb.gate_in',
				'tr_wb.gate_out',
				'tr_wb.boarding_in AS boarding_ext',
				'tr_wb.jenis_unit',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.kode_supplier',
				'tr_wb.nama_supplier',
				'tr_wb.wilayah_asal_tbs',
				'tr_wb.kab_prop',
				'tr_wb.kab_createdate',
				'tr_wb.kab_createby',
				'tr_wb.jjg_ext',
			]);
		
		if ($chitnumber != null) {
			$tr_wb_data = $tr_wb_data->where(['tr_wb.chitnumber' => $chitnumber]);
		}
		
		$tr_wb_data = $tr_wb_data->findAll(5);
		

		$terproses = 0;
		$success_count = 0;
		$errors = [];
		// $db = Database::connect();
		//$lastQuery = $db->showLastQuery();
		// echo($lastQuery);
		//print_r($tr_wb_data);die();

		foreach ($tr_wb_data as $tr_wb) {
			# code...
			$tr_wb['wb_in'] = date('d-m-Y H:i:s', strtotime($tr_wb['wb_in']));
			$tr_wb['wb_out'] = '';
			$noc_data = $TrKabModel->where('chitnumber', $tr_wb['chitnumber'])
					->select(['tr_kab.chitnumber',
							'tr_kab.nocvalue'],
					)
					->groupBy([
						'tr_kab.chitnumber',
						'tr_kab.nocvalue',
					])
					->findAll();
			$noclist = '';
			foreach ($noc_data as $noc) {
				if ($noclist != '') $noclist .= '|';
				$noclist .= $noc['nocvalue'];
			}
			$tr_wb['noclist'] = $noclist;
			
			//print_r($tr_wb);
			
			$api_response = $this->postAPIWb($tr_wb);
			//print_r($api_response);die();
			echo $tr_wb['chitnumber'] . ' => ' . PHP_EOL;
			echo print_r($tr_wb);
			
			if ($api_response['success']) {
				$TrWbModel->update($tr_wb['chitnumber'], ['sent' => 'Y']);
				$success_count++;
			} else {				
				$TrWbModel->update($tr_wb['chitnumber'], ['sent' => '0']);
				print_r($tr_wb); //die();
			}
			$terproses++;
		}

		$bklReturn = PHP_EOL
			. '---- Summary ------' . PHP_EOL 
		 	. $terproses . ' data' . PHP_EOL
			. $success_count . ' sukses terkirim' . PHP_EOL;
		if (count($errors) > 0) {
			$bklReturn .= 'Errors :' . PHP_EOL .  print_r($errors, true);
		}

		if ($chitnumber == null) {
			helper("filesystem");
			
			$folderCron = WRITEPATH . 'cron/';
			if (file_exists($folderCron)) {
				mkdir($folderCron);
			}

			$filename = $folderCron . date('Y-m-d h.i.s a') .'.txt'; 
			write_file($filename, $bklReturn);
			
			return $filename . PHP_EOL . $bklReturn . PHP_EOL;
		} else {
			return [
				'success' => (count($errors) > 0 ? false : true),
				'terproses' => $terproses,
				'terkirim' => $success_count,
				'errors' => $errors,
			];
		}
	}

	public function sendWbOut($chitnumber = null){
		$TrWbModel = new TrWbModel();

		$tr_wb_data = $TrWbModel->where('status ="1" and sent ="Y" and sentout ="0"')
			->select([
				'tr_wb.chitnumber',
				'tr_wb.wb_out',
				'tr_wb.weight_out',
				'tr_wb.operator_out',
				'tr_wb.keterangan',
			]);
		
		if ($chitnumber != null) {
			$tr_wb_data = $tr_wb_data->where(['tr_wb.chitnumber' => $chitnumber]);
		}
		
		$tr_wb_data = $tr_wb_data->findAll(5);
		//$db = Database::connect();
		//$lastQuery = $db->showLastQuery();
		//echo($lastQuery); die();
		

		$terproses = 0;
		$success_count = 0;
		$errors = [];
		//print_r ($tr_wb_data);die();
		foreach ($tr_wb_data as $tr_wb) {
			# code...
			// $tr_wb['wb_in'] = date('d-m-Y H:i:s', strtotime($tr_wb['wb_in']));
			$tr_wb['wb_out'] = date('d-m-Y H:i:s', strtotime($tr_wb['wb_out']));
			//print_r($tr_wb);die();
			$api_response = $this->updateAPIWb($tr_wb);
			
			echo $tr_wb['chitnumber'] . ' => ' . PHP_EOL;
			//echo $tr_wb['chitnumber']; die();
			
			if ($api_response['success']) {
				$TrWbModel->update($tr_wb['chitnumber'], ['sentout' => 'Y']);
				$success_count++;
			} else {				
				$TrWbModel->update($tr_wb['chitnumber'], ['sentout' => '0']);
				print_r($tr_wb); //die();
			}
			$terproses++;
		}

		$bklReturn = PHP_EOL
			. '---- Summary ------' . PHP_EOL 
		 	. $terproses . ' data' . PHP_EOL
			. $success_count . ' sukses terkirim' . PHP_EOL;
		if (count($errors) > 0) {
			$bklReturn .= 'Errors :' . PHP_EOL .  print_r($errors, true);
		}

		if ($chitnumber == null) {
			helper("filesystem");
			
			$folderCron = WRITEPATH . 'cron/';
			if (file_exists($folderCron)) {
				mkdir($folderCron);
			}

			$filename = $folderCron . date('Y-m-d h.i.s a') .'.txt'; 
			write_file($filename, $bklReturn);
			
			return $filename . PHP_EOL . $bklReturn . PHP_EOL;
		} else {
			return [
				'success' => (count($errors) > 0 ? false : true),
				'terproses' => $terproses,
				'terkirim' => $success_count,
				'errors' => $errors,
			];
		}
	}

	public function BukaMDB(){
		//tblProducts(Code, Name)
		$db = new PDO('odbc:Driver=MDBTools;DBQ=/home/kurnia/Downloads;', null, 'MEWAALPWD2014');
		$result = $db->query('SELECT [Code], [Name] FROM tblProducts')->fetchAll(PDO::FETCH_ASSOC);
		return print_r($result, true) . PHP_EOL;
	}

	public function postAPIWb($form_params){
		/**
		 * @var ResponseInterface $response
		 */
		$ci_env = getenv('CI_ENVIRONMENT');
		$web_api_url = '';
		// if(strtolower($ci_env)=='production'){
		// 	$web_api_url = getenv('app.webAPIUrlProduction');
		// }else{
		// 	$web_api_url = getenv('app.webAPIUrlDevelopment');
		// }

		$web_api_url = ParameterValueModel::getValue('APICONFIG');
		
		if(empty($web_api_url)){
			return ['success'=>false,'messages'=>'Web API Url is not configure'];
		}
		//echo ($web_api_url . static::webAPIEndpoint);
		//die();
		try {

			$client = \Config\Services::curlrequest();

			$response = $client->request('POST', $web_api_url . static::webAPIEndpoint, [
				'form_params' => $form_params,
				'timeout' => 60
			]);
			//print_r($response);die();

			if($response->getStatusCode() == 200){
				return [
					'success' => true,
					'messages' => 'Success Post Timbang',
					'statusCode' => $response->getStatusCode(),
					'reason' => $response->getReason(),
				];
			}else if($response->getStatusCode() == 302) {
				return [
					'success' => true,
					'messages' => 'data sudah ada',
					'statusCode' => $response->getStatusCode(),
					'reason' => $response->getReason(),
				];
			}else{
				/*$body = $response->getJSON();
				if (isset($body['message']) && ($body['message'] == 'data sudah ada')) {
					return [
						'success' => false,
						'messages' => $body['message'],
					];
				} else {*/
					return [
						'success' => false,
						'messages' => 'error:' . $response->getStatusCode().' '.$response->getReason(),
						'statusCode' => $response->getStatusCode(),
						'reason' => $response->getReason(),	
					];	
				//}
			}

		} catch (\Exception $e) {			
			return [
				'success'=>false,
				'messages' => 'Error: ' . $e->getMessage(),
			];
		}				
	}

	public function updateAPIWb($form_params){
		/**
		 * @var ResponseInterface $response
		 */
		$ci_env = getenv('CI_ENVIRONMENT');
		$web_api_url = '';
		$web_api_url = ParameterValueModel::getValue('APICONFIG');

		if(empty($web_api_url)){
			return ['success'=>false,'messages'=>'Web API UPDATE Url is not configure'];
		}
		// /echo($web_api_url . static::updateAPIEndpoint);die();
		try {

			$client = \Config\Services::curlrequest();

			$response = $client->request('POST', $web_api_url . static::updateAPIEndpoint, [
				'form_params' => $form_params,
				'timeout' => 30
			]);

			if($response->getStatusCode() == 200){
				return [
					'success' => true,
					'messages' => 'Success Post Timbang',
					'statusCode' => $response->getStatusCode(),
					'reason' => $response->getReason(),
				];
			}else if($response->getStatusCode() == 302) {
				return [
					'success' => true,
					'messages' => 'data sudah ada',
					'statusCode' => $response->getStatusCode(),
					'reason' => $response->getReason(),
				];
			}else{
				return [
					'success' => false,
					'messages' => 'error:' . $response->getStatusCode().' '.$response->getReason(),
					'statusCode' => $response->getStatusCode(),
					'reason' => $response->getReason(),	
				];
			}

		} catch (\Exception $e) {			
			return [
				'success'=>false,
				'messages' => 'Error: ' . $e->getMessage(),
			];
		}				
	}
}