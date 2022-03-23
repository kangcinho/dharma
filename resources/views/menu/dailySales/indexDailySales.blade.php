@extends('master.master.masterPage')
@section('pageTitle','Dashboard Daily Sales')
@section('content')
<h3 class="ui top attached segment teal inverted segment">
  Periode Daily Sales
</h3>
<div class="ui attached segment clearing">
  <form class="ui form" action="{{ route('get data daily sales') }}" method="POST">
    @csrf
    <div class="field">
      <label>Akhir Periode Actual Sales</label>
    <input type="date" name="lastPeriode" id="lastPeriode" value="{{ isset($tglAkhirActualPeriode)?$tglAkhirActualPeriode:null}}" required>
    </div>
    <button type="submit" name="prosesData" id="prosesData" class="ui button primary labeled icon right floated">
      <i class="sync icon"></i>
      Tampilkan Data Daily Sales
    </button>
  </form>
</div>
{{-- <div class="ui attached segment clearing">
  <table id="dailySalesTable" class="ui very compact small table strip celled structured selectable stackable">
    <thead>
      <tr>
        <td>a</td>
        <td>a</td>
        <td>a</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>b</td>
        <td>b</td>
        <td>b</td>
      </tr>
    </tbody>
  </table>
</div> --}}
@if(isset($revenueRawatDarurat))
<h3 class="ui top attached segment teal inverted segment">
  Result
</h3>
<div class="ui attached segment clearing">
  <table id="dailySalesTable" class="ui very compact small table strip celled structured selectable stackable">
    <thead>
      <tr class="center aligned">
        <th rowspan="3">Deskripsi</th>
        <th colspan='2'>Actual</th>
        <th colspan='2'>Last Month</th>
        <th>Last Year</th>
      </tr>
      <tr class="center aligned">
        <th>To Day</th>
        <th>To Date </th>
        <th>To Day</th>
        <th>To Date</th>
        <th>To Date</th>
      </tr>
      <tr class="center aligned">
        <th>{{ $tglToDayActual }}</th>
        <th>{{ $tglToDateActual }} </th>
        <th>{{ $tglToDayLastMonth }}</th>
        <th>{{ $tglToDateLastMonth }}</th>
        <th>{{ $tglToDateLastYear }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Rawat Darurat</td>
        <td class="right aligned">{{ $revenueRawatDarurat->toDayActual }}</td>
        <td class="right aligned">{{ $revenueRawatDarurat->toDateActual }}</td>
        <td class="right aligned">{{ $revenueRawatDarurat->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatDarurat->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatDarurat->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Rawat Jalan</td>
        <td class="right aligned">{{ $revenueRawatJalan->toDayActual }}</td>
        <td class="right aligned">{{ $revenueRawatJalan->toDateActual }}</td>
        <td class="right aligned">{{ $revenueRawatJalan->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatJalan->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatJalan->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Rawat Inap</td>
        <td class="right aligned">{{ $revenueRawatInap->toDayActual }}</td>
        <td class="right aligned">{{ $revenueRawatInap->toDateActual }}</td>
        <td class="right aligned">{{ $revenueRawatInap->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatInap->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueRawatInap->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Voucher</td>
        <td class="right aligned">{{ $revenueVoucher->toDayActual }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateActual }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Cafe</td>
        <td class="right aligned">{{ $revenueCafe->toDayActual }}</td>
        <td class="right aligned">{{ $revenueCafe->toDateActual }}</td>
        <td class="right aligned">{{ $revenueCafe->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueCafe->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueCafe->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Lain-lain</td>
        <td class="right aligned">{{ $revenueLainLain->toDayActual }}</td>
        <td class="right aligned">{{ $revenueLainLain->toDateActual }}</td>
        <td class="right aligned">{{ $revenueLainLain->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueLainLain->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueLainLain->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Diskon Penjualan</td>
        <td class="right aligned">{{ $revenueDiskonPenjualan->toDayActual }}</td>
        <td class="right aligned">{{ $revenueDiskonPenjualan->toDateActual }}</td>
        <td class="right aligned">{{ $revenueDiskonPenjualan->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueDiskonPenjualan->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueDiskonPenjualan->toDateLastYear }}</td>
      </tr>
      <tr class="positive">
        <td>Total Revenue</td>
        <td class="right aligned">{{ $revenueTotal->toDayActual }}</td>
        <td class="right aligned">{{ $revenueTotal->toDateActual }}</td>
        <td class="right aligned">{{ $revenueTotal->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueTotal->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueTotal->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Persalinan SC</td>
        <td class="right aligned">{{ $revenuePersalinanSC->toDayActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanSC->toDateActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanSC->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanSC->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanSC->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Persalinan Normal</td>
        <td class="right aligned">{{ $revenuePersalinanNormal->toDayActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanNormal->toDateActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanNormal->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanNormal->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanNormal->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Persalinan Patalogi</td>
        <td class="right aligned">{{ $revenuePersalinanPatalogi->toDayActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanPatalogi->toDateActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanPatalogi->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanPatalogi->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanPatalogi->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>Voucher</td>
        <td class="right aligned">{{ $revenueVoucher->toDayActual }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateActual }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueVoucher->toDateLastYear }}</td>
      </tr>
      <tr class="positive">
        <td>Revenue Persalinan</td>
        <td class="right aligned">{{ $revenuePersalinanTotal->toDayActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanTotal->toDateActual }}</td>
        <td class="right aligned">{{ $revenuePersalinanTotal->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanTotal->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenuePersalinanTotal->toDateLastYear }}</td>
      </tr>
      <tr class="negative">
        <td>Revenue WIN</td>
        <td class="right aligned">{{ $revenueWIN->toDayActual }}</td>
        <td class="right aligned">{{ $revenueWIN->toDateActual }}</td>
        <td class="right aligned">{{ $revenueWIN->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueWIN->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueWIN->toDateLastYear }}</td>
      </tr>
      <tr class="warning">
        <td>Revenue Vidastana</td>
        <td class="right aligned">{{ $revenueVidastana->toDayActual }}</td>
        <td class="right aligned">{{ $revenueVidastana->toDateActual }}</td>
        <td class="right aligned">{{ $revenueVidastana->toDayLastMonth }}</td>
        <td class="right aligned">{{ $revenueVidastana->toDateLastMonth }}</td>
        <td class="right aligned">{{ $revenueVidastana->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>SC</td>
        <td class="right aligned">{{ $jumlahPasienSC->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienSC->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienSC->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienSC->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienSC->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>NORMAL</td>
        <td class="right aligned">{{ $jumlahPasienNormal->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienNormal->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienNormal->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienNormal->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienNormal->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>PATALOGI</td>
        <td class="right aligned">{{ $jumlahPasienPatalogi->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPatalogi->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPatalogi->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPatalogi->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPatalogi->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>VOUCHER</td>
        <td class="right aligned">{{ $jumlahPasienVoucher->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienVoucher->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienVoucher->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienVoucher->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienVoucher->toDateLastYear }}</td>
      </tr>
      <tr>
        <td>IUI</td>
        <td class="right aligned">{{ $jumlahPasienIUI->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienIUI->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienIUI->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienIUI->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienIUI->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>GYNEKOLOGI</td>
        <td class="right aligned">{{ $jumlahPasienGynekologi->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienGynekologi->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienGynekologi->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienGynekologi->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienGynekologi->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>LAPARASCOPY ANAK</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyAnak->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyAnak->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyAnak->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyAnak->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyAnak->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>LAPARASCOPY OPERATIF</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyOperatif->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyOperatif->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyOperatif->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyOperatif->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienLaparascopyOperatif->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>OPERASI SEDANG / BERAT / KHUSUS / EXTRA BERAT</td>
        <td class="right aligned">{{ $jumlahPasienTindakanOperasi->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienTindakanOperasi->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienTindakanOperasi->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienTindakanOperasi->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienTindakanOperasi->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>HISTERECTOMI</td>
        <td class="right aligned">{{ $jumlahPasienHisterectomi->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienHisterectomi->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienHisterectomi->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienHisterectomi->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienHisterectomi->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>THT ANAK</td>
        <td class="right aligned">{{ $jumlahPasienTHTAnak->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienTHTAnak->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienTHTAnak->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienTHTAnak->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienTHTAnak->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>ET</td>
        <td class="right aligned">{{ $jumlahPasienET->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienET->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienET->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienET->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienET->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>AMNIO INFUSION</td>
        <td class="right aligned">{{ $jumlahPasienAminioInFusion->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienAminioInFusion->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienAminioInFusion->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienAminioInFusion->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienAminioInFusion->toDateLastYear }}</td>
      </tr> 
      <tr>
        <td>BEDAH ANAK</td>
        <td class="right aligned">{{ $jumlahPasienBedahAnak->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienBedahAnak->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienBedahAnak->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienBedahAnak->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienBedahAnak->toDateLastYear }}</td>
      </tr> 
      <tr class="positive">
        <td>RJ IBU / POLI</td>
        <td class="right aligned">{{ $jumlahPasienPoliObgyn->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliObgyn->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliObgyn->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliObgyn->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliObgyn->toDateLastYear }}</td>
      </tr> 
      <tr class="warning">
        <td>RJ WIN</td>
        <td class="right aligned">{{ $jumlahPasienPoliWIN->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliWIN->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliWIN->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliWIN->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliWIN->toDateLastYear }}</td>
      </tr> 
      <tr class="negative">
        <td>RJ VIDASTANA</td>
        <td class="right aligned">{{ $jumlahPasienPoliVidastana->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliVidastana->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliVidastana->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliVidastana->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliVidastana->toDateLastYear }}</td>
      </tr> 
      <tr class="positive">
        <td>RJ ANAK</td>
        <td class="right aligned">{{ $jumlahPasienPoliAnak->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliAnak->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliAnak->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliAnak->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliAnak->toDateLastYear }}</td>
      </tr> 
      <tr class="warning">
        <td>RJ IGD</td>
        <td class="right aligned">{{ $jumlahPasienPoliUGD->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUGD->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUGD->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUGD->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUGD->toDateLastYear }}</td>
      </tr> 
      <tr class="positive">
        <td>RJ UMUM</td>
        <td class="right aligned">{{ $jumlahPasienPoliUmum->toDayActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUmum->toDateActual }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUmum->toDayLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUmum->toDateLastMonth }}</td>
        <td class="right aligned">{{ $jumlahPasienPoliUmum->toDateLastYear }}</td>
      </tr>
    </tbody>  
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
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#dailySalesTable').DataTable({
      "paging":   false,
      "ordering": false,
      "info":     false,
      dom: 'tB',
      buttons: [
        {
          extend: 'excelHtml5',
          text: '<i class="download icon"></i>Excel',
          className: 'ui button primary icon'
        },
      ]
    });
    
    $('#prosesData').on('click', function(e){
      if($('#lastPeriode').val() != ''){
        $('#loaderDataDailySales').addClass('active')
      }
    })
  });
</script>
@endsection