@extends('master.master.masterPage')
@section('pageTitle','Group Kategori List')
@section('content')
	<h2 class="ui header dividing">Group Kategori Revenue</h2>
	<table class="ui table celled striped selectable very compact">
		<thead>
			<tr>
				<th class="center aligned">Nama Group Kategori</th>
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($groupKategoris as $groupKategori)
			<tr>
				<td class="left aligned"> {{ $groupKategori->namaGroupKategori }} </td>
				<td class="center aligned">
					<a href="{{ route('tambah edit group kategori', $groupKategori->id)}}">
						<i class="pencil icon"></i>
					</a>
					<a href="{{ route('delete group kategori', $groupKategori->id)}}">
						<i class="trash icon"></i>
					</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<a href="{{ route('tambah edit group kategori') }}" class="ui button primary icon labeled">
		<i class="plus icon"></i>
		Kategori Group Revenue
	</a>
@endsection