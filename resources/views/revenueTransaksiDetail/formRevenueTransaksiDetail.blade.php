@extends('master.master.masterPage')
@section('pageTitle','Revenue Transaksi Detail Form')
@section('content')
	<div class="ui breadcrumb">
	  <i class="left chevron icon divider"></i>
	  <a class="active section" href="{{ route('revenue list perbulan', $id) }}">Kembali</a>
	</div>
	<h2 class="ui header dividing">{{ 'Form Revenue Transaksi ' . $tahunRevenue->revenue->tahun->tahun . '-' . $bulanRevenue->bulan->bulan }}</h2>
	<form class="ui form" action="{{ route('tambah save revenue transaksi detail',$id) }}" method="POST" id="formRevenue">
		@csrf
		<div class="required field">
			<label for="idTahun">Kategori</label>
			<div class="ui selection dropdown" >
			  <input type="hidden" name="idKategori" id="idKategori" value="">
			  <i class="dropdown icon"></i>
			  <div class="default text">Pilih Kategori</div>
			  <div class="menu">
			  	@foreach($kategoris as $kategori)
			    	<div class="item" data-value="{{ $kategori->id }}">{{ $kategori->namaKategori }}</div>
			    @endforeach
			  </div>
			</div>
		</div>
		<div class="required field">
			<label for="totalRevenue">Total Revenue</label>
			<input type="text" name="totalRevenue" id="totalRevenue" value="">
		</div>
		<button class="ui button primary labeled icon" id="simpanRevenueTransaksiDetail"> <i class="icon save"></i>Simpan</button>
	</form>
@endsection
@section('additionalJS')
	<script type="text/javascript" src="{{ asset('js/navigasi/functionRupiah.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/navigasi/formRevenueTransaksiDetail.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/validasi/formRevenueTransaksiDetail.js') }}"></script>
@endsection