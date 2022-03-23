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

class PasienBaruDetailController extends Controller
{
    public function dashboard($idSectionSanata){
        $tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

        $tahunX_2 = $this->dataJumlahPasien($idSectionSanata, $tahunAktif-2);
        $tahunX_1 = $this->dataJumlahPasien($idSectionSanata, $tahunAktif-1);
        $tahunX = $this->dataJumlahPasien($idSectionSanata, $tahunAktif);

        $this->chartJumlahPasien($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        return view('menu.pasienBaru.pasienBaruDetail.indexPasienBaruDetail', compact('tahunX','lastUpdate'));
    }

    public function dataJumlahPasien($idSectionSanata, $tahunAktif){
        $dataJumlahPasien  = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienTotal AS jumlahPasien ')
        ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
        ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
        ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
        ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
        ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
        ->where('trRevenueTransaksiDetail.isPasien', 1)
        ->where('trRevenueTransaksiDetail.idSyncToSanata',$idSectionSanata)
        ->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataJumlahPasien;
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

        \Lava::LineChart('jumlahPasienBaru', $lineChart, [
            'title' => 'Jumlah '.$tahunX[0]->namaKategori
        ]);
    }

    public function dashboardDaily($idSectionSanata){
        $kategori = Kategori::where('idSyncToSanata', $idSectionSanata)->first();
        $kategori->namaKategori = ucwords(strtolower($kategori->namaKategori));
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $tgl = new HelperTanggal();
        $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
        $tglSekarang = $tgl->tanggalBacaBulanTahun($tglSekarang);

        if($idSectionSanata == 1){
            $idSectionSanata = 'RI';
        }else{
            $idSectionSanata = 'RJ';
        }

        $dataPasienBaru = $this->dataJumlahPasienDaily($tahunSekarang, $bulanSekarang, $idSectionSanata);
        $dataPasienBaru_1 = $this->dataJumlahPasienDaily($tahunSekarang-1, $bulanSekarang, $idSectionSanata);
        $dataPasienBaru_2 = $this->dataJumlahPasienDaily($tahunSekarang-2, $bulanSekarang, $idSectionSanata);

        $this->chartJumlahPasienDaily($dataPasienBaru, $dataPasienBaru_1, $dataPasienBaru_2, $tglSekarang);

        return view('menu.pasienBaru.pasienBaruDetail.indexPasienBaruDetailDaily', compact('tglSekarang','kategori'));
    }
    public function dataJumlahPasienDaily($tahunSekarang, $bulanSekarang, $idSectionSanata){
        $dataJumlahPasienDaily = \DB::connection('sqlsrv')->table('SIMtrKasir')
        ->selectRaw('DAY(Tanggal) as tanggal, count(SIMtrRegistrasi.PasienBaru) as jumlahPasien')
        ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg', '=', 'SIMtrKasir.NoReg')
        ->where('SIMtrKasir.Batal', '0')
        ->where('SIMtrRegistrasi.PasienBaru',1)
        ->whereYear('Tanggal', $tahunSekarang)
        ->whereMonth('Tanggal',$bulanSekarang)
        ->where('SimTrKasir.RJ', $idSectionSanata)
        ->groupBy(\DB::raw('DAY(SimTrKasir.Tanggal)'))
        ->get();
        return $dataJumlahPasienDaily;
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