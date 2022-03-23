$(document).ready(function(){
	$('#idKategori').on('change', function(e){
		$('#totalRevenue').focus();
	})

	$('#totalRevenue').on('keyup', function(e){
		if (e.keyCode === 13) {
			e.preventDefault();
			$('#simpanRevenueTransaksiDetail').focus();
		}
		this.value = formatRupiah(this.value, '');
	})
})