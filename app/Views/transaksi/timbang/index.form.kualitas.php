<button id="kualitas-button" type="button" class="btn btn-success" data-toggle="modal" data-target="#kualitas_formModal"><i class="fas fa-tags"></i> Kualitas</button>
<div id="kualitas_formModal" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="kualitas_formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="kualitas_formModalLabel" class="modal-title">Kualitas Kernel / CPO</h5>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="kualitas-FFA" class="col-sm-4 col-form-label">FFA</label>
					<div class="col-sm-8">
						<input type="text" name="kualitas[ffa]" value="<?= $tr_quality['ffa'] ?? '' ?>" id="kualitas-FFA" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="kualitas-Temperature" class="col-sm-4 col-form-label">Temperature</label>
					<div class="col-sm-8">
						<input type="number" name="kualitas[temperature]" value="<?= $tr_quality['temperature'] ?? '' ?>" id="kualitas-Temperature" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="kualitas-Moist" class="col-sm-4 col-form-label">MOIST</label>
					<div class="col-sm-8">
						<input type="number" name="kualitas[moist]" value="<?= $tr_quality['moist'] ?? '' ?>" id="kualitas-Moist" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="kualitas-Dirt" class="col-sm-4 col-form-label">DIRT</label>
					<div class="col-sm-8">
						<input type="number" name="kualitas[dirt]" value="<?= $tr_quality['dirt'] ?? '' ?>" id="kualitas-Dirt" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="kualitas-KernelP" class="col-sm-4 col-form-label">Kernel Pecah</label>
					<div class="col-sm-8">
						<input type="number" name="kualitas[kernel_pecah]" value="<?= $tr_quality['kernel_pecah'] ?? '' ?>" id="kualitas-KernelP" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="kualitas-SealNumber" class="col-sm-4 col-form-label">Seal Number</label>
					<div class="col-sm-8">
						<input type="number" name="kualitas[seal_number]" value="<?= $tr_quality['seal_number'] ?? '' ?>" id="kualitas-SealNumber" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fas fa-save"></i> Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
			</div>
		</div>
	</div>
</div>