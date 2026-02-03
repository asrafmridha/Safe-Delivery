@extends('layouts.frontend.app')

@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Contact</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Contact</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

   <!-- Contact Area -->
	<div class="contact-section section-padding">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-12 col-sm-12">
					<div class="section-title">
						<h2>Find Us Easy Way</h2>
					</div>
					<div class="row">
                        @if ($offices->count() > 0)
                        @foreach ($offices as $office)
                        <div class="col-lg-6 col-md-6 col-sm-6">
							<h5>{{ $office->name }} Office </h5>
							<div class="contact-detail">
								<p><i class="las la-mobile"></i><b>Phone</b>
									<span>{{ $office->contact_number }}</span>
								</p>
							</div>

							<div class="contact-detail">
								<p><i class="las la-map-marker"></i><b>Address</b>
									<span>{{ $office->address }}</span>
								</p>
							</div>

							<div class="contact-detail">
								<p><i class="las la-envelope"></i><b>Email</b>
									<span>{{ $office->email }}</span>
								</p>
							</div>
						</div>
                        @endforeach
                        @endif
					</div>
				</div>
				<div class="col-lg-6">
					<div class="contact-form">
						<h3>Get in Touch</h3>
						<form name="contact-form" id="contactForm" action="{{ route('frontend.visitorMessages') }}" method="POST">
							<input type="text" name="name" id="name" required="" placeholder="User Name">
							<input type="email" name="email" id="email" required="" placeholder="Your E-mail">
							<input type="text" name="subject" id="subject" required=""  placeholder="Subject">
							<textarea name="message" id="message" cols="30" rows="10" required="" placeholder="How can help you?"></textarea>
							<button class="btn btn-primary" type="submit" name="submit">Send Message</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>



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


@endpush

 @push('script_js')

    <script>
        $(function(){

            $('#contactForm').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    cache       : false,
                    type        : "POST",
                    dataType    : "JSON",
                    data        : {
                        name      : $("#name").val(),
                        email     : $("#email").val(),
                        subject   : $("#subject").val(),
                        message   : $("#message").val(),
                        _token    : "{{ csrf_token() }}"
                    },
                    error     : function(xhr){
                        console.log(xhr);
                    },
                    url       : this.action,
                    success   : function(response){
                        if(response.success){
                            $("#name").val(' ');
                            $("#email").val(' ');
                            $("#subject").val(' ');
                            $("#message").val(' ');
                            $.toast({
                                text : response.success,
                                heading : 'Success',
                                icon : 'success',
                                hideAfter : 5000,
                                textAlign : 'left',
                                position : 'bottom-right',
                            });
                        }
                        else{
                            var getError = response.error;
                            var message = "";
                            if(getError.name){
                                message = getError.name[0];
                            }
                            if(getError.email){
                                message = getError.email[0];
                            }
                            if(getError.subject){
                                message = getError.subject[0];
                            }
                            if(getError.message){
                                message = getError.message[0];
                            }
                            $.toast({
                                text : message,
                                heading : 'Error',
                                icon : 'error',
                                hideAfter : 5000,
                                textAlign : 'left',
                                position : 'bottom-right',
                            });
                        }
                    }
                });
            });

        });
    </script>
 @endpush
