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

class PasienRepeaterDetailSectionController extends Controller
{
    public function dashboardSectionPasien($idSectionSanata){
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $tgl = new HelperTanggal();
        $helperUang = new HelperUang();
        $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
        $tglSekarang = $tgl->tanggalRangeWithBulanTahun($tglSekarang);

        $dataPasienRepeaterPerSections = $this->getDataListPasienRepeaterPerSection($bulanSekarang, $tahunSekarang, $idSectionSanata);

        foreach($dataPasienRepeaterPerSections as $dataPasienRepeaterPerSection){
            $dataPasienRepeaterPerSection->jumlahPasien = $helperUang->tambahkanTitik($dataPasienRepeaterPerSection->jumlahPasien);
            $dataPasienRepeaterPerSection->jumlahPasienYTD = $helperUang->tambahkanTitik($dataPasienRepeaterPerSection->jumlahPasienYTD);
        }
        return view('menu.pasienRepeater.pasienRepeaterDetailPerSection.indexPasienRepeaterPerSection', compact('dataPasienRepeaterPerSections', 'tglSekarang',  'idSectionSanata'));
    }

    public function getDataListPasienRepeaterPerSection($bulanSekarang, $tahunSekarang, $idSectionSanata){
        $dataPasienRepeaterPerSections = \DB::connection('sqlsrv')->table('SIMtrKasir')
              ->selectRaw('SIMtrKasir.SectionPerawatanID as SectionPerawatanID, SIMmSection.SectionName as namaSection,  COUNT(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
              ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', 'SIMtrKasir.NoReg')
              ->join('SIMmSection', 'SIMmSection.SectionID', 'SIMtrKasir.SectionPerawatanID')
              ->where('SIMtrKasir.Batal', '0')
              ->whereYear('Tanggal', $tahunSekarang)
              ->whereMonth('Tanggal',$bulanSekarang)
              ->where('SimTrKasir.RJ', $idSectionSanata)
              ->where('SIMtrRegistrasi.PasienBaru',0)
              ->groupBy(['SIMtrKasir.SectionPerawatanID', 'SIMmSection.SectionName'])
              ->orderBy('jumlahPasien','desc')
              ->get();

         $querdataListPoliYTDs = RevenueTransaksiDetail::selectRaw('trRevenueTransaksiDetail.idSyncToSanata as SectionPerawatanID, SUM(trRevenueTransaksiDetail.totalRevenue) as totalRevenue, SUM(trRevenueTransaksiDetail.jumlahPasienRepeater) as jumlahPasien')
          ->join('trRevenueTransaksi', 'trRevenueTransaksi.id', '=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
          ->join('trRevenue', 'trRevenueTransaksi.idTrRevenue', '=', 'trRevenue.id')
          ->join('mTahun', 'trRevenue.idTahun', '=', 'mtahun.id')
          ->where('mTahun.tahun', $tahunSekarang)
          ->where('trRevenueTransaksi.idBulan', '<', $bulanSekarang)
          ->groupBy('trRevenueTransaksiDetail.idSyncToSanata')
          ->get();

          foreach($dataPasienRepeaterPerSections as $dataPasienRepeaterPerSection){
            $dataPasienRepeaterPerSection->namaSection = ucwords(strtolower($dataPasienRepeaterPerSection->namaSection));
            
            foreach( $querdataListPoliYTDs as  $querdataListPoliYTD){
              if($dataPasienRepeaterPerSection->SectionPerawatanID != $querdataListPoliYTD->SectionPerawatanID){
                continue;
              }
              $dataPasienRepeaterPerSection->jumlahPasienYTD = $querdataListPoliYTD->jumlahPasien + $dataPasienRepeaterPerSection->jumlahPasien;
              break;
            }
          }

        return $dataPasienRepeaterPerSections;
    }

    public function dashboardSectionPasienGrafikYTD($idSectionSanata, $sectionID){
    	$tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

  		$tahunX = $this->dataPasienRepeaterPerSection($sectionID, $tahunAktif);
  		$tahunX_1 = $this->dataPasienRepeaterPerSection($sectionID, $tahunAktif-1);
  		$tahunX_2 = $this->dataPasienRepeaterPerSection($sectionID, $tahunAktif-2);

  		$this->chartJumlahPasienPerSectionYTD($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);

  		return view('menu.pasienRepeater.pasienRepeaterDetailPerSection.indexPasienRepeaterPerSectionChart', compact('tahunX','lastUpdate','idSectionSanata'));
    }

    public function dataPasienRepeaterPerSection($sectionID, $tahunAktif){
        $dataPasienRepeaterPerSection = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienRepeater AS jumlahPasien ')
        ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
        ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
        ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
        ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
        ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
        ->where('trRevenueTransaksiDetail.idSyncToSanata',$sectionID)
        ->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataPasienRepeaterPerSection;
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

        \Lava::LineChart('jumlahPasienRepeater', $lineChart, [
            'title' => 'Jumlah Pasien Repeater '.$tahunX[0]->namaKategori
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

      $dataPasienRepeater = $this->dataPasienRepeaterPerSectionDaily($tahunSekarang, $bulanSekarang, $sectionID);
      $dataPasienRepeater_1 = $this->dataPasienRepeaterPerSectionDaily($tahunSekarang-1, $bulanSekarang, $sectionID);
      $dataPasienRepeater_2 = $this->dataPasienRepeaterPerSectionDaily($tahunSekarang-2, $bulanSekarang, $sectionID);

      $this->chartJumlahPasienDaily($dataPasienRepeater, $dataPasienRepeater_1, $dataPasienRepeater_2, $tglSekarang);

      return view('menu.pasienRepeater.pasienRepeaterDetailPerSection.indexPasienRepeaterPerSectionDetailDaily', compact('tglSekarang','kategori','idSectionSanata'));
    }

    public function dataPasienRepeaterPerSectionDaily($tahunSekarang, $bulanSekarang, $sectionID){
        $dataPasienRepeaterPerSectionDaily = \DB::connection('sqlsrv')->table('SIMtrKasir')
            ->selectRaw('DAY(Tanggal) as tanggal, count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
            ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
            ->where('SIMtrKasir.Batal', '0')
            ->where('SIMtrRegistrasi.PasienBaru',0)
            ->whereYear('Tanggal', $tahunSekarang)
            ->whereMonth('Tanggal',$bulanSekarang)
            ->where('SimTrKasir.SectionPerawatanID', $sectionID)
            ->groupBy(\DB::raw('DAY(SimTrKasir.Tanggal)'))
            ->get();
        return $dataPasienRepeaterPerSectionDaily;
    }

    public function chartJumlahPasienDaily($dataPasienRepeater, $dataPasienRepeater_1, $dataPasienRepeater_2, $tglSekarang){
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
        $dataPasienRepeater = $balancingData->balancingDataJumlahPasien($dataPasienRepeater);
        $dataPasienRepeater_1 = $balancingData->balancingDataJumlahPasien($dataPasienRepeater_1);
        $dataPasienRepeater_2 = $balancingData->balancingDataJumlahPasien($dataPasienRepeater_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataPasienRepeater[$i]->tanggal,
                $dataPasienRepeater_2[$i]->jumlahPasien,
                $dataPasienRepeater_1[$i]->jumlahPasien,
                $dataPasienRepeater[$i]->jumlahPasien,
                $color      
            ]);
        }

        \Lava::LineChart('jumlahPasienDaily', $lineChart, [
            'title' => 'Jumlah Pasien'
        ]);
    }
}
