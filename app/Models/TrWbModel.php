<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class TrWbModel extends Model{
	protected $table = "tr_wb";
	protected $primaryKey = 'chitnumber';
	
	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $allowedFields = [
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
		'nama_supplier',
		'wilayah_asal_tbs',
		'kab_prop',
		'kab_createdate',
		'kab_createby',
		'status',
		'sent',
		'created_at',
		'updated_at',
		'deleted_at',
		'driver_manual',
		'keterangan',
		'sentout',
		'supplier_group',
		'supplier_group_description',
	];

	protected function _set_datatables_query()
	{
		$request = Services::request();
		$this->builder = $this->db->table($this->table);
		$column_search = $this->allowedFields;
		$column_order = $this->allowedFields;

		if ($this->useSoftDeletes) {
			$this->builder->where('deleted_at', null);
		}

		$search = $request->getPost('search')['value'] ?? false;
		$i = 0;
		foreach ($column_search as $item) {
			if ($search) {
				if ($i === 0) {
					$this->builder->groupStart();
					$this->builder->like($item, $search);
				} else {
					$this->builder->orLike($item, $search);
				}
				if (count($column_search) -1 == $i) $this->builder->groupEnd();
			}
			$i++;
		}

		$order = $request->getPost('order');
		if ($order) {
			$this->builder->orderBy($column_order[$order['0']['column']], $order['0']['dir']);
		}

		$selectFields = $this->allowedFields;
		if (!in_array($this->primaryKey, $selectFields)) {
			$selectFields[] = $this->primaryKey;
		}
		$this->builder->select($selectFields);
	}

	public function makeDataTable()
	{
		$post = Services::request()->getPost();
		$this->_set_datatables_query();

		if ($post['length'] != -1) $this->builder->limit($post['length'], $post['start']);

		$source = $this->builder->get()->getResultArray();
		$data = [];
		foreach ($source as $item) {
			$pk = $item[$this->primaryKey];
			$item = array_values($item);
			//$item[] = $pk;
			//$item[] = 'Tambahan 1';
			//$item[] = 'Tambahan 2';
			$data[] = $item;
		}
		$recordsFiltered = count($source);
		$recordsTotal = $this->db->table($this->table)->countAllResults();

		return json_encode([
			'draw' => $post['draw'] ?? '',
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		]);
	}
}
?>