@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Revenue')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard rawat inap') }}"> Rawat Inap </a><i class="right arrow icon divider"></i>
    <div class="active section">{{ $kategori->namaKategori }} </div>
  </div>

  <h3 class="ui dividing header">
    Dashboard Daily {{ $kategori->namaKategori }} - {{ $tglSekarang }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="revenueDailyRI"></div>
      @linechart('revenueDailyRI','revenueDailyRI')
    </div>
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
      <div id="jumlahPasienDailyRI"></div>
      @linechart('jumlahPasienDailyRI','jumlahPasienDailyRI')
    </div>
  </div>
@endsection
