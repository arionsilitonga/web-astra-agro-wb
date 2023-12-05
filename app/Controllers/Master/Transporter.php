<?php
namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MTransporterModel;
use Throwable;
use Andre;

class Transporter extends BaseController{

	public function index(){
		$model = new MTransporterModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		return view('master/transporter', [
			'model' => $model,
			'request' => $this->request,
		]);
	}

	public function view($id) {
		$model = new MTransporterModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save(){
		$post = $this->request->getPost();
		$model = new MTransporterModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($model->update($post['old_id'], $post)) $return = [
					'status' => 'success',
					'messages' => 'Update Transporter sukses',
					'transportercode' => $post['transportercode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Transporter', 'update', $post);

			} else {
				$model->insert($post);
				$return = [
					'status' => 'success',
					'messages' => 'Insert Transporter sukses',
					'transportercode' => $post['transportercode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Transporter', 'insert', $post);
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
		$model = new MTransporterModel();
		try {
			$model->delete($id);

			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Transporter', 'delete', $id);

		} catch (Throwable $th) {
			throw $th;
		}
	}
}