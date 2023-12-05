<div class="form-group row" id="remark_driver">
	<label for="timbang-DriverManual" class="col-sm-3 col-form-label">Nama Driver</label>
	<div class="col-sm-9">
		<input type="text" name="driver_manual" value="<?= $tr_wb['driver_manual'] ?? '' ?>" id="timbang-DriverManual" class="form-control">
	</div>
</div>
<div class="form-group row" id="remark_keterangan">
	<label for="timbang-Keterangan" class="col-sm-3 col-form-label">Keterangan</label>
	<div class="col-sm-9">
		<input type="text" name="keterangan" value="<?= $tr_wb['keterangan'] ?? '' ?>" id="keterangan" class="form-control">
	</div>
</div>

<div class="form-group row" id="driver_kab">
	<label for="timbang-Driver" class="col-sm-3 col-form-label">Driver</label>
	<div class="col-sm-9">
		<!-- <input type="text" name="npk_driver" value="" id="timbang-Driver" class="form-control"> -->
		<select name="npk_driver" id="timbang-Driver" class="form-control select2">
			<option></option>
			<?php foreach ($employees as $emp) : ?>
				<option value="<?= $emp['npk'] ?>" <?= (($tr_wb['npk_driver'] ?? '') == $emp['npk']) ? 'selected' : '' ?>><?= $emp['name'] . ' - ' . $emp['npk'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<div class="form-group row" id="helper1_kab">
	<label for="timbang-Helper1" class="col-sm-3 col-form-label">Helper 1</label>
	<div class="col-sm-9">
		<!-- <input type="text" name="npk_helper1" value="" id="timbang-Helper1" class="form-control"> -->
		<select name="npk_helper1" id="timbang-Helper1" class="form-control select2">
			<option></option>
			<?php foreach ($employees as $emp) : ?>
				<option value="<?= $emp['npk'] ?>" <?= (($tr_wb['npk_helper1'] ?? '') == $emp['npk']) ? 'selected' : '' ?>><?= $emp['name'] . ' - ' . $emp['npk'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<div class="form-group row" id="helper2_kab">
	<label for="timbang-Helper2" class="col-sm-3 col-form-label">Helper 2</label>
	 <div class="col-sm-9">
	 	<!-- <input type="text" name="npk_helper2" value="" id="timbang-Helper2" class="form-control"> -->
		<select name="npk_helper2" id="timbang-Helper2" class="form-control select2">
			<option></option>
			<?php foreach ($employees as $emp) : ?>
				<option value="<?= $emp['npk'] ?>" <?= (($tr_wb['npk_helper2'] ?? '') == $emp['npk']) ? 'selected' : '' ?>><?= $emp['name'] . ' - ' . $emp['npk'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<div class="form-group row">
	<label for="timbang-SabNo" class="col-sm-3 col-form-label">SABNO</label>
	<div class="col-sm-9">
		<input type="text" name="sabno" value="<?= $tr_wb['sabno'] ?? '' ?>" id="timbang-SabNo" class="form-control" readonly>
	</div>
</div>