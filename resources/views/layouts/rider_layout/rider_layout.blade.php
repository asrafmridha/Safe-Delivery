<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @if(session()->get('company_name'))
        <link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" alt="{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}">
    @endif
    <title>  {{ isset($page_title) ?  $page_title." || " : ''}}{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}  </title>
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}">
    @stack('style_css')
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini {{ isset($collapse) ? $collapse : ''}}">
<div class="wrapper">

    @include('layouts.rider_layout.rider_header')

    @include('layouts.rider_layout.rider_sidebar')


    <div class="content-wrapper">
      @include('layouts.rider_layout.rider_session_alert')
      @yield('content')
    </div>

    @include('layouts.rider_layout.rider_footer')

</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/admin_js/main.js') }}"></script>
@stack('script_js')
</body>
</html>
