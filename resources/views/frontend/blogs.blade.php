@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Blogs</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Blogs</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

    @if ($blogs->count() > 0)
	<!-- blog start -->
	<div id="blog" class="blog-main-block">
		<div class="container">
			<div class="row">
                @foreach ($blogs as $blog)
                <div class="col-lg-12 col-xl-6 col-md-6">
                    <div class="blog-block">
                        <div class="row">
                            <div class="col-xl-5 col-lg-3">
                                <div class="blog-img">
                                    <a href="{{ route('frontend.blogDetails', $blog->slug) }}" title="{{ $blog->title }}">
                                        <img src="{{ asset('uploads/blog/'.$blog->image) }}" alt="{{ $blog->title }}" style="height: 200px; width:200px;">
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
                                    <h6 class="blog-heading"><a href="#" title="{{ $blog->title }}">{{ $blog->title }}</a></h6>
                                    <p>
                                        {{ substr($blog->short_details,0,100) }}
                                    </p>
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
                @endforeach
              
            </div>
            <div class="row">
                <div class="col-lg-12 col-xl-6 col-md-6"> 
                    {{ $blogs->links('vendor.pagination.custom') }}
                </div> 
            </div>
		</div>
	</div>
    <!-- blog end-->
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

@push('style_css')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style type="text/css">
        .my-actives span{
            background-color: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }
        .pager li{
            font-size: 18px !important;
        }
        .btn-primary {
            background-color: #ff0000;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600px;
            padding: 14px 28px;
            border: 1px solid#ff0000;
            text-transform: uppercase;
            -webkit-transition: none;
            -moz-transition: none;
            transition: none;
            -webkit-transition: all 0.5s ease;
            -ms-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }
    </style>
@endpush
 