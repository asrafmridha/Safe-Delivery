@php
    
    $socialLinks = App\Models\SocialLink::where('status', 1)->get();
    $partners = App\Models\Partner::where('status', 1)->get();
    
    //dd($partners)
    //$blogs          = App\Models\Blog::where('status', 1)->orderBy('id', 'desc')->take(2)->get();
    
@endphp
@extends('layouts.frontend.app')

@section('content')



    <section class="hero" id="hero">
        @if ($sliders->count() > 0)
            <div id="home-slider" class="home-main-block owl-carousel">
                @foreach ($sliders as $slider)
                    <div class="item home-slider-bg"
                        style="background-image: url('{{ asset('uploads/slider/' . $slider->image) }}');">
                    </div>
                @endforeach
            </div>
        @endif
    </section>
    <!-- End hero -->


    <section class="track" id="tracking" style="position: relative; z-index: 1;">
        <div class="container">
            <form id="tracking-form" class="track-form p-4 shadow" action="{{ route('frontend.orderTracking') }}"
                method="POST" target="_blank" onsubmit="return createForm(this)">
                @csrf
                <div class="d-flex flex-column flex-md-row">
                    <div class="flex-fill">
                        <div class="track-input d-flex align-items-center">
                            <label for="trackingBox">
                                <img height="30" src="{{ asset('assets/img/track-search.jpg') }}" alt="treack search">
                            </label>
                            <input name="trackingBox" id="trackingBox" type="text" class="w-100"
                                placeholder="Type your track number">
                            <input type="submit" class="btn btn-info" value="Track Parcel">
                        </div>
                    </div>

                </div>
            </form>








            <!-- Objectives -->
            <!--@if ($objectives->count() > 0)-->
            <!--    <div class=" track-theory row mt-5 pt-5">-->
            <!--        @foreach ($objectives as $objective)-->
            <!--            <div class="box col-lg-4 col-md-6">-->
            <!--                <div class="row">-->
            <!--                    <div-->
            <!--                        class="contact-border px-lg-4 d-flex flex-column align-items-center track-theory col-md-12">-->
            <!--                        <img width="80" src="{{ asset('uploads/objective/' . $objective->image) }}"-->
            <!--                            alt="{{ $objective->name }}">-->
            <!--                        <h5 class="color1 mt-4 mb-1">{{ $objective->name }}</h5>-->
            <!--                        <p class="text-small">{{ $objective->short_details }}</p>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        @endforeach-->
            <!--    </div>-->
                
       
       
       
       
       
     
                
                <div class="trackbody  track-theory row  ">
                     @foreach ($objectives as $objective)
             <div class = "trackcontainer   col-lg-4 col-md-6" >
             <div class = "card  mx-auto ">
           <div class = "image w-75 mx-auto">
        <img href = "#" src ={{ asset('uploads/objective/' . $objective->image) }}>
             </div>
      <div class = "content">
        <h3 style="margin-top: 10px">{{ $objective->name }}</h3>
        <p style="font-size: 12px;">{{ $objective->short_details }}</p>
      </div>
    </div>    
  </div>
    @endforeach
</div>
                
                
                
                
                
                
    </section>
    
      

    @if($deliveryServices->count() > 0)
        <section class="end-to-end mt-5 pt-4">
             <div class="container">
            <h2 class="color1 text-center h4 font-weight-bold">First to Last mile Delivery Solutions for Every Types of
                Merchants</h2>
            <div class="row mx-width mt-5 pt-4" >
                @foreach($deliveryServices as $deliveryService)
                    <div class="col-lg-4 col-md-6" style="@if($loop->iteration > 3) margin-top: 20px; @endif">
                        <div class="endtoend-card w-100"
                             style="background-image: url({{ asset('uploads/deliveryService/'.$deliveryService->image) }}); border-radius: 15px;">
                            {{--<div class="endtoend-card w-100" style="background-image: url({{ asset('assets/img/02.jpg') }})">--}}
                            <div class="endtoend-card-footer d-flex align-items-center">
                                {{--<span class="fas fa-business-time mr-2"></span>--}}
                                <img src="{{ asset('uploads/deliveryService/'.$deliveryService->icon) }}"
                                     alt="Service Icon" style="height: 50px; margin-right: 15px;">
                                <div class="flex-fill">
                                    <b class="text-shadow">{{ $deliveryService->name }}</b>
                                    <p class="pb-0 mb-0">{{ $deliveryService->short_details }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                
            </div>
            </div>
        </section>
    @endif
    <!--  End end-to-end  -->




  <div id="about" class="about-main-block theme-2">
            <div class="container">
    @endif
    <!--  End end-to-end  -->
    <!-- End Track -->
    
    @if ($aboutPoints->count() > 0)
                <div class="row">
                    @foreach ($aboutPoints as $aboutPoint)
                    <div class="col-lg-4 col-md-6">
                        <div class="about-block-two" style="
    background: aliceblue; border-radius: 55px;
">
                            <div class="about-icon-two">
                                <img src="{{ asset('uploads/aboutPoint/'.$aboutPoint->image) }}" class="img-fluid" alt="about-img">
                            </div>
                            <div class="about-dtl-two">
                                <h4 class="about-heading">{{ $aboutPoint->title }}</h4>
                                <p>{{ $aboutPoint->details }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                </div>
                </div>
	<!-- services start -->
	<div id="service-detail" class="services-main-block-3">
		<div class="container">
			<div class="section text-center">
			                <h2 class="color1 text-center h4 font-weight-bold">Our Services</h2>

				<h1 class="section-heading"></h1>
			</div>
			{{-- <div class="row">
                <div class="col-md-12">
                    <p>
                        @if (!empty($servicePage))
                            {!! $servicePage->long_details !!}
                        @endif
                    </p>
                </div>
			</div> --}}
            @if ($services->count() > 0)
			<div class="row">
                @foreach ($services as $service)
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="services-block-2">
						<div class="services-img">
							<a href="{{ route('frontend.serviceDetails', $service->slug) }}" title="Real Time Status">
                                <img src="{{ asset('uploads/service/'.$service->image) }}" class="img-fluid-img" alt="Real Time Status" style="height: 250px">
                            </a>
						</div>
						<div class="services-dtl-2">
							<h4 class="services-heading"><a href="{{ route('frontend.serviceDetails', $service->slug) }}" title="{{ $service->name }}">{{ $service->name }}</a></h4>
							<p>{{ $service->short_details }}</p>
							<a href="{{ route('frontend.serviceDetails', $service->slug) }}" class="btn btn-link">Read More<i class="las la-arrow-right"></i></a>
						</div>
					</div>
                </div>
                @endforeach
            </div>
			<div class="row">
				<div class="col-md-12">
                    {{-- {{ $services->links() }} --}}
				</div>
            </div>
            @endif
		</div>
	</div>
    <!-- services end -->



<!--    @if ($services->count() > 0)-->
<!--        <section id="service" class="service mt-5 pt-5 container pb-3">-->
<!--            <h2 class="color1 text-center h4 font-weight-bold">Our Services</h2>-->


<!--            <div class="row mt-5">-->

<!--                @foreach ($services as $service)-->
<!--                    <div class="box col-lg-4 col-md-6">-->
<!--                        <div class="row">-->
<!--                            <div class="contact-border px-lg-4 d-flex flex-column align-items-center px-5 py-2" style="-->
<!--    background: white;-->
<!--">-->
                                
<!--                                <img width="80" src="{{ asset('uploads/service/' . $service->image) }}"-->
<!--                                    alt="{{ $service->name }}">-->
<!--                                <h5 class="color1 mt-3 mb-2 text-center mx-auto w-75 font-weight-semibold">-->
<!--                                    {{ $service->name }}</h5>-->
<!--                                <p class="text-small text-dark" style="text-align: justify">{{ $service->short_details }}-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                @endforeach-->

<!--            </div>-->
<!--        </section>-->
<!--    @endif-->
    <!--  End Service  -->

    <section class="districts mt-5" id="coverage">
        <div class="container">
            <div class="d-md-flex">
                <div class="align-self-center pt-3 pt-md-0">
                    <h3 class="text-light h2">{{ $application->name }} provides logistics support in all 64
                        districts across <span class="font-weight-semibold">Bangladesh</span></h3>
                </div>
                <div class="text-center text-md-left">
                    <img height="325" src="{{ asset('assets/img/05.png') }}" alt="Bangladesh">
                </div>
            </div>
        </div>
    </section>
    <!--  End districts  -->


    {{-- 

        <!-- partner starts -->

        <style>
        

            /* Slider */
    
            .slick-slide {
                margin: 0px 20px;
            }
    
            .slick-slide img {
                width: 100%;
            }
    
            .slick-slider {
                position: relative;
                display: block;
                box-sizing: border-box;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                -webkit-touch-callout: none;
                -khtml-user-select: none;
                -ms-touch-action: pan-y;
                touch-action: pan-y;
                -webkit-tap-highlight-color: transparent;
            }
    
            .slick-list {
                position: relative;
                display: block;
                overflow: hidden;
                margin: 0;
                padding: 0;
            }
    
            .slick-list:focus {
                outline: none;
            }
    
            .slick-list.dragging {
                cursor: pointer;
                cursor: hand;
            }
    
            .slick-slider .slick-track,
            .slick-slider .slick-list {
                -webkit-transform: translate3d(0, 0, 0);
                -moz-transform: translate3d(0, 0, 0);
                -ms-transform: translate3d(0, 0, 0);
                -o-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
    
            .slick-track {
                position: relative;
                top: 0;
                left: 0;
                display: block;
            }
    
            .slick-track:before,
            .slick-track:after {
                display: table;
                content: "";
            }
    
            .slick-track:after {
                clear: both;
            }
    
            .slick-loading .slick-track {
                visibility: hidden;
            }
    
            .slick-slide {
                display: none;
                float: left;
                height: 100%;
                min-height: 1px;
            }
    
            [dir="rtl"] .slick-slide {
                float: right;
            }
    
            .slick-slide img {
                display: block;
            }
    
            .slick-slide.slick-loading img {
                display: none;
            }
    
            .slick-slide.dragging img {
                pointer-events: none;
            }
    
            .slick-initialized .slick-slide {
                display: block;
            }
    
            .slick-loading .slick-slide {
                visibility: hidden;
            }
    
            .slick-vertical .slick-slide {
                display: block;
                height: auto;
                border: 1px solid transparent;
            }
    
            .slick-arrow.slick-hidden {
                display: none;
            }
        </style>
        
        
        <div style="background: #FF7425; padding-bottom: 38px; padding-top: 39px;">
          <h1 style="text-align: center; padding-bottom: 45px; color: #006B50;" >Our Delivery Partners</h1>
          <section class="customer-logos slider">
            @foreach ($partners as $partner)
            <div class="slide"><img src="{{ asset('uploads/partner/' . $partner->image) }}">
            </div>
            @endforeach
          </section>
    
        </div>
        
    
        
        
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $(".customer-logos").slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 1500,
                    arrows: false,
                    dots: false,
                    pauseOnHover: false,
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 4
                            }
                        },
                        {
                            breakpoint: 520,
                            settings: {
                                slidesToShow: 3
                            }
                        }
                    ]
                });
            });
        </script>
    
        <!-- partner ends --> --}}








    <div class="calculate-charge-section testimonials-sec" id="calculateCharge">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-8">
                    <div class="sec-title">
                        <h2 class="text-center"><span>Calculate charge</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="align-items-center">
                        <div class="col-auto">
                            <label class="mr-sm-2" for="From"> Delivery Area </label>
                            <select class="form-control" name="service_area_id" id="service_area_id">
                                <option value="">Select Area</option>
                                @foreach ($serviceAreas as $serviceArea)
                                    <option value="{{ $serviceArea->id }}">{{ $serviceArea->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="align-items-center">
                        <div class="col-auto">
                            <label class="mr-sm-2" for="service_type_id"> Service Type </label>
                            <select name="service_type_id" id="service_type_id" class="form-control select2"
                                style="width: 100%" disabled>
                                <option value="0">Select Service Type</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="align-items-center">
                        <div class="col-auto">
                            <label class="mr-sm-2" for="item_type_id"> Item Type </label>
                            <select name="item_type_id" id="item_type_id" class="form-control select2" style="width: 100%"
                                disabled>
                                <option value="0">Select Item Type</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="only_merchant_service_area_charge" name="only_merchant_service_area_charge"
                    value="0">
                <div class="col-md-3">
                    <div class="align-items-center">
                        <div class="col-auto">
                            <label class="mr-sm-2" for="weight_package_id"> Weight </label>
                            <select name="weight_package_id" id="weight_package_id" class="form-control select2"
                                style="width: 100%" disabled>
                                <option value="0" data-charge="0">Select Weight
                                    Package
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="contant-section">
                        <input type="hidden" id="deliverCharge" value="00">
                        <input type="hidden" id="confirm_weight_package_charge" value="00">
                        <p><span id="total_charge">00</span> TK</p>
                        <ul>
                            <li>* 1% COD Charge City will be added only for the deliveries outside Dhaka .</li>
                            <li>* Price may vary according to parcel size & weight.</li>
                            <li>* All charges are VAT & Tax included.</li>
                            <li>* Time of delivery may be delayed due to unavoidable circumstances like accidents or natural
                                calamities.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="ripple_wrap">
            <div class="left_top_ripples">
                <div class="ripples"></div>
            </div>
            <div class="right_bottom_ripples">
                <div class="ripples"></div>
            </div>
        </div>
    </div>




    @if ($features->count() > 0)
        <section class="why mt-5 container">
            <h2 class="color1 text-center h4 font-weight-bold">Why {{ $application->name }}</h2>
            <div class="row mt-5 pt-4 overflow-hidden">
                @foreach ($features as $feature)
                    <div class="box col-lg-4 col-md-6">
                        <div class="row">
                            <div class="contact-border px-lg-4 d-flex flex-column align-items-center track-theory col-md-12"
                                style="background: white; padding: 5px; ">
                                <img height="70" src="{{ asset('uploads/feature/' . $feature->image) }}"
                                    alt="{{ $feature->title }}">
                                <h3 class="color1 h5 mb-3 font-weight-semibold">{{ $feature->heading }}</h3>
                                <p class="why-card-txt">{{ $feature->details }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    <!-- End Why -->


    @if ($teamMembers->count() > 0)
    <!-- team start-->
	<div id="team" class="team-main-block">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Our Team Member</h1>
			</div>
			<div class="row" style="justify-content: center;">
                @foreach ($teamMembers as $teamMember)
                    <div class="col-lg-3 col-md-6 col-sm-12 animate-box" data-animate-effect="fadeInLeft">
                        <div class="team"> <img src="{{ asset('uploads/teamMember/' . $teamMember->image) }}" class="img-fluid" alt="">
                            <div class="details">
                                <h6>{{ $teamMember->name }}</h6>
                                <span>{{ $teamMember->designation->name }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
			</div>
		</div>
	</div>
	<!-- team end-->
    @endif


    
    <!-- End review -->

    {{-- //slaider --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  <!-- Demo styles -->
  <style>
    html,
    body {
      position: relative;
      height: 100%;
    }

    body {
      background: #eee;
      font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
      font-size: 14px;
      color: #000;
      margin: 0;
      padding: 0;
    }

    .swiper-wrapper{
        
    }


    .swiper {
      width: 100%;
      height: 150px;

      /* margin-top: 20px; */
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 150px;
      height: 150px;
    }

    .swiper-slide img {
      display: block;
      width: 150px;
      height: 150px;
      object-fit: cover;
    }

    
  </style>
&nbsp;
&nbsp;
&nbsp;
&nbsp;

<div style="background: #FF7425; padding-bottom: 38px; padding-top: 39px;">
    <h1 style="text-align: center; padding-bottom: 45px; color: #ffffff;">OUR TRUSTED PARTNER</h1>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">

        @foreach ($partners as $partner)

      <div class="swiper-slide"><img src="{{ asset('uploads/partner/' . $partner->image) }}"></div>
      @endforeach
     
      
    </div>
    <div class="swiper-pagination"></div>
    <!-- If we need navigation buttons -->
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
  </div>
</div>







      
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
      
        <!-- Initialize Swiper -->
        <script>
          var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            pagination: {
              el: ".swiper-pagination",
              clickable: true,
            },
            autoplay: {
                    delay: 3000,
                },


                    // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            breakpoints: {
                    0: {
                        slidesPerView: 2,
                    },
                    520: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 3,
                    },
                    1000: {
                        slidesPerView: 4,
                    },

                    1200: {
                        slidesPerView: 5,
                    },

                    1400: {
                        slidesPerView: 6,
                    },
                }
          });
                    
        
        </script>



    {{-- //slaider --}}





    <section class="contact mt-5 pt-3 pb-3 container">
        <h2 class="color1 text-center h4 font-weight-bold">Contact Us</h2>
        <div class="row mt-5">
            <div class="col-lg-4 col-md-6">
                <div class="contact-border h-100 position-relative">
                    <ul class="contact-details p-0 list-group color1 sticky-top font-weight-medium">
                        <li class="list-group-item border-0 d-flex align-items-center py-4">
                            <div><img height="45" src="{{ asset('assets/img/contact/16.png') }}" alt="">
                            </div>
                            {{-- <p class="ml-2 address-txt mb-0">House# 196, Road# 5, Mohammadia Housing Limited, Mohammadpur, Dhaka-1207.</p> --}}
                            <p class="ml-2 address-txt mb-0">{{ $application->address }}</p>
                        </li>
                        <li class="list-group-item border-0 d-flex align-items-center py-4">
                            <div><img height="45" src="{{ asset('assets/img/contact/18.png') }}" alt="">
                            </div>
                            <p class="ml-2 address-txt mb-0">{{ $application->email }}</p>
                            {{-- <p class="ml-2 address-txt mb-0">ping@eFastcourier.com</p> --}}
                        </li>
                        <li class="list-group-item border-0 d-flex align-items-center py-4">
                            <div><img height="45" src="{{ asset('assets/img/contact/17.png') }}" alt="">
                            </div>
                            <p class="ml-2 address-txt mb-0">{{ $application->contact_number }}</p>
                            {{-- <p class="ml-2 address-txt mb-0">+8809612116655</p> --}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 mt-4 mt-md-0">
                <form action="{{ route('frontend.visitorMessages') }}" method="POST" class="contact-border px-4 py-3"
                    id="contactForm">
                    <div class="form-group py-2">
                        <input id="name" class="form-control contact-input" type="text" placeholder="Your Name">
                    </div>
                    <div class="form-group py-2">
                        <input id="email" class="form-control contact-input" type="email"
                            placeholder="Your Email">
                    </div>
                    <div class="form-group py-2">
                        <input id="subject" class="form-control contact-input" type="text" placeholder="Subject">
                    </div>
                    <div class="form-group py-2">
                        <textarea id="message" rows="5" class="form-control contact-input py-2" placeholder="Message"></textarea>
                    </div>
                    <div class="form-group py-2">
                        <div class="text-right">
                            <input type="submit" value="Submit"
                                class="btn btn-danger btn-contact px-5 font-weight-semibold">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- End contact -->

    {{-- <section class="contact mt-5 pt-3 pb-3 container">
        <h2 class="color1 text-center h4 font-weight-bold">Contact Us</h2>
        <div class="row mt-5">
            @foreach ($partners as $partner)
                    <div class="col-lg-4 col-md-3">
                        <img src="{{ asset('uploads/partner/' . $partner->image) }}" style="width: 100px; margin:auto">
                    </div>
                @endforeach
        </div>
    </section> --}}



    
@endsection


@push('script_js')
    <script>
        try {

            // Reivew swippr
            $("#review-items").carouselTicker({
                speed: 1,
                delay: 30,
                direction: "prev",
                mode: "horizontal",
                onCarouselTickerLoad: function() {},
            })


            // Hero Typing
            //		const options = {
            //		  	strings: ['Easy...', 'Supper...', 'Amazing...'],
            //		  	typeSpeed: 100,
            //		  	startDelay: 256,
            //		  	loop: true
            //		}
            //		const typed = new Typed('#hero-txt', options)


            // // AOS
            // AOS.init()


            // Counter
            $('.counterup').counterUp({
                delay: 100,
                time: 3000
            })
        } catch (err) {
            console.log(err)
        }

        function createForm(event) {

            var trackingInputBox = $('#trackingBox').val();
            if (trackingInputBox.length < 5) {
                toastMessage("Please Enter Valid Order Number", 'Error', 'error');
                return false;
            }
        }

        //    $("#tracking-form").submit(function(event){
        //        var trackingInputBox = $('#trackingBox').val();
        //        if(trackingInputBox.length < 5 ){
        //            toastMessage("Please Enter Valid Order Number", 'Error', 'error');
        //            event.preventDefault();
        //            return false;
        //        }
        //        // $(`#trackingBox`).val('');
        //    });


        /** Contact Form */
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                cache: false,
                type: "POST",
                dataType: "JSON",
                data: {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    subject: $("#subject").val(),
                    message: $("#message").val(),
                    //                 purpose   : $('input[name=purpose]:checked').val(),
                    _token: "{{ csrf_token() }}"
                },
                error: function(xhr) {
                    console.log(xhr);
                },
                url: this.action,
                success: function(response) {
                    if (response.success) {
                        $("#name").val(' ');
                        $("#email").val(' ');
                        $("#subject").val(' ');
                        $("#message").val(' ');
                        $.toast({
                            text: response.success,
                            heading: 'Success',
                            icon: 'success',
                            hideAfter: 5000,
                            textAlign: 'left',
                            position: 'bottom-right',
                        });
                    } else {
                        var getError = response.error;
                        var message = "";
                        if (getError.name) {
                            message = getError.name[0];
                        }
                        if (getError.email) {
                            message = getError.email[0];
                        }
                        if (getError.subject) {
                            message = getError.subject[0];
                        }
                        if (getError.message) {
                            message = getError.message[0];
                        }
                        $.toast({
                            text: message,
                            heading: 'Error',
                            icon: 'error',
                            hideAfter: 5000,
                            textAlign: 'left',
                            position: 'bottom-right',
                        });
                    }
                }
            });
        });
    </script>




    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v17.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>





    <script>
        window.onload = function() {

            $('#service_area_id').on('change', function() {
                $("#deliverCharge").val(0);
                $("#confirm_weight_package_charge").val(0);
                $("#service_type_id").val(0).attr('disabled', true);
                $("#item_type_id").val(0).attr('disabled', true);
                $("#weight_package_id").val(0).change().attr('disabled', true);
                var service_area_id = $("#service_area_id option:selected").val();
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        service_area_id: service_area_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('returnWeightPackageOptionAndCharge') }}",
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            $("#service_type_id").html(response.serviceTypeOption).attr('disabled',
                                false);
                            $("#item_type_id").html(response.itemTypeOption).attr('disabled',
                                false);
                            $("#weight_package_id").html(response.weightPackageOption).attr(
                                'disabled', false);
                            $("#deliverCharge").val(response.charge);
                            $("#only_merchant_service_area_charge").val(response.charge);


                            calculate_total_charge();
                        } else {
                            toastr.error("something is wrong");
                        }
                    }
                });
            });

            $('#service_type_id').on('change', function() {
                var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
                var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
                var service_type_charge = returnNumber($("#service_type_id option:selected").attr(
                    'data-charge'));
                var charge = old_delivery_charge + item_type_charge + service_type_charge;
                $("#deliverCharge").val(charge);

                calculate_total_charge();
            });

            $('#item_type_id').on('change', function() {
                var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
                var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
                var service_type_charge = returnNumber($("#service_type_id option:selected").attr(
                    'data-charge'));
                var charge = old_delivery_charge + item_type_charge + service_type_charge;
                $("#deliverCharge").val(charge);

                calculate_total_charge();
            });

            $('#weight_package_id').on('change', function() {
                var weight_package_id = $("#weight_package_id option:selected").val();
                var charge = returnNumber($("#weight_package_id option:selected").attr('data-charge'));
                var merchant_service_area_charge = returnNumber($("#confirm_merchant_service_area_charge")
                    .val());

                if (weight_package_id != 0) {
                    $("#confirm_weight_package_charge").val(charge.toFixed(2));
                } else {
                    $("#confirm_weight_package_charge").val(0);
                }
                calculate_total_charge();
            });
        }

        function calculate_total_charge() {
            var delivery_charge = returnNumber($("#deliverCharge").val());
            var weight_package_charge = returnNumber($("#confirm_weight_package_charge").val());
            var total_charge = delivery_charge + weight_package_charge;
            $("#total_charge").html(total_charge.toFixed(2));
        }

        function returnNumber(value) {
            value = parseFloat(value);
            return !isNaN(value) ? value : 0;
        }
    </script>
@endpush

@push('style_css')
    <style>
        .calculate-charge-section {
            position: relative;
            padding: 55px 0;
            background: -moz-linear-gradient(-45deg, #043776 0%, #FF7425 100%);
            background: -webkit-linear-gradient(-45deg, #043776 0%, #FF7425 100%);
            background: linear-gradient(135deg, #043776 0%, #FF7425 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8400fc', endColorstr='#11def4', GradientType=1);
        }

        /*  .calculate-charge-section {
                    position: relative;
                    padding: 150px 0;
                    background: -moz-linear-gradient(-45deg, #ffffff 0%, #ffffff 100%);
                    background: -webkit-linear-gradient(-45deg, #ffffff 0%, #ffffff 100%);
                    background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8400fc', endColorstr='#11def4', GradientType=1);
                }*/

        .calculate-charge-section .contant-section {
            padding-top: 30px;
            text-align: center;
        }

        .calculate-charge-section .contant-section p {
            text-align: center;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px;
            display: inline-block;
            color: #fff;
        }

        .calculate-charge-section .contant-section ul {
            text-align: left;
            padding-top: 20px;
        }

        .contant-section ul li {
            font-size: 18px;
            font-weight: 500;
            padding: 3px 0;
            color: #fff;
        }

        .box {
            display: flex;
            padding: 7px 23px;
        }

        .calculate-charge-section .sec-title h2 {
            color: #fff;
        }

        .calculate-charge-section label {
            color: #fff;
        }
    </style>
    
        <style>
        .calculate-charge-section {
            position: relative;
            padding: 55px 0;
            background: -moz-linear-gradient(-45deg, #043776 0%, #053574 100%);
            background: -webkit-linear-gradient(-45deg, #043776 0%, #053574 100%);
            background: linear-gradient(135deg, #043776 0%, #053574 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8400fc', endColorstr='#11def4', GradientType=1);
        }

        /*  .calculate-charge-section {
                    position: relative;
                    padding: 150px 0;
                    background: -moz-linear-gradient(-45deg, #ffffff 0%, #ffffff 100%);
                    background: -webkit-linear-gradient(-45deg, #ffffff 0%, #ffffff 100%);
                    background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8400fc', endColorstr='#11def4', GradientType=1);
                }*/

        .calculate-charge-section .contant-section {
            padding-top: 30px;
            text-align: center;
        }

        .calculate-charge-section .contant-section p {
            text-align: center;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px;
            display: inline-block;
            color: #fff;
        }

        .calculate-charge-section .contant-section ul {
            text-align: left;
            padding-top: 20px;
        }

        .contant-section ul li {
            font-size: 18px;
            font-weight: 500;
            padding: 3px 0;
            color: #fff;
        }

        .box {
            display: flex;
            padding: 7px 23px;
        }

        .calculate-charge-section .sec-title h2 {
            color: #fff;
        }

        .calculate-charge-section label {
            color: #fff;
        }
        
        
        
        
        
        
        
        
      /*TESTING   */

.trackbody {
 
  align-items : center;
  justify-content : center;  

  min-height : 300px;
}

.trackcontainer {
  position : relative;
  width : 1100px;
  margin-top: 100px;

}

.trackcontainer .card {
  position: relative;
  max-width : 300px;
  height : 215px;  
  background-color : #fff;
  margin : 30px 0px;
  padding : 20px 15px;
  
  display : flex;
  flex-direction : column;
  box-shadow : 0 5px 20px rgba(0,0,0,0.5);
  transition : 0.3s ease-in-out;
  border-radius : 15px;
}
.trackcontainer .card:hover {
  height : 320px;    
}


.trackcontainer .card .image {
  position : relative;
  width : 260px;
  height : 260px;
  
  top : -40%;
background-color: white;
  box-shadow : 0 5px 20px rgba(0,0,0,0.2);
  z-index : 1;
}

.trackcontainer .card .image img {
  max-width : 100%;
  border-radius : 15px;
}

.trackcontainer .card .content {
  position : relative;
  top : -140px;
  padding : 10px 15px;
  color : #111;
  text-align : center;
  
  visibility : hidden;
  opacity : 0;
  transition : 0.3s ease-in-out;
    
}

.trackcontainer .card:hover .content {
   margin-top : 30px;
   visibility : visible;
   opacity : 1;
   transition-delay: 0.2s;
  
}
        
        
        
        
        
    </style>

@endpush
