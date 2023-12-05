<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class ParameterValueModel extends Model{
	protected $table = "m_parameter_values";
	protected $primaryKey = 'id';

	protected $allowedFields = [
		'id',
		'parameter_code',
		'value',
		'description',
		'order_number',
		'active',
	];

	protected $column_order = [
		'id',
		'parameter_code',
		'value',
		'description',
		'order_number',
		'active'
	];

	protected $column_search = [
		'id',
		'parameter_code',
		'value',
		'description',
	];

	protected $order = [
		'order_number' => 'asc',
	];

	protected $request;
	protected $dt;
	function __construct($request)
	{
		parent::__construct();
		$this->db = db_connect();
		$this->request = $request;
		$this->dt = $this->db->table($this->table);

		return $this;
	}

	private function _get_datatable_query(){
		if($this->request->getPost('parameter_code')){
			$this->dt->where(['parameter_code' => $this->request->getPost('parameter_code')]);
		}
		$i = 0;
		foreach ($this->column_search as $item) {
			if($this->request->getPost('search')['value'] ?? false){
				if ($i === 0) {
					$this->dt->groupStart();
					$this->dt->like($item, $this->request->getPost('search')['value']);
				}else{
					$this->dt->orLike($item, $this->request->getPost('search')['value']);
				}
				if ((count($this->column_search) - 1) == $i) $this->dt->groupEnd();
			}
			$i++;
		}
		if ($this->request->getPost('order')){
			$this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], 
				$this->request->getPost('order')['0']['dir']);
		}else if (isset($this->order)){
			$order = $this->order;
			$this->dt->orderBy(key($order), $order[key($order)]);
		}
	}

	function get_datatables(){
		$this->_get_datatable_query();
		if ($this->request->getPost('length') != -1)
			$this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
		$query = $this->dt->get();
		return $query->getResult();
	}

	function count_filtered(){
		$this->_get_datatable_query();
		return $this->dt->countAllResults();
	}

	public function count_all(){
		$tbl = $this->db->table($this->table);
		return $tbl->countAllResults();
	}

	/**
	 * get parameter value dalam array 
	 */
	public static function getValues($parameterCode){
		$model = new ParameterValueModel(Services::request());
		$result = $model->where([
				'parameter_code' => $parameterCode,
				'active' => 'Y'
			])
			->select([
				'value',
				'description',
			])
			->findAll();
		return $result;
	}

	/**
	 * get parameter value baris pertama
	 */
	public static function getValue($parameterCode){
		$model = new ParameterValueModel(Services::request());
		$result = $model->where([
				'parameter_code' => $parameterCode,
				'active' => 'Y'
			])->orderBy('order_number', 'asc')->first();
		return $result['value'];
	}

	public static function getValueDescription($parameterCode)
	{
		$model = new ParameterValueModel(Services::request());
		$result = $model->where([
			'parameter_code' => $parameterCode,
			'active' => 'Y',
		])->orderBy('order_number', 'asc')->first();
		return $result;
	}
}