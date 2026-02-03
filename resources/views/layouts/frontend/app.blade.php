@php
    $socialLinks    = App\Models\SocialLink::where('status', 1)->get();
    $services       = App\Models\Service::where('status', 1)->get();
    $blogs          = App\Models\Blog::where('status', 1)->orderBy('id', 'desc')->take(2)->get();
@endphp
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $application->name }} </title>
    <meta name="description" content="{{ $application->meta_description }}" />
    <meta name="keywords" content="{{ $application->meta_description }}">
    <meta name="author" content="{{ $application->meta_description }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="article:modified_time" content="2023-12-18T07:31:37+00:00" />
	<meta property="og:image" content="{{ asset('uploads/application/') . '/' . $application->og_image }}" />
	<meta property="og:image:width" content="1534" />
	<meta property="og:image:height" content="747" />


	@if(!empty($application->logo))
        <link rel="icon" type="image/png" href="{{ asset('assets/logo.jpg') }}" alt="{{ $application->name ?? config('app.name', 'Express') }}" >
        <link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->favicon }}" alt="{{ $application->name ?? config('app.name', 'Express') }}" >
         <!--<link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">-->
    @else
        <link rel="shortcut icon" href="{{ asset('assets/logo.jpg') }}" type="image/x-icon">
    @endif
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
	<link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">

    <!-- Slider -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/line-awesome.min.css') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/toast.css') }}">
    <style>
        [data-aos][data-aos][data-aos-duration="400"], body[data-aos-duration="400"] [data-aos] {
            transition-duration: 3s;
        }
    </style>
    
    
    <style>

.navbar-toggler {
    order: -1; /* This reorders the toggler to the beginning of its flex container */
}
    body {
      font-size: 14px;
    }
    
    @media (max-width: 991.98px) {
    .navbar-brand {
        margin-right: 0rem;
    }
}

  @media (max-width: 767px) {
    .navigate .nav-link.btn-header1 {
        color: var(--white);
        padding: 3px 8px !important; /* Adjust padding for smaller screens */
        background: var(--color1);
    }
}



    
  </style>
    @stack('style_css')
</head>
<body data-spy="scroll" data-target="#navigate" data-offset="50">

	<!--<nav class="navigate fixed-top navbar active navbar-expand-xl navbar-dark bg-dark">-->
	    	<nav class="navigate navbar active navbar-expand-xl navbar-dark bg-dark" style="
    padding: 5px 0;
">
		<div class="container px-2 justify-content-between">
			<div class="navbar-brand">
				<a class="brand-anchor" href="{{ route('frontend.home') }}">
                    @if(!empty($application->photo))
                        <img src="{{ asset('logo.png') }}" height="70" alt="BRAND">
                    @else
                        <img src="{{ asset('assets/img/logo.png') }}" height="50" alt="BRAND">
                    @endif
                </a>
			</div>
			
			<li class="nav-item ml-xl-3 mb-2 mb-xl-2 d-xl-none" style="
    list-style: none;
">
						<a href="{{ route('frontend.login') }}" class="nav-link btn btn-danger px-3 btn-header1">Login</a>
			</li>
			<li class="nav-item ml-xl-3 mb-2 mb-xl-2 d-xl-none" style="
    list-style: none;
">
						<a href="{{ route('frontend.merchantRegistration') }}" class="nav-link btn btn-danger px-3 btn-header1">Sign Up</a>
			</li>

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
						<a href="{{ route('frontend.home') }}/services" class="nav-link text-white">Services</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('frontend.home') }}/faq" class="nav-link text-white">FAQ</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('frontend.home') }}/about" class="nav-link text-white">About Us</a>
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
					<li class="nav-item ml-xl-3 mb-2 mb-xl-2 ">
						<a href="{{ route('frontend.login') }}" class="nav-link btn btn-danger px-3 btn-header1">Login</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- End Navbar -->


	@yield('content')






    <div class="py-5"></div>
    
    @include("layouts.frontend.footer")
    <!-- End Footer-->


	<!-- Link of javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<!-- <script src="./assets/js/jquery.min.js"></script> -->
	<script src="{{ asset('assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    {{--<script src="{{ asset('js/frontend_js/bootstrap.bundle.js') }}"></script>--}}
    <!-- Slider -->
    <script src="{{ asset('js/frontend_js/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('assets/js/typed.js') }}"></script>

    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('assets/js/jquery.carouselTicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('js/frontend_js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('js/frontend_js/toast.js') }}"></script>


	<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#newsLetter').on('submit',function(e){
            e.preventDefault();
            $.ajax({
                cache       : false,
                type        : "POST",
                dataType    : "JSON",
                data        : $('#newsLetter').serialize(),
                // data        : {
                //     email     : $("#newsLetterEmail").val(),
                // },
                error     : function(xhr){
                    console.log(xhr);
                },
                url       : this.action,
                success   : function(response){
                    console.log(response);
                    if(response.success){
                        $("#newsLetterEmail").val(' ');
                        toastMessage(response.success, 'Success', 'success');
                    }
                    else{
                        var getError = response.error;
                        var message = "";
                        if(getError.email){
                            message = getError.email[0];
                            toastMessage(message, 'Error', 'error');
                        }
                    }
                }
            });
        });

        // AOS
	    AOS.init()

    });

    function toastMessage(message, type, heading=''){
        if(heading == ''){
            if(type == 'success'){
                heading = "Success";
            }
            else{
                heading = "error";
            }
        }
        $.toast({
            text : message,
            heading : type,
            icon : heading,
            hideAfter : 5000,
            textAlign : 'left',
            position : 'bottom-right',
        });
    }
</script>
@stack('script_js')
</body>
</html>
