@extends('master.master.masterPage')
@section('pageTitle','Dashboard Pasien')
@section('content')
  <div class="ui large breadcrumb">
    <a class="section" href="{{ route('dashboard') }}">Dashboard</a>
    <i class="right arrow icon divider"></i>
    <a class="active section">Dashboard Pasien</a>
  </div>
  <div class="ui divider"></div>
  <h3 class="ui dividing header">
    Dashboard Pasien
  </h3>
  <div class="ui grid">
    <div class="sixteen wide mobile eight wide tablet five wide computer column">
      <div class="ui segments" style="min-height: 14rem">
        <div class="ui horizontal segments" style="min-height: 10rem">
          <div class="ui segment  inverted teal padded">
            <div class="ui header icon middle aligned">
              <i class="user icon"></i>
            </div>
          </div>
          <div class="ui segment right aligned inverted teal noneBL">
            <div class="content">
              <h1>{{ isset($dataPasienBaru[0])?$dataPasienBaru[0]->jumlahPasien:0 }}</h1>
              <p>
                Pasien Baru
                <br/>
                {{ $tglSekarang }}
              </p>
            </div>
          </div>
        </div>
        <div class="ui horizontal segments">
          <div class="ui segment left aligned">
            <a href="{{ route('dashboard pasien baru') }}">View Detail</a>
          </div>
          <div class="ui segment right aligned noneBL">
            <a href="{{ route('dashboard pasien baru') }}">
              <i class="setting icon"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="sixteen wide mobile eight wide tablet five wide computer column">
      <div class="ui segments" style="min-height: 14rem">
        <div class="ui horizontal segments" style="min-height: 10rem">
          <div class="ui segment  inverted orange padded">
            <div class="ui header icon middle aligned">
              <i class="user md icon"></i>
            </div>
          </div>
          <div class="ui segment right aligned inverted orange noneBL">
            <div class="content">
              <h1>{{ isset($dataPasienRepeater[0])?$dataPasienRepeater[0]->jumlahPasien:0 }}</h1>
              <p>
                Pasien Repeater
                <br/>
                {{ $tglSekarang }}
              </p>
            </div>
          </div>
        </div>
        <div class="ui horizontal segments">
          <div class="ui segment left aligned">
            <a href="{{ route('dashboard pasien repeater') }}">View Detail</a>
          </div>
          <div class="ui segment right aligned noneBL">
            <a href="{{route('dashboard pasien repeater')}}">
              <i class="setting icon"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection