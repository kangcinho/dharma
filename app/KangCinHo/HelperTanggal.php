<?php

namespace App\KangCinHo;
use App\RevenueTransaksiDetail;

class HelperTanggal{
 	public function tanggalBaca($tglRobot){
 		$tglAkanOlah = explode(" ",$tglRobot)[0];
 		$pecahTanggal = explode("-",$tglAkanOlah);

 		switch($pecahTanggal[1]){
 			case "01": $pecahTanggal[1] = 'Januari' ; break;
 			case "02": $pecahTanggal[1] = 'Februari' ; break;
 			case "03": $pecahTanggal[1] = 'Maret' ; break;
 			case "04": $pecahTanggal[1] = 'April' ; break;
 			case "05": $pecahTanggal[1] = 'Mei' ; break;
 			case "06": $pecahTanggal[1] = 'Juni' ; break;
 			case "07": $pecahTanggal[1] = 'Juli' ; break;
 			case "08": $pecahTanggal[1] = 'Agustus' ; break;
 			case "09": $pecahTanggal[1] = 'September' ; break;
 			case "10": $pecahTanggal[1] = 'Oktober' ; break;
 			case "11": $pecahTanggal[1] = 'November' ; break;
 			case "12": $pecahTanggal[1] = 'Desember' ; break;
 		}

 		return $pecahTanggal[2]." ".$pecahTanggal[1]." ".$pecahTanggal[0];
 	}

 	public function tanggalBacaWithWaktu($tglRobot){
 		$tglAkanOlah = explode(" ",$tglRobot);
 		return $this->tanggalBaca($tglRobot).' '.$tglAkanOlah[1];
 	}

 	public function tanggalBacaBulanTahun($tglRobot){
 		$tglAkanOlah = explode(" ",$tglRobot)[0];
 		$pecahTanggal = explode("-",$tglAkanOlah);

 		switch($pecahTanggal[1]){
 			case "01": $pecahTanggal[1] = 'Januari' ; break;
 			case "02": $pecahTanggal[1] = 'Februari' ; break;
 			case "03": $pecahTanggal[1] = 'Maret' ; break;
 			case "04": $pecahTanggal[1] = 'April' ; break;
 			case "05": $pecahTanggal[1] = 'Mei' ; break;
 			case "06": $pecahTanggal[1] = 'Juni' ; break;
 			case "07": $pecahTanggal[1] = 'Juli' ; break;
 			case "08": $pecahTanggal[1] = 'Agustus' ; break;
 			case "09": $pecahTanggal[1] = 'September' ; break;
 			case "10": $pecahTanggal[1] = 'Oktober' ; break;
 			case "11": $pecahTanggal[1] = 'November' ; break;
 			case "12": $pecahTanggal[1] = 'Desember' ; break;
 		}
 		return $pecahTanggal[1]." ".$pecahTanggal[0];
 	}
	public function getLastUpdate(){
		return $this->tanggalBacaWithWaktu(RevenueTransaksiDetail::selectRaw('MAX(updated_at) as lastUpdate')->first()->lastUpdate);
	}

	public function bacaBulan($bulan){
		switch($bulan){
 			case "1": $bulan = 'Januari' ; break;
 			case "2": $bulan = 'Februari' ; break;
 			case "3": $bulan = 'Maret' ; break;
 			case "4": $bulan = 'April' ; break;
 			case "5": $bulan = 'Mei' ; break;
 			case "6": $bulan = 'Juni' ; break;
 			case "7": $bulan = 'Juli' ; break;
 			case "8": $bulan = 'Agustus' ; break;
 			case "9": $bulan = 'September' ; break;
 			case "10": $bulan = 'Oktober' ; break;
 			case "11": $bulan = 'November' ; break;
 			case "12": $bulan = 'Desember' ; break;
 		}
 		return $bulan;
	}

	public function bacaBulanSingkatan($bulan){
		switch($bulan){
 			case "1": $bulan = 'Jan' ; break;
 			case "2": $bulan = 'Feb' ; break;
 			case "3": $bulan = 'Mar' ; break;
 			case "4": $bulan = 'Apr' ; break;
 			case "5": $bulan = 'Mei' ; break;
 			case "6": $bulan = 'Jun' ; break;
 			case "7": $bulan = 'Jul' ; break;
 			case "8": $bulan = 'Aug' ; break;
 			case "9": $bulan = 'Sep' ; break;
 			case "10": $bulan = 'Okt' ; break;
 			case "11": $bulan = 'Nov' ; break;
 			case "12": $bulan = 'Des' ; break;
 		}
 		return $bulan;
	}

	public function tanggalRangeWithBulanTahun($tglRobot){
		$tglAkanOlah = explode(" ",$tglRobot)[0];
 		$pecahTanggal = explode("-",$tglAkanOlah);
 		$bulanSekarang = $this->bacaBulan($pecahTanggal[1]);
 		if($pecahTanggal[2] == '1'){
 			return '1 '.$bulanSekarang.' '.$pecahTanggal[0];
 		}
 		return '1-'.$pecahTanggal[2].' '.$bulanSekarang.' '.$pecahTanggal[0];
	}
}
