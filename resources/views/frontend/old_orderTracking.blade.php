@extends('layouts.frontend.app')

@section('content')
    <!-- Breadcroumb Area -->
    <div class="breadcroumb-area bread-bg" style="background-color: #3dc5f0">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-centered">
                    {{--<form id="tracking-form" role="search" action="{{ route('frontend.orderTracking') }}"--}}
                    {{--method="POST">--}}
                    {{--@csrf--}}
                    {{-- <div class="form-group" > --}}
                    {{--<div class="input-group mb-3" style="font-size: 55px;" id="trackingInputBox">--}}
                    {{--<input class="form-control" placeholder="Enter tracking number"--}}
                    {{--type="text"--}}
                    {{--name="trackingBox"--}}
                    {{--id="trackingBox"--}}
                    {{--style="font-size: 30px; border-top-left-radius: 26px; border-bottom-left-radius: 26px;">--}}
                    {{--<div class="input-group-append">--}}
                    {{--<button class="btn btn-default btn-parcels" type="submit" id="trackingBtn">--}}
                    {{--<div class="fa fa-binoculars"></div>--}}
                    {{--<span class="hidden-xs" >--}}
                    {{--Track package--}}
                    {{--</span>--}}
                    {{--</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{-- </div> --}}
                    {{--</form>--}}

                    <form id="tracking-form" class="track-form p-4 shadow"
                          action="{{ route('frontend.orderTracking') }}"
                          method="POST" style="margin-top: 10px;">
                        @csrf
                        <div class="d-flex flex-column flex-md-row">
                            <div class="flex-fill">
                                <div class="track-input d-flex align-items-center">
                                    <label for="trackingBox">
                                        <img height="30" src="{{ asset('assets/img/track-search.jpg') }}"
                                             alt="treack search">
                                    </label>
                                    <input name="trackingBox" id="trackingBox" type="text" class="w-100"
                                           placeholder="Type your track number">
                                    <input type="submit" class="btn btn-info" value="Track Parcel">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @if ($parcel)
        <!-- about start-->
        <div id="about" class="about-main-block theme-2">
            <div class="content" style="margin: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Parcel Log</legend>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <ul class="events">
                                            @foreach ($parcelLogs as $parcelLog)
                                                @php
                                                    $to_user    = "";
                                                    $from_user  = "";
                                                    $status     = "";

                                                    switch($parcelLog->status){
                                                        case 1 :
                                                            $status     = "Parcel Send Pick Request";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                                                $from_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name : " ";
                                                            }
                                                            break;
                                                        case 2 :
                                                            $status     = "Parcel Hold";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                                            }
                                                            break;
                                                        case 3 :
                                                            $status     = "Parcel Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->merchant)){
                                                                    $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                                                }
                                                            }
                                                            break;
                                                        case 4 :
                                                            $status     = "Parcel Reschedule";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            }else{
                                                                if(!empty($parcelLog->pickup_rider)){
                                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                                }
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 5 :
                                                            $status     = "Pickup Run Start";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 6 :
                                                            $status     = "Pickup Run Create";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 7 :
                                                            $status     ="Pickup Run Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 8 :
                                                            $status     = "Pickup Run Accept Rider";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_rider)){
                                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 9 :
                                                            $status     = "Pickup Run Cancel Rider";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_rider)){
                                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 10 :
                                                            $status     = "Pickup Run Complete Rider";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_rider)){
                                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                                }
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 11 :
                                                            $status     = "Pickup Complete";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 12 :
                                                            $status     = "Assign Delivery Branch";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $from_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 13 :
                                                            $status     = "Assign Delivery Branch Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->pickup_branch)){
                                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 14 :
                                                            $status     = "Assign Delivery Branch Received";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 15 :
                                                            $status     = "Assign Delivery Branch Reject";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 16 :
                                                            $status     = "Delivery Run Create";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 17 :
                                                            $status     = "Delivery Run Start";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 18 :
                                                            $status     = "Delivery Run Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 19 :
                                                            $status     = "Delivery Run Rider Accept";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 20 :
                                                            $status     = "Delivery Run Rider Reject";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 21 :
                                                            $status     = "Delivery Rider Delivery";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 22 :
                                                            $status     = "Delivery Rider Partial Delivery";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 23 :
                                                            $status     = "Delivery Rider Reschedule";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name." (Reschedule Date : " .\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
                                                                }
                                                            }
                                                            break;
                                                        case 24 :
                                                            $status     = "Delivery Rider Return";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_rider)){
                                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 25 :
                                                            $status     = "Delivery  Complete";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 26 :
                                                            $status     = "Return Branch Assign";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $from_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 27 :
                                                            $status     = "Return Branch Assign Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->delivery_branch)){
                                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 28 :
                                                            $status     = "Return Branch Assign Received";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 29 :
                                                            $status     = "Return Branch Assign Reject";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 30 :
                                                            $status     = "Return Branch   Run Create";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                                if(!empty($parcelLog->return_rider)){
                                                                    $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 31 :
                                                            $status     = "Return Branch  Run Start";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 32 :
                                                            $status     =  "Return Branch  Run Cancel";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                        case 33 :
                                                            $status     = "Return Rider Accept";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_rider)){
                                                                    $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 34 :
                                                            $status     = "Return Rider Reject";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_rider)){
                                                                    $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 35 :
                                                            $status     = "Return Rider Complete";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_rider)){
                                                                    $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                                                }
                                                            }
                                                            break;
                                                        case 36 :
                                                            $status     =  "Return Branch  Run Complete";
                                                            if(!empty($parcelLog->admin)){
                                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                                            } else{
                                                                if(!empty($parcelLog->return_branch)){
                                                                    $to_user    = "Return Branch : ".$parcelLog->return_branch->name;
                                                                }
                                                            }
                                                            break;
                                                    }
                                                @endphp
                                                <li>
                                                    <time>{{ \Carbon\Carbon::parse($parcelLog->date)->format('d/m/Y')." ".\Carbon\Carbon::parse($parcelLog->time)->format('H:i:s') }}</time>
                                                    {{--                                                                                                        <span><strong>{{ $status }}</strong></span>--}}
                                                    <span><strong>{{ $status }}</strong>To: {{$to_user}}<br> From: {{$from_user}} </span>
                                                </li>
                                            @endforeach


                                        </ul>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Parcel Information</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-striped borderless">
                                            <tr>
                                                <th style="width: 40%">Invoice</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Date</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Merchant Order ID</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->merchant_order_id }} </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Weight Package</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                                            </tr>
                                            {{-- <tr>
                                                <th style="width: 40%">Delivery Charge </th>
                                                <td style="width: 10%"> : </td>
                                                <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                                            </tr>
                                            @if($parcel->cod_charge != 0 && $parcel->total_collect_amount)
                                                <tr>
                                                    <th style="width: 40%">COD Percent </th>
                                                    <td style="width: 10%"> : </td>
                                                    <td style="width: 50%"> {{ $parcel->cod_percent }} % </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">COD Charge </th>
                                                    <td style="width: 10%"> : </td>
                                                    <td style="width: 50%"> {{ $parcel->cod_charge }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Collection Amount </th>
                                                    <td style="width: 10%"> : </td>
                                                    <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                                                </tr>
                                            @endif --}}
                                            <tr>
                                                <th style="width: 40%">Total Charge</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ number_format($parcel->total_charge,2) }} </td>
                                            </tr>

                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset>
                                            <legend>Merchant Information</legend>
                                            <table class="table table-striped borderless">
                                                <tr>
                                                    <th style="width: 40%"> Name</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Contact</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Address</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->merchant->address }} </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                        <fieldset>
                                            <legend>Customer Information</legend>
                                            <table class="table table-striped borderless">
                                                <tr>
                                                    <th style="width: 40%"> Name</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Contact</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->customer_contact_number }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Address</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- about end-->
    @else

    @endif


@endsection

@push('style_css')
    <style>
        .borderless td, .borderless th {
            border: none;
        }

        table {
            border: none;
        }

        fieldset {
            border: 2px solid #007bff96 !important;
            margin: 0 !important;
            xmin-width: 0 !important;
            padding: 3px !important;
            position: relative !important;
            border-radius: 4px !important;
            background-color: #f5f5f5 !important;
            padding-left: 10px !important;
            margin-bottom: 7px !important;
        }

        legend {
            font-size: 18px !important;
            font-weight: bold !important;
            margin-bottom: 0px !important;
            width: 50% !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            padding: 1px 1px 1px 10px !important;
            background-color: #cceed6 !important;
        }

        .events li {
            display: flex;
            color: #1e1e1e;
        }

        .events time {
            position: relative;
            padding: 0 1.5em;
        }

        .events time::after {
            content: "";
            position: absolute;
            z-index: 2;
            right: 0;
            top: 0;
            transform: translateX(50%);
            border-radius: 50%;
            background: #110a61;
            border: 1px #ff0000 solid;
            width: .8em;
            height: .8em;
        }


        .events span {
            padding: 0 1.5em 1.5em 1.5em;
            position: relative;
        }

        .events span::before {
            content: "";
            position: absolute;
            z-index: 1;
            left: 0;
            height: 100%;
            border-left: 1px #ff0000 solid;
        }

        .events strong {
            display: block;
            font-weight: bolder;
        }

        .events {
            margin: 1em;
        }

        .events,
        .events *::before,
        .events *::after {
            box-sizing: border-box;
            font-family: arial;
        }
    </style>
@endpush
