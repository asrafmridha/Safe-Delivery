@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Service</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Service</h6>
					</div>
				</div>
			</div>
		</div>
    </div>


	<!-- services start -->
	<div id="service-detail" class="services-main-block-3">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Courier Services</h1>
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


	<!-- facts start-->
	<!--<div id="facts" class="facts-main-block" style="background-image: url({{ asset('image/frontend_images/bg/facts-bg.html') }});">-->
	<!--	<div class="container">-->
	<!--		<div class="row no-gutters text-white">-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">2150</h1><span>+</span>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Satisfied Clients</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">100</h1>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Offices Worldwide</div>-->
	<!--				</div>-->
	<!--			</div>-->

	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">55</h1>-->
	<!--					<div class="facts-dtl">Countries Covered</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--			<div class="col-lg-3 col-md-3 col-sm-6">-->
	<!--				<div class="facts-block text-center mrg-btm-30">-->
	<!--					<h1 class="facts-heading text-white counter">4.6</h1>-->
	<!--					<div class="facts-heading-sign text-white"></div>-->
	<!--					<div class="facts-dtl">Reviews</div>-->
	<!--				</div>-->
	<!--			</div>-->
	<!--		</div>-->
	<!--	</div>-->
	<!--</div>-->
    <!-- facts end-->

    @if ($deliveryServices->count() > 0)
    <!-- services start -->
	<div id="services" class="services-main-block-2" style="background-image: url({{ asset('image/frontend_images/bg/facts-bg.jpg') }});">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Delivery Services</h1>
			</div>
			<div class="row">
                @foreach ($deliveryServices as $deliveryService)
				<div class="col-lg-4 col-md-6 col-sm-12">
					<div class="single-service-item">
						<div class="download-icon">
							<img src="{{ asset('uploads/deliveryService/'.$deliveryService->image) }}" class="img-fluid" alt="about-img">
						</div>
						<h4>{{ $deliveryService->name }}</h4>
						<p>{{ $deliveryService->short_details }}</p>
					</div>
                </div>
                @endforeach

			</div>
		</div>
	</div>
    <!-- services end-->
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

@endsection
