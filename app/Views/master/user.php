<?php

use App\Filters\UsersAuthFilter;
use App\Models\UserModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\View\View;

/**
 * @var View $this
 * @var Request $request
 * @var UserModel $model
 */

$this->title = 'User Management';
$this->bredcrumbs = [$this->title];
?>
<?=$this->extend('layouts/main')?>

<?=$this->section('on-header')?>
<link rel="stylesheet" type="text/css" href="/assets/datatables.css"/>
<?=$this->endSection()?>

<?=$this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="master-page">
			<p>
				<button id="master-tambah" class="btn btn-success" data-toggle="modal" data-target="#master_formModal" data-proses="tambah"><i class="fas fa-plus"></i> Tambah</button>
			</p>
			<table id="master-table" class="table table-bordered">
				<thead class="bg-success">
					<tr>
						<?php foreach($model->getListColumns() as $columnName): ?>
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
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="master_formModalLabel" class="modal-title">Master Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="master-form">
					<?php foreach($model->getDetailColumns() as $key => $attr): ?>
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
					<p>Khusus untuk password user baru dibuat akan diset default : 123456</p>
					<div class="form-group">
						<label class="col-form-label">Authorization Settings :</label>
						<div class="card">
							<div class="card-body">
								<ul>
									<?php
										$AllAuths = UsersAuthFilter::$allAuths;
										$resetIds = [];
										foreach ($AllAuths as $value) {
											if (isset($value['controller'])) {
												$authKey = str_replace('\\', '', $value['controller']) . '-' . ($value['action'] ?? 'index');
												$authAsli = $value['controller'] . '::' . ($value['action'] ?? 'index');
												$label = $value['label'];
											} else {
												$authKey = '';
												$authAsli = '';
												$label = '<i class="fas fa-' . $value['icon'] . '"></i> ' . $value['label'];
											}
											?><li><?php if($authKey != ''): ?><input type="checkbox" name="auth[<?=$authAsli?>]" id="auth-<?=$authKey?>"><?php endif; ?> <?=$label?><?php 
											$resetIds[] = 'auth-' . $authKey;
											if (isset($value['items'])) {
												echo '<ul>';
												foreach ($value['items'] as $item) {
													$itemAuthKey = str_replace('\\', '', $item['controller']) . '-' . ($item['action'] ?? 'index');
													$authAsli = $item['controller'] . '::' . ($item['action'] ?? 'index');
													?><li><input type="checkbox" name="auth[<?=$authAsli?>]" id="auth-<?=$itemAuthKey?>"> <?=$item['label']?></li><?php
													$resetIds[] = 'auth-' . $itemAuthKey;
												}
												echo '</ul>';
											}
											?></li><?php
										}
									?>
								</ul>
							</div>
						</div>
					</div>
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
		<?php foreach($model->getDetailColumns() as $key => $attr): ?>
			$('#master-<?=$key?>').val(msg.<?=$key?>);
			$('#input-error-<?=$key?>').text('');
		<?php endforeach; ?>
		$('#master-old-id').val(msg.old_id);
		<?php
		foreach ($resetIds as $id) {
			?>$('#<?=$id?>').prop('checked', false);<?php
		}
		?>
		$.each(msg.auth, function(index, value) {
			$('#auth-' + value).prop('checked', true);
		});
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
<?php foreach($model->getDetailColumns() as $key => $attr): ?>
	$('#master-<?=$key?>').val('');
	$('#input-error-<?=$key?>').text('');
<?php endforeach; ?>
	$('#master-old-id').val(null);
<?php
	foreach ($resetIds as $id) {
		?>$('#<?=$id?>').prop('checked', false);<?php
	}
?>
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
			<?php foreach ($model->getDetailColumns() as $key => $attr): ?>
				$('span#input-error-<?=$key?>').text(msg.messages.<?=$key?>);
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