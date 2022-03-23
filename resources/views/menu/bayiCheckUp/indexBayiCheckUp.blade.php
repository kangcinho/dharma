@extends('master.master.masterPage')
@section('pageTitle','Dashboard Bayi Check Up')
@section('content')
<h3 class="ui top attached segment teal inverted segment">
  Periode Bayi Check Up
</h3>
<div class="ui attached segment clearing">
  <form class="ui form" action="{{ route('get data bayi check up') }}" method="POST">
    @csrf
    <div class="two fields">
      <div class="field">
        <label>Periode Awal Bayi Lahir</label>
        <input type="date" name="firstPeriode" id="firstPeriode" value="" required>
      </div>
      <div class="field">
        <label>Periode Akhir Bayi Lahir</label>
        <input type="date" name="lastPeriode" id="lastPeriode" value="" required>
      </div>
    </div>
    <button type="submit" name="prosesData" id="prosesData" class="ui button primary labeled icon right floated">
      <i class="sync icon"></i>
      Tampilkan Data Bayi Check Up
    </button>
  </form>
</div>

@if(isset($dataCounting))
  <h3 class="ui top attached segment teal inverted segment">
    Result
  </h3>
  <div class="ui attached segment">
    <h4 class="ui left floated header">
      <div class="content">
        Jumlah Bayi Lahir: {{ count($dataCounting) }} Orang
        <div class="sub header">Periode: {{$tglAwal}} - {{$tglAkhir}}</div>
      </div>
    </h4>
    <div class="ui right floated right aligned header">
      <div class="ui header big content">
        {{ round((( count($bayiReCheckUp) / count($dataCounting) ) * 100),2).' %' }}
        <div class="sub header">
          {{ count($bayiReCheckUp) }} Bayi Kontrol Kembali
        </div>
      </div>
    </div>
    <br/>
    <br/>
    <br/>
    <div class="ui divider"></div>
    
    <table id="dataPasien" class="ui very compact small table strip celled selectable stackable">
      <thead>
        <tr class="center aligned">
          <th>NRM</th>
          <th>Tgl Lahir</th>
          <th>Nama</th>
          <th>Frekuensi Kedatangan Pasien</th>
        </tr>
      </thead>
      <tbody>
        @foreach($dataCounting as $dataPasien)
          @if($dataPasien->jumlahDatang == 1)
            @continue
          @endif
          <tr>
            <td>{{ $dataPasien->NRM }}</td>
            <td>{{ $dataPasien->tglReg }}</td>
            <td>{{ $dataPasien->namaPasien }}</td>
            <td class="right aligned">{{ $dataPasien->jumlahDatang }}</td>
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
        $('#dataPasien').DataTable({
          "order": [[ 3, "desc" ]]
        });

        $('#prosesData').on('click', function(e){
          if($('#firstPeriode').val() != '' && $('#lastPeriode').val() != ''){
            $('#loaderDataBayi').addClass('active')
          }
        })
      });
  </script>

@endsection