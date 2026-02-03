@extends('layouts.frontend.app')

@section('content')


    <div class="breadcroumb-area bread-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcroumb-title text-center">
                        <h1> {{ $service->name }} </h1>
                        <h6><a href="{{ route('frontend.home') }}">Home</a> / <a href="{{ route('frontend.services') }}">Service</a>/ {{ $service->name }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @if ($service->count() > 0)
    <!-- Service Details -->
	<div class="service-details-section section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="service-list">
                        <h5>Service Lists</h5>
                        @if ($services->count() > 0)
                            @foreach ($services as $serviceItem)
                            <a @if($serviceItem->id == $service->id) class="active"  @endif href="{{ route('frontend.serviceDetails', $serviceItem->slug) }}">
                                {{ $serviceItem->name }}
                                <span><i class="las la-arrow-right"></i></span>
                            </a>
                            @endforeach
                        @endif
					</div>
				</div>

				<div class="col-lg-8">
					<div class="single-service">
						<img src="{{ asset('uploads/service/'.$service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 400px;">
						<h2>{{ $service->name }}</h2> <br>
                        {!! $service->long_details !!}
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- Service Details -->
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
