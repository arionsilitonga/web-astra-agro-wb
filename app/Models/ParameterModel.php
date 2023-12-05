<?php
namespace App\Models;

use CodeIgniter\Model;

class ParameterModel extends Model{
	protected $table = "m_parameters";
	protected $primaryKey = 'parameter_code';

	protected $allowedFields = [
		'parameter_code',
		'description',
		'form_code',
		'data_type',
	];
}
?>