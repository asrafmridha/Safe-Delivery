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
                                    <input name="trackingBox" id="trackingBox" type="text" class="w-100" value="{{$trackingBox ?? ''}}"
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
                                <legend>Order Tracking</legend>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <h4 class="underline" style="font-weight: bold">Tracking ID</h4>
                                        <p style="color: #000000">{{ $parcel->parcel_invoice }}</p>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <ul class="events">
                                            @php
                                                $finalStatus=[];
                                            @endphp

                                            @foreach ($parcelLogs as $parcelLog)
                                                @php
                                                    $to_user    = "";
                                                    $from_user  = "";
                                                    $status     = "";
                                            $date=\Carbon\Carbon::parse($parcelLog->date)->format('F jS, Y')." ".\Carbon\Carbon::parse($parcelLog->time)->format('h:i a');
                                                    switch($parcelLog->status){
                                                        case 5: case 6: case 7: case 4: case 8: case 9: case 1 :
                                                            $status="Merchant Send Pickup Request";
                                                            $finalStatus[1]=[
                                                                "date"=> $date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 2 :
                                                            $status="Parcel Hold";
                                                           $finalStatus[2]=[
                                                                "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 3 :
                                                            $status     = "Parcel Cancel";
                                                           $finalStatus[3]=[
                                                                "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 11: case 12: case 13: case 10 :
                                                            $status     = "Pickup Complete";
                                                            $finalStatus[11]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 16: case 17: case 14 :
                                                            $status     = "Delivery Branch(".optional($parcelLog->delivery_branch)->name.") Receive Parcel";
                                                            $finalStatus[14]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 15 :
                                                            $status     = "Assign Delivery Branch Reject";
                                                            $finalStatus[15]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 18 :
                                                            $status     = "Delivery Run Cancel";
                                                            $finalStatus[18]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                       case 19:
                                                            $deliveryRiderName = $parcelLog->delivery_rider->name ?? 'N/A';
                                                            $deliveryRiderContact = $parcelLog->delivery_rider->contact_number ?? 'N/A';
                                                            $status = "Delivery Rider ($deliveryRiderName - $deliveryRiderContact) Start Run";
                                                            $finalStatus[19] = [
                                                                "date" => $date,
                                                                "status" => $status
                                                            ];
                                                            break;

                                                        case 20 :
                                                            $status     = "Delivery Run Rider Reject";
                                                            $finalStatus[20]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 25:
                                                            if ($parcel->delivery_type == 3){
                                                                $status     = "Delivery Rescheduled (Date: ".\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('F jS, Y').")";
                                                            $finalStatus[24]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                            }
                                                        case 25:
                                                            if ($parcel->delivery_type == 4){
                                                                $status     = "Delivery Cancel (Note: ".$parcel->parcel_note.")";
                                                            $finalStatus[24]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                            }
                                                        case 25:
                                                            if ($parcel->delivery_type == 2){
                                                                $status     = "Delivery  Complete (Partial)";
                                                            $finalStatus[24]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                            }
                                                        case 25: case 21 :
                                                             $status     = "Delivered";
                                                            $finalStatus[25]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 22 :
                                                            $status     = "Delivery Rider Partial Delivery";
                                                            $finalStatus[22]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 23 :
                                                            $status     = "Delivery Rider Reschedule";
                                                            $finalStatus[23]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 24 :
                                                            $status     = "Delivery Rider Return";
                                                            $finalStatus[24]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 26 :
                                                            $status     = "Return Branch Assign";
                                                            $finalStatus[26]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 27 :
                                                            $status     = "Return Branch Assign Cancel";
                                                            $finalStatus[27]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 28 :
                                                            $status     = "Return Branch Assign Received";
                                                           $finalStatus[28]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 29 :
                                                            $status     = "Return Branch Assign Reject";
                                                           $finalStatus[29]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 30 :
                                                            $status     = "Return Branch   Run Create";
                                                            $finalStatus[30]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 31 :
                                                            $status     = "Return Branch  Run Start";
                                                             $finalStatus[31]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 32 :
                                                            $status     =  "Return Branch  Run Cancel";
                                                            $finalStatus[32]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 33 :
                                                            $status     = "Return Rider Accept";
                                                             $finalStatus[33]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 34 :
                                                            $status     = "Return Rider Reject";
                                                            $finalStatus[34]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 35 :
                                                            $status     = "Return Rider Complete";
                                                            $finalStatus[35]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                        case 36 :
                                                            $status     =  "Return Branch  Run Complete";
                                                             $finalStatus[36]=[
                                                               "date"=>$date,
                                                                "status"=>$status
                                                                ];
                                                            break;
                                                    }
                                                @endphp
                                            @endforeach
                                            {{--@dd($finalStatus)--}}
                                            @foreach($finalStatus as $item)
                                                <li>
                                                    <time></time>
                                                    <span><strong>{{ $item['status'] }}</strong> {{ $item['date'] }}</span>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <h4 style="font-weight: bold">Customer And Order Information</h4>
                                        <table class="table table-striped borderless" style="font-size: 100%;">
                                            <tr>
                                                <th style="width: 40%">Parcel ID</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%">{{ $parcel->parcel_invoice }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Name</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Address</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                            </tr>
                                        </table>
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
        .underline:after {
            background-color: #20b249;
            bottom: -10px;
            height: 4px;
            width: 100px;
            position: relative;
            content: "";
            display: block;
        }

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
            padding: 0 .5em;
        }

        .events time::after {
            content: "";
            position: absolute;
            z-index: 2;
            right: 0;
            top: 0;
            transform: translateX(50%);
            border-radius: 50%;
            background: #000000;
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
            left: -0.5px;
            height: 100%;
            border-left: 2px #ff0000 solid;
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
