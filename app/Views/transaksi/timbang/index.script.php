<?php

use App\Controllers\Timbang;
use App\Models\ParameterValueModel;

$adjustWeight = (ParameterValueModel::getValue('ADJUSTWEIGHT') == 'Y');
?>
<script src="/assets/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script src="/assets/datatables.min.js"></script>
<script>
var tbs_external;
var reloadTransType = true;
var reloadUnit = true;
var isTimbang2 = false;
var isSetWeight = false;

$(document).ready(function() {

    $('.select2').select2();

    $('#form-wb').hide();
    $('#form-wb-external').hide();
	$('#NomorChitPre').hide();
	

	if ('<?= ($tr_wb['kab_type'] ?? '') ?>' == 'EXTERNAL') {
		tbs_external = false;
		setTbsExternal();
	} else {
		tbs_external = true;
		setNonTbsExternal();
	}

	$('#driver_kab').hide();
	$('#helper1_kab').hide();
	$('#helper2_kab').hide();



	//cekTimbangan();
	//testOutputTimbangan();
	


	$('select#timbang-ProductCode').change(function(){
		if (reloadTransType) {
			var produkCode = $('select#timbang-ProductCode').val();
			if (produkCode == '<?= Timbang::produkTBS['value'] ?>')	{
				$('#grading-button').removeAttr('disabled');
				$('#kualitas-button').attr('disabled', 'disabled');
			} else if ((produkCode == '<?= Timbang::produkCpoKode ?>') || (produkCode == '<?= Timbang::produkKernelKode ?>')) {
				$('#grading-button').attr('disabled', 'disabled');
				$('#kualitas-button').removeAttr('disabled');
			} else {
				$('#grading-button').removeAttr('disabled');
				$('#kualitas-button').removeAttr('disabled');
			}

			$.get('/timbang/transaction-code/' + produkCode, function(data){
				var transaksiSelect = $('select#timbang-TransactionType');
				transaksiSelect.empty();
				data.forEach(tr => {
					transaksiSelect.append($(
						'<option/>',
						{
							value: tr.transactioncode,
							text: tr.title
						}
					));
				});
				$('select#timbang-TransactionType').change();
			}).fail(function(jqXHR, textStatus, errorThrown){});
		}
	});

	/*$('select#timbang-CodeUnit').change(function(){
		var chitOnTrans = null;	
		if (reloadUnit) {
			var unitcodeSelect = $('select#timbang-CodeUnit').val();
			$.get('/timbang/transaction-transportermap/' + unitcodeSelect, function(data){
				var trasnporterSelect = $('select#timbang-TransporterCode');
				trasnporterSelect.empty();
				data.forEach(tr => {
					trasnporterSelect.append($(
						'<option/>',
						{
							value: tr.TransporterCode,
							text: tr.name
						}
					));
				});
				$('select#timbang-TransporterCode').change();
			}).fail(function(jqXHR, textStatus, errorThrown){});
			
			
		}		
	});*/
	$('select#timbang-CodeUnit').change(function(){
		
		if (reloadUnit) {
			var unitcodeSelect = $('select#timbang-CodeUnit').val();
			
			cekUnitOnTrans(unitcodeSelect);
			//alert('test')+unitcodeSelect;
			if (chitOnTrans!="-"){
				if (confirm('Kode Unit '+unitcodeSelect+' telah Timbang 1 Sebelumnya, Apakah Akan Timbang 2 sekarang?')) {
					// location.href =  '/timbang?id=' + chitOnTrans;
					getPending(chitOnTrans);
				}else{
					location.href =  '/timbang';
				}
				
			}else {
				//$.post('/timbang/transaction-transportermap/' + unitcodeSelect, 
				$.post(
					'/timbang/transaction-transportermap',
					{'unitcode':unitcodeSelect}
				).done(function(data){
				var trasnporterSelect = $('select#timbang-TransporterCode');
				trasnporterSelect.empty();
				data.forEach(tr => {
					trasnporterSelect.append($(
						'<option/>',
						{
							value: tr.TransporterCode,
							text: tr.name
						}
					));
				});
				$('select#timbang-TransporterCode').change();
				}).fail(function(jqXHR, textStatus, errorThrown){});
			}
		}		
	});
});

function getPending(chitOnTrans){
	reloadTransType=false;
	reloadUnit = false;
	$tambahBaru = false;
	isTimbang2 = false;
	var param = {'chitnumber':chitOnTrans};
	$.ajax({
		type: 'post',
		url:'/timbang/getPending',
		data:param
	}).done(function(response){
		if (response.status == 'success') {
			isTimbang2 = true;
			$('#btn_setwbin').prop('disabled', true);
			$('#btn_setwbout').prop('disabled', false);
			var trasnporterSelect = $('select#timbang-TransporterCode');
			console.log(response);
			// console.log(response.tr_wb.transportercode);
			/*trasnporterSelect.empty();
			response.transporters.forEach(tr => {
					trasnporterSelect.append($(
						'<option/>',
						{
							value: tr.TransporterCode,
							text: tr.name
						}
					));
				});*/
			// trasnporterSelect.val(response.tr_wb.transportercode);
			$('#timbang-kabraw').val(response.tr_wb.kabraw);
			$('input#timbang-NomorTicket').val(response.tr_wb.chitnumber);
			$('input#timbang-NomorChit').val(response.tr_wb.chitnumber);
			$('#timbang-kab_type').val(response.tr_wb.kab_type);
			$('select#timbang-CustomerCode').val(response.tr_wb.customercode);
			$('select#timbang-CustomerCode').change();

			if (response.tr_wb.kab_type == 'EXTERNAL'){
				setTbsExternal();
				$('input#timbang-NomorTicket').val(response.tr_wb.nomorticket);
				$('input#timbang-kab_type').val('EXTERNAL');
				
				$('select#timbang-ProductCode').append($('<option/>', response.tr_wb.productcode));

				$('select#timbang-ProductCode').val(response.tr_wb.productcode).change();

				$('select#timbang-TransactionType').append($('<option/>', response.tr_wb.transactiontype));
				$('select#timbang-TransactionType').val(response.tr_wb.transactiontype).change();

				$('input#timbang-GateIn').val(response.tr_wb.gate_in);
				$('input#timbang-GateOut').val(response.tr_wb.gate_out);
				$('input#timbang-BoardingIn').val(response.tr_wb.boarding_in);
				$('input#timbang-JenisUnit').val(response.tr_wb.jenis_unit);
				$('input#timbang-NomorPolisi').val(response.tr_wb.nomor_polisi);
				$('input#timbang-NamaDriver').val(response.tr_wb.nama_driver);
				$('input#timbang-KodeSupplier').val(response.tr_wb.kode_supplier);
				$('input#timbang-NamaSupplier').val(response.tr_wb.nama_supplier);
				$('input#timbang-WilayahAsalTBS').val(response.tr_wb.wilayah_asal_tbs);
				$('input#timbang-Kab_Prop').val(response.tr_wb.kab_prop);
				$('input#timbang-Kab_createdate').val(response.tr_wb.kab_createdate);
				$('input#timbang-Kab_createby').val(response.tr_wb.kab_createby);
				// $('input#timbang-supplier-group').val(response.tr_wb.supplier_group);
				// $('input#timbang-supplier-group-description').val(response.tr_wb.supplier_group_description);
				$('#timbang-kabraw').val(response.tr_wb.kabraw);
			}
			

			$('select#timbang-CodeUnit').val(response.tr_wb.unitcode);
			$('select#timbang-CodeUnit').change();
			$('select#timbang-TransporterCode').val(response.tr_wb.transportercode);
			$('select#timbang-TransporterCode').change();

			$('select#timbang-ProductCode').val(response.tr_wb.productcode);
			$('select#timbang-ProductCode').change();

			$('select#timbang-TransactionType').val(response.tr_wb.transactiontype);
			$('select#timbang-TransactionType').change();

			

			$('select#timbang-SiteCode').val(response.tr_wb.sitecode);
			$('select#timbang-SiteCode').change();

			
			
			$('#timbang-DriverManual').val(response.tr_wb.driver_manual);
			$('#keterangan').val(response.tr_wb.keterangan);
			$('#timbang-DriverManual').val(response.tr_wb.driver_manual);
			$('select#timbang-Driver').val(response.tr_wb.npk_driver);
			$('select#timbang-Helper1').val(response.tr_wb.npk_helper1);
			$('select#timbang-Helper2').val(response.tr_wb.npk_helper2);
			$('#timbang-SabNo').val(response.tr_wb.sabno);

			$('#timbang-TimeIn').val(response.tr_wb.wb_in);
			$('#timbang-WeightIn').val(response.tr_wb.weight_in);			
			$('#timbang-Netto').val(response.tr_wb.netto);
			var tbDataKABtbody = $('table#timbang-DataKAB tbody');
				tbDataKABtbody.empty();
				var totalJJG = 0;
				$.each(response.tr_kab, function(index, value){
					hidden = '<input name="noc['+ index +'][nocvalue]" value="'+ value.nocvalue +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocsite]" value="'+ value.nocsite +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocdate]" value="'+ value.nocdate +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][harvestdate]" value="'+ value.harvestdate +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocafd]" value="'+ value.nocafd +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocblock]" value="'+ value.nocblock +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][tgl_panen]" value="'+ value.tgl_panen +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][jjg]" value="'+ value.jjg +'" type="hidden" />';

					tr_string = '<tr><td>'+ (index + 1) +'</td><td>'+ value.nocafd +'</td><td>'+ value.nocblock +'</td><td>'+ value.jjg +'</td>'+ hidden +'</tr>';
					tbDataKABtbody.append(tr_string);
					totalJJG += parseInt(value.jjg);
				});
				$('#total-jjg').html(totalJJG);
				$('#timbang-jjg-ext').html(totalJJG);
			
		} else {
			$(document).Toasts('create', {
				class: 'bg-danger',
				title: 'Error',
				subtitle: response.status,
				body: response.messages,
				autohide: false,
				delay: 5000,
			});
			if (confirm('KARTU INI SUDAH SELESAI TIMBANG KELUAR !')) {
					location.href =  '/timbang';
				}else{
					location.href =  '/timbang';
				}
		}
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(errorThrown);	
	});

}

function cekUnitOnTrans(unitcodeSelect) {
	
	
	/*
	$.get('/timbang/transaction-transbyunit/' + unitcodeSelect, function(data){
		chitOnTrans = data;
	}).fail(function(jqXHR, textStatus, errorThrown){});
	*/
	$.ajax({
		url: '/timbang/transaction-transbyunit/' + unitcodeSelect,
		method: 'GET',
		async: false
	}).done(function(data) {
		chitOnTrans = data;	
		//alert (data);
	}).fail(function() {
		return "-";
	}).always(function() {
	});
	// return chitOnTrans;
};

function setNonTbsExternal(){
	if (tbs_external) {
		// html_form_wb = $('#form-wb').html();
		// $('#form-container').html(html_form_wb);

        $('#form-wb').show();
        $('#form-wb-external').hide();
	}
	tbs_external = false;
};

function setTbsExternal(){
	if (! tbs_external) {
		// html_form_wb = $('#form-wb-external').html();
		// $('#form-container').html(html_form_wb);

        $('#form-wb').hide();
        $('#UnitGroup').hide();
        $('#TransporterGroup').hide();
        $('#form-wb-external').show();
	}
	tbs_external = true;
};

function cekTimbangan() {
	$.ajax({
		url: '/timbang/weighRead',
		method: 'GET',
	}).done(function(msg) {
		//alert('Berat : ' + msg);
		$('#weight-current').val(msg.value);
		checkWeight(msg.value);
		setTimeout(function() {
			cekTimbangan();
		}, (msg.value == '#Err' ? 5000 : <?= ParameterValueModel::getValue('WBBROWSERREFRESH') ?? '2000' ?>))
	}).fail(function() {
		$('#weight-current').val('#Err');
		setTimeout(function() {
			cekTimbangan();
		}, 5000)
	}).always(function() {
	});
};

/*function cekTimbanganKosong() {
	$.ajax({
		url: '/timbang/weighRead',
		method: 'GET',
	}).done(function(msg) {
		//alert('Berat : ' + msg);
		$('#weight-current').val(msg.value);
		checkWeight(msg.value);
		setTimeout(function() {
			cekTimbangan();
		}, (msg.value == '#Err' ? 5000 : <?= ParameterValueModel::getValue('WBBROWSERREFRESH') ?? '2000' ?>))
	}).fail(function() {
		$('#weight-current').val('#Err');
		setTimeout(function() {
			cekTimbangan();
		}, 5000)
	}).always(function() {
	});
};*/

// $('#btn_setwbin').prop('disabled', false);
// $('#btn_setwbout').prop('disabled', false);
function cekWbKosong() {
	//$('#start-read-wb').prop('disabled', true);
	$('#btn_setwbin').prop('disabled', true);
	$('#btn_setwbout').prop('disabled', true);
	$.ajax({
		url: '/timbang/weighRead',
		method: 'GET',
	}).done(function(msg) {
		if(msg.value=='#Err'){
			alert('Periksa Koneksi Timbangan');
		}else if (msg.value=="0"){
		//else if (msg.value.includes("16")){
			//$('#start-read-wb').prop('disabled', true);
			cekTimbangan();
		}else if (msg.value!="0"){
		// else if (!msg.value.includes("16")){
			if (confirm('Timbangan Rit Sebelumnya belum Kosong ')) {
					cekWbKosong();
			}else{
				location.href =  '/timbang';
			}
			
		}
	}).fail(function() {
		alert('Periksa Koneksi Timbangan');
	}).always(function() {
	});
};

function testOutputTimbangan() {
	$.ajax({
		url: '/timbang/weighRead',
		method: 'GET',
	}).done(function(msg) {
		if(msg.value=="0"){
			cekTimbangan();
		}else if (msg.value=='#Err'){
			alert('Periksa Koneksi Timbangan');
		}else if (msg.value!="0"){
			$('#weight-current').val(msg.value);
			// alert('Timbangan Rit Sebelumnya belum Kosong ');
			if (confirm('Timbangan Rit Sebelumnya belum Kosong ')) {
				location.href =  '/timbang';
			}else{
				location.href =  '/timbang';
			}
			
		}
	}).fail(function() {
		alert('Periksa Koneksi Timbangan');
		// $('#weight-current').val('#Err');
		// setTimeout(function() {
		// 	cekTimbangan();
		// }, 5000)
	}).always(function() {
	});

	
};


/** Andre S Mangindaan, 26-10-2022 */

var arr = [];
//  $('#btn_setwbin').prop('disabled', true);
//  $('#btn_setwbout').prop('disabled', true);
function checkWeight(value, state){
	arr.unshift(value);
	if (arr.length == 3){
		arr.pop()
		const allEqual = arr => arr.every(val => val === arr[0]);
		var urls = window.location.href;
		if (allEqual(arr)){
			
			if (isTimbang2 === true) {
				if (isSetWeight === false){
					$('#btn_setwbout').prop('disabled', false);
				}
				$('#btn_setwbin').prop('disabled', true);
			} else {
				if (isSetWeight === false){
					$('#btn_setwbin').prop('disabled', false);
				}
				$('#btn_setwbout').prop('disabled', true);
			}
		} else {
			
			if (isTimbang2 === true) {
				$('#btn_setwbout').prop('disabled', true);
				$('#btn_setwbin').prop('disabled', true);
			} else {
				$('#btn_setwbin').prop('disabled', true);
				$('#btn_setwbout').prop('disabled', true);
			}
		}
	}
}

/** ============================================================== */

function set_wb_in(){
	var weightCurrent = $('input#weight-current').val();

	//alert(getServerTime());
	//$('input#timbang-TimeIn').val(now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds());
	$('input#timbang-TimeIn').val(getServerTime());
	
	if (weightCurrent !== '#Err' && weightCurrent.substring(0, 1)!=='0') {
		$('input#timbang-WeightIn').val(weightCurrent);
	}
	$('#btn_setwbin').prop('disabled', true);
	isSetWeight = true;
}

function getServerTime() {
	var currentDate;
	$.ajax({
		url: '/timbang/getCurrentDate',
		method: 'GET',
		async: false
	}).done(function(data) {
		currentDate = data;	
		//alert (data);
	}).fail(function() {
		return "";
	}).always(function() {
	});
	return currentDate;
};

function set_wb_out(){
	var weightCurrent = $('input#weight-current').val();
	var now = new Date();
	//$('input#timbang-TimeOut').val(now.getFullYear() + '-' + ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds());
	$('input#timbang-TimeOut').val(getServerTime());
	
	if (weightCurrent !== '#Err' && weightCurrent.substring(0, 1)!=='0') {
		$('input#timbang-WeightOut').val(weightCurrent);
	
		var weight_in = $('input#timbang-WeightIn').val();
		weight_out = parseInt(weightCurrent.replace('.', ''));
		weight_in = parseInt(weight_in.replace('.', ''));
		netto = Math.abs(weight_out - weight_in);
		$('input#timbang-Netto').val(netto);
	}
	$('#btn_setwbout').prop('disabled', true);
	isSetWeight = true;
}

$('input#timbang-WeightOut').on('input', function(){
	var weight_out = $('input#timbang-WeightOut').val();
	var weight_in = $('input#timbang-WeightIn').val();
	weight_out = parseInt(weight_out.replace('.', ''));
	weight_in = parseInt(weight_in.replace('.', ''));
	netto = Math.abs(weight_out - weight_in);
	$('input#timbang-Netto').val(netto);
});

$('#start-read-wb').on('click', function() {
	//cekTimbangan();
	// testOutputTimbangan();
	cekWbKosong();
});

$('#master_formModal').modal('hide');

$('#btn_getpending').on('click', function(){	
	
	var tbPending = $('table#pending-table').DataTable({
		retrieve: true,
		serverSide: true,
		ajax: {
			url: "/pending",
			type: 'post',
		},
		processing: true,
		order: [],
		lengthMenu: [
			[5, 50, 100],
			[5, 50, 100],
		],
		columnDefs: [
			{
				targets: -1,
				data: null,
				defaultContent: '<button class="btn btn-xs btn-success"><i class="fas fa-pencil-alt"></button>',
			}
		],
	});

	tbPending.on('click', 'tr', function () {
        var data = tbPending.row(this).data();
		$('#master_formModal').modal('hide');
		$('table#pending-table').dataTable();
        alert('Memanggil Transaksi : ' + data[0] + "'");
		getPending(data[0]);
    });

	$('#master_formModal').modal('show');

});

$('#get-NFC-button').on('click', function() {
	reloadTransType = false;
	reloadUnit = false;
	var $this = $(this);
	var $html = $this.html();
	//$this.prop('disabled', true);
	$this.hide();
	$('#nfcCancel-button').show();
	$this.html($this.data('loading-text'));
	$this.prop('data-loading-text', $html);
	$.ajax({
		url: '/timbang/nfcRead',
		method: 'GET',
	}).done(function(msg, textStatus, jqXHR){
		/** Log responseText */
		$('#nfc-data').append('<div class="console-text">' + jqXHR.responseText + '</div>');
		//alert('test'+'<?= ($tr_wb['status'] ?? '') ?>');
		if (msg.tipe == 'KAB External') {
			
			setTbsExternal();
			var sitecodeSelect = $('select#timbang-SiteCode');
			if (msg.chitnumber != '') {
					/*if (confirm('Apakah anda akan mengisi grading?')) {
						$('#grading_formModal').modal('show');
						return;
					}*/
				if (confirm('ID KAB telah direkam sebelumnya, akan WB Out sekarang?')) {
					//location.href =  '/timbang?id=' + msg.chitnumber;
					getPending(msg.chitnumber);
				}else{
					location.href =  '/timbang';
				}
			}
			
			$('input#timbang-NomorTicket').val(msg.nomorticket);
			$('input#timbang-kab_type').val('EXTERNAL');
			if ($('select#timbang-ProductCode option[value="'+ msg.produk.value +'"]').length <= 0){/** Cek Kode Produk tidak ada dalam option */
				$('select#timbang-ProductCode').append($('<option/>', msg.produk));
			}
			$('select#timbang-ProductCode').val(msg.produk.value).change();

			if ($('select#timbang-TransactionType option[value="'+ msg.transaksi.value +'"]').length <= 0){/** Cek Kode Transaksi tidak ada dalam option */
				$('select#timbang-TransactionType').append($('<option/>', msg.transaksi));
			}
			$('select#timbang-TransactionType').val(msg.transaksi.value).change();



			/**KAB PLASMA */
			if ($('select#timbang-SiteCode option[value="'+ msg.sitecode.value +'"]').length <= 0){/** Cek jenis transaksi tidak ada dalam option */
				$('select#timbang-SiteCode').append($('<option/>', msg.sitecode));
			}
			$('select#timbang-SiteCode').val(msg.sitecode.value).change();

			if ($('select#timbang-CustomerCode option[value="'+ msg.customercode.value +'"]').length <= 0){/** Cek customercode tidak ada dalam option */
				$('select#timbang-CustomerCode').append($('<option/>', msg.customercode));
			}
			$('select#timbang-CustomerCode').val(msg.customercode.value).change();
			/**END KAB PLASMA */

			$('input#timbang-GateIn').val(msg.gate_in);
			$('input#timbang-GateOut').val(msg.gate_out);
			$('input#timbang-BoardingIn').val(msg.boarding_in);
			$('input#timbang-JenisUnit').val(msg.jenis_unit);
			$('input#timbang-NomorPolisi').val(msg.nomor_polisi);
			$('input#timbang-NamaDriver').val(msg.nama_driver);
			$('input#timbang-KodeSupplier').val(msg.kode_supplier);
			$('input#timbang-NamaSupplier').val(msg.nama_supplier);
			$('input#timbang-WilayahAsalTBS').val(msg.wilayah_asal_tbs);
			$('input#timbang-Kab_Prop').val(msg.kab_prop);
			$('input#timbang-Kab_createdate').val(msg.kab_createdate);
			$('input#timbang-Kab_createby').val(msg.kab_createby);
			// $('input#timbang-supplier-group').val(msg.supplier_group);
			// $('input#timbang-supplier-group-description').val(msg.supplier_group_description);
			$('input#timbang-sitecode-plasma').val(msg.sitecode_plasma);
			$('input#timbang-afdeling-plasma').val(msg.afdeling_plasma);
			$('#timbang-kabraw').val(msg.kabraw);
			$('#timbang-jjg-ext').val(msg.jjg_ext);
			

			

		} else {
			setNonTbsExternal();
			var driverSelect = $('select#timbang-Driver');
			var helper1Select = $('select#timbang-Helper1');
			var helper2Select = $('select#timbang-Helper2');
			var unitSelect = $('select#timbang-CodeUnit');
			var transporterSelect = $('select#timbang-TransporterCode');
			var customerSelect = $('select#timbang-CustomerCode');
			var produkSelect = $('select#timbang-ProductCode');
			var transaksiSelect = $('select#timbang-TransactionType');
			var sitecodeSelect = $('select#timbang-SiteCode');
			var inputKabType = $('#timbang-kab_type')
			var kartuError = false;

			if (msg.tipe == 'SKU') { /** Cek is Employee ID Card */
				var employeId = msg.data.npk;
				var employeName = msg.data.name;

				var driver_id = driverSelect.val();
				if (driver_id == null || driver_id == '') {/** ID Driver masih kosong */
					if ($("select#timbang-Driver option[value='" + employeId + "']").length <= 0) { /** Cek ID Employe tidak ada dalam option */
						driverSelect.append($('<option/>', {
							value: employeId,
							text: employeName + ' - ' + employeId,
						}));					
					}
					driverSelect.val(employeId).change();
				} else { /** ID Driver telah ada, masukkan ke helper1 atau helper2 */
					var helper1_id = helper1Select.val();
					if (helper1_id == null || helper1_id == '') {/** ID Helper 1 masih kosong */
						if ($('select#timbang-Helper1 option[value="'+ employeId +'"]').length <= 0) { /** Cek ID Employee tidak ada di dalam option */
							helper1Select.append($('<option/>', {
								value: employeId,
								text: employeName + ' - ' + employeId,
							}));
						}
						helper1Select.val(employeId).change();
					} else {
						var helper2_id = helper2Select.val();
						if (helper2_id == null || helper2_id == '') {/** ID Helper 2 masih kosong */
							if ($('select#timbang-Helper2 option[value="'+ employeId +'"]').length <= 0) { /** Cek ID Employee tidak ada di dalam option */
								helper2Select.append($('<option/>', {
									value: employeId,
									text: employeName + ' - ' + employeId,
								}));
							}
							helper2Select.val(employeId).change();
						} else {
							alert('Driver, Helper1 dan Helper2 telah ada');
						}
					}
				}
			} else if (msg.tipe == 'ID_DT') {
				if ($('select#timbang-CodeUnit option[value="'+ msg.data.unit_id +'"]').length <= 0) {/** Cek ID Unit tidak ada dalam option */
					unitSelect.append($('<option/>', {
						value: msg.data.unit_id,
						text: msg.data.unit_id + ' - ' + msg.data.plat_no,
					}));
				}
				unitSelect.val(msg.data.unit_id).change();
				
				if ($('select#timbang-TransporterCode option[value="'+ msg.data.tran_id +'"]').length <= 0){/** Cek ID Transporter tidak ada dalam option */
					transporterSelect.append($('<option/>', {
						value: msg.data.tran_id,
						text: msg.data.tran_name,
					}));
				}
				transporterSelect.val(msg.data.tran_id).change();
			} else if (msg.tipe == 'KAB'){
				// if (msg.status!='1'){

				// }else
				if (msg.chitnumber != '') {
					if (confirm('ID KAB telah direkam sebelumnya, akan WB Out sekarang?')) {
						// location.href =  '/timbang?id=' + msg.chitnumber;
						getPending(msg.chitnumber);
					}else{
						location.href =  '/timbang';
					}
				}
				inputKabType.val(msg.kab_type);
				$('#kabcode').val(msg.kabcode);

				$('#timbang-kabraw').val(msg.kabraw);
				if ($('select#timbang-CustomerCode option[value="'+ msg.customer.value +'"]').length <= 0){/** Cek ID Customer tidak ada dalam option */
					customerSelect.append($('<option/>', msg.customer));
				}
				customerSelect.val(msg.customer.value).change();

				if ($('select#timbang-ProductCode option[value="'+ msg.produk.value +'"]').length <= 0){/** Cek Kode Produk tidak ada dalam option */
					produkSelect.append($('<option/>', msg.produk));
				}
				produkSelect.val(msg.produk.value).change();

				if ($('select#timbang-TransactionType option[value="'+ msg.transaksi.value +'"]').length <= 0){/** Cek Kode Transaksi tidak ada dalam option */
					transaksiSelect.append($('<option/>', msg.transaksi));
				}
				transaksiSelect.val(msg.transaksi.value).change();

				if ($('select#timbang-SiteCode option[value="'+ msg.sitecode.value +'"]').length <= 0) { /** Cek kode site tidak ada dalam option */
					sitecodeSelect.append($('<option/>', msg.sitecode));
				}
				sitecodeSelect.val(msg.sitecode.value).change();

				var tbDataKABtbody = $('table#timbang-DataKAB tbody');
				tbDataKABtbody.empty();
				var totalJJG = 0;
				$.each(msg.noc, function(index, value){
					hidden = '<input name="noc['+ index +'][nocvalue]" value="'+ value.nocvalue +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocsite]" value="'+ value.nocsite +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocdate]" value="'+ value.nocdate +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][harvestdate]" value="'+ value.harvestdate +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocafd]" value="'+ value.nocafd +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][nocblock]" value="'+ value.nocblock +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][tgl_panen]" value="'+ value.tgl_panen +'" type="hidden" />';
					hidden += '<input name="noc['+ index +'][jjg]" value="'+ value.jjg +'" type="hidden" />';

					tr_string = '<tr><td>'+ (index + 1) +'</td><td>'+ value.nocafd +'</td><td>'+ value.nocblock +'</td><td>'+ value.jjg +'</td>'+ hidden +'</tr>';
					tbDataKABtbody.append(tr_string);
					totalJJG += value.jjg;
				});
				$('#total-jjg').html(totalJJG);

				if (typeof msg.transporter !== 'undefined'){
					if ($('select#timbang-TransporterCode option[value="'+ msg.transporter.value +'"]').length <= 0){/** Cek ID Transporter tidak ada dalam option */
						transporterSelect.append($('<option/>', msg.transporter));
					}
					transporterSelect.val(msg.transporter.value).change();
				}
				if (typeof msg.unit !== 'undefined'){
					if ($('select#timbang-CodeUnit option[value="'+ msg.unit.value +'"]').length <= 0) {/** Cek ID Unit tidak ada dalam option */
						unitSelect.append($('<option/>', msg.unit));
					}
					unitSelect.val(msg.unit.value).change();
				}
				if (typeof msg.driver !== 'undefined'){
					if ($("select#timbang-Driver option[value='" + msg.driver.value + "']").length <= 0) { /** Cek ID Employe tidak ada dalam option */
						driverSelect.append($('<option/>', msg.driver));					
					}
					driverSelect.val(msg.driver.value).change();
				}
				if (typeof msg.helper1 !== 'undefined'){
					if ($("select#timbang-Helper1 option[value='" + msg.helper1.value + "']").length <= 0) { /** Cek ID Employe tidak ada dalam option */
						helper1Select.append($('<option/>', msg.helper1));					
					}
					helper1Select.val(msg.helper1.value).change();
				}
				if (typeof msg.helper2 !== 'undefined'){
					if ($("select#timbang-Helper2 option[value='" + msg.helper2.value + "']").length <= 0) { /** Cek ID Employe tidak ada dalam option */
						helper2Select.append($('<option/>', msg.helper2));					
					}
					helper2Select.val(msg.helper2.value).change();
				}
			} else if (msg.tipe == 'Canceled') {
				alert('Read cancelled');
				kartuError = true;
			} else if (msg.tipe == 'Unknown') {
				if (msg.invalid=='jam boarding'){
					alert('Kartu Tidak Lengkap!.\nAnda Belum Boarding');
				}else{
					alert('Kartu Tidak Lengkap atau Belum Melakukan Boarding!.\nSilahkan TAP Ulang kartu atau Buat KAB ulang');
					kartuError = true;
				}
				
			}

			setTimeout(function(){
				if ($('input#type_continous_tapping').prop('checked') 
				&& !kartuError
				&& (inputKabType.val() != 'BOARDING')
				&& (
					(driverSelect.val() == '') ||
					(helper1Select.val() == '') ||
					(helper2Select.val() == '') ||
					(unitSelect.val() == '') ||
					(transporterSelect.val() == '') ||
					(customerSelect.val() == '') ||
					(produkSelect.val() == '') || 
					(transaksiSelect.val() == '')
				)){
					$this.click();
				}
			}, 2000);

		}
	}).fail(function(msg){
		alert('NFC Reader disconected');
		//alert('Read NFC Fail : \n' + msg.responseJSON.message);
	}).always(function(){
		$this.data('loading-text', $this.html());
		$this.html($html);
		//$this.prop('disabled', false);
		$this.show();
		$('#nfcCancel-button').hide();
	});
});

$('#nfcCancel-button').on('click', function(){
	$.ajax({
		url: '/timbang/nfcCancel',
		method: 'GET'
	})
});

function timbang_save(btn){
	btn = $(btn)
	// btn.prop('disabled', true);
	if(parseInt($('input#timbang-WeightIn').val().replace('.', '')) == 0){
		alert('Berat timbang masuk (Weight In) belum diisi');
		return;
	}
	var complete = parseInt($('input#timbang-WeightOut').val().replace('.', '')) > 0;
	if(($('input#timbang-NomorChit').val() != '') && !complete){
		alert('Berat timbang keluar (Weight Out) belum diisi');
		return;
	}

	//complete dan berhasil simpan:
		//KAB Boarding, write wb_in - wb_out, weight_in - weight_out
		//Kab Non Boarding, format KAB
		//Print Nota Timbang

	var produkCode = $('select#timbang-ProductCode').val();
	if (complete > 0) {
		//complete dan produk == TBS, harus isi grading
		if ((produkCode == '<?= Timbang::produkTBS['value'] ?>') && 
		($('#grading-JumlahSampling').val() == '')) {
			if (confirm('Apakah anda akan mengisi grading?')) {
				$('#grading_formModal').modal('show');
				return;
			}
				
		//complete dan produk == CPO atau Kernel, opsi isi quality
		} else if (((produkCode == '<?= Timbang::produkCpoKode ?>') || (produkCode == '<?= Timbang::produkKernelKode ?>')) && 
		($('#kualitas-FFA').val() == '' && $('#kualitas-Temperature'))) {
			if (confirm('Apakah anda akan mengisi kualitas?')) {
				$('#kualitas_formModal').modal('show');
				return;
			}

		}
	}

	//proses simpan
	
	var data = $('form#timbang-form').serialize();
	$.post(
		'/timbang/save',
		data
	).done(function(msg, textStatus, jqXHR){
		if (msg.status == 'success') {
			//Berhasil simpan
			$(document).Toasts('create', {
				class: 'bg-success',
				title: 'Success',
				//subtitle: msg.status,
				body: msg.messages,
				autohide: true,
				delay: 1000,
			});

			if (complete) {
				<?php /*
				if (msg.saveAPI) {
					$.get('/timbang/save-api/' + msg.chitnumber)
					.done(function(msg, textStatus, jqXHR){
						$(document).Toasts('create', {
							class: 'bg-success',
							title: 'Success Post API',
							//subtitle: msg.status,
							//body: msg.messages,
							autohide: true,
							delay: 1000,
						});
					})
					.fail(function(jqXHR, textStatus, errorThrown){
						$(document).Toasts('create', {
							class: 'bg-danger',
							title: 'Error Post API',
							body: jqXHR.responseText,
							autohide: true,
							delay: 3000,
						});
					})
					.always(function(){});
				}
				*/ ?>
				boarding_type = $('#timbang-kab_type').val();
				

				if (boarding_type === 'BOARDING' || boarding_type === 'EXTERNAL') {					
					prepareWriteKAB(msg.chitnumber);
				} else {
					printNota(msg.chitnumber);
				}
			} else {
				setTimeout(function(){
					if (msg.new) {
						window.location.replace('/timbang');
					} else {
						window.location.href = '/pending';
					}
				}, 1500);
			}
		} else {
			$(document).Toasts('create', {
				class: 'bg-danger',
				title: 'Error',
				subtitle: msg.status,
				body: msg.messages,
				autohide: true,
				delay: 5000,
			});
		}
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(errorThrown);
	}).always(function(){
		btn.prop('disabled', false);
	});

	/*
	setInterval(function(){
		btn.prop('disabled', false);
	}, 3000);
	*/
}

function prepareWriteKAB(chitnumber){
	$('#writeKAB_formModal').modal('show');
	$('#write-chitnumber').val(chitnumber);
	$('#write-transaction-type').val($('#timbang-TransactionType').val());
	$('#write-kab_type').val($('#timbang-kab_type').val());
	$('#writeKAB_formModal .modal-footer .btn-success').prop('disabled', false);
}

function writeKAB(elementButton){
	btn = $(elementButton);
	btn.prop('disabled', true);
	chitnumber = $('#write-chitnumber').val()
	$.getJSON('/timbang/write-kab/' + chitnumber, function(data){
		if (data.status == 'success') {
			$(document).Toasts('create', {
				class: 'bg-success',
				title: 'Success',
				//subtitle: msg.status,
				body: data.messages,
				autohide: true,
				delay: 1000,
			});
			setTimeout(function(){
				//$('#writeKAB_formModal').modal('hide');
				printNota(chitnumber);
			}, 1200);
		} else {
			alert(data.messages);
			btn.prop('disabled', false);
		}
	}).fail(function(jqXHR, textStatus, errorThrown){
		alert(errorThrown.messages);
		btn.prop('disabled', false);
	});
}

function printNota(chitnumber, autoRedirect = true){
	var newWin = window.open('/nota/' + chitnumber, 'Print-Windows');
	//newWin.document.open('/nota/' + chitnumber);
	//newWin.print();
	newWin.document.close();
	setTimeout(function(){
		//newWin.close();
		if (autoRedirect) {
			setTimeout(function(){
				window.location.href = '/timbang';
			}, 1500);
		}
	}, 100);
}
</script>