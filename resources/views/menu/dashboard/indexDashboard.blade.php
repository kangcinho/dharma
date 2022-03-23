@extends('master.master.masterPage')
@section('pageTitle','Dashboard Page')
@section('content')
  <h3 class="ui dividing header">
    Dashboard Revenue
  </h3>
  <div class="ui grid">
    <!-- <div class="two column row"> -->
      @include('menu.dashboard.dashboardListChart')
    <!-- </div> -->
    <!-- <div class="five column equal width row"> -->
      @include('menu.dashboard.dashboardListMenu')
    <!-- </div> -->
  </div>
@endsection
@section('additionalJS')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#updateRevenue').on('click', function(e){
        $('#loaderUpdate').addClass('active')
      })
    })
  </script>
@endsection