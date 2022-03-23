@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Pasien Repeater')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard pasien repeater') }}">Pasien Repeater</a>    
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard detail section pasien repeater', $idSectionSanata) }}">Pasien Repeater PerSection</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">{{ $kategori->namaKategori }} </a>
  </div>

  <h3 class="ui dividing header">
    Dashboard Daily Jumlah Pasien Repeater {{ $kategori->namaKategori }} - {{ $tglSekarang }}
  </h3>
  <div class="ui stackable one column wide grid">
    <div class="column">
      <div id="jumlahPasienDaily"></div>
      @linechart('jumlahPasienDaily','jumlahPasienDaily')
    </div>
  </div>
@endsection
