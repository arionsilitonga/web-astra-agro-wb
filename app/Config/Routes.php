<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->add('/login', '\App\Controllers\Master\Users::login');
$routes->get('/logout', '\App\Controllers\Master\Users::logout');

$routes->get('/shutdown', '\App\Controllers\Master\Users::shutdown');
$routes->get('/restart', '\App\Controllers\Master\Users::restart');

$routes->add('/change-password', '\App\Controllers\Master\Users::changePassword');

/** User Management */
$routes->add('user-management', '\App\Controllers\Master\Users::index');
$routes->get('user-management/(:segment)', '\App\Controllers\Master\Users::view/$1');
$routes->post('user-management/save', '\App\Controllers\Master\Users::save');
$routes->post('user-management/delete', '\App\Controllers\Master\Users::delete');


/** Setup Routes */
$routes->get('/setup', 'Setup::index');
$routes->post('/setup/parameter-value-list', 'Setup::parameter_value_list');
$routes->post('/setup/save-value', 'Setup::saveParameterValue');
$routes->post('/setup/delete-value', 'Setup::deleteParameterValue');

/** Transaksi Timbang Routes */
$routes->get('/timbang', 'Timbang::index', ['as' => 'transaksi_timbang']);
$routes->get('/timbang/nfcRead', 'Timbang::nfcRead');
$routes->get('/timbang/nfcCancel', 'Timbang::nfcCancel');
$routes->get('/timbang/weighRead', 'Timbang::weighRead');

$routes->get('/timbang/getWeight', 'Timbang::getWeight');
$routes->get('/timbang/getWeightLength', 'Timbang::getWeightLength');

$routes->post('/timbang/save', 'Timbang::save');
$routes->get('/timbang/cekChitNumberExists/(:segment)', 'Timbang::cekChitNumberExists/$1');
$routes->get('/timbang/save-api/(:segment)', 'Timbang::saveAPI/$1');
$routes->get('/timbang/write-kab/(:segment)', 'Timbang::writeKab/$1');
$routes->get('/timbang/transaction-code/(:segment)', 'Timbang::getTransaction/$1');
//$routes->get('/timbang/transaction-transportermap/(:segment)', 'Timbang::getTransporterMap/$1');
$routes->get('/timbang/transaction-transbyunit/(:segment)', 'Timbang::getTransByUnit/$1');
$routes->post('/timbang/getPending', 'Timbang::getPending');
$routes->get('/timbang/getCurrentDate', 'Timbang::getCurrentDate');

$routes->post('/timbang/transaction-transportermap', 'Timbang::getTransporterMap');

/** Data Transaksi Timbang Routes*/
$routes->add('/pending', 'DataTimbang::pending', ['as' => 'transaksi_pending']);
$routes->add('/all-data', 'DataTimbang::index');
$routes->add('/unsent', 'DataTimbang::unsent');
$routes->add('/nota/(:segment)', 'DataTimbang::nota/$1');
$routes->add('/nota-preprint/(:segment)', 'DataTimbang::notaPrePrint/$1');

$routes->add('/report', 'DataTimbang::report');

/** Report Routes */
$routes->add('/report-all-column', 'Report::allcolumn');
$routes->add('/report-production', 'Report::produksi');
$routes->add('/report-summary', 'Report::summary');
/** Master Routes */
$routes->add('customer', '\App\Controllers\Master\Customer::index');
$routes->get('customer/(:segment)', '\App\Controllers\Master\Customer::view/$1');
$routes->post('customer/save', '\App\Controllers\Master\Customer::save');
$routes->post('customer/delete', '\App\Controllers\Master\Customer::delete');

$routes->add('transporter', '\App\Controllers\Master\Transporter::index');
$routes->get('transporter/(:segment)', '\App\Controllers\Master\Transporter::view/$1');
$routes->post('transporter/save', '\App\Controllers\Master\Transporter::save');
$routes->post('transporter/delete', '\App\Controllers\Master\Transporter::delete');

$routes->add('unit', '\App\Controllers\Master\Unit::index');
$routes->get('unit/(:segment)', '\App\Controllers\Master\Unit::view/$1');
$routes->post('unit/save', '\App\Controllers\Master\Unit::save');
$routes->post('unit/delete', '\App\Controllers\Master\Unit::delete');

$routes->add('karyawan', '\App\Controllers\Master\Employee::index');
$routes->get('karyawan/(:segment)', '\App\Controllers\Master\Employee::view/$1');
$routes->post('karyawan/save', '\App\Controllers\Master\Employee::save');
$routes->post('karyawan/delete', '\App\Controllers\Master\Employee::delete');

$routes->add('site-code', '\App\Controllers\Master\SiteCode::index');
$routes->get('site-code/(:segment)', '\App\Controllers\Master\SiteCode::view/$1');
$routes->post('site-code/save', '\App\Controllers\Master\SiteCode::save');
$routes->post('site-code/delete', '\App\Controllers\Master\SiteCode::delete');

$routes->add('product-map', '\App\Controllers\Master\ProductMap::index');

$routes->cli('schedule', 'Cron::scheduler');
$routes->cli('mirror', 'Cron::BukaMDB');
$routes->get('/timbang/serialRead', 'Timbang::serialRead');

$routes->cli('sendWbIn', 'Cron::sendWbIn');
$routes->cli('sendWbOut', 'Cron::sendWbOut');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
