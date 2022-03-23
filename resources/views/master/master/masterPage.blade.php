<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('pageTitle')</title>
    <link rel="stylesheet" href="{{ asset('semanticui/semantic.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('additionalCSS')
  </head>
  <body>
    @include('master.header.generalHeader')
    <div class="ui dimmer" id="loaderUpdate">
      <div class="ui text loader">Sedang Melakukan Update Revenue</div>
    </div>
    <div class="ui dimmer" id="loaderDataBayi">
      <div class="ui text loader">Sedang Melakukan Kalkulasi Data Bayi</div>
    </div>
    <div class="ui dimmer" id="loaderDataDailySales">
      <div class="ui text loader">Sedang Melakukan Kalkulasi Data Daily Sales</div>
    </div>
    <script type="text/javascript" src="{{ asset('semanticui/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('semanticui/semantic.min.js') }}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.ui.sidebar').sidebar({
          context: $('.bottom.segment')
        }).sidebar('attach events', '.menu #menuSidebar');
        $('.ui.dropdown').dropdown();
      })
    </script>
    @yield('additionalJS')
  </body>
</html>
