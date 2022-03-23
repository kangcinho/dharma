<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;

class PasienController extends Controller
{
	public function dashboardPasien(){
		$bulanSekarang = \Carbon\Carbon::now()->month;
    	$tahunSekarang = \Carbon\Carbon::now()->year;
    	$tanggal = new HelperTanggal();
    	$tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
    	$tglSekarang = $tanggal->tanggalRangeWithBulanTahun($tglSekarang);
    	$dataPasienBaru = $this->dataPasienBaru($bulanSekarang, $tahunSekarang);
    	$dataPasienRepeater = $this->dataPasienRepeater($bulanSekarang, $tahunSekarang);
		return view('menu.dashboard.dashboardPasienAll', compact('dataPasienBaru','dataPasienRepeater', 'tglSekarang' ));
	}

	public function dataPasienBaru($bulanSekarang, $tahunSekarang){
	    return \DB::connection('sqlsrv')->table('SIMtrKasir')
	    ->selectRaw('count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
	    ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
	    ->where('SIMtrKasir.Batal', '0')
	    ->where('PasienBaru',1)
	    ->whereYear('Tanggal', $tahunSekarang)
	    ->whereMonth('Tanggal',$bulanSekarang)
	    ->get();
	  }

  public function dataPasienRepeater($bulanSekarang, $tahunSekarang){
    return \DB::connection('sqlsrv')->table('SIMtrKasir')
    ->selectRaw('count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
    ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
    ->where('SIMtrKasir.Batal', '0')
    ->where('PasienBaru',0)
    ->whereYear('Tanggal', $tahunSekarang)
    ->whereMonth('Tanggal',$bulanSekarang)
    ->get();
  }
}
