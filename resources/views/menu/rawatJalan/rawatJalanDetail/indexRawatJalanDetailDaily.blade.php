@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Revenue')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard rawat jalan') }}"> Rawat Jalan </a><i class="right arrow icon divider"></i>
    <div class="active section">{{ $kategori->namaKategori }} </div>
  </div>

  <h3 class="ui dividing header">
    Dashboard Daily {{ $kategori->namaKategori }} - {{ $tglSekarang }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="revenueDailyRJ"></div>
      @linechart('revenueDailyRJ','revenueDailyRJ')
    </div>

    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="jumlahPasienDailyRJ"></div>
      @linechart('jumlahPasienDailyRJ','jumlahPasienDailyRJ')
    </div>
  </div>
@endsection
