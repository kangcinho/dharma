<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;

class PaketPerawatanController extends Controller
{
    public function dashboardPaket(){
    	return view('menu.paketPersalinan.indexPaketPersalinan');
    }

	public function getPaketPersalinan(Request $request){
		// $dataPaketPersalinan = \DB::connection('sqlsrv')->table('SIMtrRegistrasi')
		// ->selectRaw('PaketJasaID, SIMmListJasa.JasaName, COUNT(PaketJasaID) as jumlahPasien')
		// ->join('SIMmListJasa','SIMtrRegistrasi.PaketJasaID', 'SIMmListJasa.JasaID')
		// ->whereMonth('TglReg', 6)
		// ->whereYear('TglReg', 2019)
		// ->where('PaketJasaID', '!=' , '')
		// ->where('batal' , 0)
		// ->where('SIMtrRegistrasi.PaketBayiTabung', 0)
		// ->groupBy(['PaketJasaID', 'SIMmListJasa.JasaName'])
		// ->orderBy('jumlahPasien', 'desc')
		// ->get();
		$tglFirstBulanLalu = '';
		$tglSecondBulanLalu = '';
		
		$bulanFirstSet = (new \Carbon\Carbon($request->firstPeriode))->month;
		$bulanSecondSet = (new \Carbon\Carbon($request->lastPeriode))->month;

		if( $bulanFirstSet == 1){
			$tglFirstBulanLalu = $request->firstPeriode;
		}else{
			$tglFirstBulanLalu = (new \Carbon\Carbon($request->firstPeriode))->subMonth();
		}

		if( $bulanSecondSet == 1){
			$tglSecondBulanLalu = $request->lastPeriode;
		}else{
			$tglSecondBulanLalu = (new \Carbon\Carbon($request->lastPeriode))->subMonth();
		}

		$dataCounting = \DB::connection('sqlsrv')->table('SIMtrRegistrasi')
		->selectRaw('PaketJasaID, SIMmListJasa.JasaName as namaJasa, COUNT(PaketJasaID) as jumlahPasien')
		->join('SIMmListJasa','SIMtrRegistrasi.PaketJasaID', 'SIMmListJasa.JasaID')
		->where('TglReg','>=', $request->firstPeriode)
		->where('TglReg','<=',$request->lastPeriode)
		->where('PaketJasaID', '!=' , '')
		->where('batal' , 0)
		->where('SIMtrRegistrasi.PaketBayiTabung', 0)
		->groupBy(['PaketJasaID', 'SIMmListJasa.JasaName'])
		->orderBy('jumlahPasien', 'desc')
		->get();

		$listDataCounting = [];

		foreach($dataCounting as $dataPaket){
			$listDataCounting[] = $dataPaket->PaketJasaID;
		}
		
		$dataCountingBulanLalu = \DB::connection('sqlsrv')->table('SIMtrRegistrasi')
		->selectRaw('PaketJasaID, SIMmListJasa.JasaName as namaJasa, COUNT(PaketJasaID) as jumlahPasien')
		->join('SIMmListJasa','SIMtrRegistrasi.PaketJasaID', 'SIMmListJasa.JasaID')
		->where('TglReg','>=', $tglFirstBulanLalu)
		->where('TglReg','<=',$tglSecondBulanLalu)
		->whereIn('PaketJasaID',$listDataCounting)
		->where('batal' , 0)
		->where('SIMtrRegistrasi.PaketBayiTabung', 0)
		->groupBy(['PaketJasaID', 'SIMmListJasa.JasaName'])
		->orderBy('jumlahPasien', 'desc')
		->get();
		
		foreach($dataCounting as $dataPaketBulanIni){
			$dataPaketBulanIni->jumlahPasienBulanLalu = 0;
			foreach($dataCountingBulanLalu as $dataPaketBulanLalu){
				if($dataPaketBulanIni->PaketJasaID == $dataPaketBulanLalu->PaketJasaID){
					$dataPaketBulanIni->jumlahPasienBulanLalu = $dataPaketBulanLalu->jumlahPasien;
					break;
				}
			}
		}				
		$tgl = new HelperTanggal();

    	$tglAwal = $tgl->tanggalBaca($request->firstPeriode);
    	$tglAkhir = $tgl->tanggalBaca($request->lastPeriode);
    	$tglAwalBulanLalu = $tgl->tanggalBaca($tglFirstBulanLalu);
    	$tglAkhirBulanLalu = $tgl->tanggalBaca($tglSecondBulanLalu);

    	return view('menu.paketPersalinan.indexPaketPersalinan', compact('dataCounting','tglAwal', 'tglAkhir', 'tglAwalBulanLalu', 'tglAkhirBulanLalu'));
	}
}
