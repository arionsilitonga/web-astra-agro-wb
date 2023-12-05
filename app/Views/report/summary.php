<?php
ini_set('display_errors', 1);
use CodeIgniter\View\View;
use App\Models\ParameterValueModel;

/**
 * @var View $this
 */

$this->title = 'Report Produksi';
$this->breadcrumbs = [$this->title];
?>
<?= $this->extend('layouts/main') ?>
<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<link rel="stylesheet" type="text/css" href="/MockUp/plugins/jquery-ui/jquery-ui.css"/>
<style>
	.dt-buttons {
		margin: 3px 0;
	}
	table.table-filter td {
		padding: 5px 10px;
	}
</style>
<?=$this->endSection()?>

<?=$this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="produksi-tbs-filter">
			<table class="table-filter">
				<tr>
					<td>
						<label>Tanggal</label>
					</td>
					<td>
						<input type="text" name="dateFrom" id="filter-DateFrom" value="<?= date('Y-m-d')/*date('Y-m-d', strtotime('-14 days'))*/ ?>" style="width: 100px;">
						<label>JAM</label>
						<select name="jam_mulai" id="filter-jam-mulai" >
						<!-- <select name="jam_mulai" id="filter-jam-mulai" class="form-control select2"> -->
							
							<option value="00">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							
						</select>	
						s/d
						<input type="text" name="dateTo" id="filter-DateTo" value="<?= date('Y-m-d') ?>" style="width: 100px;">
						<label>JAM</label>
						<select name="jam_selesai" id="filter-jam-selesai" >
						<!-- <select name="jam_selesai" id="filter-jam-selesai" class="form-control select2"> -->
							
							<option value="00">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							
						</select>	
					</td>
					
				</tr>
			
				<!-- <tr>
					<td>
						<label>Produk</label>
					</td>
					<td>
						<select name="productcode" id="filter-Productcode" class="form-control select2">
							<option></option>
							<?php foreach ($parameter_values['PRODUCTCODE'] as $value => $desctription) : ?>
								<option value="<?= $value ?>" <?= (($tr_wb['productcode'] ?? '') == $value) ? 'selected' : '' ?>><?= $desctription ?></option>
							<?php endforeach; ?>
						</select>	
					</td>
				</tr> -->
				<td>
						<label>JENI TRANSAKSI</label>
					</td>
					<td>
						<select name="transtype" id="filter-Transactiontype" class="form-control select2">
							<option></option>
							<?php foreach ($parameter_values['TRANSACTIONTYPE'] as $value => $desctription) : ?>
								<option value="<?= $value ?>" <?= (($tr_wb['transactiontype'] ?? '') == $value) ? 'selected' : '' ?>><?= $desctription ?></option>
							<?php endforeach; ?>
						</select>	
					</td>
				<tr>
					<td>
						<label>Customer</label>
					</td>
					<td>
						<select name="customercode" id="filter-Customercode" class="form-control select2">
							<option></option>
							<?php foreach ($customers as $cst) : ?>
								<option value="<?= $cst['customercode'] ?>" <?= (($tr_wb['customercode'] ?? '') == $cst['customercode']) ? 'selected' : '' ?>><?= $cst['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td rowspan="2" style="vertical-align: bottom;">
						<button class="btn btn-primary" onclick="dataTableReload()">Show Data</button>
					</td>
				</tr>

			</table>
		</div>
		<div class="produksi-tbs-page">
			<table id="produksiTBS-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
					<th>CUSTOMER</th>
					<th>PRODUK</th>
					<th>TYPE</th>
					<th>RIT</th>
					<th>WEIGH IN</th>
					<th>WEIGH OUT</th>
					<th>WEIGHNET</th>
					<th>ADJUST</th>
					<th>NETTO</th>
					</tr>
				</thead>
				<tbody></tbody>
				<tfoot>
					<tr>
						<th colspan="3" class="text-center">Total</th>
						<th id="sum-rit"></th>
						<th id="sum-weightin"></th>
						<th id="sum-weightout"></th>
						<th id="sum-weightnet"></th>
						<th id="sum-adjustweight"></th>
						<th id="netto"></th>
					</tr> 
				</tfoot>
			</table>
		</div>
	</div>
</section>
<?=$this->endSection()?>

<?=$this->section('end-body')?>
<script src="/assets/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script src="/assets/datatables.min.js"></script>
<script src="/MockUp/plugins/jquery-ui/jquery-ui.js"></script>

<script src="/assets/js/dataTables.buttons.min.js"></script>
<script src="/assets/js/jszip.min.js"></script>
<script src="/assets/js/pdfmake.min.js"></script>
<script src="/assets/js/vfs_fonts.js"></script>
<script src="/assets/js/buttons.html5.min.js"></script>
<script src="/assets/js/buttons.print.min.js"></script>
<script src="/assets/js/buttons.print.min.js"></script>
<script src="/assets/js/jquery.datetimepicker.js"></script>

<script type="text/javascript">
var tbProduksi;
$(document).ready(function(){
	$('#datetimepicker_mask').datetimepicker({
		mask:'9999/19/39 29:59'
	});
	$('#filter-DateFrom').datepicker({dateFormat: 'yy-mm-dd'});
	$('#filter-DateTo').datepicker({dateFormat: 'yy-mm-dd'});

	$('table#produksiTBS-table').on('xhr.dt', function(e, settings, json, xhr){
		
		$('#sum-weightin').text(json.sum_weight_in);
		$('#sum-weightout').text(json.sum_weight_out);
		$('#sum-weightnet').text(json.sum_weightnet);
		$('#sum-adjustweight').text(json.sum_adjustweight);
		$('#netto').text(json.netto);
	});

	tbProduksi = $('table#produksiTBS-table').DataTable({
		serverSide: true,
		ajax: {
			url: '/report-summary',
			type: 'post',
			data: function(data) {
				data.dateFrom = $('#filter-DateFrom').val();
				data.dateTo = $('#filter-DateTo').val();
				data.productcode =$('#filter-Productcode').val();
				data.customercode =$('#filter-Customercode').val();
				data.transactiontype =$('#filter-Transactiontype').val();
				data.jam_mulai =$('#filter-jam-mulai').val();
				data.jam_selesai =$('#filter-jam-selesai').val();
				// data.productcode = $('#filter-Productcode').val();
				// data.customercode = $('#filter-Productcode').val();//$('#filter-Customercode').val();
			},
		},
		processing: true,
		order: [],
		lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		columns: [
			{data: 'customername'},
			{data: 'description'},
			{data: 'transactiontype'},
			{data: 'rit'},
			{data: 'weight_in'},
			{data: 'weight_out'},
			{data: 'weightnet'},
			{data: 'adjustweight'},
			{data: 'netto'},
		],
		columnDefs: [
			{
				targets: [],
				orderable: false,
			}
		],
		dom: "<'row'<'col-sm-12'B><'col-sm-6'l><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			{
				extend: 'csv',
				className: 'btn btn-secondary btn-sm',
				footer: true,
			},
			{
				extend: 'excel',
				className: 'btn btn-secondary btn-sm',
				footer: true,
			},
			{
				extend: 'pdf',
				className: 'btn btn-secondary btn-sm',
				footer: true,
			},
		],
	});
});

function dataTableReload()
{
	tbProduksi.ajax.reload();
}
</script>
<?=$this->endSection()?>
