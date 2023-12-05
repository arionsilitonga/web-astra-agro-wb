<?php
use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = 'Pending Transaction';
$this->breadcrumbs = [$this->title];
?>
<?= $this->extend('layouts/main')?>

<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<?=$this->endSection()?>

<?=$this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="pending-transaction-page">
			<table id="pending-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
						<th>CHIT Number</th>
						<th>PRODUK</th>
						<th>Transaction Type</th>
						<th>Driver</th>
						<th>Transporter</th>
						<th>Unit</th>
						<th>WB-IN</th>
						<th>Weight-IN</th>
						<!-- <th></th> -->
						
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
	var tbPending = $('table#pending-table').DataTable({
			serverSide: true,
			ajax: {
				url: "/pending",
				type: 'post',
			},
			processing: true,
			order: [],
			lengthMenu: [
				[25, 50, 100],
				[25, 50, 100],
			],
			// columnDefs: [
			// 	{
			// 		targets: -1,
			// 		data: null,
			// 		defaultContent: '<button class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></button>',
			// 	}
			// ],
		});

	});

	$('table#pending-table').on('click', 'button', function () {
        // var data = tbPending.row($(this).parents('tr')).data();
        // alert(data[0] + "'s salary is: " + data[5]);
		alert('test');
    });
	
</script>
<?=$this->endSection()?>