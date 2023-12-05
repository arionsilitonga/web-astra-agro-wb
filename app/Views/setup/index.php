<?php 

use CodeIgniter\View\View;

/**
 * @var View $this
 * @var array $parameter
 */

$this->title = 'Setup';
$this->breadcrumbs = [$this->title];
?>
<?= $this->extend('layouts/main')?>

<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<?=$this->endSection()?>

<?= $this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				<div class="form-group">
					<label for="parameter-code">Parameter</label>
					<select name="parameter_code" id="parameter-code" class="form-control select2">
						<option></option>
						<?php foreach($parameter as $p): ?>
							<option value="<?=$p['parameter_code']?>"><?=$p['parameter_code']?> - <?=$p['description']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="setup-page">
			<p>
				<button id="setup-tambah" class="btn btn-success" data-toggle="modal" data-target="#setup_formModal" data-proses="tambah"><i class="fas fa-plus"></i> Tambah</button>
			</p>
			<table id="parameter-value-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
						<th>Value</th>
						<th>Description</th>
						<th>Order Number</th>
						<th>Active</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</section>

<!-- _setup_form.php -->
<div id="setup_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="setup_formModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="setup_formModalLabel" class="modal-title">SETUP</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="property-value-form">
					<div class="form-group row">
						<div class="col-md-7">
							<div class="form-group">
								<label class="col-form-label">Parameter Code:</label>
								<input id="property-value-parameter_code" name="parameter_code" readonly type="text" class="form-control">
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="col-form-label">ID:</label>
								<input id="property-value-id" name="id" readonly type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-form-label">Value:</label>
						<input id="property-value-value" name="value" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label class="col-form-label">Description:</label>
						<input id="property-value-description" name="description" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label class="col-form-label">Order Number:</label>
						<input id="property-value-order_number" name="order_number" type="number" class="form-control">
					</div>
					<div class="form-group">
						<label class="col-form-label">Active:</label>
						<select id="property-value-active" name="active" class="form-control">
							<option value="Y">Yes</option>
							<option value="N">No</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal" onclick="saveSetup()">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection()?>

<?=$this->section('end-body')?>
<script src="/assets/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script src="/assets/datatables.min.js"></script>
<script type="text/javascript">
var tbParameterValue;

$(document).ready(function(){
	var parameterCode = $('#parameter-code').val();
	tbParameterValue = $('#parameter-value-table').DataTable({
		serverSide: true,
		ajax:{
			url: "/setup/parameter-value-list",
			type: 'post',
			data: function(d){
				d.parameter_code = parameterCode;
			},
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
	$('select#parameter-code').on('change', function(){
		parameterCode = this.value;
		tbParameterValue.ajax.reload();
	});
	$('#parameter-value-table tbody').on('click', 'button.btn-edit', function(){
		var data = tbParameterValue.row($(this).parents('tr')).data();
		//alert(data[0] + ',\n' + data[1] + ',\n' + data[2] + ',\n' + data[3]);
		$('#property-value-id').val(data[5]);
		$('#property-value-parameter_code').val(data[6]);
		$('#property-value-value').val(data[0]);
		$('#property-value-description').val(data[1]);
		$('#property-value-order_number').val(data[2]);
		$('#property-value-active').val(data[3]);
	});
	$('#parameter-value-table tbody').on('click', 'button.btn-delete', function(){
		var data = tbParameterValue.row($(this).parents('tr')).data();
		if (confirm("Yakin akan menghapus "+ data[0] +"?")) {
			$.post(
				'/setup/delete-value',
				{id:data[5]}
			).done(function(msg, textStatus, jqXHR){
				tbParameterValue.ajax.reload();				
			}).fail(function(jqXHR, textStatus, errorThrown){
				alert(errorThrown);
			}).always(function(){});
		}
	});
});
$('#setup-tambah').on('click', function(){
	var parameter_code = $('select#parameter-code').val();
	$('#property-value-id').val('');
	$('#property-value-parameter_code').val(parameter_code);
	$('#property-value-value').val('');
	$('#property-value-description').val('');
	$('#property-value-order_number').val('');
	$('#property-value-active').val('');
});
function saveSetup(){
	var data = $('form#property-value-form').serialize();
	$.post(
		'/setup/save-value',
		data
	).done(function(msg, textStatus, jqXHR){
		if (msg.status == 'fail') {
			alert(msg.message);
		}
		tbParameterValue.ajax.reload();
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(errorThrown);
	}).always(function(){

	});
}
</script>
<?=$this->endSection()?>