<?php
namespace App\Controllers;

use App\Models\ServerSideModel;
use App\Models\TrWbModel;
use CodeIgniter\Database\Config;
use Config\Database;
use Config\Services;
use App\Models\ParameterValueModel;
use App\Models\MCustomerModel;


ini_set('display_errors', 1);
class Report extends BaseController
{
	private $serverside_model;

	public function __construct()
	{
		$this->serverside_model = new ServerSideModel;
		return $this;
	}

	public function allcolumn()
	{
		$model = new TrWbModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		$listColumns = [
			'wbsitecode',
			'sitecode',
			'chitnumber',
			'sabno',
			'customercode',
			'productcode',
			'transactiontype',
			'unitcode',
			'npk_driver',
			'npk_helper1',
			'npk_helper2',
			'boarding',
			'wb_in',
			'wb_out',
			'weight_in',
			'weight_out',
			'kab_type',
			'kabcode',
			'kabraw',
			'operator_in',
			'terminal_in',
			'terminal_out',
			'operator_out',
			'nomorticket',
			'gate_in',
			'gate_out',
			'boarding_in',
			'jenis_unit',
			'nomor_polisi',
			'nama_driver',
			'kode_supplier',
			'wilayah_asal_tbs',
			'nama_supplier',
			'status',
			'sent',
		];
		return view('report/allcolumn', [
			'model' => $model,
			'request' => $this->request,
			'listColumns' => $listColumns,
			'detailColumns' => [],
		]);
	}

	public function produksiOld()
	{
			
		if ($this->request->getMethod() == 'post') {
			// $post = $this->request->getPost();
			$dateFrom = $this->request->getPost('dateFrom');
			$dateTo = $this->request->getPost('dateTo') . ' 23:59:59';
			$afdeling = $this->request->getPost('afdeling');
			$paramProduct = $this->request->getPost('productcode');
			$paramTranstype = $this->request->getPost('transtype');
			$paramCustomer = $this->request->getPost('customercode');
			$paramJamMulai = $this->request->getPost('jam_mulai');
			$paramJamSelesai = $this->request->getPost('jam_selesai');
			
			

			$db = Database::connect();
			$tabel = $db->table('tr_kab')
				->join('tr_wb', 'tr_wb.chitnumber = tr_kab.chitnumber')
				->select([
					'tr_wb.wb_in AS tanggal',
					'tr_wb.chitnumber',
					'tr_kab.nocafd AS afdeling',
					'tr_kab.nocblock AS block',
					'SUM(tr_kab.jjg) AS janjang',
				])
				->groupBy([
					'tr_wb.wb_in',
					'tr_wb.chitnumber',
					'tr_kab.nocafd',
					'tr_kab.nocblock',
				])
				->where("tr_wb.wb_in BETWEEN '$dateFrom' AND '$dateTo'");
			if ($afdeling != '') {
				$tabel->where('tr_kab.nocafd', $afdeling);
			}
			
			$db = Database::connect();
			$lastQuery = $db->showLastQuery();
			return $lastQuery; die;
			

			$count_all = $tabel->countAllResults(false);
			$count_all_query = $db->showLastQuery();
			
			if ($_POST['search']['value']) {
				$searchValue = $_POST['search']['value'];
				$tabel->groupStart();
				$tabel->like('tr_wb.chitnumber', $searchValue);
				$tabel->orLike('tr_kab.nocafd', $searchValue);
				$tabel->orLike('tr_kab.nocblock', $searchValue);
				$tabel->groupEnd();
			}

			$count_filtered = $tabel->countAllResults(false);
			$sum_janjang = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
				->selectSum('janjang', 'sum_jjg')->get()->getFirstRow()->sum_jjg;
			//$count_filtered_query = $db->showLastQuery();

			if ($_POST['length'] != -1) $tabel->limit($_POST['length'], $_POST['start']);

			$data = $tabel->get()->getResult();
			//$data_query = $db->showLastQuery();

			return json_encode([
				'draw' => $this->request->getPost('draw'),
				'recordsTotal' => $count_all,
				'recordsFiltered' => $count_filtered,
				'sum_janjang' => $sum_janjang,
				'data' => $data,
				'count_all_query' => $count_all_query,
				//'count_filtered_query' => $count_filtered_query,
			]);
		}

		
		
		$parameterValueModel = new ParameterValueModel($this->request);
		$parameter_values = $parameterValueModel->where('active', 'Y')
			->orderBy('description', 'asc')
			->findAll();
		$parameterValuesMap = [];
		foreach ($parameter_values as $value) {
			if (!isset($parameterValuesMap[$value['parameter_code']])) {
				$parameterValuesMap[$value['parameter_code']] = [
					$value['value'] => $value['description']
				];
			} else {
				$parameterValuesMap[$value['parameter_code']][$value['value']] = $value['description'];
			}
		}

		$customerModel = new MCustomerModel();
		$customers = $customerModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();
		$preset = [
			'parameter_values' => $parameterValuesMap,
			'customers'=>$customers,
			];
		return view('report/produksi',$preset);
	}

	public function produksi()
	{
		
		if ($this->request->getMethod() == 'post') {
			// $post = $this->request->getPost();
			$dateFrom = $this->request->getPost('dateFrom').' '.$this->request->getPost('jam_mulai').':00:01';
			$dateTo = $this->request->getPost('dateTo').' '.$this->request->getPost('jam_selesai').':59:59';//. ' 23:59:59';
			$afdeling = $this->request->getPost('afdeling');
			$paramProduct = $this->request->getPost('productcode');
			$paramTranstype = $this->request->getPost('transactiontype');
			$paramCustomer = $this->request->getPost('customercode');
			$paramJamMulai = $this->request->getPost('jam_mulai');
			$paramJamSelesai = $this->request->getPost('jam_selesai');
			
			

			$db = Database::connect();
			$tabel = $db->table('v_report_harian')
					->join('tr_grading', 'tr_grading.chitnumber = v_report_harian.chitnumber','left')
				
				// ->join('tr_wb', 'tr_wb.chitnumber = tr_kab.chitnumber')

				/*
				->join('tr_grading', 'tr_grading.chitnumber = tr_wb.chitnumber','left')
				// ->select('m_customer.name as customername, m_parameter_values.description,COUNT(DISTINCT tr_wb.chitnumber) AS rit,  SUM(tr_wb.weight_in) AS weight_in, SUM(tr_wb.weight_out) AS weight_out, ABS(SUM(tr_wb.weight_out)-SUM(tr_wb.weight_in)) AS weightnet, SUM(COALESCE(tr_grading.adjustweight,0)) as adjustweight, ABS(SUM(tr_wb.weight_in)-SUM(tr_wb.weight_out)) - SUM(COALESCE(tr_grading.adjustweight,0)) as netto ')
				->select('m_customer.name as customername,m_parameter_values.description, tr_wb.transactiontype,COUNT(DISTINCT tr_wb.chitnumber) AS rit,  SUM(tr_wb.weight_in) AS weight_in, SUM(tr_wb.weight_out) AS weight_out, ABS(SUM(tr_wb.weight_out)-SUM(tr_wb.weight_in)) AS weightnet, SUM(COALESCE(tr_grading.adjustweight,0)) as adjustweight, ABS(SUM(tr_wb.weight_in)-SUM(tr_wb.weight_out)) - SUM(COALESCE(tr_grading.adjustweight,0)) as netto ')
				
				*/
				->select([
					'DATE_FORMAT(v_report_harian.wb_in, "%d/%m/%Y %H:%i:%s") AS tanggal',
					'v_report_harian.chitnumber',
					'v_report_harian.transactiontype',
					'v_report_harian.productcode',
					'v_report_harian.product',
					'v_report_harian.customercode',
					'v_report_harian.customername',
					'v_report_harian.unitcode',
					'v_report_harian.transportercode',
					// 'v_report_harian.wb_in',
					'DATE_FORMAT(v_report_harian.wb_in, "%d/%m/%Y %H:%i:%s") AS wb_in',
					'DATE_FORMAT(v_report_harian.wb_out, "%d/%m/%Y %H:%i:%s") AS wb_out',
					// 'v_report_harian.wb_out',
					'v_report_harian.weight_in',
					'v_report_harian.weight_out',
					'v_report_harian.weightnet',
					'v_report_harian.npk_driver',
					'v_report_harian.name',
					'v_report_harian.afd AS afdeling',
					'v_report_harian.blok AS block',
					'v_report_harian.jjg AS janjang',
					'COALESCE(tr_grading.adjustweight,0) AS adjustweight',
					'v_report_harian.weightnet - COALESCE(tr_grading.adjustweight,0) AS netto'
				])
				// ->groupBy([
				// 	'v_report_harian.wb_in',
				// 	'v_report_harian.chitnumber',
				// 	'v_report_harian.nocafd',
				// 	'v_report_harian.nocblock',
				// ])
				->where("v_report_harian.wb_in BETWEEN '$dateFrom' AND '$dateTo'");
			if ($afdeling != '') {
				$tabel->where('v_report_harian.afd', $afdeling);
			}
			if ($paramCustomer!=''){
				$tabel->where('v_report_harian.customercode', $paramCustomer);
			}
			if ($paramProduct!=''){
				$tabel->where('v_report_harian.productcode', $paramProduct);
			}
			if ($paramTranstype!=''){
				$tabel->where('v_report_harian.transactiontype', $paramTranstype);
			}

			$count_all = $tabel->countAllResults(false);
			
			
			if ($_POST['search']['value']) {
				$searchValue = $_POST['search']['value'];
				$tabel->groupStart();
				$tabel->like('v_report_harian.chitnumber', $searchValue);
				$tabel->orLike('v_report_harian.customercode', $searchValue);
				$tabel->orLike('v_report_harian.customername', $searchValue);
				
				$tabel->orLike('v_report_harian.unitcode', $searchValue);
				$tabel->orLike('v_report_harian.transportercode', $searchValue);
				
				$tabel->orLike('v_report_harian.transactiontype', $searchValue);
				$tabel->orLike('v_report_harian.productcode', $searchValue);
				$tabel->groupEnd();
			}

			$count_filtered = $tabel->countAllResults(false);
			$sum_janjang = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
				->selectSum('janjang', 'sum_jjg')->get()->getFirstRow()->sum_jjg;

			$sum_tonase = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
				->selectSum('weightnet', 'sum_tonase')->get()->getFirstRow()->sum_tonase;
			
			if ($_POST['length'] != -1) $tabel->limit($_POST['length'], $_POST['start']);

			$data = $tabel->get()->getResult();
			$lastQuery = $db->showLastQuery();
			// $lastQuery= $this->db->last_query(); 
			return json_encode([
				'draw' => $this->request->getPost('draw'),
				'recordsTotal' => $count_all,
				'recordsFiltered' => $count_filtered,
				'sum_janjang' => $sum_janjang,
				'sum_tonase' => $sum_tonase,
				'data' => $data,
				'query' => $lastQuery,
				//'count_all_query' => $count_all_query,
				//'count_filtered_query' => $count_filtered_query,
			]);
			
			// print_r();    die();
			
		}
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

		$customerModel = new MCustomerModel();
		$customers = $customerModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();
			$preset = [
				'parameter_values' => $parameterValuesMap,
				'customers'=>$customers,
				];
		return view('report/produksi',$preset);
	}

	public function summary()
	{
		
		if ($this->request->getMethod() == 'post') {
			// $post = $this->request->getPost();
			$dateFrom = $this->request->getPost('dateFrom').' '.$this->request->getPost('jam_mulai').':00:01';
			$dateTo = $this->request->getPost('dateTo').' '.$this->request->getPost('jam_selesai').':59:59';//. ' 23:59:59';
			$paramProduct = $this->request->getPost('productcode');
			$paramCustomer = $this->request->getPost('customercode');
			
			$paramTranstype = $this->request->getPost('transactiontype');
			$paramJamMulai = $this->request->getPost('jam_mulai');
			$paramJamSelesai = $this->request->getPost('jam_selesai');
			
			

			$db = Database::connect();
			// $tabel = $db->table('v_summary')
			// 	// ->join('tr_wb', 'tr_wb.chitnumber = tr_kab.chitnumber')
			// 	->select([
			// 		'v_summary.customername',
			// 		'v_summary.description',
			// 		'v_summary.weight_in',
			// 		'v_summary.weight_out',
			// 		'v_summary.weightnet',
			// 		'v_summary.adjustweight',
			// 		'v_summary.netto',
			// 	])
			// 	// ->where("v_summary.wb_in BETWEEN '$dateFrom' AND '$dateTo'")
			// 	;

			$tabel = $db->table('tr_wb')
				->join('m_customer', 'm_customer.customercode = tr_wb.customercode','left')
				->join('m_parameter_values', 'm_parameter_values.value = tr_wb.productcode','left')
				->join('tr_grading', 'tr_grading.chitnumber = tr_wb.chitnumber','left')
				// ->select('m_customer.name as customername, m_parameter_values.description,COUNT(DISTINCT tr_wb.chitnumber) AS rit,  SUM(tr_wb.weight_in) AS weight_in, SUM(tr_wb.weight_out) AS weight_out, ABS(SUM(tr_wb.weight_out)-SUM(tr_wb.weight_in)) AS weightnet, SUM(COALESCE(tr_grading.adjustweight,0)) as adjustweight, ABS(SUM(tr_wb.weight_in)-SUM(tr_wb.weight_out)) - SUM(COALESCE(tr_grading.adjustweight,0)) as netto ')
				->select('m_customer.name as customername,m_parameter_values.description, tr_wb.transactiontype,COUNT(DISTINCT tr_wb.chitnumber) AS rit,  SUM(tr_wb.weight_in) AS weight_in, SUM(tr_wb.weight_out) AS weight_out, ABS(SUM(tr_wb.weight_out)-SUM(tr_wb.weight_in)) AS weightnet, SUM(COALESCE(tr_grading.adjustweight,0)) as adjustweight, ABS(SUM(tr_wb.weight_in)-SUM(tr_wb.weight_out)) - SUM(COALESCE(tr_grading.adjustweight,0)) as netto ')
				// ->groupBy([
				// 	'm_customer.name',
				// 	'm_parameter_values.description',
				// ])
				->groupBy([
					'm_customer.name',
					'm_parameter_values.description',
					'tr_wb.transactiontype',
				])
				->where("tr_wb.wb_in BETWEEN '$dateFrom' AND '$dateTo'")
				->where("tr_wb.status ='1'")
				->orderBy('tr_wb.transactiontype', 'desc')
				// ->orderBy('m_customer.name', 'asc')
				;
				
				
			
			//$lastQuery = $db->showLastQuery();
			// echo "test";die;
			// echo($this->db->last_query()) ; die;
			
			if ($paramCustomer!=''){
				$tabel->where('tr_wb.customercode', $paramCustomer);
			}
			if ($paramProduct!=''){
				$tabel->where('tr_wb.productcode', $paramProduct);
			}
			if ($paramTranstype!=''){
				$tabel->where('tr_wb.transactiontype', $paramTranstype);
			}
			
			

			$count_all = $tabel->countAllResults(false);
			
			
			if ($_POST['search']['value']) {
				$searchValue = $_POST['search']['value'];
				$tabel->groupStart();
				$tabel->orLike('m_customer.name', $searchValue);
				$tabel->orLike('m_parameter_values.vale', $searchValue);
				$tabel->groupEnd();
			}

			$count_filtered = $tabel->countAllResults(false);
			$sum_weight_in = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
				->selectSum('weight_in', 'sum_weight_in')->get()->getFirstRow()->sum_weight_in;

			
			$sum_weight_out = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
			->selectSum('weight_out', 'sum_weight_out')->get()->getFirstRow()->sum_weight_out;

			
			$sum_weightnet = abs($sum_weight_in - $sum_weight_out);

			$sum_adjustweight = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
			->selectSum('adjustweight', 'sum_adjustweight')->get()->getFirstRow()->sum_adjustweight;

			$netto = $sum_weightnet -$sum_adjustweight;
			// $sum_weighnet = $db->table('(' . $tabel->getCompiledSelect(false) . ') AS src')
			// 	->selectSum('weightnet', 'sum_weightnet')->get()->getFirstRow()->sum_weightnet;
			
			// if ($_POST['length'] != -1) $tabel->limit($_POST['length'], $_POST['start']);
			$data = $tabel->get()->getResult();
			$lastQuery = $db->showLastQuery();
			return json_encode([
				'draw' => $this->request->getPost('draw'),
				'recordsTotal' => $count_all,
				'recordsFiltered' => $count_filtered,
				'sum_weight_in' => $sum_weight_in,
				'sum_weight_out' => $sum_weight_out,
				'sum_weightnet'=>$sum_weightnet,
				'sum_adjustweight'=>$sum_adjustweight,
				'netto'=>$netto,
				'data' => $data,
				'query' => $lastQuery,
				//'count_all_query' => $count_all_query,
				//'count_filtered_query' => $count_filtered_query,
			]);
			
			// print_r();    die();
			
		}
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

		$customerModel = new MCustomerModel();
		$customers = $customerModel->where(['deleted_at' => null])
			->orderBy('name', 'asc')
			->where('active', 'Y')
			->findAll();
			$preset = [
				'parameter_values' => $parameterValuesMap,
				'customers'=>$customers,
				];
		return view('report/summary',$preset);
	}
	
}