<?php

use CodeIgniter\View\View;

/**
 * @var View $this
 */

$this->title = '';

?>
<?= $this->extend('layouts/main')?>
<?= $this->section('content')?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 offset-md-2 mt-5 login-logo">
				<div class="text-center"><img src="/MockUp/icon/logo.png"></div>
				<div class="text-center">
					<b>WEIGHT BRIDGE </b><?php echo getenv('app.version')?><br>
					PT. Astra Agro Lestari Tbk
				</div>
			</div>
		</div>
	</div>
</section>
<?= $this->endSection()?>