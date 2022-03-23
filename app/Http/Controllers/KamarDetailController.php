<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Tahun;
use App\Kategori;
use Carbon\Carbon;
use App\KangCinHo\HelperTanggal;
use App\KangCinHo\HelperBalancingData;

class KamarDetailController extends Controller
{
    public function dashboard($idSectionSanata){
        $tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

        $tahunX_2 = $this->transaksiKamar($idSectionSanata, $tahunAktif-2);     
        $tahunX_1 = $this->transaksiKamar($idSectionSanata, $tahunAktif-1);
        $tahunX = $this->transaksiKamar($idSectionSanata, $tahunAktif);

        $this->chartJumlahPasien($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        return view('menu.kamar.kamarDetail.indexKamarDetail', compact('tahunX','lastUpdate'));
    }

    public function transaksiKamar($idSectionSanata, $tahunAktif){
        $dataSanata = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienTotal AS jumlahPasien ')
        ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
        ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
        ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
        ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
        ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
        ->where('trRevenueTransaksiDetail.isKamar', 1)
        ->where('trRevenueTransaksiDetail.idSyncToSanata',$idSectionSanata)
        ->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataSanata;
    }

    public function chartJumlahPasien($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif){
       
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

        \Lava::LineChart('jumlahKamar', $lineChart, [
            'title' => 'Perbandingan Jumlah Kamar '.$tahunX[0]->namaKategori
        ]);
    }

    public function dashboardDaily($idSectionSanata){
        $kategori = Kategori::where('idSyncToSanata', $idSectionSanata)->first();
        $kategori->namaKategori = ucwords(strtolower($kategori->namaKategori));
        
        $tgl = new HelperTanggal();
        $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
        $tglSekarang = $tgl->tanggalBacaBulanTahun($tglSekarang);
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;

        $dataDailyRI = $this->transaksiKamarDaily($tahunSekarang,$bulanSekarang, $idSectionSanata );
        $dataDailyRI_1 = $this->transaksiKamarDaily($tahunSekarang-1,$bulanSekarang, $idSectionSanata );
        $dataDailyRI_2 = $this->transaksiKamarDaily($tahunSekarang-2,$bulanSekarang, $idSectionSanata );

        $this->chartDailyRIJumlahPasien($dataDailyRI, $dataDailyRI_1, $dataDailyRI_2, $tglSekarang);
        return view('menu.kamar.kamarDetail.indexKamarDetailDaily', compact('tglSekarang','kategori'));
    }

    public function transaksiKamarDaily($tahunSekarang,$bulanSekarang, $idSectionSanata ){
        $dataDailyKamar = \DB::connection('sqlsrv')->table('SIMtrKasir')
        ->selectRaw('DAY(Tanggal) as tanggal, COUNT(KelasID) as jumlahPasien')
        ->where('Batal', '0')
        ->whereYear('Tanggal', $tahunSekarang)
        ->whereMonth('Tanggal',$bulanSekarang)
        ->where('KelasID',$idSectionSanata)
        ->groupBy(\DB::raw('DAY(Tanggal)'))
        ->orderBy(\DB::raw('DAY(Tanggal)'),'asc')
        ->get();
        return $dataDailyKamar;
    }

    public function chartDailyRIJumlahPasien($dataDailyRI, $dataDailyRI_1, $dataDailyRI_2, $tglSekarang){
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
        $dataDailyRI = $balancingData->balancingDataJumlahPasien($dataDailyRI);
        $dataDailyRI_1 = $balancingData->balancingDataJumlahPasien($dataDailyRI_1);
        $dataDailyRI_2 = $balancingData->balancingDataJumlahPasien($dataDailyRI_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }

            $lineChart->addRow([
                $dataDailyRI[$i]->tanggal,
                $dataDailyRI_2[$i]->jumlahPasien,
                $dataDailyRI_1[$i]->jumlahPasien,
                $dataDailyRI[$i]->jumlahPasien,
                $color
            ]);
        }
        \Lava::LineChart('jumlahPasienDailyRI', $lineChart, [
            'title' => 'Jumlah Pasien'
        ]);
    }
}