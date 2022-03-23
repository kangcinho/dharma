@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Pasien Baru')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien baru') }}"> Pasien Baru </a><i class="right arrow icon divider"></i>
    <div class="active section">{{ $kategori->namaKategori }} </div>
  </div>

  <h3 class="ui dividing header">
    Dashboard Daily Jumlah {{ $kategori->namaKategori }} - {{ $tglSekarang }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="jumlahPasienDaily"></div>
      @linechart('jumlahPasienDaily','jumlahPasienDaily')
    </div>
  </div>
@endsection
