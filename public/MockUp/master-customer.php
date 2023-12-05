<?php
$tableName = 'Customer';
$tableColumn = [
	'Customer_Code',
	'Name',
	'Address',
	'Phone',
	'Active',
	'Created_Date',
];
$tableData = [
	['CV. MGP', 'CV MANDIRI GILANG PERKASA', 'JL. ASDFG , Kecamatan Sei Manding KEBUN RIAU A2', '022-23134', 'Y', '21-03-2021 23:23:23'],
	['SAI1', 'PT SAWIT ASAHAN INDAH', 'Kecamatan Sei Manding KEBUN RIAU A2', '0234-343243', 'Y', '21-03-2021 23:23:23'],
];

$title = "Master $tableName";
$breadcrumbs = [$tableName];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?> | Weight Bridge - PT ASTRA AGRO LESTARI Tbk</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="./plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light accent-success">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="./home.php" class="nav-link">Home</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="./index.html" class="nav-link">Log Out</a>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-success elevation-4">
			<!-- Brand Logo -->
			<a href="./home.php" class="brand-link">
				<img src="./icon/logo.png" alt="AAL Logo" class="brand-image img-circle elevation-3" style="opacity: .8;">
				<span class="brand-text font-weight-light">PT Astra Agro Lestari</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image"><i class="fas fa-user img-circle elevation-2 p-2" style="background-color: white;"></i></div>
					<div class="info">
						<a href="#" class="d-block">Joko Dargombez</a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-item">
							<a href="./setup.php" class="nav-link">
								<i class="nav-icon fas fa-tools"></i>
								<p>Setup</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-balance-scale"></i>
								<p>
									Transaksi
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="./timbang.php" class="nav-link">
										<i class="nav-icon fas fa-weight"></i>
										<p>Timbang</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./pending.php" class="nav-link">
										<i class="nav-icon fas fa-history"></i><p>Pending Transaction</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./sync-data.php" class="nav-link">
										<i class="nav-icon fas fa-sync"></i>
										<p>Sync Data</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item menu-open">
							<a href="#" class="nav-link active">
								<i class="nav-icon fas fa-list-alt"></i>
								<p>
									Master Data
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="./master-customer.php" class="nav-link active">
										<i class="nav-icon fas fa-list-alt"></i>
										<p>Customer</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./master-transporter.php" class="nav-link">
										<i class="nav-icon fas fa-list-alt"></i>
										<p>Transporter</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./master-units.php" class="nav-link">
										<i class="nav-icon fas fa-list-alt"></i>
										<p>Units</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./master-karyawan.php" class="nav-link">
										<i class="nav-icon fas fa-list-alt"></i>
										<p>Karyawan</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-scroll"></i>
								<p>
									Report
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="./report-transaksi-tbs.html" class="nav-link">
										<i class="nav-icon fas fa-file-alt"></i>
										<p>Report Transaksi TBS</p>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

			<?php if (isset($breadcrumbs) && count($breadcrumbs) > 0) : ?>
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1><?= $title ?></h1>
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="./home.php">Home</a></li>
									<?php foreach ($breadcrumbs as $key => $value) : ?>
										<li class="breadcrumb-item active"><?= $value ?></li>
									<?php endforeach ?>
								</ol>
							</div>
						</div>
					</div><!-- /.container-fluid -->
				</section>
			<?php endif; ?>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="master-data">
						<p>
							<button class="btn btn-success" data-toggle="modal" data-target="#master_formModal" data-proses="tambah"><i class="fas fa-plus"></i> Tambah</button>
						</p>
						<table class="table table-bordered">
							<thead class="bg-success">
								<tr>
									<?php foreach ($tableColumn as $key => $value) : ?>
										<th><?= $value ?></th>
									<?php endforeach; ?>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($tableData as $key => $row) : ?>
									<tr>
										<?php foreach ($row as $value) : ?>
											<td><?= $value ?></td>
										<?php endforeach; ?>
										<td class="text-center" style="white-space: nowrap;">
											<button title="Edit" class="btn btn-xs btn-success" data-toggle="modal" data-target="#master_formModal" data-proses="edit" data-id="<?= $key ?>"><span class="fas fa-pencil-alt"></span></button>
											<button title="Delete" class="btn btn-xs btn-danger" onclick="master_delete('<?= $key ?>'')"><span class="fas fa-trash-alt"></span></button>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="./plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="./plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="./dist/js/adminlte.min.js"></script>

	<!-- _master_form.php -->
	<div id="master_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="master_formModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header bg-success">
					<h5 id="setup_formModalLabel" class="modal-title"><?= $tableName ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<?php foreach($tableColumn as $clm):?>
							<div class="form-group">
								<label class="col-form-label"><?=$clm?> : </label>
								<input type="text" class="form-control">
							</div>
						<?php endforeach;?>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</body>

</html>