<?php
namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MCustomerModel;
use App\Models\ServerSideModel;
use Config\Validation;
use Andre;

/**
 * @property ServerSideModel $serverside_model
 */
class Customer extends BaseController{

	public function index(){
		$model = new MCustomerModel();
		if ($this->request->getMethod() == 'post'){
			return $model->makeDataTable();
		}
		return view('master/customer', [
			'model' => $model,
			'request' => $this->request,
		]);
	}

	public function view($id) {
		$model = new MCustomerModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save(){
		$post = $this->request->getPost();
		$model = new MCustomerModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($model->update($post['old_id'], $post)) $return = [
					'status' => 'success',
					'messages' => 'Update customer sukses',
					'customercode' => $post['customercode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Customer', 'update', $post);

			} else {
				$model->insert($post);
				$return = [
					'status' => 'success',
					'messages' => 'Insert customer sukses',
					'customercode' => $post['customercode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Customer', 'insert', $post);
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
		$model = new MCustomerModel();
		try {
			$model->delete($id);

			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Customer', 'delete', $id);

		} catch (\Throwable $th) {
			throw $th;
		}
	}
}