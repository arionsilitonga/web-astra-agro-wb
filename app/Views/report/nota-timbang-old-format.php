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
		/* .garis-bawah{
			border-bottom: 1px solid black;
			margin-bottom: 3px;
		} */
		.header,
		.body{
			margin-left: 185px;
			margin-top: 30px;
			margin-bottom: 0px;
			/* border: 1px solid black; */
			/* padding: 15px 5px; */
			font-size: 23px;
			font-family: Arial, Helvetica, sans-serif;
			font-weight:bold;
			line-height: 1.5;
		}

		.body2{
			margin-left: 420px;
			margin-top: 0px;
			/* border: 1px solid black; */
			/* padding: 15px 5px; */
			font-size: 22px;
			font-family: Arial, Helvetica, sans-serif;
			line-height: 2;
		}	

		
		.footer {
			margin-top: 40px;
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
				margin: 1.5cm;
			}
		}
	</style>
</head>
<body>
<div class="nota-timbang">
	<div class="header">
		<div class="judul">&nbsp;</div>
	</div> 
	<div class="body row">
		<table>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=$tr_wb['unitcode'] .$tr_wb['nomor_polisi'] ?? ''?></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=$tr_wb['chitnumber']?></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=$tr_wb['wb_in']?></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=number_format($tr_wb['weight_in'],0,",",".")?></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=$tr_wb['wb_out']?></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=number_format($tr_wb['weight_out'],0,",",".")?></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><?=number_format(abs($tr_wb['weight_out']-$tr_wb['weight_in']),0,",",".")?></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<?php if (!($tr_grading ?? false)){ ?>	
				<td></td>
				<?php } else {?>
				<td>-<?=number_format($tr_grading['adjustweight'],0,",",".")?></td>
				<?php } ?>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<?php if (!($tr_grading ?? false)){ ?>	
				<td></td>
				<?php } else {?>
					<td><?=number_format(abs($tr_wb['weight_out']-$tr_wb['weight_in'])-$tr_grading['adjustweight'],0,",",".")?></td>
				<?php } ?>
			</tr>
		</table>
	</div>
	<div class="body2 row">
		<div class="col-8 row">
			<div class="label col-6"></div><div class="isi col-9"> <?=$tr_wb['nama_barang']?></div>	
			<div class="label col-6"></div><div class="isi col-9"> <?=$tr_wb['transactiontype'] ?? ' '?></div>	
			<?php if ($tr_wb['transactiontype']=='TBS External'){ ?>
				<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['nama_supplier'] . $tr_wb['customername'] ?></div>
			<?php }else {?>
				<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['customername'] ?? ' '?></div>
			<?php } ?>
			<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['sabno'] ?? ' '?></div>
			<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['nama_transporter'] ?? ''?></div>
			<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['afdeling'] . ' ' . $tr_wb['total_jjg'] .' '. $tr_wb['blok_list']?></div>
			<div class="label col-3"></div><div class="isi col-9"><?=$tr_wb['keterangan'] ?? ' ' ?></div>
		</div>
		<div class="col-4 row">
			<?php if ($tr_grading ?? false): ?>	
				<div class="col-12">&nbsp;</div>
				<div class="col-1">&nbsp;</div>
				<div class="col-11 row">
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_grading['bm'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_grading['bb'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_grading['tp'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_grading['or'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_grading['adjustweight'] ?? '-'?></div>
				</div>
				<div class="col-12">&nbsp;</div>
			<?php endif; ?>
			<?php if ($tr_grading ?? false): ?>	
			
			<?php endif; ?>
			<?php if ($tr_quality ?? false): ?>
				<div class="col-12">&nbsp;</div>
				<div class="col-1">&nbsp;</div>
				<div class="col-11 row">
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['ffa'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['temperature'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['moist'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['dirt'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['kernel_pecah'] ?? '-'?></div>
					<div class="col-3"></div><div class="col-1"></div><div class="col-6 text-right"><?=$tr_quality['seal_number'] ?? '-'?></div>
				</div>
				<div class="col-12">&nbsp;</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="footer row">
		<?php $ttd = '...................................';?>
		<div class="col-3">
			<div class="signature-title"></div>
			<div class="signature">(<?=$tr_wb['driver_manual']. $tr_wb['name'] ?>)</div>
		</div>
		<div class="col-3">
			<div class="signature-title"></div>
			<div class="signature">(<?=$ttd?>)</div>
		</div>
		<div class="col-3">
			<div class="signature-title"></div>
			<div class="signature">(<?=$tr_wb['operator_out'] ?? $ttd?>)</div>
		</div>
		<div class="col-3">
			<div class="signature-title"></div>
			<div class="signature">(<?=$ttd?>)</div>
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