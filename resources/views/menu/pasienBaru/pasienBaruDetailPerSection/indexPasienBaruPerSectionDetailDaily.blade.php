@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Pasien Baru')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien baru') }}">Pasien Baru</a>    
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard detail section pasien baru', $idSectionSanata) }}">Pasien Baru PerSection</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">{{ $kategori->namaKategori }} </a>
  </div>

  <h3 class="ui dividing header">
    Dashboard Daily Jumlah Pasien Baru {{ $kategori->namaKategori }} - {{ $tglSekarang }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="jumlahPasienDaily"></div>
      @linechart('jumlahPasienDaily','jumlahPasienDaily')
    </div>
  </div>
@endsection
