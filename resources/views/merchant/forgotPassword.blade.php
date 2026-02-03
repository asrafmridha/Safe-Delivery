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
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .login-box, .register-box{
            width: 422px !important;
        }

        .login-card-body, .register-card-body {
            padding: 30px !important;
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
                <p class="login-box-msg">Merchant Rest Password </p>
                @include('layouts.merchant_layout.merchant_session_alert')

                <form action="{{ route('merchant.forgotPassword') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                Send Password Reset Link
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>

    <script>
        $('.alert').delay(5000).slideUp('slow', function() {
            $(this).alert('close');
        });

    </script>
</body>

</html>
