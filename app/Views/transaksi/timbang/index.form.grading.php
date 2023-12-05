<button id="grading-button" type="button" class="btn btn-success" data-toggle="modal" data-target="#grading_formModal"><i class="fas fa-tags"></i> Grading</button>
<div id="grading_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="grading_formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-success">
				<h5 id="grading_formModalLabel" class="modal-title">Grading TBS</h5>
				<!-- button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button -->
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="grading-JumlahSampling" class="col-sm-4 col-form-label">Jumlah Sampling</label>
					<div class="col-sm-8">
						<input type="number" name="grading[jumlahsampling]" value="<?= $tr_grading['jumlahsampling'] ?? '' ?>" id="grading-JumlahSampling" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-BB" class="col-sm-4 col-form-label">BB</label>
					<div class="col-sm-8">
						<input type="number" name="grading[bb]" value="<?= $tr_grading['bb'] ?? '' ?>" id="grading-BB" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-BM" class="col-sm-4 col-form-label">BM</label>
					<div class="col-sm-8">
						<input type="number" name="grading[bm]" value="<?= $tr_grading['bm'] ?? '' ?>" id="grading-BM" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-TP" class="col-sm-4 col-form-label">TP</label>
					<div class="col-sm-8">
						<input type="number" name="grading[tp]" value="<?= $tr_grading['tp'] ?? '' ?>" id="grading-TP" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-OR" class="col-sm-4 col-form-label">OR</label>
					<div class="col-sm-8">
						<input type="number" name="grading[or]" value="<?= $tr_grading['or'] ?? '' ?>" id="grading-OR" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-TKS" class="col-sm-4 col-form-label">TKS</label>
					<div class="col-sm-8">
						<input type="number" name="grading[tks]" value="<?= $tr_grading['tks'] ?? '' ?>" id="grading-TKS" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label for="grading-PotonganBerat" class="col-sm-4 col-form-label">Potongan Berat</label>
					<div class="col-sm-8">
						<input type="number" name="grading[adjustweight]" value="<?= $tr_grading['adjustweight'] ?? '' ?>" id="grading-PotonganBerat" class="form-control">
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