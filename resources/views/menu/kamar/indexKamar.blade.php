@extends('master.master.masterPage')
@section('pageTitle','Dashboard Kamar')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Data Kamar</a>
  </div>
  <div class="ui divider"></div>

  <div class="ui grid">
    @foreach($dataKamarPerBulans as $dataKamarPerBulan)
      <div class="sixteen wide mobile eight wide tablet five wide computer column">
        <div class="ui fluid card green">
          <div class="content">
            <div class="header">
              {{ $dataKamarPerBulan->namaKelas }}
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
                      {{' '.$dataKamarPerBulan->jumlahPasien }}
                    </b></span>
                  </div>            
                </div>
                <div class="column">
                  <h4 class="ui header">YTD Pasien</h4>
                  <div class="ui labeled icon">
                    <i class="users icon"></i>
                    <span><b>
                      {{' '.$dataKamarPerBulan->jumlahPasienYTD }}
                    </b></span>
                  </div>            
                </div>         
              </div>       
            </div>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid primary" href="{{ route('dashboard detail kamar', $dataKamarPerBulan->kelasID ) }}">
              <i class="chart line icon"></i> Grafik YTD
            </a>
          </div>
          <div class="extra content">
            <a class="ui labeled icon button fluid green" href="{{ route('dashboard detail kamar daily', $dataKamarPerBulan->kelasID) }}">
            <i class="chart bar icon"></i> Grafik MTD
            </a>
          </div>          
        </div>
      </div>
    @endforeach
  </div>
@endsection
