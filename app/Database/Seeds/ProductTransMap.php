<?php

namespace App\Database\Seeds;

use App\Models\MProductTransMapModel;
use App\Models\ParameterValueModel;
use CodeIgniter\Database\Seeder;
use Config\Services;

class ProductTransMap extends Seeder
{
    public function run()
    {
		$parameterValueModel = new ParameterValueModel(Services::request());
		$parameterValueModel->insertBatch([
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TBS Afiliasi', 'description' => 'TBS AFILIASI', 'order_number' => 0, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'Surat Pengantar', 'description' => 'SURAT PENGANTAR BARANG JADI', 'order_number' => 12, 'active' => 'Y',],
			['parameter_code' => 'TRANSACTIONTYPE',	'value' => 'TTG Trading', 'description' => 'TTG TRADING', 'order_number' => 12, 'active' => 'Y',],
		]);

        $model = new MProductTransMapModel();
		$model->insertBatch([
			['productcode' => '400012100', 'transactioncode' => 'TBS Afiliasi', 'title' => 'TBS AFILIASI'],
			['productcode' => '400012100', 'transactioncode' => 'TBS External', 'title' => 'TBS EXTERNAL AAL'],
			['productcode' => '400012100', 'transactioncode' => 'TBS Internal', 'title' => 'TBS INTI AAL'],
			['productcode' => '400012100', 'transactioncode' => 'TBS Plasma/KPA', 'title' => 'TBS PLASMA/KPA'],
			['productcode' => '400012100', 'transactioncode' => 'TBS Titip Olah', 'title' => 'TBS Titip Olah dari Sesama Group AAL'],

			['productcode' => '501000001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '501000001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '501000001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '501000001', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '501000001', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '501010001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '501010001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '501010001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '501010001', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '501010001', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '501020002', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '501020002', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '501020002', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '501020002', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '501020002', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '511000001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '511000001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '511000001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '511000001', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '511000001', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '511000002', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '511000002', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '511000002', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '511000002', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '511000002', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '511000004', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '511000004', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '511000004', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '511000004', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '511000004', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '511010001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '511010001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '511010001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '511010001', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '511010001', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '511020001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '511020001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '511020001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '511020001', 'transactioncode' => 'Surat Pengantar', 'title' => 'SURAT PENGANTAR BARANG JADI'],
			['productcode' => '511020001', 'transactioncode' => 'TTG Trading', 'title' => 'TTD TRADING'],

			['productcode' => '900000001', 'transactioncode' => 'Nota Kirim', 'title' => 'NOTA KIRIM'],
			['productcode' => '900000001', 'transactioncode' => 'Nota Terima', 'title' => 'NOTA TERIMA'],
			['productcode' => '900000001', 'transactioncode' => 'Surat Jalan', 'title' => 'SURAT JALAN'],
			['productcode' => '900000001', 'transactioncode' => 'Others', 'title' => 'OTHERS'],
		]);
    }
}
