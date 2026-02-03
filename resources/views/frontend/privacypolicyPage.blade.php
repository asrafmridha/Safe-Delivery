@extends('layouts.frontend.app')


@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Privacy Policy</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Privacy Policy</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

   <!-- Contact Area -->
	<div class="contact-section section-padding">
		<div class="container registrationContainer">
			<div class="row">
				<div class="col-md-12  col-sm-12" style="margin-top: 10px;">
                    @if ($privacyPolicyPage)
                        {!! $privacyPolicyPage->long_details !!}
                    @endif
				</div> 
			</div>
		</div>
	</div>


@endsection

@push('style_css')
    <style>
        #contactForm{
            font-size: 15px;
        }
        .contact-form{
            background-color: rgb(236, 236, 236);
            margin-top: 10px;
            padding: 16px 5px 16px 10px;
        }

        .contact-form input, .contact-form textarea{
            margin-bottom: 0px;
        }
        .form-control{
            padding: 8px 8px;
            font-size: 0.79rem;
            line-height: 1;
            border: 1px solid #c1c2c4;
        }
        .select2-results__option{
            padding: 1px;
        }
        .select2-results__options{
            font-size: 14px;
        }
        .btn-primary.submit:hover{
            background-color: #61B334;
            color: #fffdfd;
        }
        .btn-primary.submit{
            padding : 6px 16px;
        }

        @media (min-width:1200px) {
            .registrationContainer {
                max-width: 1300px !important;
            }
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered{
            font-size : 12px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    {{--Sweet Alert--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css" rel="stylesheet" type="text/css">

@endpush

 @push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.js"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function(){
     

        });
    </script>
 @endpush
