$(document).ready(function(){
	$('#idTahun').on('change', function(e){
		$('#minBatasMerah').focus();
	})

	$('#minBatasMerah').on('keyup', function(e){
		if (e.keyCode === 13) {
			e.preventDefault();
			$('#maxBatasMerah').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#maxBatasMerah').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#minBatasKuning').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#minBatasKuning').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#maxBatasKuning').focus();
		}		
		this.value = formatRupiah(this.value, '');
	})

	$('#maxBatasKuning').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#minBatasHijau').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#minBatasHijau').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#maxBatasHijau').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#maxBatasHijau').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetJanuari').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#budgetJanuari').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetFebruari').focus();
		}
		this.value = formatRupiah(this.value, '');
	})

	$('#budgetFebruari').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetMaret').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetMaret').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetApril').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetApril').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetMei').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetMei').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetJuni').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetJuni').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetJuli').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetJuli').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetAgustus').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetAgustus').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetSeptember').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetSeptember').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetOktober').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetOktober').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetNovember').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetNovember').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#budgetDesember').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
	$('#budgetDesember').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#targetRevenue').focus();
		}
		this.value = formatRupiah(this.value, '');
	})											
	$('#targetRevenue').on('keyup', function(e){
		if (e.keyCode === 13) {
			$('#simpanRevenue').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
})