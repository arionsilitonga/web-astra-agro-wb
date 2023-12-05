<?php
use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = 'Report Transaksi TBS';
$this->breadcrumbs = [$this->title];
?>
<?= $this->extend('layouts/main') ?>

<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<?=$this->endSection()?>

<?=$this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="report-transaction-page">
			<table id="report-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
						<th>CHIT Number</th>
						<th>Customer</th>
						<th>TRANSACTIONTYPE</th>
						<th>Transporter</th>
						<th>Unit</th>
						<th>Driver</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</section>
<?=$this->endSection()?>

<?=$this->section('end-body')?>
<script src="/assets/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script src="/assets/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var tbPending = $('table#report-table').DataTable({
		serverSide: true,
		ajax: {
			url: "/report",
			type: 'post',
		},
		processing: true,
		order: [],
		lengthMenu: [
			[25, 50, 100],
			[25, 50, 100],
		],
		columnDefs: [
			{
				targets: [],
				orderable: false,
			}
		],
	});
});
</script>
<?=$this->endSection()?>