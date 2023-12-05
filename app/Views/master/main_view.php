<?php

use App\Models\MyBaseModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\View\View;

/**
 * @var View $this
 * @var Request $request
 * @var MyBaseModel $model
 */

$this->breadcrumbs = [$this->title];

if (! isset($listColumns) || is_null($listColumns)) $listColumns = $model->getListColumns();
if (! isset($detailColumns) || is_null($detailColumns)) $detailColumns = $model->getDetailColumns();
?>
<?=$this->extend('layouts/main')?>

<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<?=$this->endSection()?>

<?=$this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="filter-head">
			<?= $this->renderSection('filter-head') ?>
		</div>
		<div class="master-page">
			<p>
				<?php if (count($detailColumns) > 0) : ?>
				<button id="master-tambah" class="btn btn-success" data-toggle="modal" data-target="#master_formModal" data-proses="tambah"><i class="fas fa-plus"></i> Tambah</button>
				<?php endif; ?>
			</p>
			<table id="master-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
						<?php foreach($listColumns as $columnName): ?>
							<th><?=strtoupper($columnName)?></th>
						<?php endforeach;?>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</section>
<div id="master_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="master_formModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="master_formModalLabel" class="modal-title">Master Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="master-form">
					<?php foreach($detailColumns as $key => $attr): ?>
						<div class="form-group">
							<label class="col-form-label"><?=strtoupper($key)?> :</label>
						<?php if (is_array($attr['type'])) { ?>
							<select id="master-<?=$key?>" name="<?=$key?>" class="form-control">
								<option value=""></option>
								<?php 
								foreach($attr['type'] as $idx => $textx) {
									$_idx = $idx;
									$_textx = $textx;
									if (is_array($textx)) {
										$_idx = $textx['id'];
										$_textx = $textx['text'];
									}
								?>
									<option value="<?=$_idx?>"><?=$_textx?></option>
								<?php } ?>
							</select>
						<?php } else { ?>
							<input id="master-<?=$key?>" name="<?=$key?>" type="<?=$attr['type']?>" <?= $attr['maxlength'] ? ('maxlength="'. $attr['maxlength'] .'"') : '' ?> <?= $attr['readonly'] ?> class="form-control">
						<?php } ?>
							<span id="input-error-<?=$key?>" class="error invalid-feedback" style="display: block;"></span>
						</div>
					<?php endforeach; ?>
					<input type="hidden" id="master-old-id" name="old_id">
				</form>
			</div>
			<div class="modal-footer">
				<button id="btn-save-master" type="button" class="btn btn-success" onclick="saveMaster()">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<?=$this->endSection()?>

<?=$this->section('end-body')?>
<script src="/assets/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script src="/assets/datatables.min.js"></script>
<script type="text/javascript">
var tbMaster;
$(document).ready(function(){
	tbMaster = $('table#master-table').DataTable({
		serverSide: true,
		ajax: {
			url: "<?=$request->uri?>",
			type: 'post',
			async: true,
			error: function(xhr, error, code){
				alert(error);
				return false;
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
});
$('#master-table tbody').on('click', 'button.btn-edit', function(){
	var data = tbMaster.row($(this).parents('tr')).data();
	var pk = data[data.length -1];
	$('#master-old-id').val(null);
	$.get(
		'<?=$request->uri?>/' + pk
	).done(function(msg, textStatus, jqXHR){
		<?php foreach($detailColumns as $key => $attr): ?>
			$('#master-<?=$key?>').val(msg.<?=$key?>);
			$('#input-error-<?=$key?>').text('');
		<?php endforeach; ?>
		$('#master-old-id').val(msg.old_id);
		$('#master_formModal').modal('show');
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(textStatus);
		$('#master_formModal').modal('hide');
	}).always(function(){});
});
$('#master-table tbody').on('click', 'button.btn-delete', function(){
	var data = tbMaster.row($(this).parents('tr')).data();
	var pk = data[data.length -1];
	if (confirm("Yakin akan menghapus "+ pk +"?")) {
		$.post(
			'<?=$request->uri?>/delete',
			{id:pk}
		).done(function(msg, textStatus, jqXHR){
			tbMaster.ajax.reload();				
		}).fail(function(jqXHR, textStatus, errorThrown){
			alert(errorThrown);
		}).always(function(){});
	}
});
$('#master-tambah').on('click', function(){
	var btn = $('button#btn-save-master');
	btn.prop('disabled', false);
<?php foreach($detailColumns as $key => $attr): ?>
	$('#master-<?=$key?>').val('');
	$('#input-error-<?=$key?>').text('');
<?php endforeach; ?>
	$('#master-old-id').val(null);
});
function saveMaster(){
	var mdDialog = $('#master_formModal');
	var btn = $('button#btn-save-master');
	btn.prop('disabled', true);
	
	var data = $('form#master-form').serialize();
	$.post(
		'<?=$request->uri?>/save',
		data
	).done(function(msg, textStatus, jqXHR){
		if (msg.status=='fail') {
			<?php foreach ($detailColumns as $key => $attr): ?>
				$('span#input-error-<?=$key?>').text(msg.messages.<?=$key?> ?? '');
			<?php endforeach;?>
		} else {
			tbMaster.ajax.reload();
			mdDialog.modal('hide');
		}
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(errorThrown);
	}).always(function(){
		btn.prop('disabled', false);
	});
}
</script>
<?=$this->endSection()?>