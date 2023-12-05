<?php
namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use Config\Database;
use Config\Services;

use function PHPSTORM_META\type;

class MyBaseModel extends Model{
	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $fields = [];

	public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
	{
		$this->allowedFields = array_keys($this->fields);

		parent::__construct($db, $validation);
	}
	
	protected function getColumnSearch(){
		$bklReturn = [];
		foreach ($this->fields as $key => $value) {
			if ($value['search'] ?? false) {
				$bklReturn[] = $key;
			}
		}
		return $bklReturn;
	}

	protected function getColumnOrder(){
		$bklReturn = [];
		foreach ($this->fields as $key => $value) {
			if ($value['order'] ?? false) {
				$bklReturn[] = $key;
			}
		}
		return $bklReturn;
	}

	public function getListColumns(){
		$bklReturn = [];
		foreach ($this->fields as $key => $value) {
			if ($value['list'] ?? false) {
				$bklReturn[] = $key;
			}
		}
		return $bklReturn;
	}

	public function getDetailColumns(){
		$bklReturn = [];
		foreach ($this->fields as $key => $value) {
			if (($value['type'] ?? 'hidden') != 'hidden') {
				$maxlength = false;
				$readonly = '';
				if ($value['type'] == 'varchar') {
					$type = 'text';
					if (($value['constraint'] ?? 0) > 0) {
						$maxlength = $value['constraint'];
					}
				} elseif ($value['type'] == 'CHAR') {
					$type = 'text';
					$maxlength = 1;
				} elseif ($value['type'] == 'int') {
					$type = 'number';
				} elseif ($value['type'] == 'readonly') {
					$type = 'text';
					$readonly = ' readonly';
				} elseif ($value['type'] == 'dropdown') {
					$type = [];
					if (is_array($value['source'])) {
						$source = $value['source'];
						$table = $source['table'];
						$primary_key = $source['key'];
						$name_key = $source['text'];
						$type = $this->db->table($table)->select([
							"`$primary_key` as id",
							"`$name_key` as text"
						])->get()->getResultArray();
					}
				} elseif (is_array($value['type'])) {
					$type = $value['type'];
				} else {
					$type = 'text';
				}
				$bklReturn[$key] = [
					'type' => $type,
					'maxlength' => $maxlength,
					'readonly' => $readonly,
				];
			}
		}
		return $bklReturn;
	}

	protected function _set_datatables_query(){
		$request = Services::request();
		$this->builder = $this->db->table($this->table);
		$column_search = $this->getColumnSearch();
		$column_order = $this->getColumnOrder();

		if ($this->useSoftDeletes) {
			// $this->builder->where('deleted_at', null);
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
		} else if (isset($this->defaultOrder)) {
			$order = $this->defaultOrder;
			if (count($order) > 0) $this->builder->orderBy(key($order), $order[key($order)]);
		}

		$selectFields = $this->getListColumns();
		if (!in_array($this->primaryKey, $selectFields)) {
			$selectFields[] = $this->primaryKey;
		}
		$this->builder->select($selectFields);
	}

	public function makeDataTable(){
		$post = Services::request()->getPost();
		$this->_set_datatables_query();
		
		if ($post['length'] != -1) $this->builder->limit($post['length'], $post['start']);

		$source = $this->builder->get()->getResultArray();
		$data = [];
		foreach ($source as $item) {
			$pk = $item[$this->primaryKey];
			$item = array_values($item);
			$btnEdit = '<button title="Edit" class="btn btn-xs btn-edit btn-success"><span class="fas fa-pencil-alt"></span></button>';
			$btnDelete = '<button title="Delete" class="btn btn-xs btn-delete btn-danger"><span class="fas fa-trash-alt"></span></button>';
			$item[] = "<div class=\"text-center\">$btnEdit $btnDelete</div>";
			$item[] = $pk;
			$data[] = $item;
		}
		$recordsFiltered = $this->builder->countAllResults();
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