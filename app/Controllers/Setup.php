<?php
namespace App\Controllers;

use App\Models\ParameterModel;
use App\Models\ParameterValueModel;
use CodeIgniter\HTTP\Request;
use Config\Database;
use Config\Services;
use Andre;

class Setup extends BaseController{

	public function index(){
		$parameterModel = new ParameterModel();
		$parameter = $parameterModel->findAll();
		return view('setup/index', [
			'parameter' => $parameter,
		]);
	}

	public function parameter_value_list(){
		$request = Services::request();
		$model = new ParameterValueModel($request);
		if ($request->getMethod() == 'post'){
			$lists = $model->get_datatables();
			$data = [];
			$no = $request->getPost('start');
			foreach ($lists as $list) {
				$no++;
				$row = [
					$list->value,
					$list->description,
					$list->order_number,
					$list->active,
					'<button title="Edit" class="btn btn-xs btn-edit btn-success" data-toggle="modal" data-target="#setup_formModal" data-proses="edit" data-id="'. $list->id .'"><span class="fas fa-pencil-alt"></span></button>
					<button title="Delete" class="btn btn-xs btn-delete btn-danger" onclick="setup_delete(\''. $list->id .'\')"><span class="fas fa-trash-alt"></span></button>',
					//$no,
					$list->id,
					$list->parameter_code,
				];
				$data[] = $row;
			}
			$output = [
				'draw' => $request->getPost('draw'),
				'recordsTotal' => $model->count_all(),
				'recordsFiltered' => $model->count_filtered(),
				'data' => $data,
			];
			echo json_encode($output);
		}
	}

	public function saveParameterValue(){
		$post = $this->request->getPost();
		$parameterValueModel = new ParameterValueModel($this->request);
		if (($post['id'] ?? '') == ''){
			$id = $parameterValueModel->insert($post, true);
			
			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Setup', 'insert', $post);
			
			return $this->response->setJSON([
				'status' => 'success',
				'message' => 'Sukses menambah data baru dengan id:' . $id,
			]);
		} else {
			if ($parameterValueModel->update($post['id'], $post)) {
				
				helper('Andre_helper');
				$andre = new Andre();
				$andre->save(session()->get('email'), 'Setup', 'update', $post);

				return $this->response->setJSON([
					'status' => 'success',
					'message' => 'Sukses mengubah data dengan id:' . $post['id'],
				]);
			} else {
				return $this->response->setJSON([
					'status' => 'fail',
					'message' => 'Gagal mengubah data dengan id:' . $post['id'],
				]);
			}
		}
		//return $this->response->setJSON($this->request->getVar());
	}

	public function deleteParameterValue(){
		$id = $this->request->getPost();
		$parameterValueModel = new ParameterValueModel($this->request);
		if (is_array($id)) {
			$id = $id['id'] ?? '';			
		}

		try {
			$parameterValueModel->delete($id);

			helper('Andre_helper');
			$andre = new Andre();
			$andre->save(session()->get('email'), 'Setup', 'delete', $id);
			
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}