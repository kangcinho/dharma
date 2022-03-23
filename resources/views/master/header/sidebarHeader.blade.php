<div class="ui bottom attached segment">
  <div class="ui inverted labeled icon left inline vertical demo sidebar menu inverted teal">
    @if(Auth::check())
      <a class="item" href="{{ route('dashboard') }}">
        <i class="chart line icon" ></i> Dashboard
      </a>
      <!-- <a class="item" href="{{ route('revenue') }}">
        <i class="money bill alternate outline icon" ></i> Revenue
      </a>
      <a class="item" href="{{ route('list kategori') }}">
        <i class="file outline icon" ></i> Kategori
      </a>
      <a class="item" href="{{ route('list group kategori') }}">
        <i class="copy outline icon" ></i> Group Kategori
      </a>-->
      <a class="item" href="{{ route('dashboard daily sales') }}">
        <i class="shopping bag icon" ></i> Daily Sales
      </a> 
      <a class="item" href="{{ route('dashboard bayi check up') }}">
        <i class="smile outline icon" ></i> Bayi Recheck Up
      </a>
      <a class="item" href="{{ route('dashboard paket persalinan') }}">
        <i class="shopping bag icon" ></i> Paket Persalinan
      </a>
    @endif
  </div>
  <div class="pusher">
    <div class="ui basic segment">
      @yield('content')
    </div>
  </div>
</div>
