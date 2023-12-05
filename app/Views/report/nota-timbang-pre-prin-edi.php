<?php
ini_set('display_errors', 1);
use App\Models\ParameterValueModel;
use CodeIgniter\View\View;
/**
 * @var View $this
 * @var array $tr_wb
 * @var array $tr_grading
 * @var array $tr_quality
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nota Timbang</title>
	<!-- Theme style -->
	<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
	<?php /*
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?=base_url()?>/MockUp/plugins/fontawesome-free/css/all.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?=base_url()?>/MockUp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	*/ ?>
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url()?>/MockUp/dist/css/adminlte.min.css">

	<style>
		.garis-bawah{
			border-bottom: 0px;
			margin-bottom: 3px;
		}
		.header,
		.body{
			margin-left: 310px;
			/* border: 1px solid black; */
			/* padding: 15px 5px; */
			font-size: 18px;
			font-family: Arial, Helvetica, sans-serif;
			line-height: 2;
		}
		.footer {
			/*margin-bottom: 40px;*/
			margin: 0;
			/* border: 1px solid black; */
			padding: 15px 5px;
			font-size: 18px;
			font-family: Arial, Helvetica, sans-serif;
		}
		.header .judul{
			font-size: 30px;
			text-align: center;
		}
		.footer .signature,
		.footer .signature-title{
			text-align: center;
		}
		.footer .signature{
			margin: 50px 0 0;
		}

		@media print and (width: 17cm) and (height: 12cm) {
			@page {
				margin: 2.5cm;
			}
		}
	</style>
</head>
<body>
<div class="nota-timbang">
	<div class="header">
		<div class="judul">&nbsp;</div>
		<div class="judul">&nbsp;</div>
		<div class="judul"><?=$tr_wb['chitnumber'] ?? ''?></div>
		
		<div class="judul">&nbsp;</div>
	</div>
	<div class="body row">
		<div class="col-8 row">
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['nama_barang'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['transactiontype'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['customername'] ?? ''?></div>
			<?php if ($tr_wb['transactiontype']=='TBS External'){ ?>
				<div class="label col-3"></div><div class="isi col-9">: <?=$tr_wb['nama_supplier'] ?? ''?></div>
			<?php }?>
			<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['nama_transporter'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['unitcode'] .$tr_wb['nomor_polisi'] ?? ''?> </div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['sabno'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['afdeling'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"> <?=$tr_wb['total_jjg']?? ''.$tr_wb['blok_list'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-5"> <?=$tr_wb['wb_in'] ?? ''?></div><div class="isi col-4 text-right"><?=$tr_wb['weight_in'] ?? 0?> Kg</div>
			<div class="label col-3"></div><div class="isi col-5"> <?=$tr_wb['wb_out'] ?? ''?></div><div class="isi col-4 text-right garis-bawah"><?=$tr_wb['weight_out'] ?? 0?> Kg</div>
			<?php if (!($tr_grading ?? false)){ ?>	
				<div class="label col-3"></div><div class="isi col-5"></div><div class="isi col-4 text-right"><?= abs(($tr_wb['weight_in'] ?? 0) - ($tr_wb['weight_out'] ?? 0))?> Kg</div>
			<?php } else {?>
				<div class="label col-3"></div><div class="isi col-5"></div><div class="isi col-4 text-right"><?= abs(($tr_wb['weight_in'] ?? 0) - ($tr_wb['weight_out'] ?? 0))?> Kg</div>
				<div class="label col-3"></div><div class="isi col-5"></div><div class="isi col-4 text-right garis-bawah"><?= $tr_grading['adjustweight'] ?? 0?> Kg</div>
				<div class="label col-3"></div><div class="isi col-5"></div><div class="isi col-4 text-right"><?= abs(($tr_wb['weight_in'] ?? 0) - ($tr_wb['weight_out'] ?? 0)) - ($tr_grading['adjustweight'] ?? 0) ?> Kg</div>
			<?php } ?>
		</div>
		<div class="col-4 row">
			<?php if ($tr_grading ?? false): ?>	
				<div class="col-12">&nbsp;</div>
				<div class="col-1">&nbsp;</div>
				<div class="col-11 row">
					<div class="col-12 text-center">Grading</div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['jumlahsampling'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['bm'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['bb'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['tp'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['or'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_grading['tks'] ?? '-'?></div>
				</div>
				<div class="col-12">&nbsp;</div>
			<?php endif; ?>
			<?php if ($tr_grading ?? false): ?>	
			
			<?php endif; ?>
			<?php if ($tr_quality ?? false): ?>
				<div class="col-12">&nbsp;</div>
				<div class="col-1">&nbsp;</div>
				<div class="col-11 row">
					<div class="col-12 text-center">QUALITY</div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['ffa'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['temperature'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['moist'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['dirt'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['kernel_pecah'] ?? '-'?></div>
					<div class="col-6"></div><div class="col-1"></div><div class="col-5 text-right"><?=$tr_quality['seal_number'] ?? '-'?></div>
				</div>
				<div class="col-12">&nbsp;</div>
			<?php endif; ?>
		</div>
		
	</div>
	<div class="col-12">&nbsp;</div>
	<div class="col-12">&nbsp;</div>
	<div class="col-12">&nbsp;</div>
	<div class="footer row">
		<div class="col-3">
			<div class="signature-title">&nbsp;</div>
			<div class="signature">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?=$tr_wb['name'] . $tr_wb['nama_driver']?>)</div>
		</div>
		<div class="col-3">
			<div class="signature-title">&nbsp;</div>
			<div class="signature">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div>
		</div>
		<div class="col-3">
			<div class="signature-title">&nbsp;</div>
			<div class="signature">(<?=$tr_wb['operator_in'] ." ". $tr_wb['operator_out'] ?>)</div>
		</div>
		<div class="col-3">
			<div class="signature-title">&nbsp;</div>
			<div class="signature">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div>
		</div>
	</div>
</div>
	<!-- jQuery -->
	<script src="<?=base_url()?>/MockUp/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="<?=base_url()?>/MockUp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<?php /*
	<!-- overlayScrollbars -->
	<script src="<?=base_url()?>/MockUp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?=base_url()?>/MockUp/dist/js/adminlte.min.js"></script>
	*/ ?>
	<script>
		$(document).ready(function(){
			window.print();
		});
	</script>
</body>
</html>