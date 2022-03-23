<div class="sixteen wide mobile eight wide tablet three wide computer column">
  <div class="ui segments" style="min-height: 14rem">
    <div class="ui horizontal segments" style="min-height: 10rem">
      <div class="ui segment  inverted blue padded">
        <div class="middle aligned">
          <!-- <i class="money bill alternate outline icon"></i> -->
          <img class="ui tiny image" src="{{ asset('img/rawat_jalan_icon.png')}}">
        </div>
      </div>
      <div class="ui segment right aligned inverted blue noneBL">
        <div class="content">
          <h1>{{ isset($dataMenuRJdanRI[0])?$dataMenuRJdanRI[0]->jumlahPasien:0 }}</h1>
          <p>
            Rawat Jalan
            <br/>
            {{ $tglSekarang }}
          </p>
        </div>
      </div>
    </div>
    <div class="ui horizontal segments">
      <div class="ui segment left aligned">
        <a href="{{ route('dashboard rawat jalan') }}">View Detail</a>
      </div>
      <div class="ui segment right aligned noneBL">
        <a href="{{ route('dashboard rawat jalan') }}">
          <i class="setting icon"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="sixteen wide mobile eight wide tablet three wide computer column">
  <div class="ui segments" style="min-height: 14rem">
    <div class="ui horizontal segments" style="min-height: 10rem">
      <div class="ui segment  inverted olive padded">
        <div class="middle aligned">
          <!-- <i class="money bill alternate outline icon"></i> -->
          <img class="ui tiny image" src="{{ asset('img/rawat_inap.png')}}">
        </div>
      </div>
      <div class="ui segment right aligned inverted olive noneBL">
        <div class="content">
          <h1>{{ isset($dataMenuRJdanRI[1])?$dataMenuRJdanRI[1]->jumlahPasien:0 }}</h1>
          <p>
            Rawat Inap
            <br/>
            {{ $tglSekarang }}
          </p>
        </div>
      </div>
    </div>
    <div class="ui horizontal segments">
      <div class="ui segment left aligned ">
        <a href="{{ route('dashboard rawat inap') }}" >View Detail</a>
      </div>
      <div class="ui segment right aligned noneBL">
        <a href="{{ route('dashboard rawat inap') }}">
          <i class="setting icon"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="sixteen wide mobile eight wide tablet three wide computer column">
  <div class="ui segments" style="min-height: 14rem">
    <div class="ui horizontal segments" style="min-height: 10rem">
      <div class="ui segment  inverted teal padded">
        <div class="ui header icon middle aligned">
          <i class="user icon"></i>
        </div>
      </div>
      <div class="ui segment right aligned inverted teal noneBL">
        <div class="content">
          <h1>{{ isset($dataPasienAll[0])?$dataPasienAll[0]->jumlahPasien:0 }}</h1>
          <p>
            Pasien
            <br/>
            {{ $tglSekarang }}
          </p>
        </div>
      </div>
    </div>
    <div class="ui horizontal segments">
      <div class="ui segment left aligned">
        <a href="{{ route('dashboard pasien') }}">View Detail</a>
      </div>
      <div class="ui segment right aligned noneBL">
        <a href="{{ route('dashboard pasien') }}">
          <i class="setting icon"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="sixteen wide mobile eight wide tablet three wide computer column">
  <div class="ui segments" style="min-height: 14rem">
    <div class="ui horizontal segments" style="min-height: 10rem">
      <div class="ui segment  inverted orange padded">
        <div class="ui header icon middle aligned">
          <i class="bed icon"></i>
        </div>
      </div>
      <div class="ui segment right aligned inverted orange noneBL">
        <div class="content">
          <h1>{{ isset($dataKamar[0])?$dataKamar[0]->jumlahKamar:0 }}</h1>
          <p>
            Kamar
            <br/>
            {{ $tglSekarang }}
          </p>
        </div>
      </div>
    </div>
    <div class="ui horizontal segments">
      <div class="ui segment left aligned">
        <a href="{{ route('dashboard kamar') }}">View Detail</a>
      </div>
      <div class="ui segment right aligned noneBL">
        <a href="{{route('dashboard kamar')}}">
          <i class="setting icon"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="sixteen wide mobile eight wide tablet four wide computer column">
  <div class="ui segments" style="min-height: 14rem">
    <div class="ui horizontal segments" style="min-height: 10rem">
      <div class="ui segment  inverted pink padded">
        <div class="ui header icon middle aligned">
          <i class="money bill alternate outline icon"></i>
        </div>
      </div>
      <div class="ui segment right aligned inverted pink noneBL">
        <div class="content">
          <h3>{{ "Rp ".(isset($revenueDaily[0])?$revenueDaily[0]->totalRevenue:0) }}</h3>
          <p>
            Daily Revenue
            <br/>
            {{ $tglRevenueDaily }}
          </p>
        </div>
      </div>
    </div>
    <div class="ui horizontal segments">
      <div class="ui segment left aligned">
        <a href="{{ route('dashboard daily revenue') }}">View Detail</a>
      </div>
      <div class="ui segment right aligned noneBL">
        <a href="{{ route('dashboard daily revenue') }}">
          <i class="setting icon"></i>
        </a>
      </div>
    </div>
  </div>
</div>
