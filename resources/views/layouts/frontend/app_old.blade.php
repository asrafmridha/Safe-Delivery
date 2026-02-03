@php
    $application    = App\Models\Application::first();
    $socialLinks    = App\Models\SocialLink::where('status', 1)->get();
    $services       = App\Models\Service::where('status', 1)->get();
    $blogs          = App\Models\Blog::where('status', 1)->orderBy('id', 'desc')->take(2)->get();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $application->name }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="{{ $application->meta_description }}" />
	<meta name="keywords" content="{{ $application->meta_description }}">
    <meta name="author" content="{{ $application->meta_description }}" />
    @if(!empty($application->photo))
        <link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >
        <link rel="shortcut icon" type="image/png" href="{{ asset('uploads/application/') . '/' . $application->photo }}" alt="{{ $application->name ?? config('app.name', 'Inventory') }}" >
    @endif
	<meta name="MobileOptimized" content="320" />
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/bootstrap.min.css') }}"  />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/menumaker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/animate.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/owl.carousel.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/line-awesome.min.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/flaticon.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/slicknav.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/responsive.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/style.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend_css/toast.css') }}">
	<style>
        .tab {
            overflow: hidden;
            border: 1px solid #000;
            background-color: #ffffff;
        }

        .tab button {
            background-color: #ffffff;
            float: center;
            border: none;
            outline: none;
            cursor: pointer;
            text-align:center;
            padding: 10px 10px;
            transition: 0.3s;
        }

        .tab button:hover {
            background-color: #44B9E9;
        }

        .tab button.active {
            background-color: #44B9E9;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #000;
            border-top: none;
        }

        table {
            width:100%;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        #t01 tr:nth-child(even) {
            background-color: #eee;
        }
        #t01 tr:nth-child(odd) {
            background-color: #fff;
        }
        #t01 th {
            color: 000;
            text-align:center;
        }
        .breadcroumb-area {
            background-image: url({{ asset('image/frontend_images/bread/bread-bg.png') }});
        }

        #trackingBox:focus {
            border: 1px solid  #f14e4e;
            font-size: 30px;
        }

        #trackingBtn{
            padding:0.3em 1.2em;
            border:0.16em solid rgba(255,255,255,0);
            box-sizing: border-box;
            text-decoration:none;
            font-family:'Roboto',sans-serif;
            font-weight:120;
            color:#FFFFFF;
            text-shadow: 0 0.04em 0.04em rgba(0,0,0,0.35);
            text-align:center;
            transition: all 0.2s;
            background-color:#DF1F5A;
            font-size: 16px;
            border-top-right-radius: 26px;
            border-bottom-right-radius: 26px;
        }

        #trackingBtn :hover{
            border-color: rgba(255,255,255,1);
        }

        a:hover,a:focus{
            text-decoration: none;
            outline: none;
        }


    </style>

    <style>
        #topmenu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #000;
        }

        #topmenu ul li {
        float: left;
        }

        #topmenu ul li a, .dropbtn {
        display: inline-block;
        color: #fff;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        background-color: #44B9E9;

        z-index: 999999;
        }

        #topmenu ul li a:hover, .dropdown:hover .dropbtn {
        background-color: red;
        color:white;
        }

        #topmenu ul li.dropdown {
        display: inline-block;
        }

        #dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 999999;
        }

        #dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
        }

        #dropdown-content a:hover {background-color: #f1f1f1;}

        #dropdown:hover #dropdown-content {
        display: block;
        }
    </style>

     @stack('style_css')
     
     
     
     <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WCNDFB5');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WCNDFB5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
</head>
<body>
    <div id="app"></div>
    <div id="top-bar" class="top-bar-main-block">
        <div class="container" >
            <div class="row">
                <div class="col-md-6">
                     <div class="top-nav">

                             <ul id="topmenu">

                                <li id="dropdown">
                                    <a href="{{ route('frontend.login') }}" class="dropbtn" style="color:#fff;">Log In </a>
                                    
                                </li>

                                <li id="dropdown"><a href="{{ route('frontend.merchantRegistration') }}" class="dropbtn" style="color:#fff;">Merchant Registration</a></li>


                                <li id="dropdown"> <a href="#" class="dropbtn" style="color:#fff;">Download Apps </a></li>

                             </ul>



                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="top-bar-social">
                        <ul>
                            <li class="call">
                                <i class="las la-phone-volume"></i>
                                Call us at:
                                <a href="tel:{{ $application->contact_number }}" title="">
                                    {{ $application->contact_number }}
                                </a>
                            </li>
                            @if ($socialLinks->count() > 0)
                            @foreach ($socialLinks as $socialLink)
                                @php
                                    switch ($socialLink->icon) {
                                        case "fab fa-facebook" : $name = "Facebook"; break;
                                        case "fab fa-twitter" : $name = "Twitter"; break;
                                        case "fab fa-instagram" : $name = "Instagram"; break;
                                        case "fab fa-youtube" : $name = "Youtube"; break;
                                        case "fab fa-linkedin" : $name = "Linkedin"; break;
                                        case "fab fa-skype" : $name = "Skype"; break;
                                        case "fab fa-google-plus" : $name = "Google+"; break;
                                        case "fab fa-whatsapp" : $name = "Whatsapp"; break;
                                        default : $name = ""; break;
                                    }
                                    $socialLink->url
                                @endphp
                                <li>
                                    <a href="{{ $socialLink->url }}" target="_blank" title="{{ $name }}">
                                        <i class="{{ $socialLink->icon }}"></i>
                                    </a>
                                </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- top bar end-->
    <!-- top-nav bar start-->
    <div id="nav-bar" class="nav-bar-main-block">
        <div class="sticky-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                        <!--logo-->
                        <div class="logo">
                            <a href="{{ route('frontend.home') }}" title="logo"><img src="{{ asset('uploads/application/' . $application->photo) }}" style="width: 160px; height: 56px;" alt="logo"></a>
                        </div>
                        <!-- Responsive Menu Area -->
                        <div class="responsive-menu-wrap"></div>
                    </div>

                    <div class="col-lg-7">
                        <div class="navigation text-white">
                            <div id="cssmenu">
                                <ul>
                                    <li class="active"><a href="{{ route('frontend.home') }}" title="Home">Home</a></li>
                                    <li><a href="{{ route('frontend.about') }}" title="Pages">About US +</a>
                                        <ul>
                                            <li><a href="{{ route('frontend.about') }}" title="About">About</a></li>
                                            <li><a href="{{ route('frontend.teamMember') }}" title="Team">Team</a></li>
                                            <li><a href="{{ route('frontend.quotation') }}" title="Quotation">Quotation</a></li>
                                            <li><a href="{{ route('frontend.faq') }}" title="Faq">FAQ</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('frontend.services') }}" title="Services">Services +</a>
                                        <ul>
                                            @if ($services->count() > 0)
                                                @foreach ($services as $service)
                                                    <li><a href="{{ route('frontend.serviceDetails', $service->slug ) }}" title="{{ $service->name }}">{{ $service->name }}</a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('frontend.delivery') }}" title="Delivery">Delivery</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.blogs') }}" title="blog">Blogs</a>
                                    </li>
                                    <li><a href="{{ route('frontend.contact') }}" title="contact">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="navigation-btn">
                            <a href="#" class="btn btn-primary" title="get quotes">Get Quotes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @yield('content')



    <!-- footer start-->
	<footer id="footer" class="footer-main-block">
		<div class="container">
			<div class="row text-white">
				<div class="col-lg-3 col-sm-6">
					<div class="about-widget footer-widget">
						<div class="logo-footer">
						<a href="{{ route('frontend.home') }}" title="logo"><img src="{{ asset('uploads/application/' . $application->photo) }}" style="width: 160px; height: 56px;" alt="logo"></a>
						</div>
						<p>There anyone who loves or pursues not some great to have pleasure.</p>
						<div class="row">
							<div class="col-lg-2">
								<div class="footer-icon">
									<i class="las la-home"></i>
								</div>
							</div>
							<div class="col-lg-10">
								<div class="footer-address">Corporate Office</div>
								<div class="footer-address-dtl">{{ $application->address }}</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">
								<div class="footer-icon">
									<i class="las la-phone"></i>
								</div>
							</div>
							<div class="col-lg-10">
								<div class="footer-address">Reach Us</div>
								<div class="footer-address-dtl"> Email: {{ $application->email }} </div>
								<div class="footer-address-dtl"> Cell: {{ $application->contact_number }} </div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="courier-type-widget footer-widget mrg-btm-30">
						<h6 class="footer text-white">Courier Types</h6>
						<div class="footer-list">
							<ul>
                                @if ($services->count() > 0)
                                    @foreach ($services as $service)
                                    <li>
                                        <a href="{{ route('frontend.serviceDetails', $service->slug ) }}" title="link">
                                            <i class="las la-arrow-circle-right"></i>
                                            {{ $service->name}}
                                        </a>
                                    </li>
                                    @endforeach
                                @endif
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="recent-news-widget footer-widget mrg-btm-30">
						<h6 class="footer text-white">Recent News</h6>
                        @if ($blogs->count() > 0)
                        @foreach ($blogs as $blogItem)
						<div class="row">
							<div class="col-lg-4 col-sm-3">
								<div class="footer-img">
									<a href="{{ route('frontend.blogDetails', $blogItem->slug) }}" title="{{ $blogItem->title }}">
                                        <img src="{{ asset('uploads/blog/'.$blogItem->image) }}" class="img-fluid" alt="{{ $blogItem->title }}">
                                    </a>
								</div>
                            </div>

							<div class="col-lg-8 col-sm-9">
								<div class="recent-news-footer">
									<a href="{{ route('frontend.blogDetails', $blogItem->slug) }}" title="link">
										<p>{{ $blogItem->title }}</p>
									</a>
									<div class="date-footer">
                                        <i class="las la-calendar"></i>{{ \Carbon\Carbon::parse($blogItem->date)->diffForhumans() }}
                                    </div>
								</div>
							</div>
                        </div>
                        <hr>
                        @endforeach
                        @endif
					</div>
				</div>
				<div class="col-lg-3 col-sm-6">
					<div class="news-widget footer-widget mrg-btm-30">
						<h6 class="footer text-white">Newsletter</h6>
						<p>Sign up to our courier point for surveys recent updates &amp; offers.</p>
						<form id="newsLetter" action="{{ route('frontend.newsLetter') }}" class="footer-form">
							<div class="form-group">
								<input type="email" name="email" id="newsLetterEmail" class="form-control" placeholder="Email Address" required>
							</div>
							<button type="submit" class="btn btn-primary" title="subscribe">Subscribe</button>
							<label for="mc-email"></label>
						</form>
						<div class="footer-social">
							<ul>
								<li>Follow Us :</li>
                                @if ($socialLinks->count() > 0)
                                @foreach ($socialLinks as $socialLink)
                                    @php
                                        switch ($socialLink->icon) {
                                            case "fab fa-facebook" : $name = "Facebook"; break;
                                            case "fab fa-twitter" : $name = "Twitter"; break;
                                            case "fab fa-instagram" : $name = "Instagram"; break;
                                            case "fab fa-youtube" : $name = "Youtube"; break;
                                            case "fab fa-linkedin" : $name = "Linkedin"; break;
                                            case "fab fa-skype" : $name = "Skype"; break;
                                            case "fab fa-google-plus" : $name = "Google+"; break;
                                            case "fab fa-whatsapp" : $name = "Whatsapp"; break;
                                            default : $name = ""; break;
                                        }
                                        $socialLink->url
                                    @endphp
                                    <li>
                                        <a href="{{ $socialLink->url }}" target="_blank" title="{{ $name }}">
                                            <i class="{{ $socialLink->icon }}"></i>
                                        </a>
                                    </li>
                                @endforeach
                                @endif
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tiny-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="copyright-block">
                            <p>&copy; {{ \Carbon\Carbon::now()->format('Y') }}
                                <a href="{{ route('frontend.home') }}" title="{{ $application->name }}">{{ $application->name }}</a>. All Rights Reserved.</p>
						</div>
					</div>
					<div class="col-md-6 text-right">
						<div class="copyright-social">
							<ul>
								<li class="policy"><a href="https://eazyxpressbd.com/privacypolicy.html" target="_blank" title="Privacy Policy">Privacy Policy </a></li>
								<li><a href="#" title="Terms &amp; Conditions"> Terms &amp; Conditions </a></li>
								<li>
								<li class="dropdown">
									<a href="#" data-toggle="dropdown" title="English"><i class="las la-globe"></i>English<i class="las la-caret-square-down"></i></a>
									<ul class="dropdown-menu">
										<li><a href="#" title="French">French</a></li>
										<li><a href="#" title="Germany">Germany</a></li>
										<li><a href="#" title="Urdu">Bengali</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- footer end-->

	<!-- Scroll Top Area -->
	<a href="#top" class="go-top" style="display: block;"><i class="las la-angle-up"></i></a>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery-2.min.js') }}"></script>
    <script src="{{ asset('js/frontend_js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/frontend_js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/frontend_js/smooth-scroll.js') }}"></script>
    <script src="{{ asset('js/frontend_js/menumaker.js') }}"></script>
    <script src="{{ asset('js/frontend_js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.counterup.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('js/frontend_js/jquery.ajaxchimp.js') }}"></script>
    <script src="{{ asset('js/frontend_js/theme.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
