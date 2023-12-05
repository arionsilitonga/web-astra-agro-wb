<?php
use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = 'View Transaksi';
$this->breadcrumbs = [$this->title];
?>
<?= $this->extend('layouts/main') ?>
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
						<th></th>
						<th>CHIT Number</th>
						<th>Customer</th>
						<th>Transporter</th>
						<th>Unit</th>
						<th>WB-IN</th>
						<th>Weight-IN</th>
						<th>WB-Out</th>
						<th>Weight-Out</th>
						<th>Driver</th>
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
function formatNOC(child){
	var dataNOC = '';
	var nomor = 1;
	var totalJJG = 0;

	child.forEach(noc => {
		dataNOC += '<tr>';
		dataNOC += '<td>'+ nomor +'</td>'
		dataNOC += '<td>'+ noc.sabno +'</td>'
		dataNOC += '<td>'+ noc.nocafd +'</td>'
		dataNOC += '<td>'+ noc.nocblock +'</td>'
		dataNOC += '<td>'+ noc.jjg +'</td>'
		//dataNOC += '<td>'+ noc.nocvalue +'</td>'
		dataNOC += '</tr>';

		nomor++;
		totalJJG += parseInt(noc.jjg);
	});

	if (dataNOC != '') {
		bakalReturn = '<table class="table table-bordered">';
			bakalReturn += '<thead>'
				bakalReturn += '<tr>';
					bakalReturn += '<th>NO</th>';
					bakalReturn += '<th>SAB NO</th>'
					bakalReturn += '<th>Afdeling</th>';
					bakalReturn += '<th>Block</th>';
					bakalReturn += '<th>Janjang</th>';
					//bakalReturn += '<th>Raw NOC Value</th>';
				bakalReturn += '</tr>';
			bakalReturn += '</thead>'
			bakalReturn += '<tbody>' + dataNOC + '</tbody>';
			bakalReturn += '<tfoot>'
				bakalReturn += '<tr>';
					bakalReturn += '<th colspan="4" class="text-center">Total Janjang</th>';
					bakalReturn += '<th>'+ totalJJG +'</th>';
					//bakalReturn += '<th>&nbsp;</th>';
				bakalReturn += '</tr>';
			bakalReturn += '</tfoot>'
		bakalReturn += '</table>';

		return bakalReturn;
	}
}
$(document).ready(function(){
	var tbPending = $('table#pending-table').DataTable({
		serverSide: true,
		ajax: {
			url: "/all-data",
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

	$('table#pending-table tbody').on('click', 'td button.btn-expand', function(){
		var tr = $(this).closest('tr');
		var row = tbPending.row(tr);
		var child = row.data().child;

		if (row.child.isShown()) {
			row.child.hide();
			tr.find('button.btn-expand i.fas').removeClass('fa-minus-circle').addClass('fa-plus-circle');
		} else {
			row.child(formatNOC(child)).show();
			tr.find('button.btn-expand i.fas').removeClass('fa-plus-circle').addClass('fa-minus-circle');
		}
	});
});
</script>
<?=$this->endSection()?>