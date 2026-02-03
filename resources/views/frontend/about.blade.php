@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>About Us</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / About Us</h6>
					</div>
				</div>
			</div>
		</div>
    </div>


    @if ($aboutPage)
		<!-- about start-->
        <div id="about" class="about-main-block theme-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="about-left">
                            <img src="{{ asset('uploads/pageContent/'.$aboutPage->image) }}" style="height: 100%; max-height: 500px; " alt="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="about-content">
                            <h1 class="section-heading">We're Top Mover Service <br>in Downtown</h1>
                            <p class="wow slideInDown">
                                {!! $aboutPage->long_details !!}
                            </p>
                        </div>
                    </div>
                </div>

                @if ($aboutPoints->count() > 0)
                <div class="row">
                    @foreach ($aboutPoints as $aboutPoint)
                    <div class="col-lg-4 col-md-6">
                        <div class="about-block-two" style="
    background: aliceblue;
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
        <!-- about end-->
    @endif


    @if ($customerFeedbacks->count() > 0)
	<!-- testimonial start-->
	<div id="testimonial" class="testimonial-main-block" style="background-image: url('{{ asset('image/frontend_images') }}/bg/clients.jpg')">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Customer Feedback</h1>
			</div>
			<div id="testimonial-block-slider" class="testimonial-block-slider owl-carousel">
                @foreach ($customerFeedbacks as $customerFeedback)
				<div class="item testimonial-dtl">
					<div class="testimonial-client-img">
						<img src="{{ asset('uploads/customerFeedback/'.$customerFeedback->image) }}" class="img-fluid" alt="testimonial">
						<i class="las la-quote-right"></i>
					</div>
					<p>“ {{ $customerFeedback->feedback }} ”</p>
					<div class="rating">
						<ul>
							<li><i class="las la-star"></i></li>
							<li><i class="las la-star"></i></li>
							<li><i class="las la-star"></i></li>
							<li><i class="las la-star"></i></li>
							<li><i class="las la-star"></i></li>
						</ul>
					</div>
					<div class="testimonial-name">{{ $customerFeedback->name }}</div>
                </div>
                @endforeach
			</div>
		</div>
	</div>
    <!-- testimonial end-->
    @endif

   <!-- clients start-->
   @if ($partners->count() > 0)
   <div id="clients" class="clients-main-block">
       <div class="container">
           <h1 class="">OUR TRUSTED PARTNER</h1>
           <div class="row">
               <div id="clients-slider" class="clients-slider owl-carousel">
                   @foreach ($partners  as $partner)
                   <div class="item-clients-img">
                       <img src="{{ asset('uploads/partner/'.$partner->image) }}" class="img-fluid" alt="clients-1">
                   </div>
                   @endforeach
               </div>
           </div>
       </div>
   </div>
   @endif
   <!-- clients end-->

	<!-- apps start-->
	<div id="our-app" class="our-app-main-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="app-dtl">
						<div class="section">
							<h1 class="section-heading">Download Our App</h1>
							<p>There anyone who loves or pursues nor desires to obtain pain occasionally can packages as their default.</p>
						</div>
						<div class="row">
							<div class="col-lg-2">
								<div class="download-icon">
									<img src="{{ asset('image/frontend_images') }}/icons/app-1.png" class="img-fluid" alt="about-img">
								</div>
							</div>
							<div class="col-lg-10">
								<div class="app">End to End Facilitation</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">
								<div class="download-icon">
									<img src="{{ asset('image/frontend_images') }}/icons/app-2.png" class="img-fluid" alt="about-img">
								</div>
							</div>
							<div class="col-lg-10">
								<div class="app">Real Time Updates &amp; Tracking</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">
								<div class="download-icon">
									<img src="{{ asset('image/frontend_images') }}/icons/app-3.png" class="img-fluid" alt="about-img">
								</div>
							</div>
							<div class="col-lg-10">
								<div class="app">Safety, Security, Confidentiality</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">
								<div class="download-icon">
									<img src="{{ asset('image/frontend_images') }}/icons/app-4.png" class="img-fluid" alt="about-img">
								</div>
							</div>
							<div class="col-lg-10">
								<div class="app">Save Time &amp; Effort</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 text-right">
					<div class="download-img">
						<img src="{{ asset('image/frontend_images') }}/app-mockup.png" class="img-fluid" alt="about-img">
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- app end-->


@endsection
