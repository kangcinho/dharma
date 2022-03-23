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

class PasienBaruDetailSectionController extends Controller
{
    public function dashboardSectionPasien($idSectionSanata){
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $tgl = new HelperTanggal();
        $helperUang = new HelperUang();
        $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
        $tglSekarang = $tgl->tanggalRangeWithBulanTahun($tglSekarang);

        $dataPasienBaruPerSections = $this->getDataListPasienBaruPerSection($bulanSekarang, $tahunSekarang, $idSectionSanata);

        foreach($dataPasienBaruPerSections as $dataPasienBaruPerSection){
            $dataPasienBaruPerSection->jumlahPasien = $helperUang->tambahkanTitik($dataPasienBaruPerSection->jumlahPasien);
            $dataPasienBaruPerSection->jumlahPasienYTD = $helperUang->tambahkanTitik($dataPasienBaruPerSection->jumlahPasienYTD);
        }
        return view('menu.pasienBaru.pasienBaruDetailPerSection.indexPasienBaruPerSection', compact('dataPasienBaruPerSections', 'tglSekarang',  'idSectionSanata'));
    }

    public function getDataListPasienBaruPerSection($bulanSekarang, $tahunSekarang, $idSectionSanata){
        $dataPasienBaruPerSections = \DB::connection('sqlsrv')->table('SIMtrKasir')
              ->selectRaw('SIMtrKasir.SectionPerawatanID as SectionPerawatanID, SIMmSection.SectionName as namaSection,  COUNT(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
              ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', 'SIMtrKasir.NoReg')
              ->join('SIMmSection', 'SIMmSection.SectionID', 'SIMtrKasir.SectionPerawatanID')
              ->where('SIMtrKasir.Batal', '0')
              ->whereYear('Tanggal', $tahunSekarang)
              ->whereMonth('Tanggal',$bulanSekarang)
              ->where('SimTrKasir.RJ', $idSectionSanata)
              ->where('SIMtrRegistrasi.PasienBaru',1)
              ->groupBy(['SIMtrKasir.SectionPerawatanID', 'SIMmSection.SectionName'])
              ->orderBy('jumlahPasien','desc')
              ->get();

         $querdataListPoliYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as SectionPerawatanID, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienBaru) as jumlahPasien')
          ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
          ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
          ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
          ->where('mTahun.tahun', $tahunSekarang)
          ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
          ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
          ->get();

          foreach($dataPasienBaruPerSections as $dataPasienBaruPerSection){
            $dataPasienBaruPerSection->namaSection = ucwords(strtolower($dataPasienBaruPerSection->namaSection));
            
            foreach( $querdataListPoliYTDs as  $querdataListPoliYTD){
              if($dataPasienBaruPerSection->SectionPerawatanID != $querdataListPoliYTD->SectionPerawatanID){
                continue;
              }
              $dataPasienBaruPerSection->jumlahPasienYTD = $querdataListPoliYTD->jumlahPasien + $dataPasienBaruPerSection->jumlahPasien;
              break;
            }
          }

        return $dataPasienBaruPerSections;
    }

    public function dashboardSectionPasienGrafikYTD($idSectionSanata, $sectionID){
    	$tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

  		$tahunX = $this->dataJumlahPasienBaruPerSection($sectionID, $tahunAktif);
  		$tahunX_1 = $this->dataJumlahPasienBaruPerSection($sectionID, $tahunAktif-1);
  		$tahunX_2 = $this->dataJumlahPasienBaruPerSection($sectionID, $tahunAktif-2);

  		$this->chartJumlahPasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);

  		return view('menu.pasienBaru.pasienBaruDetailPerSection.indexPasienBaruPerSectionChart', compact('tahunX','lastUpdate','idSectionSanata'));
    }
    public function dataJumlahPasienBaruPerSection($sectionID, $tahunAktif){
        $dataJumlahPasienBaruPerSection = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienBaru AS jumlahPasien ')
  		->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
  		->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
  		->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
  		->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
  		->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
  		->where('trRevenueTransaksiDetail.idSyncToSanata',$sectionID)
  		->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataJumlahPasienBaruPerSection;
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

        \Lava::LineChart('jumlahPasienBaru', $lineChart, [
            'title' => 'Jumlah Pasien Baru '.$tahunX[0]->namaKategori
        ]);
    }

    public function dashboardSectionPasienGrafikMTD($idSectionSanata, $sectionID){
      $bulanSekarang = \Carbon\Carbon::now()->month;
      $tahunSekarang = \Carbon\Carbon::now()->year;
      $tgl = new HelperTanggal();
      $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
      $tglSekarang = $tgl->tanggalBacaBulanTahun($tglSekarang);
      
      $kategori = Kategori::where('idSyncToSanata', $sectionID)->first();
      $kategori->namaKategori = ucwords(strtolower($kategori->namaKategori));

      $dataPasienBaru = $this->dataJumlahPasienBaruPerSectionDaily($tahunSekarang, $bulanSekarang, $sectionID);
      $dataPasienBaru_1 = $this->dataJumlahPasienBaruPerSectionDaily($tahunSekarang-1, $bulanSekarang, $sectionID);
      $dataPasienBaru_2 = $this->dataJumlahPasienBaruPerSectionDaily($tahunSekarang-2, $bulanSekarang, $sectionID);

      $this->chartJumlahPasienDaily($dataPasienBaru, $dataPasienBaru_1, $dataPasienBaru_2, $tglSekarang);

      return view('menu.pasienBaru.pasienBaruDetailPerSection.indexPasienBaruPerSectionDetailDaily', compact('tglSekarang','kategori','idSectionSanata'));
    }
    
    public function dataJumlahPasienBaruPerSectionDaily($tahunSekarang, $bulanSekarang, $sectionID){
        $dataJumlahPasienBaruPerSectionDaily = \DB::connection('sqlsrv')->table('SIMtrKasir')
        ->selectRaw('DAY(Tanggal) as tanggal, count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
        ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
        ->where('SIMtrKasir.Batal', '0')
        ->where('SIMtrRegistrasi.PasienBaru',1)
        ->whereYear('Tanggal', $tahunSekarang)
        ->whereMonth('Tanggal',$bulanSekarang)
        ->where('SimTrKasir.SectionPerawatanID', $sectionID)
        ->groupBy(\DB::raw('DAY(SimTrKasir.Tanggal)'))
        ->get();

        return $dataJumlahPasienBaruPerSectionDaily;
    }

    public function chartJumlahPasienDaily($dataPasienBaru, $dataPasienBaru_1, $dataPasienBaru_2, $tglSekarang){

        $balancingData = new HelperBalancingData();
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
        $dataPasienBaru = $balancingData->balancingDataJumlahPasien($dataPasienBaru);
        $dataPasienBaru_1 = $balancingData->balancingDataJumlahPasien($dataPasienBaru_1);
        $dataPasienBaru_2 = $balancingData->balancingDataJumlahPasien($dataPasienBaru_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataPasienBaru[$i]->tanggal,
                $dataPasienBaru_2[$i]->jumlahPasien,
                $dataPasienBaru_1[$i]->jumlahPasien,
                $dataPasienBaru[$i]->jumlahPasien,
                $color      
            ]);
        }

        \Lava::LineChart('jumlahPasienDaily', $lineChart, [
            'title' => 'Jumlah Pasien'
        ]);
    }
}
