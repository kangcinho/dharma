@extends('master.master.masterPage')
@section('pageTitle','Kategori Form')
@section('content')
	<div class="ui breadcrumb">
	  <i class="left chevron icon divider"></i>
	  <a class="active section" href="{{ route('list kategori') }}">Kembali</a>
	</div>
	<h2 class="ui header dividing">Form Kategori</h2>
	@if(isset($kategori))
		<form class="ui form" action="{{ route('update kategori', $kategori->id) }}" method="POST" id="formKategori">
	@else
		<form class="ui form" action="{{ route('create kategori') }}" method="POST" id="formKategori">
	@endif
		@csrf
		<div class="required field">
			<label for="idGroupKategori">Group Kategori</label>
			<div class="ui selection dropdown">
			  <input type="hidden" name="idGroupKategori" id="idGroupKategori" value="{{ isset($kategori)?$kategori->idGroupKategori:'' }}">
			  <i class="dropdown icon"></i>
			  <div class="default text">Pilih Group Kategori</div>
			  <div class="menu">
			  	@foreach($groupKategoris as $groupKategori)
			    	<div class="item" data-value="{{ $groupKategori->id }}">{{ $groupKategori->namaGroupKategori }}</div>
			    @endforeach
			  </div>
			</div>
		</div>
		<div class="required field">
			<label for="namaKategori">Nama Kategori</label>
			<input type="text" name="namaKategori" id="namaKategori" value="{{ isset($kategori)?$kategori->namaKategori:'' }}">
		</div>
		<button class="ui button primary labeled icon" id="simpanKategori"> <i class="icon save"></i>Simpan</button>
	</form>
@endsection
@section('additionalJS')
	
@endsection