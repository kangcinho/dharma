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
    <a class="active section">Paket Persalinan {{ $tahunX[0]->namaKategori }}</a>
  </div>
  <div class="ui divider"></div>

  <h3 class="ui dividing header">
    Dashboard Paket Persalinan {{ $tahunX[0]->namaKategori }}
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile sixteen wide tablet eight wide computer column">
      <div id="chartRevenuePasienPaket"></div>
      @linechart('revenuePasienPaket','chartRevenuePasienPaket')
    </div>
    <div class="sixteen wide mobile sixteen wide tablet eight wide computer column">
      <div id="chartJumlahPasienPaket"></div>
      @linechart('jumlahPasienPaket','chartJumlahPasienPaket')
    </div>
  </div>

  <footer class="right floated">
    <a href="{{ route('sync data') }}" class="ui mini button labeled icon grey" id="updateRevenue">
        <i class="refresh icon"></i>
        Update Revenue
    </a>
    <div class="ui label left pointing grey"> Last Update: {{ $lastUpdate }}</div>
  </footer>

@endsection
@section('additionalJS')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#updateRevenue').on('click', function(e){
        $('#loaderUpdate').addClass('active')
      })
    })
  </script>
@endsection