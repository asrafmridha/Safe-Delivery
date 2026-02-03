@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Quotation</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Quotation</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

    <!-- calculate start-->
	<div id="calculate" class="quotation-main-block theme-2">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12">
					<div class="quote-img">
						<img src="{{ asset('image/frontend_images/quote.png') }}" alt="">
					</div>
				</div>

				<div class="col-lg-6 col-md-12">
					<div class="quotation-form-area">
						<h1>Calculate your cost</h1>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto quasi necessitatibus hic debitis deserunt porro.</p>
						<div class="quote-form">
							<form action="http://capricorn-theme.net/html/excelsure/index.html" class="row">
								<div class="form-list">
									<div class="col-lg-3">
										<label>Height (CM):</label>
									</div>
									<div class="col-lg-9">
										<input type="text" placeholder="">
									</div>
								</div>
								<div class="form-list">
									<div class="col-lg-3">
										<label>Width (CM):</label>
									</div>
									<div class="col-lg-9">
										<input type="text" placeholder="">
									</div>
								</div>
								<div class="form-list">
									<div class="col-lg-3">
										<label>Depth (CM):</label>
									</div>
									<div class="col-lg-9">
										<input type="text" placeholder="">
									</div>
								</div>
								<div class="form-list">
									<div class="col-lg-3">
										<label>Weight (KG):</label>
									</div>
									<div class="col-lg-9">
										<input type="text" placeholder="">
									</div>
								</div>
								<div class="form-list">
									<div class="col-lg-3">
										<label>Location:</label>
									</div>
									<div class="col-lg-9">
										<div class="row no-gutters">
											<div class="col-lg-6">
												<input type="text" placeholder="FROM">
											</div>
											<div class="col-lg-6">
												<input type="text" placeholder="TO">
											</div>
										</div>
									</div>
								</div>
								<div class="form-list">
									<div class="col-lg-3">
										<label>Package:</label>
									</div>

									<div class="col-lg-9">
										<select name="courier">
											<option>Standard</option>
											<option>Express</option>
											<option>Door to Door</option>
											<option>Pallet</option>
											<option>International</option>
										</select>
									</div>
								</div>
							</form>
							<div class="row">
								<div class="col-lg-12 text-right">
									<div class="cost-center">
										<div class="btn-1"> <span> Total Cost: </span> <span class="dark">$150</span> </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
    <!-- calculate end-->


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
