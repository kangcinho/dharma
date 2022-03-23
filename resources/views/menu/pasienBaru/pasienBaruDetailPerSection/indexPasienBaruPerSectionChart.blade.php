@extends('master.master.masterPage')
@section('pageTitle','Dashboard Pasien Baru')
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
    <a class="active section"> {{ $tahunX[0]->namaKategori }} </a>
  </div>

  <h3 class="ui dividing header">
    Dashboard {{ $tahunX[0]->namaKategori }}
  </h3>
  <div class="ui grid">
  	<div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
  		<div id="chartJumlahPasienBaru"></div>
    	@linechart('jumlahPasienBaru','chartJumlahPasienBaru')
  	</div>
  </div>

  <br>
  <br>
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
