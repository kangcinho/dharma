@extends('master.master.masterPage')
@section('pageTitle','Revenue List')
@section('content')
	<h2 class="ui header dividing">Revenue Puri Bunda</h2>
	<table class="ui table celled striped selectable very compact">
		<thead>
			<tr>
				<th class="center aligned">Tahun</th>
				<th class="center aligned">Target Revenue</th>
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($datas as $data)
			<tr>
				<td class="center aligned"> {{ $data->tahun->tahun }} </td>
				<td class="right aligned"> {{ $data->targetRevenue }} </td>
				<td class="center aligned">
					<a href="{{ route('revenue list pertahun',$data->id) }}">
						<i class="search icon"></i>
					</a>
					<a href="{{ route('tambah edit tahun revenue', $data->id)}}">
						<i class="pencil icon"></i>
					</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<a href="{{ route('create revenue') }}" class="ui button primary icon labeled">
		<i class="plus icon"></i>
		Tahun Revenue
	</a>
@endsection