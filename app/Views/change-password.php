<?php

use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = 'Ubah Password';

?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="content">
	<div class="container-fluid">
		<form action="/change-password" method="post">
			<?php if (!empty(session()->getFlashdata('error'))) : ?>
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					<?php echo session()->getFlashdata('error'); ?>
				</div>
			<?php endif; ?>
			<div class="card">
				<div class="card-body login-card-body">
					<div class="input-group mb-3">
						<input name="password" type="password" class="form-control" placeholder="Password Saat Ini">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body login-card-body">
					<div class="input-group mb-3">
						<input name="new_password" type="password" class="form-control" placeholder="Password Baru">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input name="confirm_new_password" type="password" class="form-control" placeholder="Konfirmasi Password Baru">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-2">
							<button type="submit" class="btn btn-primary btn-block">Ubah Password</button>
						</div>
						<!-- /.col -->
					</div>
				</div>
			</div>
		</form>
	</div>
</section>
<?= $this->endSection() ?>