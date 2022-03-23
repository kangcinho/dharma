<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\KangCinHo\HelperRawatJalan;
use App\KangCinHo\HelperUang;
use App\KangCinHo\HelperTanggal;
use App\RevenueTransaksiDetail;

class RawatJalanController extends Controller
{
    public function dashboard(){
      $helperUang = new HelperUang();
      $helperTanggal = new HelperTanggal();
      $tanggalSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
      $bulanSekarang = \Carbon\Carbon::now()->month;
      $tahunSekarang = \Carbon\Carbon::now()->year;
      $dataListPoliPerharis = $this->getDataListPoliPerBulan($bulanSekarang, $tahunSekarang);
      // $this->getDataListPoliPerBulan($bulanSekarang, $tahunSekarang);
      // dd($this->getDataListPoliPerHari($bulanSekarang, $tahunSekarang));
      foreach($dataListPoliPerharis as $dataListPoliPerhari){
        $dataListPoliPerhari->totalRevenue = $helperUang->tambahkanTitik($dataListPoliPerhari->totalRevenue);
        $dataListPoliPerhari->totalRevenueYTD = $helperUang->tambahkanTitik($dataListPoliPerhari->totalRevenueYTD);
        $dataListPoliPerhari->jumlahPasien = $helperUang->tambahkanTitik($dataListPoliPerhari->jumlahPasien);
      	$dataListPoliPerhari->jumlahPasienYTD = $helperUang->tambahkanTitik($dataListPoliPerhari->jumlahPasienYTD);
      }

      $tanggalSekarang = $helperTanggal->tanggalRangeWithBulanTahun($tanggalSekarang);
      return view('menu.rawatJalan.indexRawatJalan', compact('dataListPoliPerharis', 'tanggalSekarang'));
    }

    public function getDataListPoliPerBulan($bulanSekarang, $tahunSekarang){
    	$dataListPoliPerbulanDBSanatas = \DB::connection('sqlsrv')
    	->table('SimTrKasir')
    	->selectRaw('SectionPerawatanID, SIMmSection.SectionName as namaSection, COUNT(SectionPerawatanID) as jumlahPasien, (SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - SUM(NilaiDiscount)) as totalRevenue')
    	->join('SIMmSection', 'SIMmSection.SectionID','=','SIMtrKasir.SectionPerawatanID')
    	->where('SIMtrKasir.Batal', 0)
      ->whereMonth('SIMtrKasir.Tanggal', $bulanSekarang)
    	->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
    	->where('SIMmSection.StatusAktif', 1)
    	->where('SIMtrKasir.RJ', 'RJ')
    	->groupBy(['SIMtrKasir.SectionPerawatanID', 'SIMmSection.SectionName'])
    	->orderBy('totalRevenue','desc')
    	->get();

      $querdataListPoliYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as SectionPerawatanID, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienTotal) as jumlahPasien')
      ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
      ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
      ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
      ->where('mTahun.tahun', $tahunSekarang)
      ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
      ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
      ->get();

      foreach($dataListPoliPerbulanDBSanatas as $dataListPoliPerbulanDBSanata){
        $dataListPoliPerbulanDBSanata->namaSection = ucwords(strtolower($dataListPoliPerbulanDBSanata->namaSection));
        
        foreach( $querdataListPoliYTDs as  $querdataListPoliYTD){
          if($dataListPoliPerbulanDBSanata->SectionPerawatanID != $querdataListPoliYTD->SectionPerawatanID){
            continue;
          }
          $dataListPoliPerbulanDBSanata->totalRevenueYTD = $querdataListPoliYTD->totalRevenue + $dataListPoliPerbulanDBSanata->totalRevenue;
          $dataListPoliPerbulanDBSanata->jumlahPasienYTD = $querdataListPoliYTD->jumlahPasien + $dataListPoliPerbulanDBSanata->jumlahPasien;
          break;
        }
      }

      // dd($dataListPoliPerbulanDBSanatas);
      //SELECT trRevenueTransaksiDetail.idSyncToSanata, SUM(trRevenueTransaksiDetail.totalRevenue), SUM(trRevenueTransaksiDetail.jumlahPasien) FROM trRevenueTransaksiDetail
      // INNER JOIN trRevenueTransaksi ON trRevenueTransaksi.id = trRevenueTransaksiDetail.idTrRevenueTransaksi
      // INNER JOIN trRevenue ON trRevenueTransaksi.idTrRevenue = trRevenue.id
      // INNER JOIN mTahun ON trRevenue.idTahun = mtahun.id
      // WHERE mTahun.tahun = '2019' AND
      // trRevenueTransaksi.idBulan <= 5
      // GROUP BY trRevenueTransaksiDetail.idSyncToSanata
    	return $dataListPoliPerbulanDBSanatas;
    }

    // public function getDataListPoliPerHari($bulanSekarang, $tahunSekarang){
    //   $query = \DB::connection('sqlsrv')
    //   ->table('SimTrKasir')
    //   ->selectRaw('SectionPerawatanID, SIMmSection.SectionName as namaSection, COUNT(SectionPerawatanID) as jumlahPasien, (SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - SUM(NilaiDiscount)) as totalRevenue')
    //   ->join('SIMmSection', 'SIMmSection.SectionID','=','SIMtrKasir.SectionPerawatanID')
    //   ->where('SIMtrKasir.Batal', 0)
    //   ->whereDay('SIMtrKasir.Tanggal', 23)
    //   ->whereMonth('SIMtrKasir.Tanggal', $bulanSekarang)
    //   ->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
    //   ->where('SIMmSection.StatusAktif', 1)
    //   ->where('SIMtrKasir.RJ', 'RJ')
    //   ->groupBy(['SIMtrKasir.SectionPerawatanID', 'SIMmSection.SectionName'])
    //   ->orderBy('totalRevenue','desc')
    //   ->get();
    //   return $query;
    // }
}
