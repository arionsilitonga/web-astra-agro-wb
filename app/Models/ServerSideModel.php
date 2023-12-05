<?php
namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class ServerSideModel extends Model{
	public $db;
	//public $builder;

	public function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
	}

	protected function _get_datatables_query(BaseBuilder $builder, $column_order, $column_search, $order, $select){
		//$this->builder = ;
		$this->builder = $builder;
		$i = 0;
		foreach ($column_search as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->builder->groupStart();
					$this->builder->like($item, $_POST['search']['value']);
				} else {
					$this->builder->orLike($item, $_POST['search']['value']);
				}
				if (count($column_search) - 1 == $i)
					$this->builder->groupEnd();
			}
			$i++;
		}

		if (isset($_POST['order'])) {
			$this->builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order)) {
			$order = $order;
			if (count($order) > 0) $this->builder->orderBy(key($order), $order[key($order)]);
		}

		$this->builder->select($select);
	}

	/**
	 * @param BaseBuilder|string $table
	 */
	public function get_datatables($table, $column_order, $column_search, $order, $where = '', $select = '*'){
		if (is_string($table)) {
			$builder = $this->db->table($table);
		}else{
			$builder = $table;
		}

		$this->_get_datatables_query($builder, $column_order, $column_search, $order, $select);

		if ($_POST['length'] != -1) $this->builder->limit($_POST['length'], $_POST['start']);
		if ($where) $this->builder->where($where);

		$query = $this->builder->get();
		return $query->getResult();
	}

	public function count_filtered($table, $column_order, $column_search, $order, $where = ''){
		if (is_string($table)) {
			$builder = $this->db->table($table);
		}else{
			$builder = $table;
		}

		$this->_get_datatables_query($builder, $column_order, $column_search, $order, '*');
		
		if ($where) $this->builder->where($where);

		//$this->builder->get();
		return $this->builder->countAllResults();
	}

	public function count_all($table, $where = ''){
		if (is_string($table)) {
			$this->builder = $this->db->table($table);
		}else{
			$this->builder = $table;
		}

		if ($where !== '') {
			$this->builder->where($where);
		}
		//$this->builder->from($table);

		return $this->builder->countAllResults();
	}
}
