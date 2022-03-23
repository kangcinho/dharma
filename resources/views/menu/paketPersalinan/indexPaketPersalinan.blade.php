@extends('master.master.masterPage')
@section('pageTitle','Dashboard Paket Persalinan')
@section('content')
<h3 class="ui top attached segment teal inverted segment">
  Periode Transaksi Paket Persalinan
</h3>
<div class="ui attached segment clearing">
  <form class="ui form" action="{{ route('get data paket persalinan') }}" method="POST">
    @csrf
    <div class="two fields">
      <div class="field">
        <label>Periode Awal Transaksi Paket Persalinan</label>
        <input type="date" name="firstPeriode" id="firstPeriode" value="" required>
      </div>
      <div class="field">
        <label>Periode Akhir Transaksi Paket Persalinan</label>
        <input type="date" name="lastPeriode" id="lastPeriode" value="" required>
      </div>
    </div>
    <button type="submit" name="prosesData" id="prosesData" class="ui button primary labeled icon right floated">
      <i class="sync icon"></i>
      Tampilkan Data Paket Persalinan
    </button>
  </form>
</div>

@if(isset($dataCounting))
  <h3 class="ui top attached segment teal inverted segment">
    Result
  </h3>
  <div class="ui attached segment clearing">
    <h4 class="ui header">
      <div class="content">
        Jumlah Transaksi Jenis Paket: {{ count($dataCounting) }} Paket
        <div class="sub header">Periode: {{$tglAwal}} - {{$tglAkhir}}</div>
      </div>
      <br/>
      <br/>
    <table id="dataPaket" class="ui very compact small table strip celled selectable stackable">
      <thead>
        <tr class="center aligned">
          <th>Deskripsi Paket</th>
          <th>Jumlah Penjualan {{$tglAwal}} - {{$tglAkhir}} </th>
          <th>Jumlah Penjualan {{$tglAwalBulanLalu}} - {{$tglAkhirBulanLalu}} </th>
        </tr>
      </thead>
      <tbody>
        @foreach($dataCounting as $dataPaket)
          <tr>
            <td>{{ $dataPaket->namaJasa }}</td>
            <td class="right aligned">{{ $dataPaket->jumlahPasien }}</td>
            <td class="right aligned">{{ $dataPaket->jumlahPasienBulanLalu }}</td>
          </tr>
        @endforeach()
      </tbody>
      <tbody></tbody>
    </table>
  </div>
@endif

@endsection

@section('additionalCSS')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.semanticui.min.css">
@endsection

@section('additionalJS')
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>

  <script type="text/javascript">
     $(document).ready(function() {
        $('#dataPaket').DataTable({
          "order": [[ 1, "desc" ]]
        });

        $('#prosesData').on('click', function(e){
          if($('#firstPeriode').val() != '' && $('#lastPeriode').val() != ''){
            $('#loaderDataBayi').addClass('active')
          }
        })
      });
  </script>

@endsection