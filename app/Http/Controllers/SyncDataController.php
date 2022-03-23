<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\Tahun;
use App\RevenueTransaksi;
use App\Bulan;
use App\Kategori;
use App\RevenueTransaksiDetail;

class SyncDataController extends Controller
{
  public function syncData(){
    $this->syncDataSchedule();
    return redirect()->back();
  }

  public function syncDataSchedule(){
    $this->syncDataDashboard();
    $this->syncDataSection();
    $this->syncDataKamar();
    $this->syncDataPasienBaru();
    $this->syncDataPasienRepeater();
    $this->syncDataPaket();
  }

  public function syncDataPaket(){
    // select SIMtrMCUDetail.SectionID as sectionID, SUM(coalesce(SIMtrKasir.Nilai,0) + coalesce(SIMtrKasir.NilaiInvoiceGabung,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung2,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung3,0)) as nilai, COUNT(*) as jumlahPasien from SIMtrMCUDetail
    // inner join SIMtrMCU on SIMtrMCU.NoBukti = SIMtrMCUDetail.NoBukti
    // inner join SIMtrKasir on SIMtrKasir.NoReg = SIMtrMCU.RegNo
    // where SIMtrKasir.Batal = 0 
    // and SIMtrKasir.SectionPerawatanID = 'SECT0021'
    // and YEAR(SIMtrKasir.Tanggal) = 2019
    // and MONTH(SIMtrKasir.Tanggal) = 6
    // and SIMtrMCUDetail.SectionID in ('SEC004', 'SEC003')
    // group by SIMtrMCUDetail.SectionID

    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;
    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')->where('finishSync',0)->whereIn('idKategori',[55,56])->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;
      $dataSanata = '';

      //sync bulan dan tahun, untuk memperhemat akses ke databases
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }
      //SEC003 -> VK
      //SEC004 -> OK

      $dataSanata = \DB::connection('sqlsrv')
      ->table('SIMtrMCUDetail')
      ->selectRaw('SIMtrMCUDetail.SectionID as sectionID, SUM(coalesce(SIMtrKasir.Nilai,0) + coalesce(SIMtrKasir.NilaiInvoiceGabung,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung2,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung3,0)) as nilai, COUNT(*) as jumlah')
      ->join('SIMtrMCU', 'SIMtrMCU.NoBukti','SIMtrMCUDetail.NoBukti')
      ->join('SIMtrKasir', 'SIMtrKasir.NoReg','SIMtrMCU.RegNo')
      ->where('SIMtrKasir.Batal', '0')
      ->where('SIMtrKasir.SectionPerawatanID', 'SECT0021')
      ->whereYear('SIMtrKasir.Tanggal', $tahun)
      ->whereMonth('SIMtrKasir.Tanggal',  $bulan)
      ->where('SIMtrMCUDetail.SectionID',  $revenueDetailTransaksi->idSyncToSanata)
      ->groupBy('SIMtrMCUDetail.SectionID')
      ->get();



      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->nilai == null){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
        $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
      }
    }
  }

  public function syncDataDashboard(){
//     select (SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as nilai from TBJ_Transaksi_Detail
// inner join TBJ_Transaksi on TBJ_Transaksi.No_Bukti = TBJ_Transaksi_Detail.No_Bukti
// where year(Transaksi_Date) = 2019
// and MONTH(Transaksi_Date) = 4
// and Akun_ID IN ('2227','2391', '2535', '2405', '2406')
// group by Akun_ID
    
    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;
    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')->where('finishSync',0)->whereIn('idKategori',[58])->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;
      $dataSanata = '';
      //sync bulan dan tahun, untuk memperhemat akses ke databases
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }

      // if($revenueDetailTransaksi->idKategori == 49){
      //   //RAWAT JALAN
      //   $dataSanata = \DB::connection('sqlsrv')
      //   ->table('SIMtrKasir')
      //   ->selectRaw('SUM(coalesce(Nilai,0) + coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as nilai')
      //   ->where('Batal', '0')
      //   ->where('RJ','RJ')
      //   ->where('SectionPerawatanID','!=','SEC002')
      //   ->whereYear('Tanggal', $tahun)
      //   ->whereMonth('Tanggal',$bulan)
      //   ->get();       
      // }else if($revenueDetailTransaksi->idKategori == 50){
      //   //RAWAT INAP
      //   $dataSanata = \DB::connection('sqlsrv')
      //   ->table('SIMtrKasir')
      //   ->selectRaw('SUM(coalesce(Nilai,0) + coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as nilai')
      //   ->where('Batal', '0')
      //   ->where('RJ','RI')
      //   ->where('SectionPerawatanID','!=','SEC002')
      //   ->whereYear('Tanggal', $tahun)
      //   ->whereMonth('Tanggal',$bulan)
      //   ->get();
      // }else if($revenueDetailTransaksi->idKategori == 51){
      //   //RAWAT DARURAT
      //   $dataSanata = \DB::connection('sqlsrv')
      //   ->table('SIMtrKasir')
      //   ->selectRaw('SUM(coalesce(Nilai,0) + coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as nilai')
      //   ->where('Batal', '0')
      //   ->where('SectionPerawatanID','SEC002')
      //   ->whereYear('Tanggal', $tahun)
      //   ->whereMonth('Tanggal',$bulan)
      //   ->get();
      // }else if($revenueDetailTransaksi->idKategori == 52){
      //   //OBAT BEBAS
      //   $dataSanata = \DB::connection('sqlsrv')
      //   ->table('SIMtrPembayaranObatBebas')
      //   ->selectRaw('SUM(NilaiTransaksi) as nilai')
      //   ->where('Batal', '0')
      //   ->where('tipe', 'OBAT BEBAS')
      //   ->whereYear(\DB::raw('convert(date,Tanggal,111)'), $tahun)
      //   ->whereMonth(\DB::raw('convert(date,Tanggal,111)'),$bulan)
      //   ->get();
      // }else if($revenueDetailTransaksi->idKategori == 53){
      //   //TATA BOGA
      //   $dataSanata = \DB::connection('sqlsrv')
      //   ->table('TBO_Transaksi')
      //   ->selectRaw('SUM(totalnilai) as nilai')
      //   ->join('SIMtrPembayaranObatBebas', 'SIMtrPembayaranObatBebas.NoBuktiTataBoga', '=', 'TBO_Transaksi.NoBukti')
      //   ->where('SIMtrPembayaranObatBebas.Batal', '0')
      //   ->where('SIMtrPembayaranObatBebas.tipe', 'TATA BOGA')
      //   ->whereYear(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'), $tahun)
      //   ->whereMonth(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'),$bulan)
      //   ->get();
      // }else if($revenueDetailTransaksi->idKategori == 54){
      //   //DISKON
      //   $dataSanata = \DB::connection('sqlsrvbo')
      //   ->table('TBJ_Transaksi_Detail')
      //   ->selectRaw('(SUM(TBJ_Transaksi_Detail.Debit) - SUM(TBJ_Transaksi_Detail.Kredit)) as nilai')
      //   ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
      //   ->whereIn('Akun_ID', ['2234', '2235','2231','2502'])
      //   ->whereYear('Transaksi_Date', $tahun)
      //   ->whereMonth('Transaksi_Date',$bulan)
      //   ->get();
      // }else if($revenueDetailTransaksi->idKategori == 57){
      //   //TAMBAHAN LAINNYA
      //   $dataSanata = \DB::connection('sqlsrvbo')
      //   ->table('TBJ_Transaksi_Detail')
      //   ->selectRaw('(SUM(coalesce(TBJ_Transaksi_Detail.Kredit,0)) - SUM(coalesce(TBJ_Transaksi_Detail.Debit,0))) as nilai')
      //   ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
      //   ->whereIn('Akun_ID', ['2227','2391', '2535', '2405', '2406'])
      //   ->whereYear('Transaksi_Date', $tahun)
      //   ->whereMonth('Transaksi_Date',$bulan)
      //   ->get();
      // }
      
      if($revenueDetailTransaksi->idKategori == 58){
        $dataSanata = \DB::connection('sqlsrvbo')
        ->table('TBJ_Transaksi_Detail')
        ->selectRaw('(SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as nilai')
        ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        // ->whereIn('Akun_ID', \DB::selectRaw('select Akun_ID from Mst_Akun where Group_ID = 4 and Aktif = 1'))
        ->whereIn('Akun_ID', function($query){
          $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1);
        })
        ->whereYear('Transaksi_Date', $tahun)
        ->whereMonth('Transaksi_Date',$bulan)
        ->get();
      }
      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->nilai == null){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
        $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
      }
    }
  }

  public function syncDataSection(){

    //select SUM(Nilai-NilaiDiscount) as nilai, count(SIMtrRegistrasi.PasienBaru) as jumlah, PasienBaru from SIMtrKasir
    // inner join SIMtrRegistrasi on SIMtrRegistrasi.NoReg = SIMtrKasir.NoReg
    // where SIMtrKasir.batal = 0 and
    // SIMtrKasir.SectionPerawatanID = 'SEC002' and
    // YEAR(SIMtrKasir.Tanggal) = '2019' and
    // MONTH(SIMtrKasir.Tanggal) = '5'
    // group by SIMtrRegistrasi.PasienBaru

    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')->where('finishSync',0)->where('isSection', 1)->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;

      //sync bulan dan tahun, untuk memperhemat akses ke databases
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }

      $dataSanata = \DB::connection('sqlsrv')
      ->table('SIMtrKasir')
      ->selectRaw('SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as nilai, count(SIMtrRegistrasi.PasienBaru) as jumlah, SIMtrRegistrasi.PasienBaru as PasienBaru')
      ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
      ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
      ->whereYear('SIMtrKasir.Tanggal', $tahun)
      ->whereMonth('SIMtrKasir.Tanggal',$bulan)
      ->where('SIMtrKasir.batal', '0')
      ->where(function($q) use ($revenueDetailTransaksi) {
        $q->where('SIMtrRJ.SectionID',$revenueDetailTransaksi->idSyncToSanata)->orWhere('SIMtrKasir.SectionPerawatanID', $revenueDetailTransaksi->idSyncToSanata);
      })
      ->groupBy('SIMtrRegistrasi.PasienBaru')
      ->get();
      
      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->nilai == null and $dataSanata[0]->jumlah == 0){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
        $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
      }
    }
  }

  public function saveSyncDataSanata($dataSanata, $revenueDetailTransaksi){
    // echo $revenueDetailTransaksi->id. ' Tahun: '. $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun. ' Bulan: '. $revenueDetailTransaksi->revenueTransaksi->bulan->id .' Sec: '. $revenueDetailTransaksi->idSyncToSanata. ' jumlah Repeater: '. $dataSanata[0]->jumlah;
    // echo "<br/>";

    $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
    $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;

    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;
    $tanggalSekarang = \Carbon\Carbon::now()->day;

    $revenueDetailTransaksi = RevenueTransaksiDetail::where('id', $revenueDetailTransaksi->id)->firstOrFail();
    $revenueDetailTransaksi->isSync = 1;
    $revenueDetailTransaksi->lastSyncToSanata = now();
    
    if($revenueDetailTransaksi->isPasien == 1 OR $revenueDetailTransaksi->isPasienRepeater == 1){
      //pasien tidak ada nilai harga

    }else if($revenueDetailTransaksi->isKamar == 0){
      if( $dataSanata[0]->nilai  == null){
        $revenueDetailTransaksi->totalRevenue = 0;
      }else{
        if(isset($dataSanata[1]->nilai)){
          $revenueDetailTransaksi->totalRevenue = $dataSanata[0]->nilai + $dataSanata[1]->nilai;
        }else{
          $revenueDetailTransaksi->totalRevenue = $dataSanata[0]->nilai;
        }
      }
    }

    if(isset($dataSanata[0]->jumlah)){
      if($dataSanata[0]->jumlah == null){
        $revenueDetailTransaksi->jumlahPasienTotal = 0;
      }else{
        if(isset($dataSanata[1]->jumlah)){
          $revenueDetailTransaksi->jumlahPasienTotal = $dataSanata[0]->jumlah + $dataSanata[1]->jumlah;
          $revenueDetailTransaksi->jumlahPasienBaru = $dataSanata[1]->jumlah;
          $revenueDetailTransaksi->jumlahPasienRepeater = $dataSanata[0]->jumlah;
        }else{
          $revenueDetailTransaksi->jumlahPasienTotal = $dataSanata[0]->jumlah;
          $revenueDetailTransaksi->jumlahPasienRepeater = $dataSanata[0]->jumlah;
        }
      }
    }

    if($tahun < $tahunSekarang){
      $revenueDetailTransaksi->finishSync = 1;
    }else{
      if($bulan < $bulanSekarang){
        if(($bulanSekarang-1) == $bulan){
          if($tanggalSekarang >= 14){
            $revenueDetailTransaksi->finishSync = 1;
          }
        }else{
          $revenueDetailTransaksi->finishSync = 1;
        }
      }
    }

    $revenueDetailTransaksi->save();
  }

  public function syncDataKamar(){
    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')
    ->where('finishSync',0)
    ->where('isKamar', 1)
    ->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }
      //sync bulan dan tahun, untuk memperhemat akses ke databases

      $dataSanata = \DB::connection('sqlsrv')
      ->table('SIMtrKasir')
      ->selectRaw('SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as nilai, count(KelasID) as jumlah')
      ->where('Batal', '0')
      ->where('KelasID',$revenueDetailTransaksi->idSyncToSanata)
      ->whereYear('Tanggal', $tahun)
      ->whereMonth('Tanggal',$bulan)
      ->get();

      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->nilai == null and $dataSanata[0]->jumlah == 0){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
        $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
      }
    }
  }

  public function syncDataPasienBaru(){
    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')->where('finishSync',0)->where('isPasien', 1)->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }
      //sync bulan dan tahun, untuk memperhemat akses ke databases
      if($revenueDetailTransaksi->idSyncToSanata == 1){
        $revenueDetailTransaksi->idSyncToSanata = 'RI';
      }else{
        $revenueDetailTransaksi->idSyncToSanata = 'RJ';
      }

      $dataSanata = \DB::connection('sqlsrv')
      ->table('SIMtrKasir')
      ->selectRaw('COUNT(SIMtrRegistrasi.PasienBaru) as jumlah')
      ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', 'SIMtrKasir.NoReg')
      ->where('SIMtrKasir.Batal', '0')
      ->where('SIMtrRegistrasi.PasienBaru',1)
      ->whereYear('Tanggal', $tahun)
      ->whereMonth('Tanggal',$bulan)
      ->where('SIMtrKasir.RJ',$revenueDetailTransaksi->idSyncToSanata)
      ->get();

      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->jumlah == null){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
      }
      $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
    }
  }

  public function syncDataPasienRepeater(){
    $tahunSekarang = \Carbon\Carbon::now()->year;
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $revenueDetailTransaksis = RevenueTransaksiDetail::with('revenueTransaksi','revenueTransaksi.bulan', 'revenueTransaksi.revenue.tahun')->where('finishSync',0)->where('isPasienRepeater', 1)->get();

    foreach($revenueDetailTransaksis as $revenueDetailTransaksi){
      $bulan = $revenueDetailTransaksi->revenueTransaksi->bulan->id;
      $tahun = $revenueDetailTransaksi->revenueTransaksi->revenue->tahun->tahun;
      if($tahun < $tahunSekarang){

      }else{
        if($bulan <= $bulanSekarang){

        }else{
          continue;
        }
      }
      //sync bulan dan tahun, untuk memperhemat akses ke databases
      if($revenueDetailTransaksi->idSyncToSanata == 1){
        $revenueDetailTransaksi->idSyncToSanata = 'RI';
      }else{
        $revenueDetailTransaksi->idSyncToSanata = 'RJ';
      }

      $dataSanata = \DB::connection('sqlsrv')
      ->table('SIMtrKasir')
      ->selectRaw('COUNT(SIMtrRegistrasi.PasienBaru) as jumlah')
      ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', 'SIMtrKasir.NoReg')
      ->where('SIMtrKasir.Batal', '0')
      ->where('SIMtrRegistrasi.PasienBaru',0)
      ->whereYear('Tanggal', $tahun)
      ->whereMonth('Tanggal',$bulan)
      ->where('SIMtrKasir.RJ',$revenueDetailTransaksi->idSyncToSanata)
      ->get();   
      
      if(!$dataSanata->isEmpty()){
        if($dataSanata[0]->jumlah == null){
          //jika tidak ada nilai, lanjutkan proses perulangan
          continue;
        }
        $this->saveSyncDataSanata($dataSanata,$revenueDetailTransaksi);
      }
    }
  }
}
