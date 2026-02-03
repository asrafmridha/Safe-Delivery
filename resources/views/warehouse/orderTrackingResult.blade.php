<div class="content" style="margin-top: 20px;">
    <div class="container-fluid">
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Parcel Information</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Invoice </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Merchant Order ID </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant_order_id }} </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Parcel Charge </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%">Weight Package </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Delivery Charge </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                                        </tr>
                                        @if($parcel->cod_charge != 0 && $parcel->total_collect_amount)
                                        <tr>
                                            <th style="width: 40%">COD Percent </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->cod_percent }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">COD Charge </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->cod_charge }} % </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Collection Amount </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th style="width: 40%">Total Charge </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->total_charge }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Merchant Information</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->merchant->address }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Customer Information</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12 row">
                    @if(!empty($parcel->pickup_branch))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Pickup Branch Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Pickup Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_branch_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_branch->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_branch->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    @endif

                    @if(!empty($parcel->pickup_rider))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Pickup Rider Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Pickup Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    @endif

                    @if(!empty($parcel->delivery_branch))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Delivery Branch Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Delivery Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_branch_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->delivery_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->delivery_branch->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->delivery_branch->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    @endif

                    @if(!empty($parcel->delivery_rider))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Pickup Rider Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Pickup Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    @endif
                </div>

                @if(!empty($parcelLogs->count() > 0))
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Parcel Log</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 5%"> # </th>
                                    <th style="width: 10%"> Date </th>
                                    <th style="width: 10%"> Time </th>
                                    <th style="width: 25%"> Status </th>
                                    <th style="width: 25%"> To (Action) </th>
                                    <th style="width: 25%"> From </th>
                                </tr>
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
                                                    $to_user    = "Merchant : ".$parcelLog->merchant->name;
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
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 7 :
                                                $status     ="Pickup Run Cancel";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 8 :
                                                $status     = "Pickup Run Accept Rider";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                }
                                                break;
                                            case 9 :
                                                $status     = "Pickup Run Cancel Rider";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                }
                                                break;
                                            case 10 :
                                                $status     = "Pickup Run Complete Rider";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = (!empty($parcelLog->pickup_rider)) ? "Pickup Rider : ".$parcelLog->pickup_rider->name : '';
                                                    $to_user    .= (!empty($parcelLog->pickup_branch)) ? "Pickup Branch : ".$parcelLog->pickup_branch->name : '';
                                                }
                                                break;
                                            case 11 :
                                                $status     = "Pickup Complete";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 12 :
                                                $status     = "Assign Delivery Branch";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user      = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                    $from_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 13 :
                                                $status     = "Assign Delivery Branch Cancel";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 14 :
                                                $status     = "Assign Delivery Branch Received";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 15 :
                                                $status     = "Assign Delivery Branch Reject";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 16 :
                                                $status     = "Delivery Run Create";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 17 :
                                                $status     = "Delivery Run Start";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 18 :
                                                $status     = "Delivery Run Cancel";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 19 :
                                                $status     = "Delivery Run Rider Accept";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 20 :
                                                $status     = "Delivery Run Rider Reject";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 21 :
                                                $status     = "Delivery Rider Delivery";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 22 :
                                                $status     = "Delivery Rider Partial Delivery";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 23 :
                                                $status     = "Delivery Rider Reschedule";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name." (Reschedule Date : " .\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
                                                }
                                                break;
                                            case 24 :
                                                $status     = "Delivery Rider Return";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 25 :
                                                $status     = "Delivery Run Complete";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = !empty($parcelLog->delivery_branch)? "Delivery Branch : ".$parcelLog->delivery_branch->name : "";
                                                }
                                                break;
                                            case 26 :
                                                $status     = "Delivery Branch Return Parcel to Pickup Branch";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                    $from_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 27 :
                                                $status     = "Pickup Branch Received Return Parcel ";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 28 :
                                                $status     = "Pickup Branch Reject Return  Parcel";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 29 :
                                                $status     = "Pickup Branch Return Run Create";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 30 :
                                                $status     = "Pickup Branch Return Run Start";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 31 :
                                                $status     = "Pickup Branch Return Run Cancel";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 32 :
                                                $status     = "Pickup Branch Return Run Rider Accept";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 33 :
                                                $status     = "Pickup Branch Return Run Rider Reject";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 34 :
                                                $status     = "Pickup Branch Return Run Rider Complete Return";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 35 :
                                                $status     = "Pickup Branch Return Run Complete";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                } else{
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                                break;
                                        }

                                    @endphp
                                    <tr>
                                        <td > {{ $loop->iteration }} </td>
                                        <td >
                                            {{ \Carbon\Carbon::parse($parcelLog->date)->format('d/m/Y') }}
                                        </td>
                                        <td >
                                            {{ \Carbon\Carbon::parse($parcelLog->time)->format('H:i:s') }}
                                        </td>
                                        <td > {{ $status }} </td>
                                        <td > {{ $to_user }} </td>
                                        <td > {{ $from_user }} </td>
                                    </tr>
                                @endforeach
                            </table>
                        </fieldset>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
