@extends('master.master.masterPage')
@section('pageTitle','Group Kategori Form')
@section('content')
	<div class="ui breadcrumb">
	  <i class="left chevron icon divider"></i>
	  <a class="active section" href="{{ route('list group kategori') }}">Kembali</a>
	</div>
	<h2 class="ui header dividing">Form Group Kategori</h2>
	@if(isset($groupKategori))
		<form class="ui form" action="{{ route('update group kategori', $groupKategori->id) }}" method="POST" id="formGroupKategori">
	@else
		<form class="ui form" action="{{ route('create group kategori') }}" method="POST" id="formGroupKategori">
	@endif
		@csrf
		<div class="required field">
			<label for="namaGroupKategori">Nama Group Kategori</label>
			<input type="text" name="namaGroupKategori" id="namaGroupKategori" value="{{ isset($groupKategori)?$groupKategori->namaGroupKategori:'' }}">
		</div>
		<button class="ui button primary labeled icon" id="simpanGroupKategori"> <i class="icon save"></i>Simpan</button>
	</form>
@endsection
@section('additionalJS')
	
@endsection