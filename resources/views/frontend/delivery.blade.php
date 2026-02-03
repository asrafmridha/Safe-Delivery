@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Service</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Delivery</h6>
					</div>
				</div>
			</div>
		</div>
    </div>


	<!-- pricing start-->
	<div id="pricing" class="pricing-main-block" style="margin-bottom: 100px">
		<div class="container">
			<div class="row">
                <table class="">
                    <thead class="ant-table-thead">
                        <tr>
                            <th class="">
                                District
                            </th>
                            <th class="">
                                Area
                            </th>
                            <th class="">
                                Post Code
                            </th>
                            <th class="">
                                Home Delivery
                            </th>
                            <th class="">
                                Lockdown
                            </th>
                            @if($weightPackages->count() > 0)
                                @foreach ($weightPackages as $weightPackage)
                                    <th class="">
                                        Charge( {{ $weightPackage->title}} )
                                    </th>
                                @endforeach
                            @endif
                            <th class="">
                                COD
                            </th>
                        </tr>
                    </thead>
                    @if($areas->count() > 0)
                    <tbody>
                        @foreach ($areas as $area)
                            <tr>
                                <td class="">
                                    {{ $area->upazila->district->name }}
                                </td>
                                <td class="">
                                    {{ $area->name }}
                                </td>
                                <td class="">
                                    {{ $area->post_code }}
                                </td>
                                <td class="">
                                    {{ $area->upazila->district->home_delivery == 0 ? "No" : "Yes" }}
                                </td>
                                <td class="">
                                    {{ $area->upazila->district->lock_down_service == 0 ? "No" : "Yes" }}
                                </td>
                                @if($weightPackages->count() > 0)
                                    @foreach ($weightPackages as $weightPackage)
                                        <td class="">
                                            {{ number_format($weightPackage->rate,2) }}
                                        </td>
                                    @endforeach
                                @endif
                                <td class="">
                                    1 %
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
		</div>
	</div>
	<!-- pricing end-->


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
