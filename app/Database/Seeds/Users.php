<?php

namespace App\Database\Seeds;

use App\Filters\UsersAuthFilter;
use App\Models\UserAuthModel;
use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class Users extends Seeder
{
    public function run()
    {
        $users_data = [
			[
				'email' => 'admin@gmail.com',
				'password' => password_hash('123456', PASSWORD_BCRYPT),
				'name' => 'Administrator',
			], [
				'email' => 'sa@sa.com',
				'password' => password_hash('123456', PASSWORD_BCRYPT),
				'name' => 'System Administrator',
			],
		];

		$userModel = new UserModel();
		$userModel->insertBatch($users_data);

		$allAuth = UsersAuthFilter::$allAuths;
		$allUserAuths = array_merge(
			$this->getAuthNeed('admin@gmail.com', $allAuth),
			$this->getAuthNeed('sa@sa.com', $allAuth)
		);
		$userAuthModel = new UserAuthModel();
		$userAuthModel->insertBatch($allUserAuths);
    }

	private function getAuthNeed($email, $items){
		$bklReturn = [];
		foreach ($items as $item) {
			if (is_array($item)) {
				if (($item['controller'] ?? false) && ($item['action'] ?? false)) {
					$bklReturn[] = [
						'email' => $email,
						'auth_to' => $item['controller'] . '::' . $item['action'],
					];
				}
				if (($item['items'] ?? false) && (is_array($item['items']))) {
					$bklReturnItems = $this->getAuthNeed($email, $item['items']);
					$bklReturn = array_merge($bklReturn, $bklReturnItems);
				}
			}
		}
		return $bklReturn;
	}
}
