<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\KangCinHo\HelperUang;

class KamarController extends Controller
{
    public function dashboard(){
      $helperUang = new HelperUang();
      $helperTanggal = new HelperTanggal();
      $tanggalSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
      $bulanSekarang = \Carbon\Carbon::now()->month;
      $tahunSekarang = \Carbon\Carbon::now()->year;
      $dataKamarPerBulans = $this->getDataKamar($bulanSekarang, $tahunSekarang);

      foreach($dataKamarPerBulans as $dataKamarPerBulan){
        $dataKamarPerBulan->jumlahPasien = $helperUang->tambahkanTitik($dataKamarPerBulan->jumlahPasien);
        $dataKamarPerBulan->jumlahPasienYTD = $helperUang->tambahkanTitik($dataKamarPerBulan->jumlahPasienYTD);
      }

      $tanggalSekarang = $helperTanggal->tanggalRangeWithBulanTahun($tanggalSekarang);
      return view('menu.kamar.indexKamar', compact('dataKamarPerBulans', 'tanggalSekarang'));
    }

    public function getDataKamar($bulanSekarang, $tahunSekarang){
    	$dataListPoliPerbulanDBSanatas = \DB::connection('sqlsrv')
    	->table('SimTrKasir')
    	->selectRaw('SIMtrKasir.KelasID as kelasID, SIMmKelas.NamaKelas as namaKelas, COUNT( SIMtrKasir.KelasID) as jumlahPasien')
    	->join('SIMmKelas', 'SIMmKelas.KelasID','=', 'SIMtrKasir.KelasID')
    	->where('SIMtrKasir.Batal', 0)
      	->whereMonth('SIMtrKasir.Tanggal', $bulanSekarang)
    	->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
    	->where('SIMtrKasir.KelasID', '!=', 'xx')
    	->groupBy(['SIMmKelas.NamaKelas', 'SIMtrKasir.KelasID'])
    	->orderBy('jumlahPasien','desc')
    	->get();

      $querdataListPoliYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as kelasID, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienTotal) as jumlahPasien')
      ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
      ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
      ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
      ->where('mTahun.tahun', $tahunSekarang)
      ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
      ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
      ->get();

      foreach($dataListPoliPerbulanDBSanatas as $dataListPoliPerbulanDBSanata){
        $dataListPoliPerbulanDBSanata->namaKelas = ucwords(strtolower($dataListPoliPerbulanDBSanata->namaKelas));
        
        foreach( $querdataListPoliYTDs as  $querdataListPoliYTD){
          if($dataListPoliPerbulanDBSanata->kelasID != $querdataListPoliYTD->kelasID){
            continue;
          }
          $dataListPoliPerbulanDBSanata->jumlahPasienYTD = $querdataListPoliYTD->jumlahPasien + $dataListPoliPerbulanDBSanata->jumlahPasien;
          break;
        }
      }

    	return $dataListPoliPerbulanDBSanatas;
    }
}
