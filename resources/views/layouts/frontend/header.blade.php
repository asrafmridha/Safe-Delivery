<nav class="navigate sticky-top navbar active navbar-expand-xl navbar-dark bg-dark">
    <div class="container px-2 justify-content-between">
        <div class="navbar-brand">
            <a class="brand-anchor" href="{{ route('frontend.home') }}">
                @if(!empty($application->photo))
                    <img src="{{ asset('uploads/application/'.$application->photo) }}" height="50" alt="BRAND">
                @else
                    <!--<img src="{{ asset('assets/img/logo.png') }}" height="50" alt="BRAND">-->
                @endif
            </a>
        </div>

        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navigate">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navigate" class="navbar-ul collapse navbar-collapse justify-content-end mt-4 mt-xl-0">
            <ul class="navbar-nav text-center text-xl-left nav-items">
                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}" class="nav-link text-white active">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}#tracking" class="nav-link text-white">Tracking</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}services" class="nav-link text-white">Services</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('frontend.home') }}about" class="nav-link text-white">About Us</a>
                </li>

            </ul>
            <ul class="navbar-nav ml-xl-4 mt-4 mt-xl-2 text-center text-xl-left">
                <li class="nav-item mb-3 mb-xl-0 ml-n4 ml-xl-0">
                    <a href="#" class="nav-link text-info">
                        <span class="fas fa-phone header-phone text-white"></span>
                        <span class="text-white">{{ $application->contact_number }}</span>
                    </a>
                </li>
                <li class="nav-item ml-xl-3 mb-2 mb-xl-2">
                    <a href="{{ route('frontend.merchantRegistration') }}" class="nav-link btn btn-danger px-3 btn-header1">Registration</a>
                </li>
                <li class="nav-item ml-xl-3 mb-2 mb-xl-2">
                    <a href="{{ route('frontend.login') }}" class="nav-link btn btn-danger px-3 btn-header1">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
