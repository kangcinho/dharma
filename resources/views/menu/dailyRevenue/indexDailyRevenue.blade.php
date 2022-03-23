@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Revenue')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right arrow icon divider"></i>
    <a class="active section"> Revenue Daily </a>
  </div>

  <h3 class="ui dividing header">
    Dashboard Revenue MTD {{ $tglSekarang }}
  </h3>
  <div class="ui stackable one column wide grid">
  	<div class="column">
  		<div id="chartJumlahKamar"></div>
    	@linechart('revenueDaily','chartJumlahKamar')
  	</div>
  </div>
  <div class="ui header center aligned">
    {{-- {{ "Total Revenue Bulan ".$tglSekarang." : Rp ". $totalRevenue }} --}}
  </div>
  
  <h3 class="ui dividing header">
    Komparasi Daily Revenue
  </h3>
  <table class="ui celled striped table">
    <thead class="center aligned">
      <tr>
        <th colspan="4">
          Komparasi Daily Revenue Tanggal {{ $tglRange }}
        </th>
      </tr>
      <tr>
        <th>
          {{ $lastMonth.' '.$tahunSekarang }}
        </th>
        <th>
          {{ $bulanSekarang.' '.$tahunSekarang }}
        </th>
        <th>
          {{ $bulanSekarang.' '.(((int) $tahunSekarang)-1) }}
        </th>
        <th>
          {{ $bulanSekarang.' '.(((int) $tahunSekarang)-2) }}
        </th>
      </tr>
    </thead>
    <tbody>
      <tr class="right aligned">
        <td>{{ 'Rp '.$dailyRevenueKomparasiLastMonth }}</td>
        <td>{{ 'Rp '.$dailyRevenueKomparasi }}</td>
        <td>{{ 'Rp '.$dailyRevenueKomparasi_1 }}</td>
        <td>{{ 'Rp '.$dailyRevenueKomparasi_2 }}</td>
      </tr>
    </tbody>
  </table>
@endsection
