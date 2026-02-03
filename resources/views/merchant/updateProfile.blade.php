@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Update Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Update Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Update Merchant </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="offset-md-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('merchant.confirmUpdateProfile') }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="company_name">Company Name <span style="font-weight: bold; color: red;">*</span></label>
                                                    <input type="text" name="company_name" id="company_name" value="{{ $merchant->company_name ?? old('company_name') }}" class="form-control" placeholder="Company Name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="name">Name <span style="font-weight: bold; color: red;">*</span></label>
                                                    <input type="text" name="name" id="name" value="{{ $merchant->name ??  old('name') }}" class="form-control" placeholder="Merchant Name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address">Full Address <span style="font-weight: bold; color: red;">*</span></label>
                                                    <textarea name="address" id="address" class="form-control" placeholder="Merchant Address" required>{{  $merchant->address ??  old('address') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="business_address">Business Address</label>
                                                    <textarea name="business_address" id="business_address" class="form-control" placeholder="Business Address" >{{ $merchant->business_address ?? old('business_address') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="district_id"> Districts <span style="font-weight: bold; color: red;">*</span></label>
                                                        <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                                        <option value="0">Select District</option>
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
<!--                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="upazila_id"> Thana/Upazila <span style="font-weight: bold; color: red;">*</span> </label>
                                                        <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Thana/Upazila</option>
                                                            @foreach ($upazilas as $upazila)
                                                                <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>-->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="area_id"> Area <span style="font-weight: bold; color: red;">*</span> </label>
                                                        <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Area</option>
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="contact_number"> Contact Number <span style="font-weight: bold; color: red;">*</span> </label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                        <div class="input-group-text">+88</div>
                                                        </div>
                                                        <input type="text" name="contact_number" id="contact_number" value="{{ $merchant->contact_number ?? old('contact_number') }}" class="form-control"  placeholder="Merchant Contact Number" required="">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="email">Facebook</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                        <div class="input-group-text">http://</div>
                                                        </div>
                                                        <input type="text" class="form-control" id="fb_url" name="fb_url" value="{{ $merchant->fb_url ?? old('fb_url') }}" placeholder="Merchant Facebook Url" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email">Website</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                        <div class="input-group-text">http://</div>
                                                        </div>
                                                        <input type="text" class="form-control" id="web_url" name="web_url" value="{{ $merchant->web_url ??  old('web_url') }}" placeholder="Merchant Website Url" >
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="image">Image </label>
                                                    <input type="file" name="image" id="image"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_image')" >
                                                    <div id="preview_file_image" style="margin-top: 10px;">
                                                        @if ($merchant->image != null)
                                                            <img src="{{ asset('uploads/merchant/' . $merchant->image) }}"
                                                                class="img-fluid img-thumbnail" style="height: 100px" alt="Blog Image">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="email">Email <span style="font-weight: bold; color: red;">*</span> </label>
                                                    <input type="email" name="email" id="email" value="{{ $merchant->email ?? old('email') }}" class="form-control" placeholder="Email" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="password">Password</label>
                                                    <input type="text" name="password" id="password" value="{{ $merchant->cod_charge ?? old('password') }}" class="form-control" placeholder="Password" >
                                                </div>


                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="bank_account_name">Bank Account Name</label>-->
                                                <!--    <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $merchant->bank_account_name ?? old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >-->
                                                <!--</div>-->
                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="bank_account_no">Bank Account Number</label>-->
                                                <!--    <input type="text" name="bank_account_no" id="bank_account_no" value="{{ $merchant->bank_account_no ?? old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >-->
                                                <!--</div>-->
                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="bank_name">Bank Name</label>-->
                                                <!--    <input type="text" name="bank_name" id="bank_name" value="{{ $merchant->bank_name ?? old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                                <!--</div>-->

                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="bkash_number">BKash Number</label>-->
                                                <!--    <input type="text" name="bkash_number" id="bkash_number" value="{{ $merchant->bkash_number ??  old('bkash_number') }}" class="form-control" placeholder="BKash Number" >-->
                                                <!--</div>-->
                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="nagad_number">Nagad Number</label>-->
                                                <!--    <input type="text" name="nagad_number" id="nagad_number" value="{{ $merchant->nagad_number ??  old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >-->
                                                <!--</div>-->
                                                <!--<div class="col-md-4">-->
                                                <!--    <label for="rocket_name">Rocket Number</label>-->
                                                <!--    <input type="text" name="rocket_name" id="rocket_name" value="{{  $merchant->rocket_name ??  old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >-->
                                                <!--</div>-->


                                                <div class="col-md-4">
                                                    <label for="nid_card"> NID Card  </label>
                                                    <input type="file" name="nid_card" id="nid_card"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_nid_card')" >
                                                    <div id="preview_file_nid_card" style="margin-top: 10px;">
                                                        @if ($merchant->nid_card != null)
                                                            <img src="{{ asset('uploads/merchant/' . $merchant->nid_card) }}"
                                                                class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant NID Card">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="trade_license">Trade License  </label>
                                                    <input type="file" name="trade_license" id="trade_license"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_trade_license')" >
                                                    <div id="preview_file_trade_license" style="margin-top: 10px;">
                                                        @if ($merchant->trade_license != null)
                                                            <img src="{{ asset('uploads/merchant/' . $merchant->trade_license) }}"
                                                                class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant Trade License ">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="tin_certificate">TIN Certificate </label>
                                                    <input type="file" name="tin_certificate" id="tin_certificate"  class="form-control" accept="image/*" onchange="return filePreview(this, 'preview_file_tin_certificate')" >
                                                    <div id="preview_file_tin_certificate" style="margin-top: 10px;">
                                                        @if ($merchant->tin_certificate != null)
                                                            <img src="{{ asset('uploads/merchant/' . $merchant->tin_certificate) }}"
                                                                class="img-fluid img-thumbnail" style="height: 100px" alt="Merchant TIN Certificate">
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                 <div class="col-md-6">
                                                    <label for="payment_recived_by"> Payment Recived By</label>
                                                    <select name="payment_recived_by" id="payment_recived_by" class="form-control select2" style="width: 100%" >
                                                        <option value="0" {{$merchant->payment_recived_by==0 ?"selected":""}}>Not selected</option>
                                                        <option value="1" {{$merchant->payment_recived_by==1 ?"selected":""}}>Cash</option>
                                                        <option value="2" {{$merchant->payment_recived_by==2 ?"selected":""}}>Bkash</option>
                                                        <option value="3" {{$merchant->payment_recived_by==3 ?"selected":""}}>Nagad</option>
                                                        <option value="4" {{$merchant->payment_recived_by==4 ?"selected":""}}>Rocket</option>
                                                        <option value="5" {{$merchant->payment_recived_by==5 ?"selected":""}}>Bank</option>
                                                    </select>
                                                </div>

                                               <!--<div class="col-md-6">-->
                                               <!--     <label for="payment_recived_by"> Payment Recived By</label>-->
                                               <!--     <select name="payment_recived_by" id="payment_recived_by" class="form-control select2" style="width: 100%" >-->
                                               <!--         <option value="0" {{$merchant->payment_recived_by==0 ?"selected":""}}>Not selected</option>-->
                                               <!--         <option value="1" {{$merchant->payment_recived_by==1 ?"selected":""}}>Cash</option>-->
                                               <!--         <option value="2" {{$merchant->payment_recived_by==2 ?"selected":""}}>Bkash</option>-->
                                               <!--         <option value="3" {{$merchant->payment_recived_by==3 ?"selected":""}}>Nagad</option>-->
                                               <!--         <option value="4" {{$merchant->payment_recived_by==4 ?"selected":""}}>Rocket</option>-->
                                               <!--         <option value="5" {{$merchant->payment_recived_by==5 ?"selected":""}}>Bank</option>-->
                                               <!--     </select>-->
                                               <!-- </div>-->

                                                    
                                               <!-- @if($merchant->payment_recived_by==5)-->
                                               <!--     <div class="bank_info col-md-12">-->
                                               <!--         <div class="row">-->
                                               <!--             <div class="col-md-4">-->
                                               <!--                 <label for="bank_account_name">Bank Account Name</label>-->
                                               <!--                 <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $merchant->bank_account_name ?? old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >-->
                                               <!--             </div>-->
                                               <!--             <div class="col-md-4" >-->
                                               <!--                 <label for="bank_account_no">Bank Account Number</label>-->
                                               <!--                 <input type="text" name="bank_account_no" id="bank_account_no" value="{{ $merchant->bank_account_no ?? old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >-->
                                               <!--             </div>-->
                                               <!--             <div class="col-md-4">-->
                                               <!--                 <label for="bank_name">Bank Name</label>-->
                                               <!--                 <input type="text" name="bank_name" id="bank_name" value="{{ $merchant->bank_name ?? old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                               <!--             </div>-->
                                               <!--          </div>-->
                                               <!--  </div>-->
                                               <!-- @endif-->
                                                
                                                
                                                
                                               <!-- @if($merchant->payment_recived_by==2)-->
                                               <!--      <div class="bkash_info col-md-6" >-->
                                               <!--         <label for="bkash_number">BKash Number</label>-->
                                               <!--         <input type="text" name="bkash_number" id="bkash_number" value="{{ $merchant->bkash_number ??  old('bkash_number') }}" class="form-control" placeholder="BKash Number" >-->
                                               <!--     </div>-->
                                               <!--  @endif-->
                                                 
                                               <!--  @if($merchant->payment_recived_by==3)-->
                                               <!--   <div class="nagad_info col-md-6">-->
                                               <!--     <label for="nagad_number">Nagad Number</label>-->
                                               <!--     <input type="text" name="nagad_number" id="nagad_number" value="{{ $merchant->nagad_number ??  old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >-->
                                               <!-- </div>-->
                                               <!--  @endif-->
                                                 
                                               <!--  @if($merchant->payment_recived_by==4)-->
                                               <!--  <div class="rocket_info col-md-6">-->
                                               <!--     <label for="rocket_name">Rocket Number</label>-->
                                               <!--     <input type="text" name="rocket_name" id="rocket_name" value="{{  $merchant->rocket_name ??  old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >-->
                                               <!-- </div>-->

                                               <!--  @endif-->
                                                 
                                                
                                                
                                                
                                               <!--   <div class="bank_info col-md-12" style="display: none;">-->
                                               <!--     <div class="row">-->
                                               <!-- <div class="col-md-4">-->
                                               <!--     <label for="bank_account_name">Bank Account Name</label>-->
                                               <!--     <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $merchant->bank_account_name ?? old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >-->
                                               <!-- </div>-->
                                               <!-- <div class="col-md-4" >-->
                                               <!--     <label for="bank_account_no">Bank Account Number</label>-->
                                               <!--     <input type="text" name="bank_account_no" id="bank_account_no" value="{{ $merchant->bank_account_no ?? old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >-->
                                               <!-- </div>-->
                                               <!-- <div class="col-md-4">-->
                                               <!--     <label for="bank_name">Bank Name</label>-->
                                               <!--     <input type="text" name="bank_name" id="bank_name" value="{{ $merchant->bank_name ?? old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                               <!-- </div>-->
                                                
                                               <!--  </div>-->
                                               <!--  </div>-->



                                               <!-- <div class="bkash_info col-md-6" style="display: none;">-->
                                               <!--     <label for="bkash_number">BKash Number</label>-->
                                               <!--     <input type="text" name="bkash_number" id="bkash_number" value="{{ $merchant->bkash_number ??  old('bkash_number') }}" class="form-control" placeholder="BKash Number" >-->
                                               <!-- </div> -->
                                               <!-- <div class="nagad_info col-md-6" style="display: none;">-->
                                               <!--     <label for="nagad_number">Nagad Number</label>-->
                                               <!--     <input type="text" name="nagad_number" id="nagad_number" value="{{ $merchant->nagad_number ??  old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >-->
                                               <!-- </div>-->
                                               <!-- <div class="rocket_info col-md-6" style="display: none;">-->
                                               <!--     <label for="rocket_name">Rocket Number</label>-->
                                               <!--     <input type="text" name="rocket_name" id="rocket_name" value="{{  $merchant->rocket_name ??  old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >-->
                                               <!-- </div>-->


                                                
                                                 <div class="bank_info col-md-12" style="display: none;">
                                                    <div class="row">
                                                        
                                                <div class="col-md-4">
                                                    <label for="bank_name">Select Bank Name</label>
                                                    
                                                    <select name="bank_name" id="bank_name" class="form-control select2">
    <option value="Select Type" {{ (old('bank_name') == 'Select Type' || ($merchant && $merchant->bank_name == 'Select Type')) ? 'selected' : '' }}>Select Type</option>

    @foreach([
        'AB Bank', 'Agrani Bank', 'Al-Arafah Islami Bank', 'Bangladesh Commerce Bank', 'Bangladesh Development Bank (BDBL)',
        'Bangladesh Krishi Bank', 'Bangladesh Small Industries and Commerce Bank', 'Bank Al-Falah Limited', 'Bank Asia',
        'BASIC Bank Limited', 'Bengal Commercial bank', 'BRAC Bank', 'Citibank N.A', 'City Bank', 'Commercial Bank of Ceylon PLC',
        'Dhaka Bank', 'Dutch Bangla Bank', 'Eastern Bank', 'Export Import Bank Of Bangladesh', 'First Security Islami Bank',
        'Global Islami Bank', 'Habib Bank', 'ICB Islamic Bank', 'International Finance Investment and Commerce Bank',
        'Islami Bank Bangladesh Ltd', 'Jamuna Bank', 'Janata Bank', 'Meghna Bank Limited', 'Mercantile Bank', 'Midland Bank Limited',
        'Modhumoti Bank Limited', 'Mutual Trust Bank', 'National Bank', 'National Bank of Pakistan', 'National Credit & Commerce Bank',
        'NRB Bank Limited', 'NRBC Bank Limited', 'NRB Commercial Bank Limited', 'NRB Global Bank Limited', 'One Bank',
        'Padma Bank Limited', 'Prime Bank Ltd', 'Pubali Bank', 'Rajshahi Krishi Unnayan Bank', 'Rupali Bank', 'SBAC Bank',
        'Shahjalal Islami Bank', 'Shimanto Bank Limited', 'Social Islami Bank Ltd', 'Sonali Bank',
        'South Bangla Agriculture & Commerce Bank Limited', 'Southeast Bank', 'Standard Bank', 'Standard Chartered Bank',
        'State Bank of India', 'The Hongkong and Shanghai Banking Corporation', 'The Premier Bank', 'Trust Bank', 'Union Bank Limited',
        'United Commercial Bank Limited', 'Uttara Bank', 'Woori Bank'
    ] as $bank)
        <option value="{{ $bank }}" {{ (old('bank_name') == $bank || ($merchant && $merchant->bank_name == $bank)) ? 'selected' : '' }}>
            {{ $bank }}
        </option>
    @endforeach
</select>

                                                    
                                        
                                                    
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="bank_branch_name">Bank Branch Name</label>
                                                    <input type="text" name="bank_branch_name" id="bank_branch_name" value="{{ $merchant->bank_branch_name ?? old('bank_branch_name') }}" class="form-control" placeholder="Bank Branch Name" >
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label for="bank_route_no">Bank Route Number</label>
                                                    <input type="text" name="bank_route_no" id="bank_route_no" value="{{ $merchant->bank_route_no ?? old('bank_route_no') }}" class="form-control" placeholder="Bank Route Number" >
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="bank_account_name">Bank A/C Owner Name</label>
                                                    <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $merchant->bank_account_name ?? old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >
                                                </div>
                                                <div class="col-md-6" >
                                                    <label for="bank_account_no">Bank A/C Number</label>
                                                    <input type="text" name="bank_account_no" id="bank_account_no" value="{{ $merchant->bank_account_no ?? old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >
                                                </div>
                                                
                                                
                                          
                                                
                                                

                                                 
                                                
                                                 </div>
                                                 </div>



                                                <div class="bkash_info col-md-6" style="display: none;">
                                                    <label for="bkash_number">BKash Number</label>
                                                    <input type="text" name="bkash_number" id="bkash_number" value="{{ $merchant->bkash_number ??  old('bkash_number') }}" class="form-control" placeholder="BKash Number" >
                                                </div> 
                                                <div class="nagad_info col-md-6" style="display: none;">
                                                    <label for="nagad_number">Nagad Number</label>
                                                    <input type="text" name="nagad_number" id="nagad_number" value="{{ $merchant->nagad_number ??  old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >
                                                </div>
                                                <div class="rocket_info col-md-6" style="display: none;">
                                                    <label for="rocket_name">Rocket Number</label>
                                                    <input type="text" name="rocket_name" id="rocket_name" value="{{  $merchant->rocket_name ??  old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >
                                                </div>

                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <button type="reset" class="btn btn-primary">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $("#district_id").val('{{ $merchant->district_id }}');
    {{--$("#upazila_id").val('{{ $merchant->upazila_id }}');--}}
    $("#area_id").val('{{ $merchant->area_id }}');
    $("#status").val('{{ $merchant->status }}');


    window.onload = function(){
        /*$('#district_id').on('change', function(){
            var district_id   = $("#district_id option:selected").val();
            $("#upazila_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        district_id: district_id,
                        _token : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('upazila.districtOption') }}",
                success   : function(response){
                    $("#upazila_id").html(response.option).attr('disabled', false);
                }
            })
        });*/

        /*$('#upazila_id').on('change', function(){
            var upazila_id   = $("#upazila_id option:selected").val();
            $("#area_id").val(0).change().attr('disabled', true);
            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                    upazila_id : upazila_id,
                    _token  : "{{ csrf_token() }}"
                    },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url       : "{{ route('area.areaOption') }}",
                success   : function(response){
                    $("#area_id").html(response.option).attr('disabled', false);
                }
            })
        });*/

    }

    function editForm() {
        let district_id = $('#district_id').val();
        if(district_id == '0'){
            toastr.error("Please Select District..");
            return false;
        }

        let upazila_id = $('#upazila_id').val();
        if(upazila_id == '0'){
            toastr.error("Please Select Thana/Upazila..");
            return false;
        }

        let area_id = $('#area_id').val();
        if(area_id == '0'){
            toastr.error("Please Select Area..");
            return false;
        }

    }

    function filePreview(input, div) {
        $('#'+div).html('');
        if (input.files && input.files[0]) {
            $('#'+div).html('<img src="{{ asset('image/image_loading.gif') }}" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            var reader = new FileReader();

            if(input.files[0].size > 3000000){
                input.value='';
                $('#'+div).html('');
            }
            else{
                reader.onload = function (e) {
                $('#'+div).html('<img src="'+e.target.result+'" style="height:80px; width: 120px" class="profile-user-img img-responsive img-rounded  "/>');
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
    }

</script>

 <script>
 window.onload = function() {
  select_payment_method();
          $('#district_id').on('change', function(){
            var district_id   = $("#district_id option:selected").val();
            // $("#upazila_id").val(0).change().attr('disabled', true);
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
}
 
 function select_payment_method(){
     var payment_recived_by = $('#payment_recived_by').val();
     
          console.log(payment_recived_by);
          if( payment_recived_by == 2) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_recived_by == 3) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "");
          }
          else if(payment_recived_by == 4) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_recived_by == 5) {
              $(".bank_info").css("display", "");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
 }

      $("#payment_recived_by").on("change", function () {

          var payment_recived_by = $(this).val();
          console.log(payment_recived_by);
          if( payment_recived_by == 2) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_recived_by == 3) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "");
          }
          else if(payment_recived_by == 4) {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "");
              $(".nagad_info").css("display", "none");
          }
          else if(payment_recived_by == 5) {
              $(".bank_info").css("display", "");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }
          else {
              $(".bank_info").css("display", "none");
              $(".bkash_info").css("display", "none");
              $(".rocket_info").css("display", "none");
              $(".nagad_info").css("display", "none");
          }

      });



  </script>

//  <script>

//       $("#payment_recived_by").on("change", function () {

//           var payment_recived_by = $(this).val();
//           console.log(payment_recived_by);
//           if( payment_recived_by == 2) {
//               $(".bank_info").css("display", "none");
//               $(".bkash_info").css("display", "");
//               $(".rocket_info").css("display", "none");
//               $(".nagad_info").css("display", "none");
//           }
//           else if(payment_recived_by == 3) {
//               $(".bank_info").css("display", "none");
//               $(".bkash_info").css("display", "none");
//               $(".rocket_info").css("display", "none");
//               $(".nagad_info").css("display", "");
//           }
//           else if(payment_recived_by == 4) {
//               $(".bank_info").css("display", "none");
//               $(".bkash_info").css("display", "none");
//               $(".rocket_info").css("display", "");
//               $(".nagad_info").css("display", "none");
//           }
//           else if(payment_recived_by == 5) {
//               $(".bank_info").css("display", "");
//               $(".bkash_info").css("display", "none");
//               $(".rocket_info").css("display", "none");
//               $(".nagad_info").css("display", "none");
//           }
//           else {
//               $(".bank_info").css("display", "none");
//               $(".bkash_info").css("display", "none");
//               $(".rocket_info").css("display", "none");
//               $(".nagad_info").css("display", "none");
//           }

//       });



//   </script>


@endpush
