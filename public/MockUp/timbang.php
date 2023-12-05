<?php
$title = 'Timbang';
$breadcrumbs = [$title];
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

		#timbang-DataKAB tfoot td {
			background-color: lightgreen;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
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
						<li class="nav-item menu-open">
							<a href="#" class="nav-link active">
								<i class="nav-icon fas fa-balance-scale"></i>
								<p>
									Transaksi
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="./timbang.php" class="nav-link active">
										<i class="nav-icon fas fa-weight"></i>
										<p>Timbang</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="./pending.php" class="nav-link">
										<i class="nav-icon fas fa-history"></i>
										<p>Pending Transaction</p>
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
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-list-alt"></i>
								<p>
									Master Data
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="./master-customer.php" class="nav-link">
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
					<form id="timbang-form" class="form form-horizontal">
						<div class="row">
							<div class="col-md-6">
								<div class="card">
									<div class="card-body padding-low">
										<div class="row">
											<div class="col-md-10">
												<div class="row">
													<div class="col-md-4"><b>BOARDING TYPE</b></div>
													<div class="col-md-4">
														<input type="radio" name="timbang_boardingType" id="type_boarding" value="boarding">
														<label for="type_boarding">Boarding</label>
													</div>
													<div class="col-md-4">
														<input type="radio" name="timbang_boardingType" id="type_non_boarding" value="non-boarding">
														<label for="type_non_boarding">Non Boarding</label>
													</div>
												</div>
												<div class="info">
													<span class="bg-success pl-2 pr-2">Ready to tab</span>
													<span class="bg-danger pl-2 pr-2">Disconected</span>
												</div>
											</div>
											<div class="col-md-2">
												<button type="button" class="btn btn-success">
													NFC <i class="fas fa-credit-card"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-6" style="margin-top: -20px;">
										<label for="weight-current">Weight</label>
										<div class="text-center form-control weight-indicator">
											<input id="weight-current" type="text" value="15.000" readonly>
											<span><sub>Kg</sub></span>
										</div>
									</div>
									<div class="col-md-6" style="margin-top: -20px;">
										<label for="timbang-Netto">Netto</label>
										<div class="text-center form-control weight-indicator">
											<input id="timbang-Netto" type="text" name="netto" value="8.000">
											<span><sub>Kg</sub></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row">
									<label for="timbang-NomorChit" class="col-sm-4 col-form-label">Nomor Chit</label>
									<div class="col-sm-8">
										<input type="text" name="nomorChit" id="timbang-NomorChit" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-CustomerCode" class="col-sm-4 col-form-label">Customer Code</label>
									<div class="col-sm-8">
										<select name="customerCode" id="timbang-CustomerCode" class="form-control">
											<option value="CV. MGP">CV MANDIRI GILANG PERKASA</option>
											<option value="SAI1" selected>PT SAWIT ASAHAN INDAH</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-TransporterCode" class="col-sm-4 col-form-label">Transporter Code</label>
									<div class="col-sm-8">
										<select name="transporterCode" id="timbang-TransporterCode" class="form-control">
											<option value="TBPP" selected>PT. TRI BHAKTI PRIMA PERKASA</option>
											<option value="SAI1">PT SAWIT ASAHAN INDAH</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-CodeUnit" class="col-sm-4 col-form-label">Code Unit</label>
									<div class="col-sm-8">
										<select name="codeUnit" id="timbang-CodeUnit" class="form-control">
											<option value="SAIDT001">SAIDT001 - BA 2343 KCL</option>
											<option value="SAIDT002">SAIDT002 - BA 2345 KCL</option>
											<option value="XSDIDA001">XSDIDA001 - BK 2223 DD</option>
											<option value="XSAIDT001" selected>XSAIDT001 - BK 2223 DD</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-ProductCode" class="col-sm-4 col-form-label">Product Code</label>
									<div class="col-sm-8">
										<select name="productCode" id="timbang-ProductCode" class="form-control">
											<option value="400012100">400012100 - Tanda Buah Segar</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-TransactionType" class="col-sm-4 col-form-label">Transaction Type</label>
									<div class="col-sm-8">
										<select name="transactionType" id="timbang-TransactionType" class="form-control">
											<option value="0">TBS Internal</option>
											<option value="1">TBS Afiliasi</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-Driver" class="col-sm-4 col-form-label">Driver</label>
									<div class="col-sm-8">
										<select name="driver" id="timbang-Driver" class="form-control">
											<option value="9231245">ARION - 9231245</option>
											<option value="2454544">ELIOT - 2454544</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-Helper1" class="col-sm-4 col-form-label">Helper 1</label>
									<div class="col-sm-8">
										<select name="helper1" id="timbang-Helper1" class="form-control">
											<option value="9231245">ARION - 9231245</option>
											<option value="2454544">ELIOT - 2454544</option>
											<option value="3234325" selected>BEDU - 3234325</option>
											<option value="3234320">SAIPUL - 3234325</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-Helper2" class="col-sm-4 col-form-label">Helper 2</label>
									<div class="col-sm-8">
										<select name="helper2" id="timbang-Helper2" class="form-control">
											<option value="9231245">ARION - 9231245</option>
											<option value="2454544">ELIOT - 2454544</option>
											<option value="3234325">BEDU - 3234325</option>
											<option value="3234320" selected>SAIPUL - 3234325</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label for="timbang-SabNo" class="col-sm-4 col-form-label">SABNO</label>
									<div class="col-sm-8">
										<input type="text" name="sabNo" id="timbang-SabNo" class="form-control" value="TTBSAI121012768">
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
														<input type="text" name="timeIn" id="timbang-TimeIn" class="form-control" value="21-09-2021 21:34:44">
													</div>
												</div>
												<div class="form-group row">
													<label for="timbang-WightIn" class="col-sm-4 col-form-label">Weight</label>
													<div class="col-sm-5">
														<input type="text" name="weightIn" id="timbang-WeightIn" class="form-control" value="15.000">
													</div>
													<div class="col-sm-3">
														<button type="button" class="btn btn-sm btn-success"><i class="fas fa-stopwatch"></i> Set</button>
													</div>
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
														<input type="text" name="timeOut" id="timbang-TimeOut" class="form-control" value="21-09-2021 21:34:44">
													</div>
												</div>
												<div class="form-group row">
													<label for="timbang-WightOut" class="col-sm-4 col-form-label">Weight</label>
													<div class="col-sm-5">
														<input type="text" name="weightOut" id="timbang-WeightOut" class="form-control" value="7.000">
													</div>
													<div class="col-sm-3">
														<button type="button" class="btn btn-sm btn-success"><i class="fas fa-stopwatch"></i> Set</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="timbang-DataKAB">Data KAB</label>
									<table id="timbang-DataKAB" style="width: 100%;">
										<tbody>
											<tr>
												<td>1</td>
												<td>OF</td>
												<td>007</td>
												<td>89</td>
												<?php /*<td style="word-break: break-word;">11:21:BE:71;;SAI1;1309211651;1;1;OF;007;K026;V2AGLMC6C2403908;;130921;5F;17F;27F;2F;8;7F;2;58F;11;42F;13;43F;18;56F;5</td>*/ ?>
											</tr>
											<tr>
												<td>2</td>
												<td>OF</td>
												<td>007</td>
												<td>45</td>
												<?php /*<td style="word-break: break-word;">11:21:BE:71;;SAI1;1309211651;1;1;OF;007;K026;V2AGLMC6C2403908;;130921;5F;17F;27F;2F;8;7F;2;58F;11;42F;13;43F;18;56F;5</td>*/ ?>
											</tr>
											<tr>
												<td>3</td>
												<td>OF</td>
												<td>007</td>
												<td>30</td>
												<?php /*<td style="word-break: break-word;">11:21:BE:71;;SAI1;1309211651;1;1;OF;007;K026;V2AGLMC6C2403908;;130921;5F;17F;27F;2F;8;7F;2;58F;11;42F;13;43F;18;56F;5</td>*/ ?>
											</tr>
											<tr>
												<td>4</td>
												<td>OF</td>
												<td>007</td>
												<td>98</td>
												<?php /*<td style="word-break: break-word;">11:21:BE:71;;SAI1;1309211651;1;1;OF;007;K026;V2AGLMC6C2403908;;130921;5F;17F;27F;2F;8;7F;2;58F;11;42F;13;43F;18;56F;5</td>*/ ?>
											</tr>
											<tr>
												<td>5</td>
												<td>OF</td>
												<td>007</td>
												<td>130</td>
												<?php /*<td style="word-break: break-word;">11:21:BE:71;;SAI1;1309211651;1;1;OF;007;K026;V2AGLMC6C2403908;;130921;5F;17F;27F;2F;8;7F;2;58F;11;42F;13;43F;18;56F;5</td>*/ ?>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3">Total JJG</td>
												<td colspan="2">392</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="form-group row mt-3">
									<div class="col-sm-6">
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#grading_formModal"><i class="fas fa-tags"></i> Grading</button>
										<div id="grading_formModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="grading_formModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-dialog-scrollable" role="document">
												<div class="modal-content">
													<div class="modal-header bg-success">
														<h5 id="grading_formModalLabel" class="modal-title">Grading TBS</h5>
														<!-- button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button -->
													</div>
													<div class="modal-body">
														<div class="form-group row">
															<label for="grading-NoChit" class="col-sm-4 col-form-label">Nomor CHIT</label>
															<div class="col-sm-8">
																<input type="text" name="gradingNomorChit" id="grading-NoChit" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-JumlahSampling" class="col-sm-4 col-form-label">Jumlah Sampling</label>
															<div class="col-sm-8">
																<input type="number" name="gradingJumlahSampling" id="grading-JumlahSampling" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-BB" class="col-sm-4 col-form-label">BB</label>
															<div class="col-sm-8">
																<input type="number" name="gradingBB" id="grading-BB" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-BM" class="col-sm-4 col-form-label">BM</label>
															<div class="col-sm-8">
																<input type="number" name="gradingBM" id="grading-BM" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-TP" class="col-sm-4 col-form-label">TP</label>
															<div class="col-sm-8">
																<input type="number" name="gradingTP" id="grading-TP" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-OR" class="col-sm-4 col-form-label">OR</label>
															<div class="col-sm-8">
																<input type="number" name="gradingOR" id="grading-OR" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-TKS" class="col-sm-4 col-form-label">TKS</label>
															<div class="col-sm-8">
																<input type="number" name="gradingTKS" id="grading-TKS" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="grading-PotonganBerat" class="col-sm-4 col-form-label">Potongan Berat</label>
															<div class="col-sm-8">
																<input type="number" name="gradingPotonganBerat" id="grading-PotonganBerat" class="form-control">
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
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#kualitas_formModal"><i class="fas fa-tags"></i> Kualitas</button>
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
																<input type="text" name="kualitasFFA" id="kualitas-FFA" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="kualitas-Temperature" class="col-sm-4 col-form-label">Temperature</label>
															<div class="col-sm-8">
																<input type="number" name="kualitasTemperature" id="kualitas-Temperature" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="kualitas-Moist" class="col-sm-4 col-form-label">MOIST</label>
															<div class="col-sm-8">
																<input type="number" name="kualitasMoist" id="kualitas-Moist" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="kualitas-Dirt" class="col-sm-4 col-form-label">DIRT</label>
															<div class="col-sm-8">
																<input type="number" name="kualitasDirt" id="kualitas-Dirt" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="kualitas-KernelP" class="col-sm-4 col-form-label">Kernel_P</label>
															<div class="col-sm-8">
																<input type="number" name="kualitasKernelP" id="kualitas-KernelP" class="form-control">
															</div>
														</div>
														<div class="form-group row">
															<label for="kualitas-SealNumber" class="col-sm-4 col-form-label">Seal Number</label>
															<div class="col-sm-8">
																<input type="number" name="kualitasSealNumber" id="kualitas-SealNumber" class="form-control">
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
									</div>
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
								</div>
							</div>
						</div>
					</form>
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
</body>

</html>