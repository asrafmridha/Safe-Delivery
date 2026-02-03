@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Team Member</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Team Member</h6>
					</div>
				</div>
			</div>
		</div>
    </div>


    @if ($teamMembers->count() > 0)
    <!-- team start-->
	<div id="team" class="team-main-block">
		<div class="container">
			<div class="section text-center">
				<h1 class="section-heading">Our Team Member</h1>
			</div>
			<div class="row">
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
