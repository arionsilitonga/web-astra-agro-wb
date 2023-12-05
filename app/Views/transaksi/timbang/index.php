<?php

use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = 'Timbang';
$this->bodyClass = 'sidebar-collapse';
$this->breadcrumbs = [$this->title];

?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="content">
	<div class="container-fluid">
		<?= $this->include('transaksi/timbang/index.form.php') ?>
		<div class="card collapsed-card" style="margin-top: 30px;">
			<div class="card-header">
				<div class="card-title">
					<i class="fas fa-cogs"></i> NFC Logs
				</div>
				<div class="card-tools">
					<button class="btn btn-tool" data-card-widget="collapse" type="button"><i class="fas fa-plus"></i></button>
				</div>
			</div>
			<div class="card-body">
				<div id="nfc-data"></div>
			</div>
		</div>
	</div>
</section>
<div id="writeKAB_formModal" class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="writeKAB_formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="writeKAB_formModalLabel" class="modal-title">Write KAB</h5>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="" class="col-sm-4 col-form-label">CHITNUMBER</label>
					<div class="col-sm-8">
						<input type="text" id="write-chitnumber" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="" class="col-sm-4 col-form-label">TRANSACTION TYPE</label>
					<div class="col-sm-8">
						<input type="text" id="write-transaction-type" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="" class="col-sm-4 col-form-label">KAB TYPE</label>
					<div class="col-sm-8">
						<input type="text" id="write-kab_type" class="form-control">
					</div>
				</div>
				<p>
					Write KAB
					<ol>
						<li>Klik tombol [Write KAB].</li>
						<li>Tempelkan kartu (KAB) pada NFC Reader / Writer.</li>
						<li>Tunggu hingga proses selesai, kemudian angkat kartu (KAB)</li>
					</ol>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default mr-3" onclick="printNota('<?= $tr_wb['chitnumber'] ?? '' ?>', false)"><i class="fas fa-print"></i> Print Nota</button>
				<button type="button" class="btn btn-success" onclick="writeKAB(this)" ><i class="fas fa-pen-nib"></i> Write KAB</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('end-body') ?>
<?= $this->include('transaksi/timbang/index.script.php') ?>
<?= $this->endSection() ?>