<?php
namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MEmployeeModel;
use Throwable;
use Andre;

class Employee extends BaseController {

	public function index(){
		$model = new MEmployeeModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		return view('master/employee', [
			'model' => $model,
			'request' => $this->request,
		]);
	}

	public function view($id) {
		$model = new MEmployeeModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save(){
		$post = $this->request->getPost();
		$model = new MEmployeeModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($model->update($post['old_id'], $post)) $return = [
					'status' => 'success',
					'message' => 'Update Employee sukses',
					'npk' => $post['npk'],
				];
				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Karyawan', 'update', $post);
			} else {
				$post['npk'] = str_pad($post['npk'], 7, '0', STR_PAD_LEFT);
				$model->insert($post);
				$return = [
					'status' => 'success',
					'message' => 'Update Employee sukses',
					'npk' => $post['npk'],
				];
				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Karyawan', 'insert', $post);
			}
		} else {
			$return = [
				'status' => 'fail',
				'messages' => $this->validator->getErrors(),
			];
		}
		return $this->response->setJSON($return);
	}

	public function delete(){
		$id = $this->request->getPost('id');
		$model = new MEmployeeModel();
		try {
			$model->delete($id);
			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Karyawan', 'delete', $id);
		} catch (Throwable $th) {
			throw $th;
		}
	}
}