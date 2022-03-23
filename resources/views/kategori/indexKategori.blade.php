@extends('master.master.masterPage')
@section('pageTitle','Kategori List')
@section('content')
	<h2 class="ui header dividing">Kategori Revenue</h2>
	<table class="ui table celled striped selectable very compact">
		<thead>
			<tr>
				<th class="center aligned">Nama Kategori</th>
				<th class="center aligned">Nama Group</th>
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($kategoris as $kategori)
			<tr>
				<td class="left aligned"> {{ $kategori->namaKategori }} </td>
				<td class="left aligned"> {{ $kategori->groupKategori->namaGroupKategori }} </td>
				<td class="center aligned">
					<a href="{{ route('tambah edit kategori', $kategori->id)}}">
						<i class="pencil icon"></i>
					</a>
					<a href="{{ route('delete kategori', $kategori->id)}}">
						<i class="trash icon"></i>
					</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<a href="{{ route('tambah edit kategori') }}" class="ui button primary icon labeled">
		<i class="plus icon"></i>
		Kategori Revenue
	</a>
@endsection