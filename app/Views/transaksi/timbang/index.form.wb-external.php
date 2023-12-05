<div class="form-group row">
	<label for="timbang-NomorTicket" class="col-sm-3 col-form-label">Nomor Ticket</label>
	<div class="col-sm-9">
		<input type="text" name="nomorticket" value="<?= $tr_wb['nomorticket'] ?? '' ?>" id="timbang-NomorTicket" class="form-control">
	</div>
</div>
<?php /*
<div class="form-group row">
	<label for="timbang-ProductCode" class="col-sm-3 col-form-label">Product Code</label>
	<div class="col-sm-9">
		<select name="productcode" id="timbang-ProductCode" class="form-control">
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
		<select name="transactiontype" id="timbang-TransactionType" class="form-control">
			<option></option>
			<?php foreach ($parameter_values['TRANSACTIONTYPE'] as $value => $desctription) : ?>
				<option value="<?= $value ?>" <?= (($tr_wb['transactiontype'] ?? '') == $value) ? 'selected' : '' ?>><?= $desctription ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
*/?>
<div class="form-group row">
	<label for="timbang-GateIn" class="col-sm-3 col-form-label">Gate In</label>
	<div class="col-sm-9">
		<input type="text" name="gate_in" value="<?= $tr_wb['gate_in'] ?? '' ?>" id="timbang-GateIn" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-GateOut" class="col-sm-3 col-form-label">Gate Out</label>
	<div class="col-sm-9">
		<input type="text" name="gate_out" value="<?= $tr_wb['gate_out'] ?? '' ?>" id="timbang-GateOut" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-BoardingIn" class="col-sm-3 col-form-label">Boarding In</label>
	<div class="col-sm-9">
		<input type="text" name="boarding_in" value="<?= $tr_wb['boarding_in'] ?? '' ?>" id="timbang-BoardingIn" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-JenisUnit" class="col-sm-3 col-form-label">Jenis Unit</label>
	<div class="col-sm-9">
		<input type="text" name="jenis_unit" value="<?= $tr_wb['jenis_unit'] ?? '' ?>" id="timbang-JenisUnit" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-NomorPolisi" class="col-sm-3 col-form-label">Nomor Polisi</label>
	<div class="col-sm-9">
		<input type="text" name="nomor_polisi" value="<?= $tr_wb['nomor_polisi'] ?? '' ?>" id="timbang-NomorPolisi" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-NamaDriver" class="col-sm-3 col-form-label">Nama Driver</label>
	<div class="col-sm-9">
		<input type="text" name="nama_driver" value="<?= $tr_wb['nama_driver'] ?? '' ?>" id="timbang-NamaDriver" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-KodeSupplier" class="col-sm-3 col-form-label">Kode Supplier</label>
	<div class="col-sm-9">
		<input type="text" name="kode_supplier" value="<?= $tr_wb['kode_supplier'] ?? '' ?>" id="timbang-KodeSupplier" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-NamaSupplier" class="col-sm-3 col-form-label">Nama Supplier</label>
	<div class="col-sm-9">
		<input type="text" name="nama_supplier" value="<?= $tr_wb['nama_supplier'] ?? '' ?>" id="timbang-NamaSupplier" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-WilayahAsalTBS" class="col-sm-3 col-form-label">Wilayah Asal TBS</label>
	<div class="col-sm-9">
		<input type="text" name="wilayah_asal_tbs" value="<?= $tr_wb['wilayah_asal_tbs'] ?? '' ?>" id="timbang-WilayahAsalTBS" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-Kab_Prop" class="col-sm-3 col-form-label">KAB Prop</label>
	<div class="col-sm-9">
		<input type="text" name="kab_prop" value="<?= $tr_wb['kab_prop'] ?? '' ?>" id="timbang-Kab_Prop" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-Kab_createdate" class="col-sm-3 col-form-label">KAB Create Date</label>
	<div class="col-sm-9">
		<input type="text" name="kab_createdate" value="<?= $tr_wb['kab_createdate'] ?? '' ?>" id="timbang-Kab_createdate" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-Kab_createby" class="col-sm-3 col-form-label">KAB Create By</label>
	<div class="col-sm-9">
		<input type="text" name="kab_createby" value="<?= $tr_wb['kab_createby'] ?? '' ?>" id="timbang-Kab_createby" class="form-control">
	</div>
</div>
<!-- <div class="form-group row">
	<label for="timbang-supplier-group" class="col-sm-3 col-form-label">Grup Supplier</label>
	<div class="col-sm-9">
		<input type="text" name="kab_createby" value="<?= $tr_wb['supplier_group'] ?? '' ?>" id="timbang-Kab_createby" class="form-control">
	</div>
</div>
<div class="form-group row">
	<label for="timbang-supplier-group-description" class="col-sm-3 col-form-label">Nama Grup Supplier</label>
	<div class="col-sm-9">
		<input type="text" name="kab_createby" value="<?= $tr_wb['supplier_group_description'] ?? '' ?>" id="timbang-Kab_createby" class="form-control">
	</div>
</div> -->