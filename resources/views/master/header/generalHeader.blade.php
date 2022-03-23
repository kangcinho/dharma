<div class="ui top inverted teal attached demo menu">
  @if(Auth::check())
    <a class="item" id="menuSidebar">
      <i class="sidebar icon"></i> Menu
    </a>
  <a class="item float right" href="{{ route('logoutAkunYa') }}">
      Logout
    </a>
  @endif
<!--   <a href="#" class="right item">
    <i class="sign in alternate icon"></i> Sign In
  </a> -->
</div>
@include('master.header.sidebarHeader')
