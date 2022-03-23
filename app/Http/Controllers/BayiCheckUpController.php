<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;

class BayiCheckUpController extends Controller
{
    public function dashboard(){
    	return view('menu.bayiCheckUp.indexBayiCheckUp');
    }

    public function getDataBayi(Request $request){
    	$dataSanataBayi = \DB::connection('sqlsrv')->table('SimTrRegistrasi')
    	->selectRaw('CONVERT(date, TglReg, 111) as tglReg, NRM, NamaPasien_Reg as namaPasien')
    	->where('Batal', 0)
    	->where('TglReg', '>=', $request->firstPeriode)
    	->where('TglReg', '<=', $request->lastPeriode)
    	->where('PasienBaru', 1)
    	->where('UmurHr', '<=', 1)
    	->where('UmurBln', 0)
    	->where('UmurThn', 0)
    	->orderBy('SIMtrRegistrasi.TglReg')
    	->get();

    	$tgl = new HelperTanggal();

    	$dataCounting = $this->countData($dataSanataBayi);
      $bayiReCheckUp = $dataCounting->where('jumlahDatang','!=',1);

    	$tglAwal = $tgl->tanggalBaca($request->firstPeriode);
    	$tglAkhir = $tgl->tanggalBaca($request->lastPeriode);
    	foreach($dataCounting as $dataPasien){
    		$dataPasien->tglReg = $tgl->tanggalBaca($dataPasien->tglReg);
    	}
    	// dd($dataCounting);
    	return view('menu.bayiCheckUp.indexBayiCheckUp',compact('dataCounting','tglAwal','tglAkhir','bayiReCheckUp'));
    }

    public function countData($dataSanataBayi){
    	$dataTemp = collect();
    	$countData = count($dataSanataBayi);
    	for($i = 0 ; $i < $countData; $i++ ){
    		$dataTemp[$i] = collect();
    		$dataTemp[$i]->tglReg = '';
    		$dataTemp[$i]->NRM = '';
    		$dataTemp[$i]->namaPasien = '';
    		$dataTemp[$i]->jumlahDatang = 0;
    	}

    	for($i = 0; $i < $countData ; $i++){
            $tgl = new \Carbon\Carbon($dataSanataBayi[$i]->tglReg);
            $tglReg = $tgl->addDays(10);

	    	$bayiKedatangan = \DB::connection('sqlsrv')->table('SimTrRegistrasi')
	    	->selectRaw('NRM, COUNT(nrm) as jumlahDatang')
	    	->where('Batal',0)
            ->where('TglReg', '>=', $dataSanataBayi[$i]->tglReg)
	    	->where('TglReg', '<=', $tglReg)
	    	->where('NRM', $dataSanataBayi[$i]->NRM)
	    	->groupBy('SIMtrRegistrasi.NRM')
	    	->get();

	    	$dataTemp[$i]->tglReg = $dataSanataBayi[$i]->tglReg;
	    	$dataTemp[$i]->NRM = $dataSanataBayi[$i]->NRM;
	    	$dataTemp[$i]->namaPasien = $dataSanataBayi[$i]->namaPasien;
	    	$dataTemp[$i]->jumlahDatang = $bayiKedatangan[0]->jumlahDatang;
    	}
    	$dataTemp = $dataTemp->sortByDesc('jumlahDatang');
        // $dataTemp = $dataTemp->countBy('jumlahDatang');

    	// foreach($dataTemp as $key => $temp ){
    	// 	echo $key.' '.$temp.'<br/>';
    	// }
    	return $dataTemp;
    }
}
