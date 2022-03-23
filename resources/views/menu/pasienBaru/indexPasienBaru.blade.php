@extends('master.master.masterPage')
@section('pageTitle','Dashboard Pasien Baru')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Pasien Baru</a>
  </div>
  <div class="ui divider"></div>

  <div class="ui grid">
    @foreach($dataListPasienBarus as $dataListPasienBaru)
      <div class="sixteen wide mobile eight wide tablet five wide computer column">
        <div class="ui fluid card green">
          <div class="content">
            <div class="header">
              {{ $dataListPasienBaru->RawatInap=='RJ'?"Pasien Baru: Rawat Jalan":"Pasien Baru: Rawat Inap" }}
            </div>
            <div class="meta">
              <div class="time">{{ $tanggalSekarang }}</div>            
            </div>
          </div>
          <div class="content">
            <div class="ui grid two column internally celled">
              <div class="row">
                <div class="column">
                  <h4 class="ui header">MTD Pasien</h4>
                  <div class="ui labeled icon">
                    <i class="users icon"></i>
                    <span><b>
                      {{' '.$dataListPasienBaru->jumlahPasien }}
                    </b></span>
                  </div>            
                </div>
                <div class="column">
                  <h4 class="ui header">YTD Pasien</h4>
                  <div class="ui labeled icon">
                    <i class="users icon"></i>
                    <span><b>
                      {{' '.$dataListPasienBaru->jumlahPasienYTD }}
                    </b></span>
                  </div>            
                </div>         
              </div>       
            </div>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid primary" href="{{ route('dashboard detail pasien baru', $dataListPasienBaru->RawatInap=='RI'?1:0 ) }}">
              <i class="chart line icon"></i> Grafik YTD
            </a>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid green" href="{{ route('dashboard detail daily pasien baru', $dataListPasienBaru->RawatInap=='RI'?1:0) }}">
              <i class="chart bar icon"></i> Grafik MTD
            </a>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid teal" href="{{ route('dashboard detail section pasien baru', $dataListPasienBaru->RawatInap) }}">
              <i class="setting icon"></i> Detail {{ $dataListPasienBaru->RawatInap=='RJ'?"Pasien Baru: Rawat Jalan":"Pasien Baru: Rawat Inap" }}
            </a>
          </div>          
        </div>
      </div>
    @endforeach
  </div>
@endsection
