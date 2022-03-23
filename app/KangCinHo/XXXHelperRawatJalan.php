<?php

namespace App\KangCinHo;

class HelperRawatJalan{
  public function createChartPoliAnak(){
    $datas = $this->getDataPoliAnak();
    $barchart = \Lava::DataTable();
        $barchart->addStringColumn('Date')
                 ->addNumberColumn('2018')
                 ->addNumberColumn('2019');
                 // ->addNumberColumn('Target 2019');

        foreach($datas as $data){
          $barchart->addRow($data);
        }

    \Lava::BarChart('polianak', $barchart, [
       'legend' => [
            'position' => 'bottom'
        ],
        'title' => 'Poli Anak',
        'textStyle' => [
            'color'    => 'rgb(123, 65, 89)',
            'fontSize' => 11,
        ],
        'seriesType' => 'bars',
        'series' => [
            2 => ['type' => 'line']
        ],
        'fontSize' => 12
    ]);
  }

  public function getDataPoliAnak(){
    $data = [
      ['Jan', 1100, 490],
      ['Feb', 1000, 400],
      ['Mar', 1400, 450],
      ['Apr', 1250, 600],
      ['Mei', 222, 333],
      ['Jun',222,333],
      ['Jul',222,333],
      ['Aug',222,333],
      ['Sep',222,333],
      ['Okt',222,333],
      ['Nov',222,333],
      ['Des',222,333]
    ];
    return $data;
  }

  public function createChartPoliObgyn(){
    $datas = $this->getDataPoliObgyn();
    $barchart = \Lava::DataTable();
        $barchart->addStringColumn('Date')
                 ->addNumberColumn('2018')
                 ->addNumberColumn('2019');
                 // ->addNumberColumn('Target 2019');

        foreach($datas as $data){
          $barchart->addRow($data);
        }

    \Lava::BarChart('poliobgyn', $barchart, [
       'legend' => [
            'position' => 'bottom'
        ],
        'title' => 'Poli Obgyn',
        'textStyle' => [
            'color'    => 'rgb(123, 65, 89)',
            'fontSize' => 11,
        ],
        'seriesType' => 'bars',
        'series' => [
            2 => ['type' => 'line']
        ],
        'fontSize' => 12
    ]);
  }

  public function getDataPoliObgyn(){
    $data = [
      ['Jan', 1100, 490],
      ['Feb', 1000, 400],
      ['Mar', 1400, 450],
      ['Apr', 1250, 600],
      ['Mei', 222, 333],
      ['Jun',222,333],
      ['Jul',222,333],
      ['Aug',222,333],
      ['Sep',222,333],
      ['Okt',222,333],
      ['Nov',222,333],
      ['Des',222,333]
    ];
    return $data;
  }

  public function createChartUGD(){
    $datas = $this->getDataUGD();
    $barchart = \Lava::DataTable();
        $barchart->addStringColumn('Date')
                 ->addNumberColumn('2018')
                 ->addNumberColumn('2019');
                 // ->addNumberColumn('Target 2019');

        foreach($datas as $data){
          $barchart->addRow($data);
        }

    \Lava::BarChart('ugd', $barchart, [
       'legend' => [
            'position' => 'bottom'
        ],
        'title' => 'UGD',
        'textStyle' => [
            'color'    => 'rgb(123, 65, 89)',
            'fontSize' => 11,
        ],
        'seriesType' => 'bars',
        'series' => [
            2 => ['type' => 'line']
        ],
        'fontSize' => 12
    ]);
  }

  public function getDataUGD(){
    $data = [
      ['Jan', 1100, 490],
      ['Feb', 1000, 400],
      ['Mar', 1400, 450],
      ['Apr', 1250, 600],
      ['Mei', 222, 333],
      ['Jun',222,333],
      ['Jul',222,333],
      ['Aug',222,333],
      ['Sep',222,333],
      ['Okt',222,333],
      ['Nov',222,333],
      ['Des',222,333]
    ];
    return $data;
  }

  public function createChartAllPoli(){
    $datas = $this->getDataAllPoli();
    $barchart = \Lava::DataTable();
        $barchart->addStringColumn('Date')
                 ->addNumberColumn('Poli Anak')
                 ->addNumberColumn('Poli Obgyn')
                 ->addNumberColumn('UGD')
                 ->addNumberColumn('Poli Anak')
                 ->addNumberColumn('Poli Obgyn')
                 ->addNumberColumn('UGD')
                 ->addNumberColumn('Poli Anak')
                 ->addNumberColumn('Poli Obgyn')
                 ->addNumberColumn('UGD')
                 ->addNumberColumn('Poli Anak')
                 ->addNumberColumn('Poli Obgyn')
                 ->addNumberColumn('UGD');
                 // ->addNumberColumn('Target 2019');

        foreach($datas as $data){
          $barchart->addRow($data);
        }

    \Lava::ColumnChart('allpoli', $barchart, [
       'legend' => [
            'position' => 'bottom'
        ],
        'title' => 'Kunjungan Poli Tahun 2019',
        'textStyle' => [
            'color'    => 'rgb(123, 65, 89)',
            'fontSize' => 13,
        ],
        'explorer' => [
          'actions' => [
            'dragToZoom', 'rightClickToReset'
          ]
        ]
        // 'events' => [
        //   'select' => 'selectCallback'
        // ]
        // 'seriesType' => 'bars',
        // 'series' => [
        //     2 => ['type' => 'bars']
        // ],
        // 'fontSize' => 12,
        // 'width' => 950
    ]);
  }

  public function getDataAllPoli(){
    $data = [
      ['Jan', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      ['Feb', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      ['Mar', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Apr', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Mei', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Jun', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Jul', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Aug', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Sep', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Okt', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Nov', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900],
      // ['Des', 1100, 490,900, 1100, 490,900, 1100, 490,900, 1100, 490,900]
    ];
    return $data;
  }

}
