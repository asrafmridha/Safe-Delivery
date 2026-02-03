<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @if(!empty($application->photo))
        <link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >
        <link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >
    @endif
    <title>{{ $application->name ?? config('app.name', 'Flier Express') }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .login-box, .register-box{
            width: 422px !important;
        }

        .login-card-body, .register-card-body {
            padding: 30px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px !important;
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="login-box">

        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-logo">
                    @if(!empty($application->photo))
                        <img src="{{ asset('uploads/application/') . '/' . $application->photo }}"
                        alt="{{ $application->name ?? config('app.name') }}" style="height: 100px; width: 100%"
                        style="opacity: .8">
                    @else
                        <b>{{ $application->name ?? config('app.name') }}</b>
                    @endif
                </div>
                <br>
                <p class="login-box-msg">Sign in Provide Your OTP Here </p>
                @include('layouts.merchant_layout.merchant_session_alert')

                <form action="{{ route('frontend.otp_login') }}" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" maxlength="6" name="otp_token" class="form-control" placeholder="Enter Your OTP" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-"></span>
                            </div>
                        </div>
                    </div>
                    {{--<div class="input-group mb-3">--}}
                        {{--<input type="password" name="password" class="form-control" placeholder="Password" required>--}}
                        {{--<div class="input-group-append">--}}
                            {{--<div class="input-group-text">--}}
                                {{--<span class="fas fa-lock"></span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{-- <div class="input-group mb-3">
                        <select name="user_type" id="user_type" class="form-control select2" style="width: 100%">
                            <option value="1">Merchant</option>
                            <option value="2">Branch</option>
                            <option value="3">Rider</option>
                            <option value="4">Admin</option>
                            <option value="5">Operation</option>
                            <option value="6">Warehouse</option>
                            <option value="7">Accounts</option>
                        </select>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-user-lock"></i> Submit
                            </button>
                        </div>

                         <div class="col-md-12" style="margin-top: 20px">
                            <p>
                                <a href="{{ route('frontend.login') }}">Back to Login</a>
                            </p>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.alert').delay(5000).slideUp('slow', function() {
            $(this).alert('close');
        });
        $(function(){
            if ($(".select2").length > 0) $('.select2').select2();
        });
    </script>
</body>



</html>
