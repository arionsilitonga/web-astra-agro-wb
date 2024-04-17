<?php

use App\Models\ParameterValueModel;


$adjustWeight = (ParameterValueModel::getValue('ADJUSTWEIGHT') == 'Y');
$weight_in = $tr_wb['weight_in'] ?? 0;
?>

<div id="master_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
	<div class="card-header padding-low bg-success pb-1"><b><i class="far fa-arrow-alt-circle-down"></i> LIST PENDING</b></div>
			<div class="container-fluid">
			<div class="pending-transaction-page">
				<table id="pending-table" class="display nowrap" width="100%">
					<thead class="bg-success">
						<tr>
							<th>CHIT Number</th>
							<th>PRODUK</th>
							<th>Transaction Type</th>
							<th>Driver</th>
							<th>Unit</th>	
							<th></th>		
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			</div>
    </div>
  </div>
</div>

<!-- <div id="master_formModal" class="modal fade" tabindex="2" role="dialog" aria-labelledby="master_formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<section class="content">
				<div class="container-fluid">
				<div class="pending-transaction-page">
					<table id="pending-table" class="table table-bordered">
						<thead class="bg-success pb-1">
							<tr>
								<th>CHIT Number</th>
								<th>PRODUK</th>
								<th>Transaction Type</th>
								<th>Driver</th>
								<th>Unit</th>	
								<th></th>		
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				</div>
			</section>	
		</div>
	</div>
</div> -->
<form id="timbang-form" class="form form-horizontal">
	<input id="wbsitecode" name="wbsitecode" type="hidden" value="<?= $tr_wb['wbsitecode'] ?? '' ?>">
	<input id="status" name="status" type="hidden" value="<?= $tr_wb['status'] ?? '' ?>">
	<input type="hidden" name="operator_in" id="operator_in" value="<?= $tr_wb['operator_in'] ?? '' ?>">
	<input type="hidden" name="operator_out" id="operator_out" value="<?= $tr_wb['operator_out'] ?? '' ?>">
	<input type="hidden" name="kabcode" id="kabcode" value="<?= $tr_wb['kabcode'] ?? '' ?>">
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-body padding-low">
					<div class="row">
						<div class="col-md-9">
							<div class="row">
								<div class="col-md-4"><b>TAPPING TYPE</b></div>
								<div class="col-md-4">
									<input name="tapping_type" type="radio" id="type_single_tapping" value="Single Tap" checked>
									<label for="type_single_tapping">Single Tap</label>
								</div>
								<div class="col-md-4">
									<input name="tapping_type" type="radio" id="type_continous_tapping" value="Continous Tap">
									<label for="type_continous_tapping">Continous Tap</label>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<button id="get-NFC-button" type="button" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Wait">
								NFC <i class="fas fa-credit-card"></i>
							</button>
							<button id="nfcCancel-button" type="button" class="btn btn-success" style="display: none;">
								<i class='fa fa-spinner fa-spin '></i> Cancel
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<!-- <div class="col-md-3" style="margin-top: -20px;">
					<label for="weight-current"></label>
					<button id="start-read-wb" type="button" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Wait">
								READ WB
					</button>
				</div> -->
				<div class="col-md-3" style="margin-top: -20px;">
					<label for="weight-current"></label>
					<!-- <div class="text-center form-control weight-indicator"> -->
					<div class="col-sm-10">
						<button id="start-read-wb" type="button" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Wait">
								READ WB <i class="fas fa-balance-scale"></i>
					</button>
					</div>
				</div>
				<div class="col-md-4" style="margin-top: -20px;">
					<label for="weight-current">Weight</label>
					<div class="text-center form-control weight-indicator">
						<input id="weight-current" type="text" value="0" readonly>
						<span><sub>Kg</sub></span>
					</div>
				</div>
				<div class="col-md-4" style="margin-top: -20px;">
					<label for="timbang-Netto">Netto</label>
					<div class="text-center form-control weight-indicator">
						<input id="timbang-Netto" type="text" name="netto" value="<?= $tr_wb['netto'] ?? '' ?>">
						<span><sub>Kg</sub></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="fix-form-wb">
			<div class="form-group row" id="NomorChitPre">
					<label for="timbang-NomorChitPre" class="col-sm-3 col-form-label">Nomor Chit Pre</label>
					<div class="col-sm-6">
						<input type="text" name="chitnumber_pre" id="timbang-NomorChitPre" class="form-control" value="<?= $tr_wb['chitnumberpre'] ?? null?>" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label for="timbang-NomorChit" class="col-sm-3 col-form-label">Nomor Chit</label>
					<div class="col-sm-4">
						<input type="text" name="chitnumber" id="timbang-NomorChit" class="form-control" value="<?= $tr_wb['chitnumber'] ?? null?>" readonly>
					</div>
					<div class="col-sm-2">
						<input id="timbang-kab_type" type="text" name="kab_type" class="form-control" value="<?= $tr_wb['kab_type'] ?? '' ?>" readonly>
					</div>
					<div class="col-sm-3">
					<button type="button" id="btn_getpending" class="btn btn-sm btn-success">
										<i class="nav-icon fas fa-history"></i> GET PENDING LIST</button>
					</div>
				</div>
				<div class="form-group row"  id="UnitGroup">
					<label for="timbang-CodeUnit" class="col-sm-3 col-form-label">Kode Unit</label>
					<div class="col-sm-9">
						<select name="unitcode" id="timbang-CodeUnit" class="form-control select2">
							<option></option>
							<?php foreach ($units as $tr) : ?>
								<option value="<?= $tr['unitcode'] ?>" <?= (($tr_wb['unitcode'] ?? '') == $tr['unitcode']) ? 'selected' : '' ?>><?= $tr['unitcode'] . ' - ' . $tr['platenumber'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group row" id="TransporterGroup">
					<label for="timbang-TransporterCode" class="col-sm-3 col-form-label">Transporter Code</label>
					<div class="col-sm-9">
						<select name="transportercode" id="timbang-TransporterCode" class="form-control select2">
							<option></option>
							<?php foreach ($transporters as $tr) : ?>
								<option value="<?= $tr['transportercode'] ?>" <?= (($tr_wb['transportercode'] ?? '') == $tr['transportercode']) ? 'selected' : '' ?>><?= $tr['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="timbang-ProductCode" class="col-sm-3 col-form-label">Product Code</label>
					<div class="col-sm-9">
						<select name="productcode" id="timbang-ProductCode" class="form-control select2">
							<option></option>
							<?php foreach ($parameter_values['PRODUCTCODE'] as $value => $desctription) : ?>
								<option value="<?= $value ?>" <?= (($tr_wb['productcode'] ?? '') == $value) ? 'selected' : '' ?>><?= $desctription ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="timbang-TransactionType" class="col-sm-3 col-form-label">Transaction Type</label>
					<div class="col-sm-9">
						<select name="transactiontype" id="timbang-TransactionType" class="form-control select2">
							<option></option>
							<?php foreach ($parameter_values['TRANSACTIONTYPE'] as $value => $desctription) : ?>
								<option value="<?= $value ?>" <?= (($tr_wb['transactiontype'] ?? '') == $value) ? 'selected' : '' ?>><?= $desctription ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="timbang-SiteCode" class="col-sm-3 col-form-label">Site Code</label>
					<div class="col-sm-9">
						<select name="sitecode" id="timbang-SiteCode" class="form-control select2">
							<?php foreach ($sites as $st) : ?>
								<option value="<?= $st['sitecode'] ?>" <?= (($tr_wb['sitecode'] ?? '') == $st['sitecode']) ? 'selected' : '' ?> ><?= $st['description'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="timbang-CustomerCode" class="col-sm-3 col-form-label">Customer Code</label>
					<div class="col-sm-9">
						<select name="customercode" id="timbang-CustomerCode" class="form-control select2">
							<option></option>
							<?php foreach ($customers as $cst) : ?>
								<option value="<?= $cst['customercode'] ?>" <?= (($tr_wb['customercode'] ?? '') == $cst['customercode']) ? 'selected' : '' ?>><?= $cst['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div id="form-container">
                <div id="form-wb">
                    <?= $this->include('transaksi/timbang/index.form.wb.php') ?>
                </div>
                <div id="form-wb-external">
                    <?= $this->include('transaksi/timbang/index.form.wb-external.php') ?>
                </div>
            </div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-6">
					<div class="card card-aal">
						<div class="card-header padding-low bg-success pb-1"><b><i class="far fa-arrow-alt-circle-down"></i> WB - IN</b></div>
						<div class="card-body padding-low">
							<div class="form-group row">
								<label for="timbang-TimeIn" class="col-sm-4 col-form-label">Date / Time</label>
								<div class="col-sm-8">
									<input type="text" readonly name="wb_in" value="<?= $tr_wb['wb_in'] ?? '' ?>"  id="timbang-TimeIn" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label for="timbang-WightIn" class="col-sm-4 col-form-label">Weight</label>
								<div class="col-sm-5">
									<input type="text"  name="weight_in"  value="<?= number_format($tr_wb['weight_in'] ?? 0, 0, ',', '.') ?>" id="timbang-WeightIn" class="form-control">
								</div>
								<div class="col-sm-3">
									<button type="button" id="btn_setwbin"  class="btn btn-sm btn-success" <?= ($weight_in == 0) ? 'onclick="set_wb_in()"' : 'disabled' ?>>
										<i class="fas fa-stopwatch"></i> Set</button>
								</div>
								<label for="timbang-Wightin" style="color:Tomato;" class=>HANYA BISA 1X SET WB-IN</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card card-aal">
						<div class="card-header padding-low bg-success pb-1"><b><i class="far fa-arrow-alt-circle-up"></i> WB - OUT</b></div>
						<div class="card-body padding-low">
							<div class="form-group row">
								<label for="timbang-TimeOut" class="col-sm-4 col-form-label">Date / Time</label>
								<div class="col-sm-8">
									<input type="text" readonly name="wb_out" value="<?= $tr_wb['wb_out'] ?? '' ?>"  id="timbang-TimeOut" class="form-control">
								</div>
							</div>
							<div class="form-group row">
								<label for="timbang-WightOut" class="col-sm-4 col-form-label">Weight</label>
								<div class="col-sm-5">
									<input type="text"  name="weight_out"   value="<?= number_format($tr_wb['weight_out'] ?? 0, 0, ',', '.') ?>" id="timbang-WeightOut" class="form-control">
								</div>
								<div class="col-sm-3">
									<button type="button" disabled id="btn_setwbout"  <?= (!key_exists('chitnumber', $tr_wb) || $tr_wb['chitnumber'] == null) ? 'disabled' : '' ?> class="btn btn-sm btn-success" onclick="set_wb_out()"><i class="fas fa-stopwatch"></i> Set</button>
								</div>
								<label for="timbang-Wightout" style="color:Tomato;">HANYA BISA 1X SET WB-OUT</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group d-none-x"><?= $this->include('transaksi/timbang/index.form.kab.php') ?></div>
			<div class="form-group row mt-3">
				<div class="col-sm-6">
					<?= $this->include('transaksi/timbang/index.form.grading.php') ?>
					<?= $this->include('transaksi/timbang/index.form.kualitas.php') ?>
				</div>
				<?php /*
				<div class="col-sm-4">
					<button type="button" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
					<button type="button" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</button>
				</div>
				<div class="col-sm-2 text-right">
					<a href="./nota-timbang.php" class="btn btn-success" target="_blank">
						<i class="fas fa-print"></i>
						<span>Print</span>
					</a>
				</div>
				*/ ?>
				<div class="col-sm-6 text-right">
					<button type="button" class="btn btn-success" onclick="timbang_save(this)"><i class="fas fa-save"></i> Save</button>
				</div>
			</div>
		</div>
	</div>
</form>


