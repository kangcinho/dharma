<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Tahun;
use App\KangCinHo\HelperTanggal;
use App\KangCinHo\HelperBalancingData;
use App\Kategori;
use Carbon\Carbon;

class RawatInapDetailController extends Controller
{
    public function dashboard($idSectionSanata){
        $tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

        $tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

        $tahunX_2 = $this->dataRawatInap($idSectionSanata, $tahunAktif-2);
        $tahunX_1 = $this->dataRawatInap($idSectionSanata, $tahunAktif-1);
        $tahunX = $this->dataRawatInap($idSectionSanata, $tahunAktif);

        $this->chartRevenueBulanan($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        $this->chartJumlahPasien($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        return view('menu.rawatInap.rawatInapDetail.indexRawatInapDetail', compact('tahunX','lastUpdate'));
    }

    public function dataRawatInap($idSectionSanata, $tahunAktif){
        $dataRawatInap = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienTotal AS jumlahPasien ')
        ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
        ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
        ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
        ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
        ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
        ->where('trRevenueTransaksiDetail.idSyncToSanata',$idSectionSanata)
        ->where('mtahun.tahun', $tahunAktif)
        ->get();
        return $dataRawatInap;
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

        \Lava::LineChart('jumlahPasienBulananRI', $lineChart, [
            'title' => 'Jumlah Pasien: '.ucwords(strtolower($tahunX[0]->namaKategori))
        ]);
    }

    public function chartRevenueBulanan($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif){
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

        \Lava::LineChart('revenueBulananRI', $lineChart, [
            'title' => 'Revenue: '.ucwords(strtolower($tahunX[0]->namaKategori))
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

        $dataDailyRI = $this->dataRawatInapDaily($tahunSekarang, $bulanSekarang, $idSectionSanata);
        $dataDailyRI_1 = $this->dataRawatInapDaily($tahunSekarang-1, $bulanSekarang, $idSectionSanata);
        $dataDailyRI_2 = $this->dataRawatInapDaily($tahunSekarang-2, $bulanSekarang, $idSectionSanata);

        $this->chartDailyRIJumlahPasien($dataDailyRI, $dataDailyRI_1, $dataDailyRI_2, $tglSekarang);
        $this->chartDailyRIJumlahRevenue($dataDailyRI, $dataDailyRI_1, $dataDailyRI_2, $tglSekarang);
        return view('menu.rawatInap.rawatInapDetail.indexRawatInapDetailDaily', compact('tglSekarang','kategori'));
    }

    public function dataRawatInapDaily($tahunSekarang, $bulanSekarang, $idSectionSanata){
       $dataRawatInapDaily = \DB::connection('sqlsrv')->table('SIMtrKasir')
            ->selectRaw('DAY(Tanggal) as tanggal ,(SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - SUM(NilaiDiscount)) as totalRevenue, COUNT(*) as jumlahPasien')
            ->where('Batal', '0')
            ->whereYear('Tanggal', $tahunSekarang)
            ->whereMonth('Tanggal',$bulanSekarang)
            ->where('SectionPerawatanID',$idSectionSanata)
            ->groupBy(\DB::raw('DAY(Tanggal)'))
            ->orderBy(\DB::raw('DAY(Tanggal)'),'asc')
            ->get();
        return $dataRawatInapDaily;
    }
    public function chartDailyRIJumlahRevenue($dataDailyRI, $dataDailyRI_1, $dataDailyRI_2, $tglSekarang){     
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
        $dataDailyRI = $balancingData->balancingDataRevenue($dataDailyRI);
        $dataDailyRI_1 = $balancingData->balancingDataRevenue($dataDailyRI_1);
        $dataDailyRI_2 = $balancingData->balancingDataRevenue($dataDailyRI_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataDailyRI[$i]->tanggal,
                $dataDailyRI_2[$i]->totalRevenue,
                $dataDailyRI_1[$i]->totalRevenue,
                $dataDailyRI[$i]->totalRevenue,
                $color              
            ]);
        }

        \Lava::LineChart('revenueDailyRI', $lineChart, [
            'title' => 'Revenue Daily'
        ]);
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
