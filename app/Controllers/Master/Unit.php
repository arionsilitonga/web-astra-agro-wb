<?php
namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MUnitModel;
use Andre;

class Unit extends BaseController{

	public function index(){
		$model = new MUnitModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		return view('master/unit', [
			'model' => $model,
			'request' => $this->request,
		]);
	}

	public function view($id){
		$model = new MUnitModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save(){
		$post = $this->request->getPost();
		$model = new MUnitModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($model->update($post['old_id'], $post)) $return = [
					'status' => 'success',
					'message' => 'Update Unit sukses',
					'unitcode' => $post['unitcode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Unit', 'update', $post);

			} else {
				$model->insert($post);
				$return = [
					'status' => 'success',
					'message' => 'Insert Unit sukses',
					'unitcode' => $post['unitcode'],
				];

				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Unit', 'insert', $post);
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
		$model = new MUnitModel();
		try {
			$model->delete($id,true);
			
			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Unit', 'delete', $id);

		} catch (\Throwable $th) {
			throw $th;
		}
	}
}