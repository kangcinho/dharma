<?php

namespace App\KangCinHo;
use App\Revenue;
use App\RevenueTransaksi;
use App\Tahun;
use App\KangCinHo\HelperTanggal;

class HelperRevenue{

  // public function createChartRevenue(){
  //   $finances = \Lava::DataTable();
  //   $finances->addDateColumn('Year')
  //            ->addNumberColumn('Sales')
  //            ->addNumberColumn('Expenses')
  //            ->addRoleColumn('string', 'style')
  //            ->setDateTimeFormat('Y')
  //            ->addRow(['2004',1000, 400, 'color:orange'])
  //            ->addRow(['2005',1170, 460, 'color:grey']);

  //   \Lava::ColumnChart('Votes', $finances, [
  //     'title' => 'Company Performance',
  //     'titleTextStyle' => [
  //         'color'    => '#eb6b2c',
  //         'fontSize' => 14
  //     ]
  //   ]);
  // }


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
    $bulanUcapan = $bulanUcapan->bacaBulan($bulanSekarang);

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
