<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> Login</title>

    <!-- Favicons -->
    <link rel="stylesheet" href="{{asset('assets/backend/css/login.css')}}">
</head>
<body>
<div class="login-page">
    <div class="form">
        <a href="" class="logo"><img src="" alt="" width="70%"></a>
        @if(session()->has('message'))
            <div >
                <p class="alert alert-{{session()->get('alert')}} text-center">{{session()->get('message')}}</p>
            </div>
        @endif
        <form class="login-form" action="{{route('login')}}" method="post">
            @csrf
            <input type="email" name="email" placeholder="Email" style=" @error('email') border: red 1px solid;@enderror" />
            @error('email')
            <p style="color: red;margin-top: 0">{{$message}}</p>
            @enderror
            <input type="password" name="password" placeholder="Password" style=" @error('email') border: red 1px solid;@enderror" />
            @error('email')
            <p style="color: red;margin-top: 0">{{$message}}</p>
            @enderror
            <button>login</button>
        </form>
    </div>
</div>
</body>
</html>
