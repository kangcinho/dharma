@extends('master.master.masterPage')
@section('pageTitle','Dashboard Pasien Repeater')
@section('content')
	<div class="ui large breadcrumb">
		<a class="section" href="{{ route('dashboard') }}">Dashboard</a>
		<i class="right chevron icon divider"></i>
		<a class="section" href="{{ route('dashboard pasien') }}">Dashboard Pasien</a>
		<i class="right chevron icon divider"></i>
		<a class="section" href="{{ route('dashboard pasien repeater') }}">Pasien Repeater</a>
		<i class="right arrow icon divider"></i>
		<a class="active section">Pasien Repeater PerSection</a>
	</div>
	<div class="ui divider"></div>
	<h3 class="ui dividing header">
    	Dashboard Pasien Repeater {{ $idSectionSanata }} Per Section
  	</h3>
	<div class="ui grid stackable three column">
	    @foreach($dataPasienRepeaterPerSections as $dataPasienRepeaterPerSection)
	      <div class="column">
	        <div class="ui fluid card green">
	          <div class="content">
	            <div class="header">
	              {{ $dataPasienRepeaterPerSection->namaSection }}
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
	                      {{' '.$dataPasienRepeaterPerSection->jumlahPasien }}
	                    </b></span>
	                  </div>            
	                </div>
	                <div class="column">
	                  <h4 class="ui header">YTD Pasien</h4>
	                  <div class="ui labeled icon">
	                    <i class="users icon"></i>
	                    <span><b>
	                      {{' '.$dataPasienRepeaterPerSection->jumlahPasienYTD }}
	                    </b></span>
	                  </div>            
	                </div>         
	              </div>       
	            </div>
	          </div>
	          <div class="extra content">
	            <a class="ui labeled icon button fluid primary" href="{{ route('dashboard detail section pasien repeater grafikytd', ['idSectionSanata' => $idSectionSanata, 'sectionID' => $dataPasienRepeaterPerSection->SectionPerawatanID ])}}">
	              <i class="chart line icon"></i> Grafik YTD
	            </a>
	          </div>
	          <div class="extra content">
	            <a class="ui labeled icon button fluid green" href="{{ route('dashboard detail section pasien repeater grafikmtd', ['idSectionSanata' => $idSectionSanata, 'sectionID' => $dataPasienRepeaterPerSection->SectionPerawatanID ])}}">
	              <i class="chart bar icon"></i> Grafik MTD
	            </a>
	          </div>
	        </div>
	      </div>
	    @endforeach
	 </div>

@endsection