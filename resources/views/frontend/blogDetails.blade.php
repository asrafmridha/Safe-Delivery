@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcroumb-title text-center">
                        <h1> Blog Details </h1>
                        <h6><a href="{{ route('frontend.home') }}">Home</a> / <a href="{{ route('frontend.blogs') }}">Blog</a>/ {{ substr($blog->title,0,60) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($blog->count() > 0)
	<!-- blog details start -->
	<div class="blog-section section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-12">
					<div class="single-blog-wrap">
						<img src="{{ asset('uploads/blog/'.$blog->image) }}" alt="{{ $blog->title }}" style="width: 100%; height: 400px;">
						<div class="blog-meta">
							<span><i class="las la-calendar"></i>{{ \Carbon\Carbon::parse($blog->date)->format('M d, Y')}}</span>
						</div>
                        <h3>{{ $blog->title }}</h3>
                        <hr>
                        <p>
                            {!! $blog->long_details !!}
                        </p>
					</div>
				</div>
				<div class="col-lg-4 col-md-12">
					<aside class="sidebar">
						<div class="blog-search">
							{{-- <form action="http://capricorn-theme.net/html/excelsure/blog.html"> --}}
								<input type="search" placeholder="Search here">
								<button type="submit"><i class="las la-search"></i></button>
							{{-- </form> --}}
						</div>
						<div class="recent-post">
                            <h5>Recent Post</h5>
                            @if ($blogs->count() > 0)
                            @foreach ($blogs as $blogItem)
							<div class="single-recent-post" style="margin-bottom: 10px">
								<img src="{{ asset('uploads/blog/'.$blogItem->image) }}" alt="{{ $blogItem->title }}">
								<div class="recent-post-content">
									<h6><a href="{{ route('frontend.blogDetails', $blogItem->slug) }}">{{ $blogItem->title }}</a></h6>
									<p class="post-date">
                                        <i class="las la-calendar"></i>{{ \Carbon\Carbon::parse($blogItem->date)->format('M d, Y')}}
                                    </p>
								</div>
                            </div>
                            @endforeach
                            @endif

						</div>
					</aside>
				</div>
			</div>
		</div>
	</div>
    <!-- blog details end-->
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
