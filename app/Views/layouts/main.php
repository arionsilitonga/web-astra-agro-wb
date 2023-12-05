<?php

use CodeIgniter\Router\Router;
use CodeIgniter\View\View;

/**
 * @var View $this
 * @var Router $router
 */

$router = service('router');
$this->controller = $router->controllerName();
$this->method = $router->methodName();
$breadcrumbs = $this->breadcrumbs ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $this->title ?? '' ?> Weight Bridge - PT ASTRA AGRO LESTARI Tbk</title>
	<!-- Google Font: Source Sans Pro -->
	<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
	<!-- Font Awesome -->
	<link rel="stylesheet" href="./MockUp/plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="./MockUp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="./MockUp/dist/css/adminlte.min.css">
    <!-- select2 -->
    <link rel="stylesheet" href="./MockUp/plugins/select2/css/select2.min.css">

	<?= $this->renderSection('on-header') ?>
	<style>
		/*body{
			/* font-family: Verdana, Geneva, Tahoma, sans-serif; *
			font-family: Roboto, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
		}*/
		label {
			margin-bottom: 0;
			font-size: 12px;
		}

		.card-aal {
			background-color: #c4ffd1;
			box-shadow: 0 0 1px rgb(0 0 0 / 36%), 0 1px 3px rgb(0 0 0 / 44%);
		}

		/*.card-aal .card-body{
			padding: 10px;
		}*/
		.padding-low {
			padding: 10px;
		}

		section.content {
			font-size: 14px;
		}

		.form-group {
			margin-bottom: 3px;
		}

		.form-control {
			font-size: 14px;
			padding: 3px 6px;
			height: auto;
		}

		.weight-indicator {
			box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
			-o-box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
			-moz-box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
			-webkit-box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
			margin-bottom: 1rem;
		}

		.weight-indicator input {
			border: none;
			width: 85%;
			text-align: right;
			font-size: 34px;
		}

		.weight-indicator input:focus-visible {
			outline: none;
		}

		#timbang-DataKAB td {
			/* padding: 2px 4px; */
			background-color: white;
		}

		#timbang-DataKAB tfoot td,
		#timbang-DataKAB thead th {
			background-color: lightgreen;
		}

		.console-text {
			padding: 5px;
			margin-bottom: 10px;
			width: 100%;
			word-break: break-all;
			background-color: #333;
			color: whitesmoke;
			border: 1px solid white;
			font-family: 'Courier New', Courier, monospace;
			font-size: small;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed <?= $this->bodyClass ?? '' ?>">
	<!-- Site wrapper -->
	<div class="wrapper">
		<?= $this->include('layouts/header') ?>
		<?= $this->include('layouts/side') ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1><?= $this->title ?? "$this->controller@$this->method" ?></h1>
						</div>
						<div class="col-sm-6">
							<?php if (isset($breadcrumbs) && count($breadcrumbs) > 0) : ?>
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="./">Home</a></li>
									<?php foreach ($breadcrumbs as $node) : ?>
										<li class="breadcrumb-item active"><?= $node ?></li>
									<?php endforeach ?>
								</ol>
							<?php endif; ?>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<?php
			if (session()->getFlashData('success')) {
			?>
				<section class="content">
					<div class="container-fluid">
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?= session()->getFlashData('success') ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</section>
			<?php
			}
			?>

			<?php
			if (session()->getFlashData('danger')) {
			?>
				<section class="content">
					<div class="container-fluid">
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?= session()->getFlashData('danger') ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</section>
			<?php
			}
			?>

			<?php
			if (session()->getFlashData('info')) {
			?>
				<section class="content">
					<div class="container-fluid">
						<div class="alert alert-info alert-dismissible fade show" role="alert">
							<?= session()->getFlashData('info') ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</section>
			<?php
			}
			?>

			<!-- Main content -->
			<?= $this->renderSection('content') ?>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="./MockUp/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="./MockUp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="./MockUp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="./MockUp/dist/js/adminlte.min.js"></script>
    <!-- select2 -->
    <script src="./MockUp/plugins/select2/js/select2.min.js"></script>
    
	<?= $this->renderSection('end-body') ?>

</body>

</html>
