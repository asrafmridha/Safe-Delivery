@extends('layouts.frontend.app')


@section('content')

    <!-- Breadcroumb Area -->
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="breadcroumb-title text-center">
						<h1>Merchant Registration</h1>
						<h6><a href="{{ route('frontend.home') }}">Home</a> / Merchant Registration</h6>
					</div>
				</div>
			</div>
		</div>
    </div>

   <!-- Contact Area -->
	<div class="contact-section section-padding">
		<div class="container registrationContainer">
			<div class="row">
				<div class="col-lg-4 col-md-12  col-sm-12" style="margin-top: 10px;">
                    @if ($merchantRegistrationPage->count() > 0)
                    <div class="about-left">
                        <img src="{{ asset('uploads/pageContent/'.$merchantRegistrationPage->image) }}" style="height: 600px; width:100%;" alt="">
                    </div>
                    @endif
				</div>
				<div class="col-lg-8 col-md-12  col-sm-12">
                    <div class="contact-form">
                        <div class="col-sm-12 text-center" >
                            <h3>Merchant Registration Form</h3>
                        </div>
                        <form name="contact-form" id="merchantRegistrationForm" action="{{ route('frontend.confirmMerchantRegistration') }}" method="POST">
                            <div class="form-group row">
                                <label for="company_name" class="col-sm-3 col-form-label">
                                    Company Name : <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">
                                    Name : <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Merchant Name" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">
                                    Full Address : <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="address" id="address" cols="30" rows="3"  placeholder="Merchant Full Address" ></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="business_address" class="col-sm-3 col-form-label">
                                    Business Address : <span style="font-weight: bold; color: red;"></span>
                                </label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="business_address" id="business_address" cols="30" rows="3"  placeholder="Merchant Business Address"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="district_id" class="col-sm-3 col-form-label">
                                    Dist/Area : <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9 row" style="padding-right: 0px;">
                                    <div class="col-md-6">
                                        <select name="district_id" id="district_id" class="form-control select2" style="width: 100%" >
                                            <option value="0">Select District</option>
                                            @if ($districts->count() > 0)
                                                @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                            <option value="0">Select Upazila</option>
                                        </select>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                            <option value="0">Select Area</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="contact_number" class="col-sm-3 col-form-label">
                                    Contact Number : <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">+88</div>
                                        </div>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Merchant Contact Number">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fb_url" class="col-sm-3 col-form-label">
                                    Facebook Business Page:  
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">http://</div>
                                        </div>
                                        <input type="text" class="form-control" id="fb_url" name="fb_url" placeholder=" Facebook Business Page Url" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="web_url" class="col-sm-3 col-form-label">
                                    Website : <span style="font-weight: bold; color: red;"></span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">http://</div>
                                        </div>
                                        <input type="text" class="form-control" id="web_url" name="web_url" placeholder="Merchant Website Url" >
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <!--<div class="form-group row">-->
                            <!--    <label for="bank_account_name" class="col-sm-3 col-form-label">-->
                            <!--        Bank Info :-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9 row" style="padding-right: 0px;">-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="Account Name" >-->
                            <!--        </div>-->

                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_account_no" name="bank_account_no" placeholder="Account Number" >-->
                            <!--        </div>-->

                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" >-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="bkash_number" class="col-sm-3 col-form-label">-->
                            <!--        BKash/ Nagad/Rocket-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9 row" style="padding-right: 0px;">-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="bkash_number" name="bkash_number" placeholder="BKash Number" >-->
                            <!--        </div>-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="nagad_number" name="nagad_number" placeholder="Nagad Number" >-->
                            <!--        </div>-->
                            <!--        <div class="col-md-4">-->
                            <!--            <input type="text" class="form-control" id="rocket_name" name="rocket_name" placeholder="Rocket Number" >-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="nid_no" class="col-sm-3 col-form-label">-->
                            <!--        NID No :-->
                            <!--    </label>-->
                            <!--    <div class="col-sm-9">-->
                            <!--        <input type="text" class="form-control" id="nid_no" name="nid_no" placeholder="NID NO" >-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="nid_card" class="col-sm-3 col-form-label">Upload NID Card (Both Side) </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="nid_card" id="nid_card"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="trade_license" class="col-sm-3 col-form-label">Trade License </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="trade_license" id="trade_license"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="form-group row">-->
                            <!--    <label for="tin_certificate" class="col-sm-3 col-form-label">TIN Certificate </label>-->
                            <!--    <div class="col-md-9">-->
                            <!--        <input type="file" name="tin_certificate" id="tin_certificate"  class="form-control" accept="image/*">-->
                            <!--        <div id="preview_file" style="margin-top: 10px;"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            
                            
                            
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">
                                    Email: <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Merchant Email " >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">
                                    Password: <span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="confirm_password" class="col-sm-3 col-form-label">
                                    Confirm Password:<span style="font-weight: bold; color: red;">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button class="btn btn-primary submit" type="submit" name="submit" id="registrationBtn" >
                                        Submit
                                    </button>
                                </div>
                            </div>
						</form>
					</div>
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
            if ($(".select2").length > 0) $('.select2').select2();

            $('#district_id').on('change', function(){
                var district_id   = $("#district_id option:selected").val();
                $("#area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "JSON",
                    data      : {
                            district_id: district_id,
                            _token : "{{ csrf_token() }}"
                        },
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    // url       : "{{ route('upazila.districtOption') }}",
                    url       : "{{ route('area.districtWiseAreaOption') }}",
                    success   : function(response){
                        // $("#upazila_id").html(response.option).attr('disabled', false);
                        $("#area_id").html(response.option).attr('disabled', false);
                    }
                })
            });


            $('#merchantRegistrationForm').on('submit',function(e){
                e.preventDefault();

                var district_id         = $("#district_id option:selected").val();
                var upazila_id          = $("#upazila_id option:selected").val();
                var area_id             = $("#area_id option:selected").val();
                var password            = $("#password").val();
                var confirm_password    = $("#confirm_password").val();
                var fb_url              = $("#fb_url").val();
                var company_name        = $("#company_name").val();
                var name                = $("#name").val();
                var address             = $("#address").val();
                var address             = $("#address").val();
                var email               = $("#email").val();
                var contact_number      = $("#contact_number").val();

                if(company_name == ''){
                    toastMessage('Please Enter Company Name', 'Error', 'error');
                    return false;
                }
                if(name == ''){
                    toastMessage('Please Enter Merchant Name', 'Error', 'error');
                    return false;
                }
                if(address == ''){
                    toastMessage('Please Enter Merchant Address', 'Error', 'error');
                    return false;
                }
                if(district_id == '0'){
                    toastMessage('Please Select District', 'Error', 'error');
                    return false;
                }
                if(upazila_id == '0'){
                    toastMessage('Please Select Upazila', 'Error', 'error');
                    return false;
                }

                // console.log(contact_number.length);
                if(contact_number.length != 11){
                    toastMessage('Please Enter Merchant Contact Number', 'Error', 'error');
                    return false;
                }

                // if(fb_url == ''){
                //     toastMessage('Please Enter Facebook Url', 'Error', 'error');
                //     return false;
                // }
                if(email == ''){
                    toastMessage('Please Enter Merchant Email', 'Error', 'error');
                    return false;
                }
                if(password.length < 5){
                    toastMessage('Password Length Must be 5 Digit', 'Error', 'error');
                    return false;
                }
                if(password != confirm_password){
                    toastMessage("Password and Confirm Password Does Not Match ", 'Error', 'error');
                    return false;
                }





                $.ajax({
                    cache       : false,
                    type        : "POST",
                    dataType    : "JSON",
                    headers     : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data        : new FormData(this),
                    contentType: false,
                    processData: false,
                    error     : function(xhr){
                        console.log(xhr);
                    },
                    url       : this.action,
                    success   : function(response){
                        if(response.success){
                            
                          ///  window.location = "{{ route('frontend.otp_merchant_registration_login') }}";
                            $("#merchantRegistrationForm")[0].reset();
                        //    toastMessage(response.success, 'Success', 'success');
                            Swal.fire({
                                type: response.type,
                                title: response.title,
                                text: response.message,
                                showConfirmButton: true,
                                timer: 4000
                            });
                            
                            
                            setTimeout(function(){
                                window.location = "{{ route('frontend.otp_merchant_registration_login') }}";
                              },4000);
                              
                            {{--setTimeout(function(){--}}
                                {{--window.location = "{{ route('frontend.otp_merchant_registration_check') }}";--}}
                            {{--},5000);--}}
                        }
                        else{
                            var getError = response.error;
                            var message = "";
                            if(getError.company_name){
                                message = getError.company_name[0];
                            }
                            if(getError.name){
                                message = getError.name[0];
                            }
                            if(getError.address){
                                message = getError.address[0];
                            }
                            if(getError.district_id){
                                message = getError.district_id[0];
                            }
                            if(getError.area_id){
                                message = getError.area_id[0];
                            }
                            if(getError.contact_number){
                                message = getError.contact_number[0];
                            }
                            if(getError.email){
                                message = getError.email[0];
                            }
                            if(getError.password){
                                message = getError.password[0];
                            }
                            if(getError.confirm_password){
                                message = getError.confirm_password[0];
                            }
                            if(getError.fb_url){
                                message = getError.fb_url[0];
                            }
                            toastMessage(message, 'Error', 'error');
                        }
                    }
                });
            });



        });
    </script>
 @endpush
