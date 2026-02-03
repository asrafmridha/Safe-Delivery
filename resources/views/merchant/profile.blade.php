@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"> Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active"> Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="{{ route('merchant.updateProfile') }}" type="submit" class="btn btn-success">
                        Update Profile &nbsp; &nbsp; &nbsp;<i class="fa fa-edit"></i>
                    </a>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Merchant Information</legend>
                        <table class="table table-style">
                            @if($merchant->image)
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <img src="{{ asset('uploads/merchant/' . $merchant->image) }} " class="img-circle bg-success" style="height: 120px; widht: 120px" alt="Merchant Photo">
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">ID </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->m_id }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Company </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->company_name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Email </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ $merchant->email }}
                                    @if(isset($merchant->email_verified_at))
                                        <span class="bg-success" style="padding: 0px 5px; border-radius: 4px">Verified</span>
                                    @else
                                        <span class="bg-warning" style="padding: 0px 5px; border-radius: 4px">Not Verified</span><br>
                                        <p> (Please Verify your email address, <a href="http://www.{{ explode("@",$merchant->email)[1] }}" target="_blank">check your email</a>)</p>
                                        <p>If you don't have any verification link,
                                            <a href="{{ route('merchant.emailVerifyLink') }}"
                                               onclick="event.preventDefault();
                                                 document.getElementById('verify-link-form').submit();">Please resend link</a></p>

                                        <form id="verify-link-form" action="{{ route('merchant.emailVerifyLink') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ $merchant->contact_number }}
                                    @if(isset($merchant->otp_token_status) && $merchant->otp_token_status == 1)
                                        <span class="bg-success" style="padding: 0px 5px; border-radius: 4px">Verified</span>
                                    @else
                                        <span class="bg-warning" style="padding: 0px 5px; border-radius: 4px">Not Verified</span><br>
                                        {{--<p> (Please Verify your email address, <a href="http://www.{{ explode("@",$merchant->email)[1] }}" target="_blank">check your email</a>)</p>--}}
                                        {{--<p>If you don't have any verification link,--}}
                                        {{--<a href="{{ route('merchant.emailVerifyLink') }}"--}}
                                        {{--onclick="event.preventDefault();--}}
                                        {{--document.getElementById('verify-link-form').submit();">Please resend link</a></p>--}}

                                        {{--<form id="verify-link-form" action="{{ route('merchant.emailVerifyLink') }}" method="POST" class="d-none">--}}
                                        {{--@csrf--}}
                                        {{--</form>--}}
                                    @endif
                                </td>

                            </tr>
                            
                                                         <tr>
          <th style="width: 40%">Payment Recived By</th>
          <td style="width: 10%"> : </td>
          <td style="width: 50%">
            
            @php
                if($merchant->payment_recived_by== 1) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Cash</b></span>";
                }if($merchant->payment_recived_by== 2) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Bkash</b></span>";
                }if($merchant->payment_recived_by== 3) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Nagad</b></span>";
                }if($merchant->payment_recived_by== 4) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Rocket</b></span>";
                }if($merchant->payment_recived_by== 5) {
                    echo " <span class='bg-success' style='padding: 0 5px; border-radius: 4px;'><b>Bank</b></span>";
                }if($merchant->payment_recived_by== 0){
                    echo " <span class='bg-warning' style='padding: 0 5px; border-radius: 4px;'><b>Not Selected</b></span>";
                }
            @endphp
          </td>
        </tr>

                            <tr>
                                <th style="width: 40%">Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->address }} </td>
                            </tr>

                            <tr>
                                <th style="width: 40%">District</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->district->name }} </td>
                            </tr>
                            <!--<tr>-->
                            <!--    <th style="width: 40%">Upazila/Thana</th>-->
                            <!--    <td style="width: 10%"> : </td>-->
                            <!--    <td style="width: 50%"> {{ $merchant->upazila->name }} </td>-->
                            <!--</tr>-->
                            <tr>
                                <th style="width: 40%">Area</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->area->name }} </td>
                            </tr>

                            @if (!is_null($merchant->cod_charge))
                                <tr>
                                    <th style="width: 40%">COD</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $merchant->cod_charge }} % </td>
                                </tr>
                            @endif

                            @if(!empty($merchant->service_area_charges->count() > 0))
                                <tr>
                                    <th>Service Area Charge </th>
                                    <td colspan="2" >
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="10%">#</th>
                                                <th width="45%">Service Area</th>
                                                <th width="45%">Charge</td>
                                            </tr>
                                            @foreach ($merchant->service_area_charges as $service_area_charge)
                                                <tr>
                                                    <td >{{ $loop->iteration }} </th>
                                                    <td >{{ $service_area_charge->name }} </th>
                                                    <td >{{ $service_area_charge->pivot->charge }} </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif

                        </table>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $merchant->branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
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
@endpush
