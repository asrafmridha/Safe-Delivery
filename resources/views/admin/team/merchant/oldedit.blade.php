@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Merchant</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.merchant.index') }}">Merchants</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Merchant </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="offset-md-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.merchant.update', $merchant->id) }}" method="POST"
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
                                                {{-- <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="upazila_id"> Thana/Upazila <span style="font-weight: bold; color: red;">*</span> </label>
                                                        <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Thana/Upazila</option>
                                                            @foreach ($upazilas as $upazila)
                                                                <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="area_id"> Area  </label>
                                                        <select name="area_id" id="area_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Area</option>
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="branch_id"> Branch <span style="font-weight: bold; color: red;">*</span> </label>
                                                    <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%">
                                                        <option value="0">Select Branch</option>
                                                        @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
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
                                                    <input type="password" name="password" id="password" value="{{ $merchant->cod_charge ?? old('password') }}" class="form-control" placeholder="Password" >
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <label for="cod_charge">COD %</label>
                                                    <input type="number" name="cod_charge" id="cod_charge" value="{{ $merchant->cod_charge ?? old('cod_charge') }}" class="form-control" placeholder="COD %" >
                                                </div> --}}

                                                @if($serviceAreas->count() > 0)
                                                    <div class="col-md-12 row" style="margin-top: 20px;">
                                                        <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                            <label for="charge" > Service Area COD Charge</label>
                                                        </div>
                                                        @foreach ($serviceAreas as $serviceArea)
                                                            @php
                                                                $cod_charge = "";
                                                                foreach($merchant->service_area_cod_charges as $service_area_cod_charge){
                                                                    if($service_area_cod_charge->id == $serviceArea->id){
                                                                        $cod_charge = floatval($service_area_cod_charge->pivot->cod_charge);
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="col-md-4 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="cod_charge{{ $serviceArea->id }}">{{ $serviceArea->name }} COD Charge  </label>
                                                                    <input type="number" name="cod_charge[]" id="cod_charge{{ $serviceArea->id }}" placeholder="{{ $serviceArea->name }} COD Charge " value="{{ $cod_charge }}" step="any"  class="form-control">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif




                                                @if($serviceAreas->count() > 0)
                                                    <div class="col-md-12 row">
                                                        <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                            <label for="charge" > Service Area Delivery Charge</label>
                                                        </div>
                                                        @foreach ($serviceAreas as $serviceArea)
                                                            @php
                                                                $charge ="";
                                                                foreach($merchant->service_area_charges as $service_area_charge){
                                                                    if($service_area_charge->id == $serviceArea->id){
                                                                        $charge                 = floatval($service_area_charge->pivot->charge);
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="col-md-4 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="charge{{ $serviceArea->id }}">{{ $serviceArea->name }} Delivery Charge  </label>
                                                                    <input type="number" name="charge[]" id="charge{{ $serviceArea->id }}" placeholder="{{ $serviceArea->name }} Charge " value="{{ $charge }}" step="any"  class="form-control">
                                                                    <input type="hidden" name="service_area_id[]" id="service_area_id{{ $serviceArea->id }}" value="{{ $serviceArea->id }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($serviceAreas->count() > 0)
                                                    <div class="col-md-12 row" style="margin-top: 20px;">
                                                        <div class="col-md-12" style="border-bottom: 2px #000 dotted ">
                                                            <label for="charge" > Service Area Return Charge</label>
                                                        </div>
                                                        @foreach ($serviceAreas as $serviceArea)
                                                            @php
                                                                $return_charge = "";
                                                                foreach($merchant->service_area_return_charges as $service_area_return_charge){
                                                                    if($service_area_return_charge->id == $serviceArea->id){
                                                                        $return_charge = floatval($service_area_return_charge->pivot->return_charge);
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="col-md-4 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="return_charge{{ $serviceArea->id }}">{{ $serviceArea->name }} Return Charge  </label>
                                                                    <input type="number" name="return_charge[]" id="return_charge{{ $serviceArea->id }}" placeholder="{{ $serviceArea->name }} Return Charge " value="{{ $return_charge }}" step="any" class="form-control">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

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

                                                <div class="col-md-4">
                                                    <label for="status"> Status </label>
                                                    <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                                        <option value="1" {{$merchant->status==1 ?"selected":""}}>Active</option>
                                                        <option value="0"  {{$merchant->status==0 ?"selected":""}}>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="otp_token_status"> Phone Status </label>
                                                    <select name="otp_token_status" id="otp_token_status" class="form-control select2" style="width: 100%" >
                                                        <option value="1" {{$merchant->otp_token_status==1 ?"selected":""}}>Verified</option>
                                                        <option value="0" {{$merchant->otp_token_status==0 ?"selected":""}}>Not Verified</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email_verified_at"> Email Verified At </label>
                                                    <input type="date" name="email_verified_at" id="email_verified_at" value="{{$merchant->email_verified_at ? date("Y-m-d",strtotime($merchant->email_verified_at)):''}}"  class="form-control" >
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
                                                    
                                                <!--@if($merchant->payment_recived_by==5)-->
                                                <!--    <div class="bank_info col-md-12">-->
                                                <!--        <div class="row">-->
                                                <!--            <div class="col-md-6">-->
                                                <!--                <label for="bank_account_name">Bank A/C Owner Name</label>-->
                                                <!--                <input type="text" name="bank_account_name" id="bank_account_name" value="{{ $merchant->bank_account_name ?? old('bank_account_name') }}" class="form-control" placeholder="Bank Account Name" >-->
                                                <!--            </div>-->
                                                <!--            <div class="col-md-6" >-->
                                                <!--                <label for="bank_account_no">Bank A/C Number</label>-->
                                                <!--                <input type="text" name="bank_account_no" id="bank_account_no" value="{{ $merchant->bank_account_no ?? old('bank_account_no') }}" class="form-control" placeholder="Bank Account Number" >-->
                                                <!--            </div>-->
                                                            
                                                <!--            <div class="col-md-6">-->
                                                <!--    <label for="bank_name">Bank Name</label>-->
                                                <!--    <select name="bank_name" id="bank_name" class="form-control select2">-->
                                                <!--        <option value="0" {{ (old('bank_name') == '0' || ($merchant && $merchant->bank_name == '0')) ? 'selected' : '' }}>Select Bank</option>-->
                                                <!--        <option value="AB Bank Ltd" {{ (old('bank_name') == 'AB Bank Ltd' || ($merchant && $merchant->bank_name == 'AB Bank Ltd')) ? 'selected' : '' }}>AB Bank Ltd</option>-->
                                                <!--        <option value="Agrani Bank Ltd" {{ (old('bank_name') == 'Agrani Bank Ltd' || ($merchant && $merchant->bank_name == 'Agrani Bank Ltd')) ? 'selected' : '' }}>Agrani Bank Ltd</option>-->
                                                <!--        <option value="Al-Arafah Islami Bank Ltd" {{ (old('bank_name') == 'Al-Arafah Islami Bank Ltd' || ($merchant && $merchant->bank_name == 'Al-Arafah Islami Bank Ltd')) ? 'selected' : '' }}>Al-Arafah Islami Bank Ltd</option>-->
                                                <!--        <option value="Bank Asia Ltd" {{ (old('bank_name') == 'Bank Asia Ltd' || ($merchant && $merchant->bank_name == 'Bank Asia Ltd')) ? 'selected' : '' }}>Bank Asia Ltd</option>-->
                                                <!--        <option value="Brac Bank Ltd" {{ (old('bank_name') == 'Brac Bank Ltd' || ($merchant && $merchant->bank_name == 'Brac Bank Ltd')) ? 'selected' : '' }}>Brac Bank Ltd</option>-->
                                                <!--        <option value="City Bank Ltd" {{ (old('bank_name') == 'City Bank Ltd' || ($merchant && $merchant->bank_name == 'City Bank Ltd')) ? 'selected' : '' }}>City Bank Ltd</option>-->
                                                <!--        <option value="DBBL Agent Banking" {{ (old('bank_name') == 'DBBL Agent Banking' || ($merchant && $merchant->bank_name == 'DBBL Agent Banking')) ? 'selected' : '' }}>DBBL Agent Banking</option>-->
                                                <!--        <option value="Dhaka Bank Ltd" {{ (old('bank_name') == 'Dhaka Bank Ltd' || ($merchant && $merchant->bank_name == 'Dhaka Bank Ltd')) ? 'selected' : '' }}>Dhaka Bank Ltd</option>-->
                                                <!--        <option value="Dutch-Bangla Bank Ltd" {{ (old('bank_name') == 'Dutch-Bangla Bank Ltd' || ($merchant && $merchant->bank_name == 'Dutch-Bangla Bank Ltd')) ? 'selected' : '' }}>Dutch-Bangla Bank Ltd</option>-->
                                                <!--        <option value="Eastern Bank Ltd" {{ (old('bank_name') == 'Eastern Bank Ltd' || ($merchant && $merchant->bank_name == 'Eastern Bank Ltd')) ? 'selected' : '' }}>Eastern Bank Ltd</option>-->
                                                <!--        <option value="Exim Bank Bangladesh Ltd" {{ (old('bank_name') == 'Exim Bank Bangladesh Ltd' || ($merchant && $merchant->bank_name == 'Exim Bank Bangladesh Ltd')) ? 'selected' : '' }}>Exim Bank Bangladesh Ltd</option>-->
                                                <!--        <option value="First Security Islami Bank Limited" {{ (old('bank_name') == 'First Security Islami Bank Limited' || ($merchant && $merchant->bank_name == 'First Security Islami Bank Limited')) ? 'selected' : '' }}>First Security Islami Bank Limited</option>-->
                                                <!--        <option value="IFIC Bank Ltd" {{ (old('bank_name') == 'IFIC Bank Ltd' || ($merchant && $merchant->bank_name == 'IFIC Bank Ltd')) ? 'selected' : '' }}>IFIC Bank Ltd</option>-->
                                                <!--        <option value="Islami Bank Bangladesh Ltd" {{ (old('bank_name') == 'Islami Bank Bangladesh Ltd' || ($merchant && $merchant->bank_name == 'Islami Bank Bangladesh Ltd')) ? 'selected' : '' }}>Islami Bank Bangladesh Ltd</option>-->
                                                <!--        <option value="Jamuna Bank Ltd" {{ (old('bank_name') == 'Jamuna Bank Ltd' || ($merchant && $merchant->bank_name == 'Jamuna Bank Ltd')) ? 'selected' : '' }}>Jamuna Bank Ltd</option>-->
                                                <!--        <option value="Mercantile Bank Ltd" {{ (old('bank_name') == 'Mercantile Bank Ltd' || ($merchant && $merchant->bank_name == 'Mercantile Bank Ltd')) ? 'selected' : '' }}>Mercantile Bank Ltd</option>-->
                                                <!--        <option value="Meghna Bank Limited" {{ (old('bank_name') == 'Meghna Bank Limited' || ($merchant && $merchant->bank_name == 'Meghna Bank Limited')) ? 'selected' : '' }}>Meghna Bank Limited</option>-->
                                                <!--        <option value="Midland Bank Ltd" {{ (old('bank_name') == 'Midland Bank Ltd' || ($merchant && $merchant->bank_name == 'Midland Bank Ltd')) ? 'selected' : '' }}>Midland Bank Ltd</option>-->
                                                <!--        <option value="Mutual Trust Bank Ltd" {{ (old('bank_name') == 'Mutual Trust Bank Ltd' || ($merchant && $merchant->bank_name == 'Mutual Trust Bank Ltd')) ? 'selected' : '' }}>Mutual Trust Bank Ltd</option>-->
                                                <!--        <option value="National Bank Ltd" {{ (old('bank_name') == 'National Bank Ltd' || ($merchant && $merchant->bank_name == 'National Bank Ltd')) ? 'selected' : '' }}>National Bank Ltd</option>-->
                                                <!--        <option value="NRB Bank Ltd" {{ (old('bank_name') == 'NRB Bank Ltd' || ($merchant && $merchant->bank_name == 'NRB Bank Ltd')) ? 'selected' : '' }}>NRB Bank Ltd</option>-->
                                                <!--        <option value="NRB Commercial Bank Ltd" {{ (old('bank_name') == 'NRB Commercial Bank Ltd' || ($merchant && $merchant->bank_name == 'NRB Commercial Bank Ltd')) ? 'selected' : '' }}>NRB Commercial Bank Ltd</option>-->
                                                <!--        <option value="One Bank Ltd" {{ (old('bank_name') == 'One Bank Ltd' || ($merchant && $merchant->bank_name == 'One Bank Ltd')) ? 'selected' : '' }}>One Bank Ltd</option>-->
                                                <!--        <option value="Prime Bank Ltd" {{ (old('bank_name') == 'Prime Bank Ltd' || ($merchant && $merchant->bank_name == 'Prime Bank Ltd')) ? 'selected' : '' }}>Prime Bank Ltd</option>-->
                                                <!--        <option value="Shahjalal Islami Bank Limited" {{ (old('bank_name') == 'Shahjalal Islami Bank Limited' || ($merchant && $merchant->bank_name == 'Shahjalal Islami Bank Limited')) ? 'selected' : '' }}>Shahjalal Islami Bank Limited</option>-->
                                                <!--        <option value="Southeast Bank Ltd" {{ (old('bank_name') == 'Southeast Bank Ltd' || ($merchant && $merchant->bank_name == 'Southeast Bank Ltd')) ? 'selected' : '' }}>Southeast Bank Ltd</option>-->
                                                <!--        <option value="Standard Bank Ltd" {{ (old('bank_name') == 'Standard Bank Ltd' || ($merchant && $merchant->bank_name == 'Standard Bank Ltd')) ? 'selected' : '' }}>Standard Bank Ltd</option>-->
                                                <!--        <option value="Standard Chartered Bank" {{ (old('bank_name') == 'Standard Chartered Bank' || ($merchant && $merchant->bank_name == 'Standard Chartered Bank')) ? 'selected' : '' }}>Standard Chartered Bank</option>-->
                                                <!--        <option value="The Premier Bank Ltd" {{ (old('bank_name') == 'The Premier Bank Ltd' || ($merchant && $merchant->bank_name == 'The Premier Bank Ltd')) ? 'selected' : '' }}>The Premier Bank Ltd</option>-->
                                                <!--        <option value="Trust Bank Ltd" {{ (old('bank_name') == 'Trust Bank Ltd' || ($merchant && $merchant->bank_name == 'Trust Bank Ltd')) ? 'selected' : '' }}>Trust Bank Ltd</option>-->
                                                <!--        <option value="United Commercial Bank Ltd" {{ (old('bank_name') == 'United Commercial Bank Ltd' || ($merchant && $merchant->bank_name == 'United Commercial Bank Ltd')) ? 'selected' : '' }}>United Commercial Bank Ltd</option>-->
                                                <!--        <option value="Uttara Bank Ltd" {{ (old('bank_name') == 'Uttara Bank Ltd' || ($merchant && $merchant->bank_name == 'Uttara Bank Ltd')) ? 'selected' : '' }}>Uttara Bank Ltd</option>-->
                                                        
                                                <!--    </select>-->
                                                <!--</div>-->
                                                            <!--<div class="col-md-6">-->
                                                            <!--    <label for="bank_name">Bank Name</label>-->
                                                            <!--    <input type="text" name="bank_name" id="bank_name" value="{{ $merchant->bank_name ?? old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                                            <!--</div>-->
                                                            
                                                <!--             <div class="col-md-6">-->
                                                <!--                <label for="bank_route_no">Bank Route Number</label>-->
                                                <!--                <input type="text" name="bank_route_no" id="bank_route_no" value="{{ $merchant->bank_route_no ?? old('bank_route_no') }}" class="form-control" placeholder="Bank Name" >-->
                                                <!--            </div>-->
                                                            
                                                <!--         </div>-->
                                                <!-- </div>-->
                                                <!--@endif-->
                                                
                                                
                                                
                                                <!--@if($merchant->payment_recived_by==2)-->
                                                <!--     <div class="bkash_info col-md-6" >-->
                                                <!--        <label for="bkash_number">BKash Number</label>-->
                                                <!--        <input type="text" name="bkash_number" id="bkash_number" value="{{ $merchant->bkash_number ??  old('bkash_number') }}" class="form-control" placeholder="BKash Number" >-->
                                                <!--    </div>-->
                                                <!-- @endif-->
                                                 
                                                <!-- @if($merchant->payment_recived_by==3)-->
                                                <!--  <div class="nagad_info col-md-6">-->
                                                <!--    <label for="nagad_number">Nagad Number</label>-->
                                                <!--    <input type="text" name="nagad_number" id="nagad_number" value="{{ $merchant->nagad_number ??  old('nagad_number') }}" class="form-control" placeholder="Nagad Number" >-->
                                                <!--</div>-->
                                                <!-- @endif-->
                                                 
                                                <!-- @if($merchant->payment_recived_by==4)-->
                                                <!-- <div class="rocket_info col-md-6">-->
                                                <!--    <label for="rocket_name">Rocket Number</label>-->
                                                <!--    <input type="text" name="rocket_name" id="rocket_name" value="{{  $merchant->rocket_name ??  old('rocket_name') }}" class="form-control" placeholder="Rocket Number" >-->
                                                <!--</div>-->

                                                <!-- @endif-->
                                                             



                                                 <div class="bank_info col-md-12" style="display: none;">
                                                    <div class="row">
                                                        
                                                <div class="col-md-6">
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

                                                    
                                                    <!--<select name="bank_name" id="bank_name" class="form-control select2">-->
                                                    <!--    <option value="Select Type" {{ (old('bank_name') == 'Select Type' || ($merchant && $merchant->bank_name == 'Select Type')) ? 'selected' : '' }}>Select Type</option>-->
                                                    <!--    <option value="AB Bank Ltd" {{ (old('bank_name') == 'AB Bank Ltd' || ($merchant && $merchant->bank_name == 'AB Bank Ltd')) ? 'selected' : '' }}>AB Bank Ltd</option>-->
                                                    <!--    <option value="Agrani Bank Ltd" {{ (old('bank_name') == 'Agrani Bank Ltd' || ($merchant && $merchant->bank_name == 'Agrani Bank Ltd')) ? 'selected' : '' }}>Agrani Bank Ltd</option>-->
                                                    <!--    <option value="Al-Arafah Islami Bank Ltd" {{ (old('bank_name') == 'Al-Arafah Islami Bank Ltd' || ($merchant && $merchant->bank_name == 'Al-Arafah Islami Bank Ltd')) ? 'selected' : '' }}>Al-Arafah Islami Bank Ltd</option>-->
                                                    <!--    <option value="Bank Asia Ltd" {{ (old('bank_name') == 'Bank Asia Ltd' || ($merchant && $merchant->bank_name == 'Bank Asia Ltd')) ? 'selected' : '' }}>Bank Asia Ltd</option>-->
                                                    <!--    <option value="Brac Bank Ltd" {{ (old('bank_name') == 'Brac Bank Ltd' || ($merchant && $merchant->bank_name == 'Brac Bank Ltd')) ? 'selected' : '' }}>Brac Bank Ltd</option>-->
                                                    <!--    <option value="City Bank Ltd" {{ (old('bank_name') == 'City Bank Ltd' || ($merchant && $merchant->bank_name == 'City Bank Ltd')) ? 'selected' : '' }}>City Bank Ltd</option>-->
                                                    <!--    <option value="DBBL Agent Banking" {{ (old('bank_name') == 'DBBL Agent Banking' || ($merchant && $merchant->bank_name == 'DBBL Agent Banking')) ? 'selected' : '' }}>DBBL Agent Banking</option>-->
                                                    <!--    <option value="Dhaka Bank Ltd" {{ (old('bank_name') == 'Dhaka Bank Ltd' || ($merchant && $merchant->bank_name == 'Dhaka Bank Ltd')) ? 'selected' : '' }}>Dhaka Bank Ltd</option>-->
                                                    <!--    <option value="Dutch-Bangla Bank Ltd" {{ (old('bank_name') == 'Dutch-Bangla Bank Ltd' || ($merchant && $merchant->bank_name == 'Dutch-Bangla Bank Ltd')) ? 'selected' : '' }}>Dutch-Bangla Bank Ltd</option>-->
                                                    <!--    <option value="Eastern Bank Ltd" {{ (old('bank_name') == 'Eastern Bank Ltd' || ($merchant && $merchant->bank_name == 'Eastern Bank Ltd')) ? 'selected' : '' }}>Eastern Bank Ltd</option>-->
                                                    <!--    <option value="Exim Bank Bangladesh Ltd" {{ (old('bank_name') == 'Exim Bank Bangladesh Ltd' || ($merchant && $merchant->bank_name == 'Exim Bank Bangladesh Ltd')) ? 'selected' : '' }}>Exim Bank Bangladesh Ltd</option>-->
                                                    <!--    <option value="First Security Islami Bank Limited" {{ (old('bank_name') == 'First Security Islami Bank Limited' || ($merchant && $merchant->bank_name == 'First Security Islami Bank Limited')) ? 'selected' : '' }}>First Security Islami Bank Limited</option>-->
                                                    <!--    <option value="IFIC Bank Ltd" {{ (old('bank_name') == 'IFIC Bank Ltd' || ($merchant && $merchant->bank_name == 'IFIC Bank Ltd')) ? 'selected' : '' }}>IFIC Bank Ltd</option>-->
                                                    <!--    <option value="Islami Bank Bangladesh Ltd" {{ (old('bank_name') == 'Islami Bank Bangladesh Ltd' || ($merchant && $merchant->bank_name == 'Islami Bank Bangladesh Ltd')) ? 'selected' : '' }}>Islami Bank Bangladesh Ltd</option>-->
                                                    <!--    <option value="Jamuna Bank Ltd" {{ (old('bank_name') == 'Jamuna Bank Ltd' || ($merchant && $merchant->bank_name == 'Jamuna Bank Ltd')) ? 'selected' : '' }}>Jamuna Bank Ltd</option>-->
                                                    <!--    <option value="Mercantile Bank Ltd" {{ (old('bank_name') == 'Mercantile Bank Ltd' || ($merchant && $merchant->bank_name == 'Mercantile Bank Ltd')) ? 'selected' : '' }}>Mercantile Bank Ltd</option>-->
                                                    <!--    <option value="Meghna Bank Limited" {{ (old('bank_name') == 'Meghna Bank Limited' || ($merchant && $merchant->bank_name == 'Meghna Bank Limited')) ? 'selected' : '' }}>Meghna Bank Limited</option>-->
                                                    <!--    <option value="Midland Bank Ltd" {{ (old('bank_name') == 'Midland Bank Ltd' || ($merchant && $merchant->bank_name == 'Midland Bank Ltd')) ? 'selected' : '' }}>Midland Bank Ltd</option>-->
                                                    <!--    <option value="Mutual Trust Bank Ltd" {{ (old('bank_name') == 'Mutual Trust Bank Ltd' || ($merchant && $merchant->bank_name == 'Mutual Trust Bank Ltd')) ? 'selected' : '' }}>Mutual Trust Bank Ltd</option>-->
                                                    <!--    <option value="National Bank Ltd" {{ (old('bank_name') == 'National Bank Ltd' || ($merchant && $merchant->bank_name == 'National Bank Ltd')) ? 'selected' : '' }}>National Bank Ltd</option>-->
                                                    <!--    <option value="NRB Bank Ltd" {{ (old('bank_name') == 'NRB Bank Ltd' || ($merchant && $merchant->bank_name == 'NRB Bank Ltd')) ? 'selected' : '' }}>NRB Bank Ltd</option>-->
                                                    <!--    <option value="NRB Commercial Bank Ltd" {{ (old('bank_name') == 'NRB Commercial Bank Ltd' || ($merchant && $merchant->bank_name == 'NRB Commercial Bank Ltd')) ? 'selected' : '' }}>NRB Commercial Bank Ltd</option>-->
                                                    <!--    <option value="One Bank Ltd" {{ (old('bank_name') == 'One Bank Ltd' || ($merchant && $merchant->bank_name == 'One Bank Ltd')) ? 'selected' : '' }}>One Bank Ltd</option>-->
                                                    <!--    <option value="Prime Bank Ltd" {{ (old('bank_name') == 'Prime Bank Ltd' || ($merchant && $merchant->bank_name == 'Prime Bank Ltd')) ? 'selected' : '' }}>Prime Bank Ltd</option>-->
                                                    <!--    <option value="Shahjalal Islami Bank Limited" {{ (old('bank_name') == 'Shahjalal Islami Bank Limited' || ($merchant && $merchant->bank_name == 'Shahjalal Islami Bank Limited')) ? 'selected' : '' }}>Shahjalal Islami Bank Limited</option>-->
                                                    <!--    <option value="Southeast Bank Ltd" {{ (old('bank_name') == 'Southeast Bank Ltd' || ($merchant && $merchant->bank_name == 'Southeast Bank Ltd')) ? 'selected' : '' }}>Southeast Bank Ltd</option>-->
                                                    <!--    <option value="Standard Bank Ltd" {{ (old('bank_name') == 'Standard Bank Ltd' || ($merchant && $merchant->bank_name == 'Standard Bank Ltd')) ? 'selected' : '' }}>Standard Bank Ltd</option>-->
                                                    <!--    <option value="Standard Chartered Bank" {{ (old('bank_name') == 'Standard Chartered Bank' || ($merchant && $merchant->bank_name == 'Standard Chartered Bank')) ? 'selected' : '' }}>Standard Chartered Bank</option>-->
                                                    <!--    <option value="The Premier Bank Ltd" {{ (old('bank_name') == 'The Premier Bank Ltd' || ($merchant && $merchant->bank_name == 'The Premier Bank Ltd')) ? 'selected' : '' }}>The Premier Bank Ltd</option>-->
                                                    <!--    <option value="Trust Bank Ltd" {{ (old('bank_name') == 'Trust Bank Ltd' || ($merchant && $merchant->bank_name == 'Trust Bank Ltd')) ? 'selected' : '' }}>Trust Bank Ltd</option>-->
                                                    <!--    <option value="United Commercial Bank Ltd" {{ (old('bank_name') == 'United Commercial Bank Ltd' || ($merchant && $merchant->bank_name == 'United Commercial Bank Ltd')) ? 'selected' : '' }}>United Commercial Bank Ltd</option>-->
                                                    <!--    <option value="Uttara Bank Ltd" {{ (old('bank_name') == 'Uttara Bank Ltd' || ($merchant && $merchant->bank_name == 'Uttara Bank Ltd')) ? 'selected' : '' }}>Uttara Bank Ltd</option>-->
                                                        
                                                    <!--</select>-->
                                                    
                                                </div>
                                                
                                                <div class="col-md-6">
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
                                                
                                                
                                                
                                                <!--<div class="col-md-6">-->
                                                <!--    <label for="bank_name">Bank Name</label>-->
                                                <!--    <input type="text" name="bank_name" id="bank_name" value="{{ $merchant->bank_name ?? old('bank_name') }}" class="form-control" placeholder="Bank Name" >-->
                                                <!--</div>-->
                                                
                                                

                                                 
                                                
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
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush


<script>
window.onload = function(){
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
        });
  </script>




@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
         $("#district_id").val('{{ $merchant->district_id }}');
    // $("#upazila_id").val('{{ $merchant->upazila_id }}');
    $("#area_id").val('{{ $merchant->area_id }}');
    $("#status").val('{{ $merchant->status }}');

    @if($merchant->branch_id)
        $("#branch_id").val('{{ $merchant->branch_id }}');
    @endif
  
    window.onload = function(){
 
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
  
};
 
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


@endpush
