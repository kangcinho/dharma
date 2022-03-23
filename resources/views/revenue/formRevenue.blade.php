@extends('master.master.masterPage')
@section('pageTitle','Revenue Form')
@section('content')
	<div class="ui breadcrumb">
	  <i class="left chevron icon divider"></i>
	  <a class="active section" href="{{ route('revenue') }}">Kembali</a>
	</div>
	<h2 class="ui header dividing">Form Revenue</h2>
	@if(isset($revenue))
		<form class="ui form" action="{{ route('edit revenue', $revenue->id) }}" method="POST" id="formRevenue">
	@else
		<form class="ui form" action="{{ route('create revenue') }}" method="POST" id="formRevenue">
	@endif
		@csrf
		<div class="required field">
			<label for="idTahun">Tahun</label>
			<div class="ui selection dropdown">
			  <input type="hidden" name="idTahun" id="idTahun" value="{{ isset($revenue)?$revenue->idTahun:'' }}">
			  <i class="dropdown icon"></i>
			  <div class="default text">Pilih Tahun</div>
			  <div class="menu">
			  	@foreach($tahuns as $tahun)
			    	<div class="item" data-value="{{ $tahun->id }}">{{ $tahun->tahun }}</div>
			    @endforeach
			  </div>
			</div>
		</div>
		<h4 class="ui header dividing">Gauge Chart</h4>
		<div class="fields">
		  	<div class="field">
		  		<div class="fields">
		  			<div class="required field">
		  				<label>Min Merah</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="minBatasMerah" id="minBatasMerah" value="{{ isset($revenue)?$revenue->minBatasMerah:'' }}">	
		  					<div class="ui label">%</div>
		  				</div>
		  				
		  			</div>
		  			<div class="required field">
		  				<label>Max Merah</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="maxBatasMerah" id="maxBatasMerah" value="{{ isset($revenue)?$revenue->maxBatasMerah:'' }}">
		  					<div class="ui label">%</div>
		  				</div>		
		  			</div>
		  		</div>
		  	</div>
		  	<div class="field">
		  		<div class="fields">
		  			<div class="required field">
		  				<label>Min Kuning</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="minBatasKuning" id="minBatasKuning" value="{{ isset($revenue)?$revenue->minBatasKuning:'' }}">
		  					<div class="ui label">%</div>
		  				</div>		
		  			</div>
		  			<div class="required field">
		  				<label>Max Kuning</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="maxBatasKuning" id="maxBatasKuning" value="{{ isset($revenue)?$revenue->maxBatasKuning:'' }}">
		  					<div class="ui label">%</div>
		  				</div>				  				
		  			</div>
		  		</div>
		  	</div>
		  	<div class="field">
		  		<div class="fields">
		  			<div class="required field">
		  				<label>Min Hijau</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="minBatasHijau" id="minBatasHijau" value="{{ isset($revenue)?$revenue->minBatasHijau:'' }}">
		  					<div class="ui label">%</div>
		  				</div>			  					
		  			</div>
		  			<div class="required field">
		  				<label>Max Hijau</label>
		  				<div class="ui mini input right labeled">
		  					<input type="text" name="maxBatasHijau" id="maxBatasHijau" value="{{ isset($revenue)?$revenue->maxBatasHijau:'' }}">
		  					<div class="ui label">%</div>
		  				</div>
		  			</div>
		  		</div>
		  	</div>
		 </div>
		 
		 <h4 class="ui header dividing">Data Budget</h4>
		 <div class="equal width fields">
		  	<div class="field">
		  		<label>Januari</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetJanuari" id="budgetJanuari" value="{{ isset($revenue)?$revenue->budgetJanuari:'' }}">	
		  		</div>
		  		
		  	</div>
		  	<div class="field">
		  		<label>Februari</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetFebruari" id="budgetFebruari" value="{{ isset($revenue)?$revenue->budgetFebruari:'' }}">	
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Maret</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetMaret" id="budgetMaret" value="{{ isset($revenue)?$revenue->budgetMaret:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>April</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetApril" id="budgetApril" value="{{ isset($revenue)?$revenue->budgetApril:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Mei</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetMei" id="budgetMei" value="{{ isset($revenue)?$revenue->budgetMei:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Juni</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetJuni" id="budgetJuni" value="{{ isset($revenue)?$revenue->budgetJuni:'' }}">
		  		</div>
		  	</div>
		 </div>
		 <div class="equal width fields">
		  	<div class="field">
		  		<label>Juli</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetJuli" id="budgetJuli" value="{{ isset($revenue)?$revenue->budgetJuli:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Agustus</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetAgustus" id="budgetAgustus" value="{{ isset($revenue)?$revenue->budgetAgustus:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>September</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetSeptember" id="budgetSeptember" value="{{ isset($revenue)?$revenue->budgetSeptember:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Oktober</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetOktober" id="budgetOktober" value="{{ isset($revenue)?$revenue->budgetOktober:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>November</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetNovember" id="budgetNovember" value="{{ isset($revenue)?$revenue->budgetNovember:'' }}">
		  		</div>
		  	</div>
		  	<div class="field">
		  		<label>Desember</label>
		  		<div class="ui mini input labeled">
		  			<div class="ui label">Rp</div>
		  			<input type="text" name="budgetDesember" id="budgetDesember" value="{{ isset($revenue)?$revenue->budgetDesember:'' }}">
		  		</div>
		  	</div>
		 </div>
		 
		<div class="required field">
	  		<label>Target Revenue</label>
	  		<div class="ui mini input labeled">
	  			<div class="ui label">Rp</div>
	  			<input type="text" name="targetRevenue" id="targetRevenue" value="{{ isset($revenue)?$revenue->targetRevenue:'' }}">
			</div>		  		
	  	</div>

		<button class="ui button primary labeled icon" id="simpanRevenue"> <i class="icon save"></i>Simpan</button>
	</form>
@endsection
@section('additionalJS')
	<script type="text/javascript" src="{{ asset('js/navigasi/functionRupiah.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/navigasi/formRevenue.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/validasi/formRevenue.js') }}"></script>
@endsection