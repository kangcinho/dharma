@extends('master.master.masterPage')
@section('pageTitle','Revenue Tahun - Bulan')
@section('content')
	<div class="ui breadcrumb">
	  <a class="section">Home</a>
	  <i class="right chevron icon divider"></i>
	  <a class="section" href="{{ route('revenue') }}">Revenue</a>
	  <i class="right chevron icon divider"></i>
	  <a class="section" href="{{ route('revenue list pertahun', $idRevenue->idTrRevenue) }}">{{ 'Revenue Tahun '. $tahunRevenue->revenue->tahun->tahun }}</a>
	  <i class="right arrow icon divider"></i>
	  <div class="active section">{{ 'Revenue Bulan: '. $bulanRevenue->bulan->bulan.' '. $tahunRevenue->revenue->tahun->tahun }}</div>
	</div>
	<h1 class="ui header dividing">{{ 'Revenue Transaksi '. $tahunRevenue->revenue->tahun->tahun .'-'. $bulanRevenue->bulan->bulan }}</h1>

	<h3 class="ui header dividing">Data UGD</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori != "UGD")
					@continue
				@endif
				<tr>
					<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
					{{-- <td class="center aligned">
						@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
							<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
								<i class="pencil icon"></i>
							</a>
							<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
								<i class="trash icon"></i>
							</a>
						@endif
					</td> --}}
				</tr>
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Rawat Jalan</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori == "Rawat Jalan")
				<tr>
					<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
					{{-- <td class="center aligned">
						@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
							<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
								<i class="pencil icon"></i>
							</a>
							<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
								<i class="trash icon"></i>
							</a>
						@endif
					</td> --}}
				</tr>
			@else
				@continue
			@endif
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Rawat Inap</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori == "Rawat Inap" and $revenueTransaksiDetailBulanan->kategori->isKamar == 0)
					<tr>
						<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
						{{-- <td class="center aligned">
							@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
								<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
									<i class="pencil icon"></i>
								</a>
								<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
									<i class="trash icon"></i>
								</a>
							@endif
						</td> --}}
					</tr>
				@else
					@continue
				@endif
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Kamar</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Kamar</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->isKamar == 0)
					@continue
				@endif
				<tr>
					<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
					{{-- <td class="center aligned">
						@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
							<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
								<i class="pencil icon"></i>
							</a>
							<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
								<i class="trash icon"></i>
							</a>
						@endif
					</td> --}}
				</tr>
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Pasien Baru</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori != "Pasien")
					@continue
				@endif
				<tr>
					<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
					<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
					{{-- <td class="center aligned">
						@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
							<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
								<i class="pencil icon"></i>
							</a>
							<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
								<i class="trash icon"></i>
							</a>
						@endif
					</td> --}}
				</tr>
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Penunjang</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Jumlah Pasien</th>
				{{-- <th class="center aligned">Action</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori == "Penunjang")
					<tr>
						<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
						{{-- <td class="center aligned">
							@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
								<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
									<i class="pencil icon"></i>
								</a>
								<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
									<i class="trash icon"></i>
								</a>
							@endif
						</td> --}}
					</tr>
				@else
					@continue
				@endif
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Cafetaria</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				{{-- <th class="center aligned">Jumlah</th> --}}
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori == "Cafetaria")
					<tr>
						<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
						{{-- <td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td> --}}
						<td class="center aligned">
							@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
								<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
									<i class="pencil icon"></i>
								</a>
								<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
									<i class="trash icon"></i>
								</a>
							@endif
						</td>
					</tr>
				@else
					@continue
				@endif
			@endforeach
		</tbody>
	</table>

	<h3 class="ui header dividing">Data Lain-Lain</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				{{-- <th class="center aligned">Jumlah</th> --}}
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
				@if($revenueTransaksiDetailBulanan->kategori->groupKategori->namaGroupKategori == "Lain-lain")
					<tr>
						<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
						{{-- <td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td> --}}
						<td class="center aligned">
							@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
								<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
									<i class="pencil icon"></i>
								</a>
								<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
									<i class="trash icon"></i>
								</a>
							@endif
						</td>
					</tr>
				@else
					@continue
				@endif
			@endforeach
		</tbody>
	</table>


	{{-- <h3 class="ui header dividing">Semua Data</h3>
	<table class="ui table celled very compact selectable">
		<thead>
			<tr>
				<th class="center aligned">Nama Section</th>
				<th class="center aligned">Total Revenue</th>
				<th class="center aligned">Jumlah Pasien</th>
				<th class="center aligned">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan)
					<tr>
						<td class="left aligned"> {{ $revenueTransaksiDetailBulanan->kategori->namaKategori }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->totalRevenue }} </td>
						<td class="right aligned"> {{ $revenueTransaksiDetailBulanan->jumlahPasien }} </td>
						<td class="center aligned">
							@if($revenueTransaksiDetailBulanan->kategori->addBySystem != 1)
								<a href="{{ route('edit save revenue transaksi detail',['id' =>$revenueTransaksiDetailBulanan->id, 'idx' =>  $id]) }}">
									<i class="pencil icon"></i>
								</a>
								<a href="{{ route('edit delete revenue transaksi detail', $revenueTransaksiDetailBulanan->id)}}">
									<i class="trash icon"></i>
								</a>
							@endif
						</td>
					</tr>
			@endforeach
		</tbody>
	</table> --}}

	<a href="{{ route('tambah save revenue transaksi detail',$id) }}" class="ui button primary icon labeled">
		<i class="plus icon"></i> Detail Transaksi
	</a>
@endsection
