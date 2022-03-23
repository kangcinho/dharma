@extends('master.master.masterPage')
@section('pageTitle','Dashboard Pasien Baru')
@section('content')
	<div class="ui large breadcrumb">
		<a class="section" href="{{ route('dashboard') }}">Dashboard</a>
		<i class="right chevron icon divider"></i>
		<a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
		<i class="right chevron icon divider"></i>
		<a class="section" href="{{ route('dashboard pasien baru') }}">Pasien Baru</a>
		<i class="right arrow icon divider"></i>
		<a class="active section">Pasien Baru PerSection</a>
	</div>
	<div class="ui divider"></div>
	<h3 class="ui dividing header">
    	Dashboard Pasien Baru {{ $idSectionSanata }} Per Section
  	</h3>
	<div class="ui grid">
	    @foreach($dataPasienBaruPerSections as $dataPasienBaruPerSection)
	      <div class="sixteen wide mobile eight wide tablet five wide computer column">
	        <div class="ui fluid card green">
	          <div class="content">
	            <div class="header">
	              {{ $dataPasienBaruPerSection->namaSection }}
	            </div>
	            <div class="meta">
	              <div class="time">{{ $tglSekarang }}</div>            
	            </div>
	          </div>
	          <div class="content">
	            <div class="ui grid two column internally celled">
	              <div class="row">
	                <div class="column">
	                  <h4 class="ui header">MTD Pasien</h4>
	                  <div class="ui labeled icon">
	                    <i class="users icon"></i>
	                    <span><b>
	                      {{' '.$dataPasienBaruPerSection->jumlahPasien }}
	                    </b></span>
	                  </div>            
	                </div>
	                <div class="column">
	                  <h4 class="ui header">YTD Pasien</h4>
	                  <div class="ui labeled icon">
	                    <i class="users icon"></i>
	                    <span><b>
	                      {{' '.$dataPasienBaruPerSection->jumlahPasienYTD }}
	                    </b></span>
	                  </div>            
	                </div>         
	              </div>       
	            </div>
	          </div>
	          <div class="extra content">
	            <a class="ui labeled icon button fluid primary" href="{{ route('dashboard detail section pasien baru grafikytd', ['idSectionSanata' => $idSectionSanata, 'sectionID' => $dataPasienBaruPerSection->SectionPerawatanID ])}}">
	              <i class="chart line icon"></i> Grafik YTD
	            </a>
	          </div>
	          <div class="extra content">
	            <a class="ui labeled icon button fluid green" href="{{ route('dashboard detail section pasien baru grafikmtd', ['idSectionSanata' => $idSectionSanata, 'sectionID' => $dataPasienBaruPerSection->SectionPerawatanID ])}}">
	              <i class="chart bar icon"></i> Grafik MTD
	            </a>
	          </div>
	        </div>
	      </div>
	    @endforeach
	 </div>

@endsection