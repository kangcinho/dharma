@extends('master.master.masterPage')
@section('pageTitle','Dashboard Paket Persalinan')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard rawat inap') }}">Rawat Inap</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Paket Persalinan Detail</a>
  </div>
  <div class="ui divider"></div>

  <div class="ui grid">
    @foreach($dataPaketPersalinans as $dataPaketPersalinan)
      <div class="sixteen wide mobile eight wide tablet five wide computer column">
        <div class="ui fluid card green">
          <div class="content">
            <div class="header">
                {{ $dataPaketPersalinan->namaSection }}
            </div>
            <div class="meta">
              <div class="time">{{ $tanggalSekarang }}</div>
            </div>
          </div>
          <div class="content">
            <div class="ui grid two column internally celled">
              <div class="row">
                <div class="column">
                  <h4 class="ui header">MTD Revenue</h4>
                  <div class="ui labeled icon">
                    <i class="money icon"></i>
                    <span><b>
                      Rp {{ ' '. $dataPaketPersalinan->totalRevenue }}
                    </b></span>
                  </div>
                </div>
                <div class="column">
                  <h4 class="ui header">YTD Revenue</h4>
                  <div class="ui labeled icon">
                    <i class="money icon"></i>
                    <span><b>
                      Rp {{ ' '. $dataPaketPersalinan->totalRevenueYTD }}
                    </b></span>
                  </div>
                </div>         
              </div>
              <div class="row">
                <div class="column">
                  <h4 class="ui header">MTD Pasien</h4>
                  <div class="ui labeled icon">
                    <i class="users icon"></i>
                    <span><b>
                      {{' '.$dataPaketPersalinan->jumlahPasien }}
                    </b></span>
                  </div>            
                </div>
                <div class="column">
                  <h4 class="ui header">YTD Pasien</h4>
                  <div class="ui labeled icon">
                    <i class="users icon"></i>
                    <span><b>
                      {{' '.$dataPaketPersalinan->jumlahPasienYTD }}
                    </b></span>
                  </div>            
                </div>         
              </div>       
            </div>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid primary" href="{{ route('dashboard paket persalinan detail persection', $dataPaketPersalinan->SectionPerawatanID )}}">
              <i class="chart line icon"></i> Grafik YTD
            </a>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid green" href="{{ route('dashboard paket persalinan detail persection daily', $dataPaketPersalinan->SectionPerawatanID) }}">
            <i class="chart bar icon"></i> Grafik MTD
            </a>
          </div>          
        </div>
      </div>
    @endforeach
  </div>
@endsection
