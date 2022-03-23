@extends('master.master.masterPage')
@section('pageTitle','Dashboard Rawat Jalan')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right chevron icon divider"></i>
    <a class="section" href="{{ route('dashboard rawat jalan') }}">Rawat Jalan</a>
    <i class="right arrow icon divider"></i>
    <a class="active section"> {{ ucwords(strtolower($kategori->namaKategori)) }} </a>
  </div>

  <h3 class="ui dividing header">
    Dashboard Dokter {{ ucwords(strtolower($kategori->namaKategori))}}
  </h3>
  <div class="ui grid">
    @foreach($dataDokterRawatJalans as $dataDokterRawatJalan)
      <div class="sixteen wide mobile eight wide tablet five wide computer column">
        <div class="ui fluid card green">
          <div class="content">
            <div class="header">
              {{ $dataDokterRawatJalan->namaDokter }}
            </div>
            <div class="meta">
              <div class="time">{{ $tanggalSekarang }}</div>            
            </div>
          </div>
          <div class="content center aligned">
              <div class="ui header huge">
                <i class="users icon"></i>
                {{ $dataDokterRawatJalan->jumlahPasien }}
              </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

@endsection
