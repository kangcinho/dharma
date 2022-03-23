@extends('master.master.masterPage')
@section('pageTitle','Dashboard Paket Persalinan')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard rawat inap') }}">Rawat Inap</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard paket persalinan detail') }}">Paket Persalinan Detail</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Paket Persalinan {{ $kategori->namaKategori }}</a>
  </div>
  <div class="ui divider"></div>

  <h3 class="ui dividing header">
    Dashboard Paket Persalinan {{ $kategori->namaKategori }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="chartRevenuePasienPaket"></div>
      @linechart('jumlahRevenueDailyPaket','chartRevenuePasienPaket')
    </div>
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="chartJumlahPasienPaket"></div>
      @linechart('jumlahPasienDailyPaket','chartJumlahPasienPaket')
    </div>
  </div>

@endsection