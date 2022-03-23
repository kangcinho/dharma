$(document).ready(function(){
	$('#formRevenue').on('submit', function(e){
		// e.preventDefault();
		if($("#idTahun").val() == ""){
			// alert(1)
			return false;
		}
		if($("#minBatasMerah").val() == ""){
			// alert(2)
			return false;
		}
		if($("#maxBatasMerah").val() == ""){
			// alert(3)
			return false;
		}
		if($("#minBatasKuning").val() == ""){
			// alert(4)
			return false;
		}
		if($("#maxBatasKuning").val() == ""){
			// alert(5)
			return false;
		}
		if($("#minBatasHijau").val() == ""){
			// alert(6)
			return false;
		}
		if($("#maxBatasHijau").val() == ""){
			// alert(7)
			return false;
		}
		if($("#targetRevenue").val() == ""){
			// alert(8)
			return false;
		}
		// alert('hello');
		return true;
	})
	
})
