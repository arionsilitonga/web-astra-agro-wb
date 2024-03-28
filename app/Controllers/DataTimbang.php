<?php
namespace App\Controllers;

use App\Database\Migrations\TrWb;
use App\Models\ServerSideModel;
use App\Models\TrCpoKernelQualityModel;
use App\Models\TrGradingModel;
use App\Models\TrKabModel;
use App\Models\TrWbModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;
use Config\Services;
ini_set('display_errors', 1);
/**
 * @param ServerSideModel $serverside_model
 */

class DataTimbang extends BaseController{

	private $serverside_model;

	public function __construct()
	{
		$this->serverside_model = new ServerSideModel();
		return $this;
	}

	private function getList(callable $mapFunction, $where = ['tr_wb.status' => 0], $order = ['chitnumber' => 'asc']){
		//$tabel = 'tr_wb';
		$db = Database::connect();
		$tabel = $db->table('tr_wb');
		$tabel->join('m_customer', 'm_customer.customercode = tr_wb.customercode', 'left')
			->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
			->join('m_transporter', 'm_transporter.transportercode = m_unit.transportercode', 'left')
			->join('m_employee', 'm_employee.npk = tr_wb.npk_driver', 'left')
			->join('m_parameter_values','m_parameter_values.value=tr_wb.productcode','left');
		$request = Services::request();
		$list_data = $this->serverside_model;
		$column_order = [
			'chitnumber',
			'tr_wb.customercode',
			'tr_wb.transactiontype',
			'tr_wb.npk_driver',
			'm_transporter.name',
			'tr_wb.unitcode', 
			'tr_wb.wb_in',
			'tr_wb.weight_in',
			'tr_wb.weight_out',
			'tr_wb.npk_driver',
			'tr_wb.driver_manual',
			'tr_wb.nomor_polisi',
			'tr_wb.jjg_ext',
		];
		$column_search = ['chitnumber', 'tr_wb.customercode', 'tr_wb.unitcode', 'tr_wb.npk_driver','tr_wb.driver_manual','tr_wb.nomor_polisi'];//,'m_parameter_values.description'
		$select = [
			'tr_wb.chitnumber',
			'tr_wb.customercode',
			'tr_wb.unitcode',
			'tr_wb.wb_in',
			'tr_wb.weight_in',
			'tr_wb.wb_out',
			'tr_wb.weight_out',
			'tr_wb.npk_driver',
			'm_customer.name as customer_name',
			'm_unit.platenumber',
			'm_unit.transportercode',
			'm_transporter.name as transporter_name',
			'm_employee.name as driver_name',
			'tr_wb.transactiontype',
			// 'tr_wb.productcode',
			'm_parameter_values.description as productcode',
			'tr_wb.driver_manual',
			'tr_wb.nomor_polisi',
			'tr_wb.jjg_ext',
		];
		$list = $list_data->get_datatables($tabel, $column_order, $column_search, $order, $where, $select);
		$data = [];
		$no = $request->getPost('start');

		$db = Database::connect();
		$lastQuery = $db->showLastQuery();

		foreach ($list as $lst) {
			$no++;
			$data[] = $mapFunction($lst, $no);
		}

		$count_all = $list_data->count_all($tabel, $where);
		$count_all_query = $db->showLastQuery();

		$count_filtered = $list_data->count_filtered($tabel, $column_order, $column_search, $order, $where);
		$count_filtered_query = $db->showLastQuery();

		return json_encode([
			'draw' => $request->getPost('draw'),
			'recordsTotal' => $count_all,
			'recordsFiltered' => $count_filtered,
			'data' => $data,
			'lastQuery' => $lastQuery,
			'count_all_lastQuery' => $count_all_query,
			'count_filtered_lastQuery' => $count_filtered_query,
		]);
	}

	public function index(){
		if ($this->request->getMethod() == 'post'){
			$kabModel = new TrKabModel();
			return $this->getList(function($lst)use($kabModel){
				$kabData = $kabModel->where('chitnumber', $lst->chitnumber)
					->findAll();
				return [
					count($kabData) > 0 ? '<button class="btn btn-expand"><i class="fas fa-plus-circle"></i></button>' : '',
					$lst->chitnumber,
					$lst->customer_name ?? $lst->customercode,
					$lst->transporter_name ?? ($lst->transportercode ?? '-'),
					$lst->unitcode . ' - ' . ($lst->platenumber ?? ''),
					$lst->wb_in,
					number_format($lst->weight_in,0, ',', '.'),
					$lst->wb_out,
					number_format($lst->weight_out,0, ',', '.'),
					$lst->npk_driver . ' - ' . ($lst->driver_name ?? ''),
					//'<a href="/nota/'. $lst->chitnumber .'" class="btn btn-xs btn-default" target="_blank"><i class="fas fa-print"></i></a>',
					'child' => $kabData,
				];
			}, [], ['wb_in' => 'desc']);
		}
		return view('transaksi/data-timbang/index');
	}

	public function unsent(){
		if ($this->request->getMethod() == 'post') {
			$kabModel = new TrKabModel();
			return $this->getList(function($lst) use ($kabModel) {
				$kabData = $kabModel->where('chitnumber', $lst->chitnumber)
					->findAll();
				return [
					count($kabData) > 0 ? '<button class="btn btn-expand"><i class=fas fa-plus-circle"></i></button>' : '',
					$lst->chitnumber,
					$lst->productcode ?? $lst->productcode,
					$lst->transporter_name ?? ($lst->transportercode ?? '-'),
					$lst->unitcode . ' - ' . ($lst->platenumber ?? ''),
					$lst->wb_in,
					number_format($lst->weight_in,0, ',', '.'),
					$lst->wb_out,
					number_format($lst->weight_out,0, ',', '.'),
					$lst->npk_driver . ' - ' . ($lst->driver_name ?? ''),
					//'<a href="/nota/'. $lst->chitnumber .'" class="btn btn-xs btn-default" target="_blank"><i class="fas fa-print"></i></a>',
					'child' => $kabData,
				];
			}, ['sent !=' => 'Y']);
		}
		return view('transaksi/data-timbang/unsent');
	}

	public function pending(){
		if ($this->request->getMethod() == 'post'){
			return $this->getList(function($lst){
				return [
					$lst->chitnumber,
					$lst->productcode ?? $lst->productcode,
					$lst->transactiontype,
					$lst->npk_driver . ' - ' . ($lst->driver_name ?? '') .($lst->driver_manual ?? ''),
					$lst->unitcode . ' - ' . ($lst->platenumber ?? '').$lst->nomor_polisi,
					$lst->transporter_name ?? ($lst->transportercode ?? '-'),
					$lst->wb_in,
					number_format($lst->weight_in, 0, ',', '.'),
					// '<a href="/timbang?id='. $lst->chitnumber .'" class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></i></a>',
					// '<button onclick="myFunction()" class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></i></button> ',
					// $lst->chitnumber,
				];
			});
		}
		return view('transaksi/data-timbang/pending');
	}

	public function pendingLov(){
		if ($this->request->getMethod() == 'post'){
			return $this->getList(function($lst){
				return [
					$lst->chitnumber,
					$lst->productcode ?? $lst->productcode,
					$lst->transactiontype,
					$lst->npk_driver . ' - ' . ($lst->driver_name ?? '') .($lst->driver_manual ?? ''),
					$lst->unitcode . ' - ' .$lst->nomor_polisi. ' - ' . ($lst->platenumber ?? ''),
					$lst->transporter_name ?? ($lst->transportercode ?? '-'),
					$lst->wb_in,
					number_format($lst->weight_in, 0, ',', '.'),
					'<a href="/timbang?id='. $lst->chitnumber .'" class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></i></a>',
					// '<button onclick="myFunction()" class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></i></button> ',
					// $lst->chitnumber,
				];
			});
		}
		return view('transaksi/data-timbang/pending');
	}

	public function report(){
		if ($this->request->getMethod() == 'post') {
			return $this->getList(function($lst){
				return [
					$lst->chitnumber,
					$lst->customer_name ?? $lst->customercode,
					$lst->transactiontype,
					$lst->transporter_name ?? ($lst->transportercode ?? '-'),
					$lst->unitcode . ' - ' . ($lst->platenumber ?? ''),
					//$lst->wb_in,
					//number_format($lst->weight_in,0, ',', '.'),
					//$lst->wb_out,
					//number_format($lst->weight_out,0, ',', '.'),
					$lst->npk_driver . ' - ' . ($lst->driver_name ?? ''),
					'<a href="/nota/'. $lst->chitnumber .'" class="btn btn-xs btn-default" target="_blank"><i class="fas fa-print"></i></a>',
					'<a href="/nota-preprint/'. $lst->chitnumber .'" class="btn btn-xs btn-default" target="_blank"><i class="fas fa-print"></i></a>',
				];
			},[], ['wb_in' => 'desc']);
		}
		return view('transaksi/data-timbang/report');
	}

	public function nota($id){
		$trWbModel = new TrWbModel();
		$tr_wb = $trWbModel->where('tr_wb.chitnumber', $id)
			->join('m_parameter_values as produk', 'produk.value = tr_wb.productcode', 'left')
			//->join('m_parameter_values as tr_jenis', 'tr_jenis.value = tr_wb.transactiontype')
			->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
			->join('m_transporter', 'm_transporter.transportercode = m_unit.transportercode', 'left')
			->join('tr_kab', 'tr_kab.chitnumber = tr_wb.chitnumber', 'left')
			->join('m_site', 'm_site.sitecode = tr_wb.sitecode', 'left')
			->join('m_employee', 'm_employee.npk = tr_wb.npk_driver', 'left')
			->join('v_blok_jjg','v_blok_jjg.chitnumber=tr_wb.chitnumber','left')
			->join('m_customer','m_customer.customercode=tr_wb.customercode','left')
			->select([
				'tr_wb.chitnumber',
				'tr_wb.transactiontype',
				'tr_wb.customercode',
				'tr_wb.sabno',
				// 'tr_wb.wb_in',
				'DATE_FORMAT(tr_wb.wb_in, "%d/%m/%Y %H:%i:%s") AS wb_in',
				'tr_wb.weight_in',
				'tr_wb.operator_in',
				// 'tr_wb.wb_out',
				'DATE_FORMAT(tr_wb.wb_out, "%d/%m/%Y %H:%i:%s") AS wb_out',
				'tr_wb.weight_out',
				'tr_wb.operator_out',
				'produk.description as nama_barang',
				//'tr_jenis.description as jenis_transaksi',
				'tr_wb.unitcode',
				'm_unit.platenumber',
				'm_transporter.name as nama_transporter',
				'MIN(nocafd) as afdeling',
				'SUM(jjg) as total_jjg',
				'm_site.description As nama_site',
				'tr_wb.sitecode',
				'm_employee.name',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.nama_supplier',
				'v_blok_jjg.blok_list',
				'm_customer.name as customername',
				'tr_wb.npk_driver',
				'tr_wb.driver_manual',
				'tr_wb.keterangan',
				'tr_wb.sitecode',
				'tr_wb.jjg_ext',
			])
			->groupBy([
				'tr_wb.chitnumber',
				'tr_wb.transactiontype',
				'tr_wb.customercode',
				'tr_wb.sabno',
				'tr_wb.wb_in',
				'tr_wb.weight_in',
				'tr_wb.operator_in',
				'tr_wb.wb_out',
				'tr_wb.weight_out',
				'tr_wb.operator_out',
				'tr_wb.unitcode',
				'produk.description',
				//'tr_jenis.description',
				'm_unit.platenumber',
				'm_transporter.name',
				'm_employee.name',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.nama_supplier',
				'v_blok_jjg.blok_list',
				'm_customer.name',
				'tr_wb.npk_driver',
				'tr_wb.driver_manual',
				'tr_wb.keterangan',
				'tr_wb.sitecode',
				'tr_wb.jjg_ext',
			])
			->first();
		
		// return $trWbModel->db->showLastQuery(); die;
		// print_r($tr_wb) ;die;
		if ($tr_wb == null) {
			throw PageNotFoundException::forPageNotFound();
		}
		$tr_grading = (new TrGradingModel())->find($id);
		$tr_quality = (new TrCpoKernelQualityModel())->find($id);
		// $tr_wb_jjg_blok = $trWbModel->where('v_blok_jjg.chitnumber', $id)
		// 	->select([
		// 		'v_blok_jjg.chitnumber',
		// 		'v_blok_jjg.blok_list',
		// 	]);
		//print_r($tr_wb);die();
		return view('report/nota-timbang', [
			'tr_wb' => $tr_wb,
			'tr_grading' => $tr_grading,
			'tr_quality' => $tr_quality,
		]);
	}

	public function notaPrePrint($id){
		$trWbModel = new TrWbModel();
		$tr_wb = $trWbModel->where('tr_wb.chitnumber', $id)
			->join('m_parameter_values as produk', 'produk.value = tr_wb.productcode', 'left')
			//->join('m_parameter_values as tr_jenis', 'tr_jenis.value = tr_wb.transactiontype')
			->join('m_unit', 'm_unit.unitcode = tr_wb.unitcode', 'left')
			->join('m_transporter', 'm_transporter.transportercode = m_unit.transportercode', 'left')
			->join('tr_kab', 'tr_kab.chitnumber = tr_wb.chitnumber', 'left')
			->join('m_site', 'm_site.sitecode = tr_wb.sitecode', 'left')
			->join('m_employee', 'm_employee.npk = tr_wb.npk_driver', 'left')
			->join('v_blok_jjg','v_blok_jjg.chitnumber=tr_wb.chitnumber','left')
			->join('m_customer','m_customer.customercode=tr_wb.customercode','left')
			->select([
				'tr_wb.chitnumber',
				'tr_wb.transactiontype',
				'tr_wb.customercode',
				'tr_wb.sabno',
				'tr_wb.wb_in',
				'tr_wb.weight_in',
				'tr_wb.operator_in',
				// 'tr_wb.wb_out',
				// 'tr_wb.weight_out',
				'DATE_FORMAT(tr_wb.wb_in, "%d/%m/%Y %H:%i:%s") AS wb_in',
				'DATE_FORMAT(tr_wb.wb_out, "%d/%m/%Y %H:%i:%s") AS wb_out',
				'tr_wb.operator_out',
				'produk.description as nama_barang',
				//'tr_jenis.description as jenis_transaksi',
				'tr_wb.unitcode',
				'm_unit.platenumber',
				'm_transporter.name as nama_transporter',
				'MIN(nocafd) as afdeling',
				'SUM(jjg) as total_jjg',
				'm_site.description As nama_site',
				'tr_wb.sitecode',
				'm_employee.name',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.nama_supplier',
				'v_blok_jjg.blok_list',
				'm_customer.name as customername',
				'tr_wb.npk_driver',
				'tr_wb.driver_manual',
				'tr_wb.keterangan',
				'tr_wb.jjg_ext',
			])
			->groupBy([
				'tr_wb.chitnumber',
				'tr_wb.transactiontype',
				'tr_wb.customercode',
				'tr_wb.sabno',
				'tr_wb.wb_in',
				'tr_wb.weight_in',
				'tr_wb.operator_in',
				'tr_wb.wb_out',
				'tr_wb.weight_out',
				'tr_wb.operator_out',
				'tr_wb.unitcode',
				'produk.description',
				//'tr_jenis.description',
				'm_unit.platenumber',
				'm_transporter.name',
				'm_employee.name',
				'tr_wb.nomor_polisi',
				'tr_wb.nama_driver',
				'tr_wb.nama_supplier',
				'v_blok_jjg.blok_list',
				'm_customer.name',
				'tr_wb.npk_driver',
				'tr_wb.driver_manual',
				'tr_wb.keterangan',
				'tr_wb.jjg_ext',
			])
			->first();
		
		//return $trWbModel->db->showLastQuery(); die;
		if ($tr_wb == null) {
			throw PageNotFoundException::forPageNotFound();
		}
		$tr_grading = (new TrGradingModel())->find($id);
		$tr_quality = (new TrCpoKernelQualityModel())->find($id);
		// $tr_wb_jjg_blok = $trWbModel->where('v_blok_jjg.chitnumber', $id)
		// 	->select([
		// 		'v_blok_jjg.chitnumber',
		// 		'v_blok_jjg.blok_list',
		// 	]);
		// print_r($tr_wb_jjg_blok);
		return view('report/nota-timbang-pre-print', [
			'tr_wb' => $tr_wb,
			'tr_grading' => $tr_grading,
			'tr_quality' => $tr_quality,
		]);
	}
	
}
?>
