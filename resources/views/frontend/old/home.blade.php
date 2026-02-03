@extends('layouts.frontend.app')

@section('content')

    <!-- home slider start-->
    @if ($sliders->count() > 0)
	<div id="home-slider" class="home-main-block owl-carousel">
        @foreach ($sliders as $slider)
            <div class="item home-slider-bg"
                style="background-image: url('{{ asset('uploads/slider/'.$slider->image) }}'); height:500px; width:100%">
            </div>
        @endforeach
    </div>
    @endif
    <!-- home slider end-->
	<br/> <br/> <br/> <br/>


    <!-- start about -->
	<div id="about"  class="about-main-block" style="margin-top:-100px; margin-left:450px;  width:600px; margin-bottom:-10px">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-centered">

                    <form id="tracking-form" role="search" action="{{ route('frontend.orderTracking') }}"
                        method="POST" target="_black">
                        @csrf
                        {{-- <div class="form-group" > --}}
                            <div class="input-group mb-3" style="font-size: 55px;" id="trackingInputBox">
                                <input class="form-control" placeholder="Enter tracking number"
                                    type="text"
                                    name="trackingBox"
                                    id="trackingBox"
                                    style="font-size: 16px; border-top-left-radius: 26px; border-bottom-left-radius: 26px;">
                                <div class="input-group-append">
                                    <button class="btn btn-default btn-parcels" type="submit" id="trackingBtn">
                                        <div class="fa fa-binoculars"></div>
                                        <span class="hidden-xs" >
                                            Track package
                                        </span>
                                    </button>
                                </div>
                            </div>
                        {{-- </div> --}}
                    </form>
                </div>
			</div>
		</div>
	</div>
    <!-- about end-->

    {{--
        <!-- start about -->
        <div id="about" class="about-main-block">
            <div class="container">
                @if ($parcelSteps->count() > 0)
                <div class="row">
                    @foreach ($parcelSteps as $parcelStep)
                    <div class="col-lg-4 col-md-12">
                        <div class="about-block">
                            <div class="about-points-block">
                                <div class="about-points-icon">
                                    <img src="{{ asset('uploads/parcelStep/'.$parcelStep->image) }}" class="img-fluid" alt="home-icon">
                                </div>
                                <div class="about-point-dtl">
                                    <div class="about-point-heading">Step {{ $loop->iteration }}</div>
                                    <div class="about-point-text">{{ $parcelStep->title }}</div>
                                </div>
                            </div>
                            <div class="number">0{{ $loop->iteration }}</div>
                            <div class="about-type">
                                <p>It is a long established fact that a reader will be distracted by the readable content page.</p>
                                <a href="#" class="btn btn-link">Read More<i class="las la-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <!-- about end-->
    --}}

    <!-- start about -->
    <div >
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="section text-left">
                        <h1 class="section-heading">{{ $partners->count() }}+ Experiences in <br>Courier Service</h1>
                        <!-- About Content -->
                        <p>
                            {{ $aboutPage ? $aboutPage->short_details : "" }}
                        </p>
                    </div>
                    <div class="about-dtl">
                        @if ($aboutPoints->count() > 0)
                            @foreach ($aboutPoints as $aboutPoint)
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="about-icon">
                                        <img src="{{ asset('uploads/aboutPoint/'.$aboutPoint->image) }}" class="img-fluid" alt="about-img">
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="about-sub-heading">
                                        <h4 class="about-heading">{{ $aboutPoint->title }}</h4>
                                        <p>{{ $aboutPoint->details }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                    @if($aboutPage)
                    <div class="about-img">
                        <img src="{{ asset('uploads/pageContent/'.$aboutPage->image) }}" style="height: 100%; width:100%;" alt="">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- about end-->



    @if ($partners->count() > 0)
    <!-- clients start-->
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
    <!-- clients end-->
    @endif


	<!-- -Services start-->
	<div id="services" class="services-main-block">
		<div class="container-fluid">
			<div class="row no-gutters">
				<div class="col-lg-4">
					<div class="services-dtl">
						<div class="section">
							<h1 class="section-heading" style="color:#fff;">Our Services</h1>
							<p>
                                @if (!empty($servicePage))
                                    {{ $servicePage->short_details }}
                                @endif
                            </p>
						</div>
						<a href="{{ route('frontend.services') }}" class="btn btn-secondary" title="read more">Read More<i class="las la-arrow-right"></i></a>
					</div>
                </div>
                @if ($services->count() > 0)
				<div class="col-lg-8">
					<div class="service-block" style="background-image: url('{{ asset('image/frontend_images') }}/bg/service-bg.jpg')">
						<div class="overlay-bg"></div>
						<div class="row">
                            @foreach ($services as $service)
							<div class="col-lg-6 col-md-6 col-sm-6">
								<div class="service-dtl-icon">
									<div class="row">
										<div class="offset-xl-2 col-lg-2">
											<div class="service-icon">
                                                <img src="{{ asset('uploads/service/'.$service->icon) }}" class="img-fluid" alt="clients-1" style="">
											</div>
										</div>
										<div class="offset-lg-2 offset-xl-0 col-lg-8">
											<div class="service-dtl">
												<h4 class="service-heading">
                                                    <a href="{{ route('frontend.serviceDetails', $service->slug) }}" title="{{ $service->name }}">{{ $service->name }}</a>
                                                </h4>
												<p>{{ $service->short_details }}</p>
											</div>
										</div>
									</div>
								</div>
                            </div>
                            @endforeach
						</div>
					</div>
                </div>
                @endif
			</div>
		</div>
	</div>
    <!-- Services end-->

    @if ($features->count() > 0)
    <!-- features start -->
    <div id="features" class="features-main-block" style="background-image: url('{{ asset('image/frontend_images/bg/best-bg.jpg') }}')">
        <div class="container">
            <div class="section text-center">
                <h1 class="section-heading">We Give Our Best</h1>
            </div>
            <div class="row">
                @foreach ($features as $feature)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="features-block">
                        <div class="features-img">
                            <a href="javascript:void(0)" title="{{ $feature->title }}">
                                <img src="{{ asset('uploads/feature/'.$feature->image) }}" class="img-fluid-img" alt="{{ $feature->title }}">
                            </a>
                        </div>
                        <div class="features-dtl">
                            <div class="features-meta">
                                <a href="#" title="Innovation">Innovation</a>
                            </div>
                            <h4 class="features-heading"><a href="#" title="{{ $feature->title }}">{{ $feature->heading }}</a></h4>
                            <p>{{ $feature->details }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- features end-->
    @endif


	<!-- facts start-->
	<div id="facts" class="facts-main-block" style="background-image: url('{{ asset('image/frontend_images/bg/facts-bg.html') }}')">
		<div class="container">
			<div class="row no-gutters text-white">
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="facts-block text-center mrg-btm-30">
						<h1 class="facts-heading text-white counter">{{ $total_parcels }}</h1><span>+</span>
						<div class="facts-heading-sign text-white"></div>
						<div class="facts-dtl">Parcel</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="facts-block text-center mrg-btm-30">
						<h1 class="facts-heading text-white counter">{{ $total_branches }}</h1>
						<div class="facts-heading-sign text-white"></div>
						<div class="facts-dtl">Branch</div>
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="facts-block text-center mrg-btm-30">
						<h1 class="facts-heading text-white counter">{{ $total_districts }}</h1>
						<div class="facts-dtl">District</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6">
					<div class="facts-block text-center mrg-btm-30">
						<h1 class="facts-heading text-white counter">4.6</h1>
						<div class="facts-heading-sign text-white"></div>
						<div class="facts-dtl">Reviews</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!-- facts end-->


    <div id="pricing" class="pricing-main-block" align="center">
        <div class="container my-4">
            <div class="row">
                <div class="col-xl-12">
                    <h2 class="secondary-heading mb-3">
                        Delivery Charge
                    </h2>
                    <p class="mb-4">Eazy Xpress it’s a one of the best Curier service in Bangladesh</p>
                    <section>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @if($serviceAreas->count() > 0)
                                @foreach ($serviceAreas as $serviceArea)
                                <li class="nav-item waves-effect waves-light" style="background: #44B9E9;">
                                    <a class="nav-link @if($loop->first) active @endif" id="{{ $serviceArea->id }}-tab" data-toggle="tab"
                                    href="#{{ $serviceArea->id }}" role="tab" aria-controls="{{ $serviceArea->id }}" aria-selected="true">
                                    {{ $serviceArea->name }}
                                    </a>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @if($serviceAreas->count() > 0)
                                @foreach ($serviceAreas as $serviceArea)
                                <div class="tab-pane fade @if($loop->first) active show @endif " id="{{ $serviceArea->id }}" role="tabpanel" aria-labelledby="{{ $serviceArea->id }}-tab">
                                    @if($weightPackages->count() > 0)
                                    <table >
                                        <tr>
                                            @foreach ($weightPackages as $weightPackage)
                                                @if($serviceArea->weight_type == $weightPackage->weight_type)
                                                    @php
                                                        $rate = $weightPackage->rate;
                                                        if($serviceArea->weight_packages->count() > 0){
                                                            foreach ($serviceArea->weight_packages as $servicePackage) {
                                                                if($servicePackage->pivot->weight_package_id == $weightPackage->id){
                                                                    $rate = $servicePackage->pivot->rate;
                                                                }
                                                            }
                                                        }

                                                        $rate += $serviceArea->default_charge;
                                                    @endphp
                                                    <th>
                                                        <span> {{ $weightPackage->name }} </span>
                                                        <br>
                                                        <h3> ৳ {{ $rate }} </h3>
                                                    </th>
                                                @endif
                                            @endforeach
                                        </tr>
                                    </table>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>


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

    @if ($blogs->count() > 0)
	<!-- blog start -->
	<div id="blog" class="blog-main-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-sm-8">
					<div class="section">
						<h1 class="section-heading">Recent News</h1>
					</div>
				</div>
				<div class="col-lg-6 col-sm-4">
					<div class="blog-btn">
						<a href="{{ route('frontend.blogs') }}" class="btn btn-primary" title="view all">View All<i class="flaticon-next"></i></a>
					</div>
				</div>
			</div>

			<div id="blog-slider" class="owl-carousel">
                @foreach ($blogs as $blog)
                    @if($loop->odd || $loop->first)
                        <div class="row">
                    @endif
                    <div class="col-lg-12 col-xl-6 col-md-6">
                        <div class="blog-block">
                            <div class="row">
                                <div class="col-xl-5 col-lg-3">
                                    <div class="blog-img">
                                        <a href="blog-detail.html" title="blog">
                                            <img src="{{ asset('uploads/blog/'.$blog->image) }}" alt="blog" style="height: 200px">
                                        </a>
                                        <div class="meta-dtl">
                                            <a href="#" title="date">
                                                <div class="date"> {{ \Carbon\Carbon::parse($blog->date)->format('d')}} </div>
                                                <div class="month">{{ \Carbon\Carbon::parse($blog->date)->format('M')}}</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-9">
                                    <div class="blog-dtl">
                                        <h6 class="blog-heading">
                                            <a href="#" title="{{ $blog->title }}">{{ $blog->title }} </a>
                                        </h6>
                                        <p>{{ substr($blog->short_details,0,100) }}</p>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-6">
                                                <a href="{{ route('frontend.blogDetails', $blog->slug) }}" class="btn btn-link">Read More<i class="las la-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($loop->even || $loop->last)
                        </div>
                    @endif
                @endforeach
			</div>
		</div>
	</div>
    <!-- blog end-->
    @endif

    <!-- Quotation start-->
	<div id="quotation" class="quotation-main-block" style="background-image: url('{{ asset('image/frontend_images') }}/bg/consult-bg.jpg')">
		<div class="overlay-bg"></div>
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Get Quotation</h1>
			</div>
			<div class="quotation-block">
				<form class="quotation-form" method="post">
					<div class="row">
						<div class="col-lg-4 col-sm-6">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" placeholder="Full Name" required>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" placeholder="Email" required>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label for="phone">Phone No.</label>
								<input type="text" class="form-control" id="phone" placeholder="Phone No." required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8">
							<div class="form-group">
								<label>Select Courier Type</label>
								<div class="form-group">
									<select class="form-control" id="courier-type-box">
										<option>Standard</option>
										<option>Express</option>
										<option>International</option>
										<option>Pallet</option>
										<option>Ware Housing</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="request-button">
								<button type="submit" class="btn btn-primary">Send Request<i class="las la-arrow-right"></i></button>
							</div>
						</div>
					</div>
				</form>
				<div class="quotation-dtl text-white">
					<p><i class="las la-mobile"></i>We are available at Mon-Fri call us<a href="tel:" title="contact no."> + 212-4000-300</a> during regular business hours</p>
				</div>
			</div>
		</div>
	</div>
	<!-- quotation end-->

@endsection


@push('script_js')
    <script>
        $("#tracking-form").submit(function(event){
            var trackingInputBox = $(`#trackingBox`).val();
            if(trackingInputBox.length < 5 ){
                toastMessage("Please Enter Valid Order Number", 'Error', 'error');
                event.preventDefault();
                return false;
            }
            // $(`#trackingBox`).val('');
        });
    </script>
@endpush
