<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link setNavBarPushMenu" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('merchant.home') }}" class="nav-link">Home</a>
    </li>
  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    {{-- <merchant-parcel-notification
            :userid="{{ auth()->guard('merchant')->user()->id }}"
            :unreads="{{ auth()->guard('merchant')->user()->unreadNotifications }}"
            :readunreads="{{ auth()->guard('merchant')->user()->notifications }}"
            :notifylisturl="'{{ route('merchant.parcel.notification') }}'"></merchant-parcel-notification> --}}

    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('merchant.logout') }}" class="nav-link">
        <i class="fas fa-power-off text-danger"></i>
        Logout
      </a>
    </li>
  </ul>
</nav>
