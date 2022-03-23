@extends('master.master.masterPage')
@section('pageTitle','Dashboard Rawat Jalan')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Rawat Jalan</a>
  </div>
  <div class="ui divider"></div>

<div class="ui grid">
  @foreach($dataListPoliPerharis as $dataListPoliPerhari)
    <div class="sixteen wide mobile eight wide tablet five wide computer column">
      <div class="ui fluid card green">
        <div class="content">
          <div class="header">
            {{ $dataListPoliPerhari->namaSection }}
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
                    Rp {{ ' '. $dataListPoliPerhari->totalRevenue }}
                  </b></span>
                </div>
              </div>
              <div class="column">
                <h4 class="ui header">YTD Revenue</h4>
                <div class="ui labeled icon">
                  <i class="money icon"></i>
                  <span><b>
                    Rp {{ ' '. $dataListPoliPerhari->totalRevenueYTD }}
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
                    {{' '.$dataListPoliPerhari->jumlahPasien }}
                  </b></span>
                </div>            
              </div>
              <div class="column">
                <h4 class="ui header">YTD Pasien</h4>
                <div class="ui labeled icon">
                  <i class="users icon"></i>
                  <span><b>
                    {{' '.$dataListPoliPerhari->jumlahPasienYTD }}
                  </b></span>
                </div>            
              </div>         
            </div>       
          </div>
        </div>
        <div class="extra content">
          <a class="ui labeled icon button fluid primary" href="{{ route('dashboard detail rawat jalan', $dataListPoliPerhari->SectionPerawatanID ) }}">
            <i class="chart line icon"></i> Grafik YTD
          </a>
        </div>
        <div class="extra content">
          <a class="ui labeled icon button fluid green" href="{{ route('dashboard detail daily rawat jalan', $dataListPoliPerhari->SectionPerawatanID) }}">
            <i class="chart bar icon"></i> Grafik MTD
          </a>
        </div>          
        <div class="extra content">
          <a class="ui labeled icon button fluid yellow" href="{{ route('dashboard dokter rawat jalan', $dataListPoliPerhari->SectionPerawatanID) }}">
            <i class="user icon"></i> Data Dokter
          </a>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection
