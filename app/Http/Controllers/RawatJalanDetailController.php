<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Tahun;
use App\Kategori;
use App\KangCinHo\HelperTanggal;
use Carbon\Carbon;
use App\KangCinHo\HelperBalancingData;

class RawatJalanDetailController extends Controller
{
    public function dashboard($idSectionSanata){
        $tanggal = new HelperTanggal();
        $lastUpdate = $tanggal->getLastUpdate();

    	$tahunAktif = Tahun::whereStatus(1)->first();
        $tahunAktif = (int) $tahunAktif->tahun;

        $tahunX_2 = $this->dataRawatJalan($idSectionSanata, $tahunAktif-2);
        $tahunX_1 = $this->dataRawatJalan($idSectionSanata, $tahunAktif-1);
        $tahunX = $this->dataRawatJalan($idSectionSanata, $tahunAktif);

        $this->chartRevenueBulanan($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        $this->chartJumlahPasien($tahunX, $tahunX_1, $tahunX_2, $idSectionSanata, $tahunAktif);
        return view('menu.rawatJalan.rawatJalanDetail.indexRawatJalanDetail', compact('tahunX','lastUpdate'));
    }

    public function dataRawatJalan($idSectionSanata, $tahunAktif){
        $dataRawatJalan = RevenueTransaksiDetail::selectRaw('mKategori.namaKategori AS namaKategori, mTahun.tahun AS tahun, mbulan.bulan AS bulan, mbulan.bulanSingkatan AS bulanSingkatan, trRevenueTransaksiDetail.totalRevenue AS totalRevenue, trRevenueTransaksiDetail.jumlahPasienTotal AS jumlahPasien ')
            ->join('trRevenueTransaksi','trRevenueTransaksi.id','=', 'trRevenueTransaksiDetail.idTrRevenueTransaksi')
            ->join('trRevenue', 'trRevenue.id', '=', 'trRevenueTransaksi.idTrRevenue')
            ->join('mBulan', 'trRevenueTransaksi.idBulan', '=', 'mBulan.id')
            ->join('mTahun', 'trRevenue.idTahun', '=', 'mTahun.id')
            ->join('mKategori', 'mKategori.id', '=', 'trRevenueTransaksiDetail.idKategori')
            ->where('trRevenueTransaksiDetail.idSyncToSanata',$idSectionSanata)
            ->where('mtahun.tahun', $tahunAktif)
            ->get();
        return $dataRawatJalan;
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

        \Lava::LineChart('jumlahPasienBulananPoli', $lineChart, [
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

        \Lava::LineChart('revenueBulananPoli', $lineChart, [
            'title' => 'Revenue : '.ucwords(strtolower($tahunX[0]->namaKategori))
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

        $dataDailyRJ = $this->dataRawatJalanDaily($tahunSekarang, $bulanSekarang, $idSectionSanata);       
        $dataDailyRJ_1 = $this->dataRawatJalanDaily($tahunSekarang-1, $bulanSekarang, $idSectionSanata);     
        $dataDailyRJ_2 = $this->dataRawatJalanDaily($tahunSekarang-2, $bulanSekarang, $idSectionSanata);

        $this->chartDailyRJJumlahPasien($dataDailyRJ, $dataDailyRJ_1, $dataDailyRJ_2, $tglSekarang);
        $this->chartDailyRJJumlahRevenue($dataDailyRJ, $dataDailyRJ_1, $dataDailyRJ_2, $tglSekarang);
        return view('menu.rawatJalan.rawatJalanDetail.indexRawatJalanDetailDaily', compact('tglSekarang','kategori'));
    }

    public function dataRawatJalanDaily($tahunSekarang, $bulanSekarang, $idSectionSanata){
        $dataRawatJalanDaily = \DB::connection('sqlsrv')->table('SIMtrKasir')
            ->selectRaw('DAY(SIMtrKasir.Tanggal) as tanggal ,(SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - SUM(NilaiDiscount)) as totalRevenue, COUNT(*) as jumlahPasien')
            ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
            ->where('SIMtrKasir.Batal', '0')
            ->where('SIMtrRJ.Batal', '0')
            ->whereYear('SIMtrKasir.Tanggal', $tahunSekarang)
            ->whereMonth('SIMtrKasir.Tanggal',$bulanSekarang)
            ->where(function($q) use ($idSectionSanata){
                $q->where('SIMtrRJ.SectionID',$idSectionSanata)->orWhere('SIMtrKasir.SectionPerawatanID', $idSectionSanata);
            })
            ->groupBy(\DB::raw('DAY(SIMtrKasir.Tanggal)'))
            ->orderBy(\DB::raw('DAY(SIMtrKasir.Tanggal)'),'asc')
            ->get();
        return $dataRawatJalanDaily;
    }
    public function chartDailyRJJumlahRevenue($dataDailyRJ, $dataDailyRJ_1, $dataDailyRJ_2, $tglSekarang){     
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
        $dataDailyRJ = $balancingData->balancingDataRevenue($dataDailyRJ);
        $dataDailyRJ_1 = $balancingData->balancingDataRevenue($dataDailyRJ_1);
        $dataDailyRJ_2 = $balancingData->balancingDataRevenue($dataDailyRJ_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataDailyRJ[$i]->tanggal,
                $dataDailyRJ_2[$i]->totalRevenue,
                $dataDailyRJ_1[$i]->totalRevenue,
                $dataDailyRJ[$i]->totalRevenue,
                $color              
            ]);
        }

        \Lava::LineChart('revenueDailyRJ', $lineChart, [
            'title' => 'Revenue Daily'
        ]);
    }

    public function chartDailyRJJumlahPasien($dataDailyRJ, $dataDailyRJ_1, $dataDailyRJ_2, $tglSekarang){
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
        $dataDailyRJ = $balancingData->balancingDataJumlahPasien($dataDailyRJ);
        $dataDailyRJ_1 = $balancingData->balancingDataJumlahPasien($dataDailyRJ_1);
        $dataDailyRJ_2 = $balancingData->balancingDataJumlahPasien($dataDailyRJ_2);

        for($i = 0 ; $i < $endMonth ; $i++){
            $color = '';
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }
            
            $lineChart->addRow([
                $dataDailyRJ[$i]->tanggal,
                $dataDailyRJ_2[$i]->jumlahPasien,
                $dataDailyRJ_1[$i]->jumlahPasien,
                $dataDailyRJ[$i]->jumlahPasien,
                $color               
            ]);
        }
        \Lava::LineChart('jumlahPasienDailyRJ', $lineChart, [
            'title' => 'Jumlah Pasien'
        ]);
    }
}
