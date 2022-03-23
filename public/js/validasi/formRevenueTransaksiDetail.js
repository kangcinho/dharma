$(document).ready(function(){
	$('#formRevenue').on('submit', function(e){
		// e.preventDefault();
		if($("#idKategori").val() == ""){
			// alert(1)
			return false;
		}
		if($("#totalRevenue").val() == ""){
			// alert(2)
			return false;
		}
		// alert('hello');
		return true;
	})
	
})
