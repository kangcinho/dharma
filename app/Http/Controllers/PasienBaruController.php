<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Tahun;
use App\KangCinHo\HelperTanggal;
use App\KangCinHo\HelperUang;
class PasienBaruController extends Controller
{
    public function dashboard(){
      $helperTanggal = new HelperTanggal();
      $helperUang = new HelperUang();
      $tanggalSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
      $bulanSekarang = \Carbon\Carbon::now()->month;
      $tahunSekarang = \Carbon\Carbon::now()->year;
      $dataListPasienBarus = $this->getDataPasienBaru($bulanSekarang, $tahunSekarang);
      // dd($this->getDataPasienBaru($bulanSekarang, $tahunSekarang));

      foreach($dataListPasienBarus as $dataListPasienBaru){
        $dataListPasienBaru->jumlahPasien = $helperUang->tambahkanTitik($dataListPasienBaru->jumlahPasien);
        $dataListPasienBaru->jumlahPasienYTD = $helperUang->tambahkanTitik($dataListPasienBaru->jumlahPasienYTD);
      }

      $tanggalSekarang = $helperTanggal->tanggalRangeWithBulanTahun($tanggalSekarang);
      return view('menu.pasienBaru.indexPasienBaru', compact('dataListPasienBarus', 'tanggalSekarang'));
    }

    public function getDataPasienBaru($bulanSekarang, $tahunSekarang){
    	// $query = \DB::connection('sqlsrv')
    	// ->table('SIMtrRegistrasi')
    	// ->selectRaw('RawatInap, count(pasienBaru) as jumlahPasien')
    	// ->where('SIMtrRegistrasi.Batal', 0)
    	// ->where('PasienBaru', 1)
     //    ->whereMonth('SIMtrRegistrasi.TglReg', $bulanSekarang)
    	// ->whereYear('SIMtrRegistrasi.TglReg', $tahunSekarang)
    	// ->groupBy('SIMtrRegistrasi.RawatInap')
    	// ->get();

    $dataListPasienBulananDBSanatas = \DB::connection('sqlsrv')->table('SIMtrKasir')
      ->selectRaw('SIMtrKasir.RJ as RawatInap, count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
      ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
      ->where('SIMtrKasir.Batal', '0')
      ->where('SIMtrRegistrasi.PasienBaru',1)
      ->whereYear('Tanggal', $tahunSekarang)
      ->whereMonth('Tanggal',$bulanSekarang)
      ->groupBy('SIMtrKasir.RJ')
      ->get();

    $dataListPasienYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as RawatInap, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienTotal) as jumlahPasien')
      ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
      ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
      ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
      ->where('mTahun.tahun', $tahunSekarang)
      ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
      ->where('trRevenueTransaksiDetail.isPasien', 1)
      ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
      ->get();

      foreach($dataListPasienBulananDBSanatas as $dataListPasienBulananDBSanata){   
        foreach( $dataListPasienYTDs as  $dataListPasienYTD){
          if($dataListPasienBulananDBSanata->RawatInap == 'RI' && $dataListPasienYTD->RawatInap == 1){
            $dataListPasienBulananDBSanata->jumlahPasienYTD = $dataListPasienYTD->jumlahPasien + $dataListPasienBulananDBSanata->jumlahPasien;
            break;
          }

          if($dataListPasienBulananDBSanata->RawatInap == 'RJ' && $dataListPasienYTD->RawatInap == 0){
            $dataListPasienBulananDBSanata->jumlahPasienYTD = $dataListPasienYTD->jumlahPasien + $dataListPasienBulananDBSanata->jumlahPasien;
            break;
          }
        }
      }

    	return $dataListPasienBulananDBSanatas;
    }
}
