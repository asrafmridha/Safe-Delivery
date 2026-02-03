@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>FAQ</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / FAQ</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

    @if ($frequentlyAskQuestions->count() > 0)
	<!-- faq start-->
	<div id="faq" class="faq-main-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="section-title">
						<h3>Customer Query</h3>
						<h2>Frequently Asked Question</h2>
					</div>
					<div class="styled-faq">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @foreach ($frequentlyAskQuestions as $frequentlyAskQuestion)
                            <div class="panel panel-default">
								<div class="panel-heading {{ $loop->first ? 'active' : ''}}" role="tab" id="heading{{ $frequentlyAskQuestion->id }}">
									<h6 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $frequentlyAskQuestion->id }}" aria-expanded="true" aria-controls="collapseOne">
											{{ $frequentlyAskQuestion->question }}
											<i class="las la-angle-up"></i>
											<i class="las la-angle-down"></i>
										</a>
									</h6>
								</div>
								<div id="collapse{{ $frequentlyAskQuestion->id }}" class="panel-collapse collapse {{ $loop->first ? 'show' : ''}}" role="tabpanel" aria-labelledby="heading{{ $frequentlyAskQuestion->id }}">
									<div class="panel-body">
										{!! $frequentlyAskQuestion->answer !!}
									</div>
								</div>
							</div>
                            @endforeach

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- faq end-->
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
