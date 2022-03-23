<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Tahun;
use App\KangCinHo\HelperTanggal;
use Carbon\Carbon;
use App\KangCinHo\HelperBalancingData;
use App\KangCinHo\HelperUang;
use App\Kategori;

class PaketPersalinanController extends Controller
{
    public function dashboard(){	

  		$helperUang = new HelperUang();
  		$helperTanggal = new HelperTanggal();
  		$tanggalSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
  		$bulanSekarang = \Carbon\Carbon::now()->month;
  		$tahunSekarang = \Carbon\Carbon::now()->year;

    	$dataPaketPersalinans = $this->getDataPaketPersalinan($bulanSekarang, $tahunSekarang);
    	
    	foreach($dataPaketPersalinans as $dataPaketPersalinan){
	      	$dataPaketPersalinan->totalRevenue = $helperUang->tambahkanTitik($dataPaketPersalinan->totalRevenue);
	        $dataPaketPersalinan->totalRevenueYTD = $helperUang->tambahkanTitik($dataPaketPersalinan->totalRevenueYTD);
	        $dataPaketPersalinan->jumlahPasien = $helperUang->tambahkanTitik($dataPaketPersalinan->jumlahPasien);
	        $dataPaketPersalinan->jumlahPasienYTD = $helperUang->tambahkanTitik($dataPaketPersalinan->jumlahPasienYTD);
	    }

  		$tanggalSekarang = $helperTanggal->tanggalRangeWithBulanTahun($tanggalSekarang);
  		return view('menu.paketPersalinan.paketPersalinanDetail.indexPaketPersalinanDetail', compact('dataPaketPersalinans', 'tanggalSekarang'));
    }

    public function getDataPaketPersalinan($bulanSekarang, $tahunSekarang){

      $dataSanatas = \DB::connection('sqlsrv')
      ->table('SIMtrMCUDetail')
      ->selectRaw('SIMmSection.SectionName as namaSection, SIMtrMCUDetail.SectionID as SectionPerawatanID, SUM(coalesce(SIMtrKasir.Nilai,0) + coalesce(SIMtrKasir.NilaiInvoiceGabung,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung2,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung3,0)) as totalRevenue, COUNT(*) as jumlahPasien')
      ->join('SIMtrMCU', 'SIMtrMCU.NoBukti','SIMtrMCUDetail.NoBukti')
      ->join('SIMtrKasir', 'SIMtrKasir.NoReg','SIMtrMCU.RegNo')
      ->join('SIMmSection', 'SIMmSection.SectionID', 'SIMtrMCUDetail.SectionID')
      ->where('SIMtrKasir.Batal', '0')
      ->where('SIMtrKasir.SectionPerawatanID', 'SECT0021')
      ->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
      ->whereMonth('SIMtrKasir.Tanggal',  $bulanSekarang)
      ->whereIn('SIMtrMCUDetail.SectionID',['SEC003','SEC004'])
      ->groupBy(['SIMtrMCUDetail.SectionID','SIMmSection.SectionName'])
      ->get();

      $querdataListPoliYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as SectionPerawatanID, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienTotal) as jumlahPasien')
      ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
      ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
      ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
      ->where('mTahun.tahun', $tahunSekarang)
      ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
      ->where('trRevenueTransaksiDetail.isPaket', 1)
      ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
      ->get();

      foreach($dataSanatas as $dataSanata){
        $dataSanata->namaSection = ucwords(strtolower($dataSanata->namaSection));
        
        foreach( $querdataListPoliYTDs as  $querdataListPoliYTD){
          if($dataSanata->SectionPerawatanID != $querdataListPoliYTD->SectionPerawatanID){
            continue;
          }
          $dataSanata->totalRevenueYTD = $querdataListPoliYTD->totalRevenue + $dataSanata->totalRevenue;
          $dataSanata->jumlahPasienYTD = $querdataListPoliYTD->jumlahPasien + $dataSanata->jumlahPasien;
          break;
        }
      }

      return $dataSanatas;
    }

    public function paketPersalinanYTD($sectionPerawatanID){
  		$tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

        $tahunX = $this->jumlahPasienPerSection($sectionPerawatanID, $tahunAktif);
  		$tahunX_1 = $this->jumlahPasienPerSection($sectionPerawatanID, $tahunAktif-1);
  		$tahunX_2 = $this->jumlahPasienPerSection($sectionPerawatanID, $tahunAktif-2);

  		$this->chartJumlahPasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $sectionPerawatanID, $tahunAktif);
  		$this->chartRevenuePasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $sectionPerawatanID, $tahunAktif);
    	return view('menu.paketPersalinan.paketPersalinanDetail.paketPersalinanDetailPerSection.indexPaketPersalinanDetailPerSection', compact('tahunX','lastUpdate','idSectionSanata'));
    }

    public function jumlahPasienPerSection($sectionPerawatanID, $tahunAktif){
        $dataPaketYTD = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienRepeater AS jumlahPasien ')
        ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
        ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
        ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
        ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
        ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
        ->where('trRevenueTransaksiDetail.idSyncToSanata',$sectionPerawatanID)
        ->where('trRevenueTransaksiDetail.isPaket', 1)
        ->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataPaketYTD;
    }

    public function chartJumlahPasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif){

    	$lineChart = \Lava::DataTable();
        $lineChart->addStringColumn('Date');
        for($i = ($tahunAktif-2) ; $i <= $tahunAktif; $i++){
          $lineChart->addNumberColumn(strval($i));
        }
        $lineChart->addRoleColumn('string', 'style');
        $bulanSekarang = \Carbon\Carbon::now()->month;

        for($i = 0 ; $i < 12 ; $i++){
            if($tahunX[$i]->jumlahPasien == 0){
                $tahunX[$i]->jumlahPasien = null;
            }
            if($i == $bulanSekarang-1){
                $color = 'color:grey';
            }else{
                $color = '';
            }
            $lineChart->addRow([
                $tahunX[$i]->bulanSingkatan,
                $tahunX_2[$i]->jumlahPasien,
                $tahunX_1[$i]->jumlahPasien,
                $tahunX[$i]->jumlahPasien,
                $color
            ]);
        }

        \Lava::LineChart('jumlahPasienPaket', $lineChart, [
            'title' => 'Jumlah Pasien Paket Persalinan '.$tahunX[0]->namaKategori
        ]);
    }
    public function chartRevenuePasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif){

    	$lineChart = \Lava::DataTable();
        $lineChart->addStringColumn('Date');
        for($i = ($tahunAktif-2) ; $i <= $tahunAktif; $i++){
          $lineChart->addNumberColumn(strval($i));
        }
        $lineChart->addRoleColumn('string', 'style');
        $bulanSekarang = \Carbon\Carbon::now()->month;

        for($i = 0 ; $i < 12 ; $i++){
            if($tahunX[$i]->totalRevenue == 0){
                $tahunX[$i]->totalRevenue = null;
            }
            if($i == $bulanSekarang-1){
                $color = 'color:grey';
            }else{
                $color = '';
            }
            $lineChart->addRow([
                $tahunX[$i]->bulanSingkatan,
                $tahunX_2[$i]->totalRevenue,
                $tahunX_1[$i]->totalRevenue,
                $tahunX[$i]->totalRevenue,
                $color
            ]);
        }

        \Lava::LineChart('revenuePasienPaket', $lineChart, [
            'title' => 'Revenue Paket Persalinan '.$tahunX[0]->namaKategori
        ]);
    }

    public function dashboardPaketPersalinanMTD($idSectionSanata){
		$bulanSekarang = \Carbon\Carbon::now()->month;
		$tahunSekarang = \Carbon\Carbon::now()->year;

		$tgl = new HelperTanggal();
		$tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
		$tglSekarang = $tgl->tanggalBacaBulanTahun($tglSekarang);

		$kategori = Kategori::where('idSyncToSanata', $idSectionSanata)->first();
      	$kategori->namaKategori = ucwords(strtolower($kategori->namaKategori));

      	$dataPaketPersalinans = $this->paketPersalinan($tahunSekarang, $bulanSekarang, $idSectionSanata);
	    $dataPaketPersalinans_1 = $this->paketPersalinan($tahunSekarang-1, $bulanSekarang, $idSectionSanata);
		$dataPaketPersalinans_2 = $this->paketPersalinan($tahunSekarang-2, $bulanSekarang, $idSectionSanata);
          
        $this->chartRevenuePasienPerSectionMTD($dataPaketPersalinans, $dataPaketPersalinans_1, $dataPaketPersalinans_2, $tglSekarang);
	    $this->chartJumlahPasienPerSectionMTD($dataPaketPersalinans, $dataPaketPersalinans_1, $dataPaketPersalinans_2, $tglSekarang);

	    return view('menu.paketPersalinan.paketPersalinanDetail.paketPersalinanDetailPerSection.indexPaketPersalinanDetailDailyPerSection', compact('tglSekarang','kategori'));
    }

    public function paketPersalinan($tahunSekarang, $bulanSekarang, $idSectionSanata){
        $dataPaketPersalinan = \DB::connection('sqlsrv')
	      ->table('SIMtrMCUDetail')
	      ->selectRaw('DAY(SIMtrKasir.Tanggal) as tanggal,SIMmSection.SectionName as namaSection, SIMtrMCUDetail.SectionID as SectionPerawatanID, SUM(coalesce(SIMtrKasir.Nilai,0) + coalesce(SIMtrKasir.NilaiInvoiceGabung,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung2,0)+ coalesce(SIMtrKasir.NilaiInvoiceGabung3,0)) as totalRevenue, COUNT(*) as jumlahPasien')
	      ->join('SIMtrMCU', 'SIMtrMCU.NoBukti','SIMtrMCUDetail.NoBukti')
	      ->join('SIMtrKasir', 'SIMtrKasir.NoReg','SIMtrMCU.RegNo')
	      ->join('SIMmSection', 'SIMmSection.SectionID', 'SIMtrMCUDetail.SectionID')
	      ->where('SIMtrKasir.Batal', '0')
	      ->where('SIMtrKasir.SectionPerawatanID', 'SECT0021')
	      ->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
	      ->whereMonth('SIMtrKasir.Tanggal',  $bulanSekarang)
	      ->where('SIMtrMCUDetail.SectionID', $idSectionSanata)
	      ->groupBy([\DB::raw('DAY(SIMtrKasir.Tanggal)'),'SIMtrMCUDetail.SectionID','SIMmSection.SectionName'])
          ->get();
        
          return $dataPaketPersalinan;
    }

    public function chartRevenuePasienPerSectionMTD($dataPaketPersalinans, $dataPaketPersalinans_1, $dataPaketPersalinans_2, $tglSekarang){
    	$balancingData = new HelperBalancingData();
    	$dataPaketPersalinans = $balancingData->balancingDataRevenue($dataPaketPersalinans);
    	$dataPaketPersalinans_1 = $balancingData->balancingDataRevenue($dataPaketPersalinans_1);
    	$dataPaketPersalinans_2 = $balancingData->balancingDataRevenue($dataPaketPersalinans_2);

    	$lineChart = \Lava::DataTable();
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $color = '';
        $lineChart->addStringColumn('Tanggal');
        $lineChart->addNumberColumn(''.($tahunSekarang-2));
        $lineChart->addNumberColumn(''.($tahunSekarang-1));
        $lineChart->addNumberColumn(''.($tahunSekarang));
        $lineChart->addRoleColumn('string', 'style');

        $endMonth = intval(explode('-', Carbon::now()->endOfMonth()->toDateString())[2]);

        $tglSekarangAbuAbu = \Carbon\Carbon::now()->day;
        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataPaketPersalinans[$i]->tanggal,
                $dataPaketPersalinans_2[$i]->totalRevenue,
                $dataPaketPersalinans_1[$i]->totalRevenue,
                $dataPaketPersalinans[$i]->totalRevenue,
                $color      
            ]);
        }

        \Lava::LineChart('jumlahRevenueDailyPaket', $lineChart, [
            'title' => 'Jumlah Revenue Paket Persalinan'
        ]);
    }	

   	public function chartJumlahPasienPerSectionMTD($dataPaketPersalinans, $dataPaketPersalinans_1, $dataPaketPersalinans_2, $tglSekarang){
		$balancingData = new HelperBalancingData();
		$dataPaketPersalinans = $balancingData->balancingDataJumlahPasien($dataPaketPersalinans);
		$dataPaketPersalinans_1 = $balancingData->balancingDataJumlahPasien($dataPaketPersalinans_1);
    	$dataPaketPersalinans_2 = $balancingData->balancingDataJumlahPasien($dataPaketPersalinans_2);

    	$lineChart = \Lava::DataTable();
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $color = '';
        $lineChart->addStringColumn('Tanggal');
        $lineChart->addNumberColumn(''.($tahunSekarang-2));
        $lineChart->addNumberColumn(''.($tahunSekarang-1));
        $lineChart->addNumberColumn(''.($tahunSekarang));
        $lineChart->addRoleColumn('string', 'style');

        $endMonth = intval(explode('-', Carbon::now()->endOfMonth()->toDateString())[2]);

        $tglSekarangAbuAbu = \Carbon\Carbon::now()->day;

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataPaketPersalinans[$i]->tanggal,
                $dataPaketPersalinans_2[$i]->jumlahPasien,
                $dataPaketPersalinans_1[$i]->jumlahPasien,
                $dataPaketPersalinans[$i]->jumlahPasien,
                $color      
            ]);
        }

        \Lava::LineChart('jumlahPasienDailyPaket', $lineChart, [
            'title' => 'Jumlah Pasien Paket Persalinan'
        ]);
    }
}
