<?php
namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MSiteModel;
use Andre;

class SiteCode extends BaseController
{
	public function index()
	{
		$model = new  MSiteModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		return view('master/sitecode', [
			'model' => $model,
			'request' => $this->request,
		]);
	}
	
	public function view($id)
	{
		$model = new MSiteModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save() 
	{
		$post = $this->request->getPost();
		$model = new MSiteModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($model->update($post['old_id'], $post)) $return = [
					'status' => 'success',
					'message' => 'Update Site Code sukses',
					'sitecode' => $post['sitecode'],
				];
				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Site Code', 'update', $post);
			} else {
				$model->insert($post);
				$return = [
					'status' => 'success',
					'message' => 'Insert Unit sukses',
					'sitecode' => $post['sitecode'],
				];
				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Site Code', 'insert', $post);
			}
		} else {
			$return = [
				'status' => 'fail',
				'messages' => $this->validator->getErrors(),
			];
		}
		return $this->response->setJSON($return);
	}

	public function delete()
	{
		$id = $this->request->getPost('id');
		$model = new MSiteModel();
		try {
			$model->delete($id);
			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Site Code', 'delete', $id);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}