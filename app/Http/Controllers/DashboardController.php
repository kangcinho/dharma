<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\KangCinHo\HelperUang;
use App\KangCinHo\HelperTanggal;
use App\Tahun;
use App\Revenue;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;

class DashboardController extends Controller
{
  public function dashboard(){
    
    $uangHelper = new HelperUang();
  	$tanggal = new HelperTanggal();
    $lastUpdate = $tanggal->getLastUpdate();
   
    $tglSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];

    $bulanSekarang = \Carbon\Carbon::now()->month;
    $tahunSekarang = \Carbon\Carbon::now()->year;
  	$dataMenuRJdanRI = $this->dataMenuRJdanRI($bulanSekarang, $tahunSekarang);
    $dataPasienAll = $this->dataPasienAll($bulanSekarang, $tahunSekarang);
    $dataKamar = $this->dataKamar($bulanSekarang, $tahunSekarang);
    $revenueDaily = $this->revenueDaily($bulanSekarang, $tahunSekarang);
    $revenueDaily[0]->totalRevenue = $uangHelper->tambahkanTitik($revenueDaily[0]->totalRevenue);
    $tglSekarang = $tanggal->tanggalRangeWithBulanTahun($tglSekarang);
    $tglRevenueDaily = $this->tglRevenueDaily($bulanSekarang, $tahunSekarang);
    $tglRevenueDaily = $tanggal->tanggalRangeWithBulanTahun($tglRevenueDaily);

    $this->createChartRevenue();
    $this->createChartSpedoMeterRevenueBulanBerjalan();
    $this->createChartSpedoMeterRevenueTahunan();
    return view('menu.dashboard.indexDashboard',compact('lastUpdate','dataMenuRJdanRI','tglSekarang','dataKamar', 'revenueDaily', 'dataPasienAll', 'tglRevenueDaily'));
  }

  // public function getLastUpdate(){
  // 	return RevenueTransaksiDetail::selectRaw('MAX(updated_at) as lastUpdate')->first();
  // }

  public function dataMenuRJdanRI($bulanSekarang, $tahunSekarang){
  	return \DB::connection('sqlsrv')->table('SimTrKasir')
  	->selectRaw('RJ, COUNT(*) as jumlahPasien')
  	->where('Batal',0)
    ->whereMonth('Tanggal', $bulanSekarang)
  	->whereYear('Tanggal', $tahunSekarang)
  	->groupBy('RJ')
    ->orderBy('RJ','desc')
  	->get();
  }
  
  public function dataPasienAll($bulanSekarang, $tahunSekarang){
    return \DB::connection('sqlsrv')->table('SIMtrKasir')
    ->selectRaw('count(NoBukti) as jumlahPasien')
    ->where('SIMtrKasir.Batal', '0')
    ->whereYear('Tanggal', $tahunSekarang)
    ->whereMonth('Tanggal',$bulanSekarang)
    ->get();
  }

  public function dataKamar($bulanSekarang, $tahunSekarang){
    return \DB::connection('sqlsrv')->table('SIMtrKasir')
    ->selectRaw('count(KelasID) as jumlahKamar')
    ->where('Batal', '0')
    ->where('KelasID','!=','xx')
    ->whereYear('Tanggal', $tahunSekarang)
    ->whereMonth('Tanggal',$bulanSekarang)
    ->get();
  }

  public function revenueDaily($bulanSekarang, $tahunSekarang){
    // $simTrKasir =  \DB::connection('sqlsrv')->table('SIMtrKasir')
    // ->selectRaw('SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) as totalRevenue')
    // ->where('Batal', '0')
    // ->whereYear('Tanggal', $tahunSekarang)
    // ->whereMonth('Tanggal',$bulanSekarang)
    // ->get();

    // $obatBebas = \DB::connection('sqlsrv')
    //     ->table('SIMtrPembayaranObatBebas')
    //     ->selectRaw('SUM(NilaiTransaksi) as totalRevenue')
    //     ->where('Batal', '0')
    //     ->where('tipe', 'OBAT BEBAS')
    //     ->whereYear(\DB::raw('convert(date,Tanggal,111)'), $tahunSekarang)
    //     ->whereMonth(\DB::raw('convert(date,Tanggal,111)'),$bulanSekarang)
    //     ->get();

    // $tataBoga = \DB::connection('sqlsrv')
    //     ->table('TBO_Transaksi')
    //     ->selectRaw('SUM(totalnilai) as totalRevenue')
    //     ->join('SIMtrPembayaranObatBebas', 'SIMtrPembayaranObatBebas.NoBuktiTataBoga', '=', 'TBO_Transaksi.NoBukti')
    //     ->where('SIMtrPembayaranObatBebas.Batal', '0')
    //     ->where('SIMtrPembayaranObatBebas.tipe', 'TATA BOGA')
    //     ->whereYear(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'), $tahunSekarang)
    //     ->whereMonth(\DB::raw('convert(date,SIMtrPembayaranObatBebas.Tanggal,111)'),$bulanSekarang)
    //     ->get();

    // $diskon = \DB::connection('sqlsrvbo')
    //     ->table('TBJ_Transaksi_Detail')
    //     ->selectRaw('(SUM(TBJ_Transaksi_Detail.Debit) - SUM(TBJ_Transaksi_Detail.Kredit)) as totalRevenue')
    //     ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
    //     ->whereIn('Akun_ID', ['2234', '2235','2231','2502'])
    //     ->whereYear('Transaksi_Date', $tahunSekarang)
    //     ->whereMonth('Transaksi_Date',$bulanSekarang)
    //     ->get();

    // $tambahanLainnya = \DB::connection('sqlsrvbo')
    //     ->table('TBJ_Transaksi_Detail')
    //     ->selectRaw('(SUM(coalesce(TBJ_Transaksi_Detail.Kredit,0)) - SUM(coalesce(TBJ_Transaksi_Detail.Debit,0))) as totalRevenue')
    //     ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
    //     ->whereIn('Akun_ID', ['2227','2391', '2535', '2405', '2406'])
    //     ->whereYear('Transaksi_Date', $tahunSekarang)
    //     ->whereMonth('Transaksi_Date',$bulanSekarang)
    //     ->get();

    // $simTrKasir[0]->totalRevenue = $simTrKasir[0]->totalRevenue + $obatBebas[0]->totalRevenue + $tataBoga[0]->totalRevenue - $diskon[0]->totalRevenue + $tambahanLainnya[0]->totalRevenue;
    // return $simTrKasir;

    $dataSanata = \DB::connection('sqlsrvbo')
    ->table('TBJ_Transaksi_Detail')
    ->selectRaw('(SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as totalRevenue')
    ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
    // ->whereIn('Akun_ID', \DB::selectRaw('select Akun_ID from Mst_Akun where Group_ID = 4 and Aktif = 1'))
    ->whereIn('Akun_ID', function($query){
      $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1);
    })
    ->whereYear('Transaksi_Date', $tahunSekarang)
    ->whereMonth('Transaksi_Date',$bulanSekarang)
    ->get();

    return $dataSanata;
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

  public function createChartRevenue(){
    $tglHelper = new HelperTanggal();
    $tglSekarang= \Carbon\Carbon::now();
    $tglSekarang = $tglHelper->tanggalRangeWithBulanTahun($tglSekarang);
    $tahunAktif = Tahun::whereStatus(1)->first();
    $tahunAktif = (int) $tahunAktif->tahun;
    $datas = $this->getDataForChartRevenue($tahunAktif);
    
    $barchart = \Lava::DataTable();
    $barchart->addStringColumn('Date');
    for($i = ($tahunAktif-2) ; $i <= $tahunAktif; $i++){
      $barchart->addNumberColumn(strval($i));
      $barchart->addRoleColumn('string', 'annotation');
    }
    $barchart->addRoleColumn('string', 'style');
    $barchart->addNumberColumn('Target '.$tahunAktif);
    // $barchart->addNumberColumn($tahunAktif);

    foreach($datas as $data){
      $barchart->addRow($data);
    }

    \Lava::ColumnChart('Votes', $barchart, [
       'legend' => [
            'position' => 'bottom'
        ],
        'barGroupWidth' => 1000,
        'colors' => ['#b5d498', '#27bcd8', '#0a57f5','green'],
        'title' => 'RSIA Puri Bunda Denpasar Revenue',
        'textStyle' => [
            'color'    => 'rgb(123, 65, 89)',
        ],
        'seriesType' => 'bars',
        'series' => [
            3 => ['type' => 'line']
        ],
        'height' => 350,
    ]);
  }

  public function getDataForChartRevenue($tahun){
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $tahunRevenueX = Tahun::with('revenue', 'revenue.revenueTransaksi', 'revenue.revenueTransaksi.bulan')->where('tahun',$tahun)->first();
    $tahunRevenueX_1 = Tahun::with('revenue', 'revenue.revenueTransaksi', 'revenue.revenueTransaksi.bulan')->where('tahun',$tahun-1)->first();
    $tahunRevenueX_2 = Tahun::with('revenue', 'revenue.revenueTransaksi', 'revenue.revenueTransaksi.bulan')->where('tahun',$tahun-2)->first();
    // $targetRevenue = ((float) $tahunRevenueX->revenue[0]->targetRevenue) / 12 ;

    $data = array();
    for ($i =0 ; $i<12 ; $i++){
      if($i == $bulanSekarang-1){
        $color = 'color:grey';
      }else{
        $color = '';
      }
      $anotation = '';
      
      $data[] = [
        $tahunRevenueX->revenue[0]->revenueTransaksi[$i]->bulan->bulanSingkatan, 
        $tahunRevenueX_2->revenue[0]->revenueTransaksi[$i]->totalRevenue,$anotation,
        $tahunRevenueX_1->revenue[0]->revenueTransaksi[$i]->totalRevenue,$anotation, 
        $tahunRevenueX->revenue[0]->revenueTransaksi[$i]->totalRevenue, $anotation, $color,
      ];
    }
    array_push($data[0], $tahunRevenueX->revenue[0]->budgetJanuari);
    array_push($data[1], $tahunRevenueX->revenue[0]->budgetFebruari);
    array_push($data[2], $tahunRevenueX->revenue[0]->budgetMaret);
    array_push($data[3], $tahunRevenueX->revenue[0]->budgetApril);
    array_push($data[4], $tahunRevenueX->revenue[0]->budgetMei);
    array_push($data[5], $tahunRevenueX->revenue[0]->budgetJuni);
    array_push($data[6], $tahunRevenueX->revenue[0]->budgetJuli);
    array_push($data[7], $tahunRevenueX->revenue[0]->budgetAgustus);
    array_push($data[8], $tahunRevenueX->revenue[0]->budgetSeptember);
    array_push($data[9], $tahunRevenueX->revenue[0]->budgetOktober);
    array_push($data[10], $tahunRevenueX->revenue[0]->budgetNovember);
    array_push($data[11], $tahunRevenueX->revenue[0]->budgetDesember);
    return $data;
  }

  public function createChartSpedoMeterRevenueTahunan(){
    $tahun = \Carbon\Carbon::now(); 
    $bulanSekarang = \Carbon\Carbon::now()->month;

    $revenueCurrent = RevenueTransaksi::selectRaw('sum(totalRevenue) as totalRevenue')
      ->join('trRevenue','trRevenue.id', 'trRevenueTransaksi.idTrRevenue')
      ->join('mTahun','mTahun.id', 'trRevenue.idTahun')
      ->where('mTahun.tahun',$tahun->year)->first();

    $revenueCurrent =  $revenueCurrent->totalRevenue;
    $dataChart = $this->getDataChartSpedoMeterRevenue($tahun->year);

    $revenueTahunan = \Lava::DataTable();
    $revenueTahunan->addStringColumn('Type')
      ->addNumberColumn('Value')
      ->addRow(['Th.'.$tahun->year, round($this->convertPersen($dataChart->targetRevenue,$revenueCurrent),2)]);

    $minBatasMerah = ($dataChart->minBatasMerah / 100) * $dataChart->targetRevenue;
    $maxBatasMerah = ($dataChart->maxBatasMerah / 100) * $dataChart->targetRevenue;
    $minBatasKuning = (($dataChart->maxBatasMerah / 100) * $dataChart->targetRevenue) + 1;
    $maxBatasKuning = ($dataChart->maxBatasKuning / 100) * $dataChart->targetRevenue;
    $minBatasHijau = (($dataChart->maxBatasKuning / 100) * $dataChart->targetRevenue) + 1;
    $maxBatasHijau = ($dataChart->maxBatasHijau / 100) * $dataChart->targetRevenue;


    \Lava::GaugeChart('revenueTahunan', $revenueTahunan, [
      'width'      => 150,
      'height'     => 150,
      'greenFrom'  => $this->convertPersen($dataChart->targetRevenue, $minBatasHijau),
      'greenTo'    => $this->convertPersen($dataChart->targetRevenue, $maxBatasHijau),
      'yellowFrom' => $this->convertPersen($dataChart->targetRevenue, $minBatasKuning),
      'yellowTo'   => $this->convertPersen($dataChart->targetRevenue, $maxBatasKuning),
      'redFrom'    => $this->convertPersen($dataChart->targetRevenue, $minBatasMerah),
      'redTo'      => $this->convertPersen($dataChart->targetRevenue, $maxBatasMerah),
      'majorTicks' => [
          '',
          ''
      ],
    ]);
  }

  public function createChartSpedoMeterRevenueBulanBerjalan(){
    $tahun = \Carbon\Carbon::now();
    $bulanSekarang = \Carbon\Carbon::now()->month;
    $bulanUcapan = new HelperTanggal();
    $bulanUcapan = $bulanUcapan->bacaBulanSingkatan($bulanSekarang);

    $revenueCurrent = RevenueTransaksi::selectRaw('sum(totalRevenue) as totalRevenue')
      ->join('trRevenue','trRevenue.id', 'trRevenueTransaksi.idTrRevenue')
      ->join('mTahun','mTahun.id', 'trRevenue.idTahun')
      ->where('mTahun.tahun',$tahun->year)->first();
    $revenueCurrent =  $revenueCurrent->totalRevenue;
    $dataChart = $this->getDataChartSpedoMeterRevenue($tahun->year);
    $targetRevenueBulanBerjalan = 0;

    if(1 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetJanuari;
    }
    if(2 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetFebruari;
    }
    if(3 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetMaret;
    }
    if(4 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetApril;
    }
    if(5 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetMei;
    }
    if(6 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetJuni;
    }
    if(7 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetJuli;
    }
    if(8 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetAgustus;
    }
    if(9 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetSeptember;
    }
    if(10 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetOktober;
    }
    if(11 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetNovember;
    }
    if(12 <= $bulanSekarang){
      $targetRevenueBulanBerjalan += $dataChart->budgetDesember;
    }  

    $minBatasMerah = ($dataChart->minBatasMerah / 100) * $targetRevenueBulanBerjalan;
    $maxBatasMerah = ($dataChart->maxBatasMerah / 100) * $targetRevenueBulanBerjalan;
    $minBatasKuning = (($dataChart->maxBatasMerah / 100) * $targetRevenueBulanBerjalan) + 1;
    $maxBatasKuning = ($dataChart->maxBatasKuning / 100) * $targetRevenueBulanBerjalan;
    $minBatasHijau = (($dataChart->maxBatasKuning / 100) * $targetRevenueBulanBerjalan) + 1;
    $maxBatasHijau = ($dataChart->maxBatasHijau / 100) * $targetRevenueBulanBerjalan;

    $revenueBulanan = \Lava::DataTable();
    $revenueBulanan->addStringColumn('Type')
      ->addNumberColumn('Value')
      ->addRow(['Jan-'.$bulanUcapan, round($this->convertPersen($targetRevenueBulanBerjalan,$revenueCurrent),2)]);

    \Lava::GaugeChart('revenueBulanan', $revenueBulanan, [
      'width'      => 150,
      'height'    => 150,
      'greenFrom'  => $this->convertPersen($targetRevenueBulanBerjalan,$minBatasHijau),
      'greenTo'    => $this->convertPersen($targetRevenueBulanBerjalan,$maxBatasHijau),
      'yellowFrom' => $this->convertPersen($targetRevenueBulanBerjalan,$minBatasKuning),
      'yellowTo'   => $this->convertPersen($targetRevenueBulanBerjalan,$maxBatasKuning),
      'redFrom'    => $this->convertPersen($targetRevenueBulanBerjalan,$minBatasMerah),
      'redTo'      => $this->convertPersen($targetRevenueBulanBerjalan,$maxBatasMerah),
      'majorTicks' => [
          '',
          ''
      ],
    ]);
  }
  
  public function convertPersen($dataMax, $dataConvert){
    return ($dataConvert/$dataMax)*100;
  }

  public function getDataChartSpedoMeterRevenue($year){
    return Revenue::selectRaw('*')->join('mTahun','mTahun.id', 'trRevenue.idTahun')->where('mTahun.tahun',$year)->first();
  }
}
