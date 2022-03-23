<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;
use App\KangCinHo\HelperUang;
use App\KangCinHo\HelperBalancingData;

class DailyRevenueController extends Controller
{
    public function dashboard(){
        //Dashboard Revenue Daily
    	$tgl = new HelperTanggal();
    	$uangHelper = new HelperUang();
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $bulanSekarang = \Carbon\Carbon::now()->month;
    	$tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
    	$tglSekarang = $tgl->tanggalBacaBulanTahun($tglSekarang);
        $dataRevenues = $this->getDataRevenue($bulanSekarang,$tahunSekarang);
        $dataRevenues_1 = $this->getDataRevenue($bulanSekarang,$tahunSekarang-1);
    	$dataRevenues_2 = $this->getDataRevenue($bulanSekarang,$tahunSekarang-2);
		$totalRevenue = 0;
		foreach($dataRevenues as $dataRevenue){
			$totalRevenue += $dataRevenue->totalRevenue;
		}
		$totalRevenue = $uangHelper->tambahkanTitik($totalRevenue);

		$this->chartRevenueDaily($dataRevenues, $dataRevenues_1, $dataRevenues_2, $tglSekarang, $tahunSekarang);
        
        //Komparasi Daily Revenue
        $tglMaksimal = (int) explode('-',$this->tglRevenueDaily($bulanSekarang, $tahunSekarang))[2];
        $dailyRevenueKomparasi = $uangHelper->tambahkanTitik($this->dailyRevenueKomparasi($bulanSekarang, $tahunSekarang, $tglMaksimal));
        $dailyRevenueKomparasi_1 = $uangHelper->tambahkanTitik($this->dailyRevenueKomparasi($bulanSekarang, $tahunSekarang-1, $tglMaksimal));
        $dailyRevenueKomparasi_2 = $uangHelper->tambahkanTitik($this->dailyRevenueKomparasi($bulanSekarang, $tahunSekarang-2, $tglMaksimal));
        $dailyRevenueKomparasiLastMonth = $uangHelper->tambahkanTitik($this->dailyRevenueKomparasi($bulanSekarang-1, $tahunSekarang, $tglMaksimal));
        $lastMonth = $tgl->bacaBulan( $bulanSekarang - 1);
        $bulanSekarang = $tgl->bacaBulan($bulanSekarang);
        $tglRange = '01 Sampai '.$tglMaksimal;
        return view('menu.dailyRevenue.indexDailyRevenue', compact('tglSekarang','totalRevenue', 'dailyRevenueKomparasi','dailyRevenueKomparasi_1', 'dailyRevenueKomparasi_2', 'dailyRevenueKomparasiLastMonth', 'tahunSekarang', 'bulanSekarang', 'lastMonth','tglRange'));
    }

    public function dailyRevenueKomparasi($bulanSekarang, $tahunSekarang, $tglMaksimal){
        $tglAwal = $tahunSekarang .'-'. $bulanSekarang .'-01';
        $tglAkhir =  $tahunSekarang .'-'. $bulanSekarang .'-'. $tglMaksimal;
        $dataRevenues = \DB::connection('sqlsrvbo')
        ->table('TBJ_Transaksi_Detail')
        ->selectRaw('(SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as totalRevenue')
        ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        ->whereIn('Akun_ID', function($query){
          $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1);
        })
        ->where('Transaksi_Date','>=',$tglAwal)
        ->where('Transaksi_Date','<=',$tglAkhir)
        ->get();

        return floatval($dataRevenues[0]->totalRevenue);
    }
    public function tglRevenueDaily($bulanSekarang, $tahunSekarang){
        $dataSanata = \DB::connection('sqlsrvbo')
        ->table('TBJ_Transaksi_Detail')
        ->selectRaw('MAX(Transaksi_Date) as tgl')
        ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        ->whereIn('Akun_ID', function($query){
          $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1);
        })
        ->whereYear('Transaksi_Date', $tahunSekarang)
        ->whereMonth('Transaksi_Date',$bulanSekarang)
        ->get();
        
        if($dataSanata[0]->tgl == null){
            $dataSanata[0]->tgl = $tahunSekarang.'-'.$bulanSekarang.'-01';
        }
        return explode(' ',$dataSanata[0]->tgl)[0];
      }

    public function chartRevenueDaily($dataRevenues, $dataRevenues_1, $dataRevenues_2, $tglSekarang, $tahunSekarang){
        $bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;
        $lineChart = \Lava::DataTable();
        $lineChart->addStringColumn('Tanggal');
        $lineChart->addNumberColumn(''.($tahunSekarang-2));
        $lineChart->addNumberColumn(''.($tahunSekarang-1));
        $lineChart->addNumberColumn(''.($tahunSekarang));
        $lineChart->addRoleColumn('string', 'style');
        $tglSekarangAbuAbu = (int) explode('-',$this->tglRevenueDaily($bulanSekarang, $tahunSekarang))[2];
        for($i = 0 ; $i < count($dataRevenues) ; $i++){
            if($i >= $tglSekarangAbuAbu-1){
                $color = 'color:grey';
            }else{
                $color = '';
            }
            $lineChart->addRow([ 
                $dataRevenues[$i]->tanggal,
                $dataRevenues_2[$i]->totalRevenue,
                $dataRevenues_1[$i]->totalRevenue,
                $dataRevenues[$i]->totalRevenue,
                $color
            ]);
        }
        \Lava::LineChart('revenueDaily', $lineChart, [
            'title' => 'Revenue MTD: '.$tglSekarang,
        ]);
    }

    public function getDataRevenue($bulanSekarang, $tahunSekarang){
        $helperBalancingData = new HelperBalancingData();
 
        $dataRevenues = \DB::connection('sqlsrvbo')
        ->table('TBJ_Transaksi_Detail')
        ->selectRaw('DAY(Transaksi_Date) as tanggal,(SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as totalRevenue')
        ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        ->whereIn('Akun_ID', function($query){
          $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1);
        })
        ->whereYear('Transaksi_Date', $tahunSekarang)
        ->whereMonth('Transaksi_Date',$bulanSekarang)
        ->groupBy(\DB::raw('DAY(Transaksi_Date)'))
        ->orderBy(\DB::raw('DAY(Transaksi_Date)'),'asc')
        ->get();
        $dataRevenues = $helperBalancingData->balancingDataRevenue($dataRevenues);
        return $dataRevenues;
        
        // $endMonth = intval(explode('-', \Carbon\Carbon::now()->endOfMonth()->toDateString())[2]);
        // $dataRevenues = \DB::connection('sqlsrv')->table('SIMtrKasir')
        //     ->selectRaw('DAY(Tanggal) as tanggal ,SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as totalRevenue')
        //     ->where('Batal', '0')
        //     ->whereYear('Tanggal', $tahunSekarang)
        //     ->whereMonth('Tanggal',$bulanSekarang)
        //     ->groupBy(\DB::raw('DAY(Tanggal)'))
        //     ->orderBy(\DB::raw('DAY(Tanggal)'),'asc')
        //     ->get();
        

        // $obatBebases = \DB::connection('sqlsrv')
        //     ->table('SIMtrPembayaranObatBebas')
        //     ->selectRaw('DAY(Tanggal) as tanggal, SUM(NilaiTransaksi) as totalRevenue')
        //     ->where('Batal', '0')
        //     ->where('tipe', 'OBAT BEBAS')
        //     ->whereYear(\DB::raw('convert(date,Tanggal,111)'), $tahunSekarang)
        //     ->whereMonth(\DB::raw('convert(date,Tanggal,111)'),$bulanSekarang)
        //     ->groupBy(\DB::raw('DAY(Tanggal)'))
        //     ->orderBy(\DB::raw('DAY(Tanggal)'),'asc')
        //     ->get();

        // $tataBogas = \DB::connection('sqlsrv')
        //     ->table('TBO_Transaksi')
        //     ->selectRaw('DAY(SIMtrPembayaranObatBebas.Tanggal) as tanggal, SUM(totalnilai) as totalRevenue')
        //     ->join('SIMtrPembayaranObatBebas', 'SIMtrPembayaranObatBebas.NoBuktiTataBoga', '=', 'TBO_Transaksi.NoBukti')
        //     ->where('SIMtrPembayaranObatBebas.Batal', '0')
        //     ->where('SIMtrPembayaranObatBebas.tipe', 'TATA BOGA')
        //     ->whereYear(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'), $tahunSekarang)
        //     ->whereMonth(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'),$bulanSekarang)
        //     ->groupBy(\DB::raw('DAY(SIMtrPembayaranObatBebas.Tanggal)'))
        //     ->orderBy(\DB::raw('DAY(SIMtrPembayaranObatBebas.Tanggal)'),'asc')
        //     ->get();
        
        // $diskons = \DB::connection('sqlsrvbo')
        // ->table('TBJ_Transaksi_Detail')
        // ->selectRaw('DAY(Transaksi_Date) as tanggal, (SUM(TBJ_Transaksi_Detail.Debit) - SUM(TBJ_Transaksi_Detail.Kredit)) as totalRevenue')
        // ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        // ->whereIn('Akun_ID', ['2234', '2235','2231','2502'])
        // ->whereYear('Transaksi_Date', $tahunSekarang)
        // ->whereMonth('Transaksi_Date',$bulanSekarang)
        // ->groupBy(\DB::raw('DAY(Transaksi_Date)'))
        // ->orderBy(\DB::raw('DAY(Transaksi_Date)'),'asc')
        // ->get();

        // $tambahanLainnyas = \DB::connection('sqlsrvbo')
        // ->table('TBJ_Transaksi_Detail')
        // ->selectRaw('DAY(Transaksi_Date) as tanggal, (SUM(coalesce(TBJ_Transaksi_Detail.Kredit,0)) - SUM(coalesce(TBJ_Transaksi_Detail.Debit,0))) as totalRevenue')
        // ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        // ->whereIn('Akun_ID', ['2227','2391', '2535', '2405', '2406'])
        // ->whereYear('Transaksi_Date', $tahunSekarang)
        // ->whereMonth('Transaksi_Date',$bulanSekarang)
        // ->groupBy(\DB::raw('DAY(Transaksi_Date)'))
        // ->orderBy(\DB::raw('DAY(Transaksi_Date)'),'asc')
        // ->get();

        // $dataRevenues = $helperBalancingData->balancingDataRevenue($dataRevenues);
        // $obatBebases = $helperBalancingData->balancingDataRevenue($obatBebases);
        // $tataBogas = $helperBalancingData->balancingDataRevenue($tataBogas);        
        // $diskons = $helperBalancingData->balancingDataRevenue($diskons); 
        // $tambahanLainnyas = $helperBalancingData->balancingDataRevenue($tambahanLainnyas);

        // for($i=0; $i < $endMonth; $i++){
        //     $dataRevenues[$i]->totalRevenue = $dataRevenues[$i]->totalRevenue + $obatBebases[$i]->totalRevenue + $tataBogas[$i]->totalRevenue - $diskons[$i]->totalRevenue + $tambahanLainnyas[$i]->totalRevenue;
        // }
    } 
}
