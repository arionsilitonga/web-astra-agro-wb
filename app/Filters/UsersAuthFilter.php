<?php

namespace App\Filters;

use App\Controllers\Home;
use App\Models\UserAuthModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Router\Router;

class UsersAuthFilter implements FilterInterface
{

	static public $allAuths = [
		[
			'label' => 'User Management',
			'icon' => 'users',
			'controller' => '\App\Controllers\Master\Users',
			'action' => 'index',
		],
		[
			'label' => 'Setup',
			'icon' => 'tools',
			'controller' => '\App\Controllers\Setup',
			'action' => 'index',
		],
		[
			'label' => 'Transaksi',
			'icon' => 'balance-scale',
			'items' => [
				[
					'label' => 'Timbang',
					'icon' => 'weight',
					'controller' => '\App\Controllers\Timbang',
					'action' => 'index',
				],
				[
					'label' => 'Pending Transaction',
					'icon' => 'history',
					'controller' => '\App\Controllers\DataTimbang',
					'action' => 'pending',
				],
				[
					'label' => 'View Transaksi',
					'icon' => 'eye',
					'controller' => '\App\Controllers\DataTimbang',
					'action' => 'index',
				],
				[
					'label' => 'View Unsent',
					'icon' => 'paper-plane',
					'controller' => '\App\Controllers\DataTimbang',
					'action' => 'unsent',
				],
				[
					'label' => 'Sync Data',
					'icon' => 'sync',
					'controller' => '\App\Controllers\Sync',
					'action' => 'index',
				],
			]
		],
		[
			'label' => 'Master Data',
			'icon' => 'list-alt',
			'items' => [
				[
					'label' => 'Customer',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\Customer',
					'action' => 'index',
				],
				[
					'label' => 'Transporter',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\Transporter',
					'action' => 'index',
				],
				[
					'label' => 'Unit',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\Unit',
					'action' => 'index',
				],
				[
					'label' => 'Karyawan',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\Employee',
					'action' => 'index',
				],
				[
					'label' => 'Site Code',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\SiteCode',
					'action' => 'index',
				],
				/*[
					'label' => 'Product Mapping',
					'icon' => 'list-alt',
					'controller' => '\App\Controllers\Master\ProductMap',
					'action' => 'index',
				],*/
			]
		],
		[
			'label' => 'Report',
			'icon' => 'scroll',
			'items' => [
				[
					'label' => 'Report Transaksi',
					'icon' => 'file-alt',
					'controller' => '\App\Controllers\DataTimbang',
					'action' => 'report',
				],
				[
					'label' => 'TR WB All Column',
					'icon' => 'file-alt',
					'controller' => '\App\Controllers\Report',
					'action' => 'allcolumn',
				],
				[
					'label' => 'Produksi',
					'icon' => 'apple-alt',
					'controller' => '\App\Controllers\Report',
					'action' => 'produksi',
				],
				[
					'label' => 'Summary',
					'icon' => 'apple-alt',
					'controller' => '\App\Controllers\Report',
					'action' => 'summary',
				],
			]
		],
	];

	static private $inheritAuth = [
		//Setup
		'\App\Controllers\Setup::parameter_value_list' => '\App\Controllers\Setup::index',
		'\App\Controllers\Setup::saveParameterValue' => '\App\Controllers\Setup::index',
		'\App\Controllers\Setup::deleteParameterValue' => '\App\Controllers\Setup::index',

		//Timbang
		'\App\Controllers\Timbang::nfcCancel' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::nfcRead' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::weighRead' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::save' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::cekChitNumberExists' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::saveAPI' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::writeKab' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getTransaction' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getTransporterMap' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getTransByUnit' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getPending' => '\App\Controllers\Timbang::index',
		
		'\App\Controllers\Timbang::getWeight' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getWeightLength' => '\App\Controllers\Timbang::index',
		'\App\Controllers\Timbang::getCurrentDate' => '\App\Controllers\Timbang::index',
		// $routes->get('/timbang/transaction-transbyunit/(:segment)', 'Timbang::getTransByUnit/$1');

		//Data Transaksi
		'\App\Controllers\DataTimbang::nota' => '\App\Controllers\Timbang::index',
		'\App\Controllers\DataTimbang::notaPrePrint' => '\App\Controllers\Timbang::index',

		//Master
		'\App\Controllers\Master\Customer::view' => '\App\Controllers\Master\Customer::index',
		'\App\Controllers\Master\Customer::save' => '\App\Controllers\Master\Customer::index',
		'\App\Controllers\Master\Customer::delete' => '\App\Controllers\Master\Customer::index',
		'\App\Controllers\Master\Transporter::view' => '\App\Controllers\Master\Transporter::index',
		'\App\Controllers\Master\Transporter::save' => '\App\Controllers\Master\Transporter::index',
		'\App\Controllers\Master\Transporter::delete' => '\App\Controllers\Master\Transporter::index',
		'\App\Controllers\Master\Unit::view' => '\App\Controllers\Master\Unit::index',
		'\App\Controllers\Master\Unit::save' => '\App\Controllers\Master\Unit::index',
		'\App\Controllers\Master\Unit::delete' => '\App\Controllers\Master\Unit::index',
		'\App\Controllers\Master\Employee::view' => '\App\Controllers\Master\Employee::index',
		'\App\Controllers\Master\Employee::save' => '\App\Controllers\Master\Employee::index',
		'\App\Controllers\Master\Employee::delete' => '\App\Controllers\Master\Employee::index',
		'\App\Controllers\Master\SiteCode::view' => '\App\Controllers\Master\SiteCode::index',
		'\App\Controllers\Master\SiteCode::save' => '\App\Controllers\Master\SiteCode::index',
		'\App\Controllers\Master\SiteCode::delete' => '\App\Controllers\Master\SiteCode::index',

		//User Management
		'\App\Controllers\Master\Users::view' => '\App\Controllers\Master\Users::index',
		'\App\Controllers\Master\Users::save' => '\App\Controllers\Master\Users::index',
		'\App\Controllers\Master\Users::delete' => '\App\Controllers\Master\Users::index',

	];

	public function before(RequestInterface $request, $arguments = null)
	{
		/**
		 * @var Router $router
		 */
		$router = service('router');
		$needAuth = $router->controllerName() . '::' . $router->methodName();

		if (static::isGuest()) {
			if (($needAuth != '\App\Controllers\Cron::scheduler') && ($needAuth != '\App\Controllers\Cron::sendWbIn') && ($needAuth != '\App\Controllers\Cron::sendWbOut') && 
			($needAuth != '\App\Controllers\Cron::BukaMDB')) {
				return redirect()->to(site_url('/login'));
			}
		} else {
			if (($needAuth != '\App\Controllers\Home::index') 
			&& ($needAuth != '\App\Controllers\Master\Users::changePassword')
			&& (!static::can($needAuth))) {
				throw new PageNotFoundException('Restricted Access', 403);
			}
		}
	}

	static public function isGuest(){
		return is_null(session()->get('email'));
	}

	static private $userAuths;
	static public function can($needAuth)
	{
		if (is_null(static::$userAuths)) {
			$authModel = new UserAuthModel();
			$auths = $authModel->where('email', session()->get('email'))
				->findAll();
			$userAuths = [];
			foreach ($auths as $value) {
				$userAuths[] = $value['auth_to'];
			}
			static::$userAuths = $userAuths;
		}
		
		if (key_exists($needAuth, static::$inheritAuth)) {
			$needAuth = static::$inheritAuth[$needAuth];
		}

		return in_array($needAuth, static::$userAuths);
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
	}
}
