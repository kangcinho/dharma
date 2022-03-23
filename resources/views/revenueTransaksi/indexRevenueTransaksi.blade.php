@extends('master.master.masterPage')
@section('pageTitle','Revenue Transaksi')
@section('content')
	<div class="ui breadcrumb">
	  <a class="section">Home</a>
	  <i class="right chevron icon divider"></i>
	  <a class="section" href="{{ route('revenue') }}">Revenue</a>
	  <i class="right arrow icon divider"></i>
	  <div class="active section">{{ 'Revenue Tahun '. $revenueTahunBerjalan->tahun->tahun }} </div>
	</div>

	<h2 class="ui header dividing">{{ 'Revenue Tahun '. $revenueTahunBerjalan->tahun->tahun .' PerBulan' }}</h2>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Bulan</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($revenueListPerTahuns as $revenueListPerTahun)
				<tr>
					<td class="center aligned"> {{ $revenueListPerTahun->bulan->bulan }} </td>
					<td class="right aligned"> {{ $revenueListPerTahun->totalRevenue }} </td>
					<td class="center aligned">
						<a href="{{ route('revenue list perbulan',$revenueListPerTahun->id) }}">
							<i class="search icon"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
<!-- 	<a href="#" class="ui button primary icon labeled">
		<i class="plus icon"></i> Transaksi
	</a> -->
@endsection