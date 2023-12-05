<?php
namespace App\Controllers\Master;

use Andre;
use Andre_helper;
use App\Controllers\BaseController;
use App\Filters\UsersAuthFilter;
use App\Models\UserAuthModel;
use App\Models\UserModel;

class Users extends BaseController{

	public function index(){
		$model = new UserModel();
		if ($this->request->getMethod() == 'post') {
			return $model->makeDataTable();
		}
		return view('master/user', [
			'model' => $model,
			'request' => $this->request,
		]);
	}

	public function view($id) {
		$model = new UserModel();
		if (($bklReturn = $model->find($id)) != null) {
			$bklReturn['old_id'] = $id;
			if (isset($bklReturn['password'])) {
				unset($bklReturn['password']);
			}
			$authModel = new UserAuthModel();
			$userAuth = $authModel->where('email', $id)
				->select([
					'id',
					'auth_to',
				])->findAll();
			$auth = [];
			foreach ($userAuth as $authItem) {
				$auth[] = str_replace("\\", '', str_replace('::', '-', $authItem['auth_to']));
			}
			$bklReturn['auth'] = $auth;
		}
		return $this->response->setJSON($bklReturn);
	}

	public function save() {
		$post = $this->request->getPost();
		$model = new UserModel();
		$authModel = new UserAuthModel();
		if ($this->validate($model->getValidationRules())) {
			if ($model->find($post['old_id'])) {
				if ($post['old_id'] != $post['email']) {
					$return = [
						'status' => 'fail',
						'messages' => [
							'email' => 'Email sebagai identitas login tidak dapat diubah.',
						],
						'post' => $post,
					];
				}else {
					$old_auth_db = $authModel->where('email', $post['email'])->select('auth_to')->findAll();
					$old_auth = [];
					foreach ($old_auth_db as $au) {
						$old_auth[] = $au['auth_to'];
					}
					$new_auth = array_keys($post['auth'] ?? []);

					$auth_tambahan = array_diff($new_auth, $old_auth);
					$auth_dihapus = array_diff($old_auth, $new_auth);

					foreach ($auth_dihapus as $dihapus) {
						$authModel->where([
							'email' => $post['email'],
							'auth_to' => $dihapus,
						])->delete();
					}

					foreach ($auth_tambahan as $baru) {
						$authModel->insert([
							'email' => $post['email'],
							'auth_to' => $baru,
						]);
					}

					if ($model->update($post['old_id'], $post)) $return = [
						'status' => 'success',
						'messages' => 'Update User sukses',
						'email' => $post['email'],
					];	
				}
			} else {
				$post['password'] = password_hash('123456', PASSWORD_BCRYPT);
				$model->insert($post);
				if (isset($post['auth'])) {
					$auth = [];
					foreach ($post['auth'] as $key => $v) {
						$auth[] = [
							'email' => $post['email'],
							'auth_to' => $key,
						];
					}
					$authModel->insertBatch($auth);
				}
				$return = [
					'status' => 'success',
					'messages' => 'Update User sukses',
					'email' => $post['email'],
				];
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
		$model = new UserModel();
		try {
			$model->delete($id);
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	public function login(){
		if (UsersAuthFilter::isGuest()) {
			if ($this->request->getMethod() == 'post') {
				//return $this->response->setJSON($this->request->getPost());
				$users = new UserModel();
				$email = $this->request->getVar('email');
				$password = $this->request->getVar('password');
				$rw_user = $users->find($email);
				if ($rw_user) {
					if (password_verify($password, $rw_user['password'])){
						session()->set([
							'email' => $email,
							'name' => $rw_user['name'],
							'logged_id' => true,
						]);

						helper('Andre_helper');
						$andre = new Andre();
						$andre->save($email, 'login', 'login', session()->get());
						
						return redirect()->to(base_url());
					} else {
						session()->setFlashdata('error', 'Username & Password salah');
						return redirect()->back();
					}
				} else {
					session()->setFlashdata('error', 'Username & Password salah');
					return redirect()->back();
				}
			}
			return view('login');
		} else {
			return redirect()->to(base_url());
		}
	}

	public function changePassword(){
		if ($this->request->getMethod() == 'post') {
			$post = $this->request->getPost();
			if (!$this->validate([
				'password' => 'required',
				'new_password' => 'required',
				'confirm_new_password' => [
					'rules' => 'matches[new_password]',
					'errors' => [
						'matches' => 'Konfirmasi password tidak sesuai'
					],
				],
			])) {
				session()->setFlashdata('error', $this->validator->listErrors());
				return redirect()->back()->withInput();
			}

			$email = session()->get('email');
			$userModel = new UserModel();
			if (($user = $userModel->find($email)) != null) {
				$password = $post['password'];
				if (password_verify($password, $user['password'])) {
					$hash_new_password = password_hash($post['new_password'], PASSWORD_BCRYPT);
					if ($userModel->update($email, ['password' => $hash_new_password])) {
						return redirect()->to(base_url())->with('success', 'Password berhasil diubah');
					} else {
						session()->setFlashdata('error', 'Password gagal diubah');
						return redirect()->back();	
					}
				} else {
					session()->setFlashdata('error', 'Username & Password salah');
					return redirect()->back();
				}
			}
		}
		return view('change-password');
	}

	public function logout(){
		helper('Andre_helper');
		$andre = new Andre();
		$andre->save(session()->get('email'), 'logout', 'logout', session()->get());
		session()->destroy();
		return redirect()->to('/login');
	}

	function shutdown() {
        // echo getcwd();
        //chdir( '/opt/pentaho/data-integration/' );
        //$cmd = "/opt/pentaho/data-integration/pan.sh -file=/opt/pentaho/marketing/master_customer.ktr 2>&1";
        //system("sudo shutdown 0");
		// $cmd = "shutdown -h now";

        // $output=shell_exec( $cmd );
        // // echo getcwd();
        // // echo $output;
        // if( strstr( $output, 'ended successfully' )) {
        //     echo json_encode( $rows=array( 'data'=> 'success' ), JSON_PRETTY_PRINT );
        // }
        // else {
        //     echo json_encode( $rows=array( 'data'=> 'failed' ), JSON_PRETTY_PRINT );
        // }
		// echo("TEST");
		// exec('/bin/sh /home/masterwb/shutdown.sh');
		// system('reboot');

		// echo phpinfo();
		//exec ('shutdown -s -t 0');
		// system('reboot');
		// exec('/bin/sh /home/masterwb/shutdown.sh');
		echo "Shutdown";
		echo exec ( 'sudo shutdown.sh' );
		
    }

	
	function restart() {
		system("sudo reboot 0");
        // echo getcwd();
        //chdir( '/opt/pentaho/data-integration/' );
        //$cmd = "/opt/pentaho/data-integration/pan.sh -file=/opt/pentaho/marketing/master_customer.ktr 2>&1";
        $cmd = "shutdown -r now";

        $output=shell_exec( $cmd );
        // echo getcwd();
        // echo $output;
        if( strstr( $output, 'ended successfully' )) {
            echo json_encode( $rows=array( 'data'=> 'success' ), JSON_PRETTY_PRINT );
        }
        else {
            echo json_encode( $rows=array( 'data'=> 'failed' ), JSON_PRETTY_PRINT );
        }
    }

}