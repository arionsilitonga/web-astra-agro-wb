<?php
namespace App\Models;

use CodeIgniter\Model;

class TrKabModel extends Model{
	protected $table = 'tr_kab';
	protected $primaryKey = 'nocvalue';//'';

	protected $useTimestamps = false;
	protected $useSoftDeletes = true;
    
	protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $allowedFields = [
		'id',
		'chitnumber',
		'sabno',
		'nocvalue',
		'nocsite',
		'nocdate',
		'harverstdate',
		'nocafd',
		'nocblock',
		'tgl_panen',
		'jjg',
		//'created_at',
		//'updated_at',
		//'deleted_at',
	];
}
?>