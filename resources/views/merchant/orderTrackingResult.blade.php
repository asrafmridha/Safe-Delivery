<div class="content" style="margin-top: 20px;">
    <div class="container-fluid">
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
                                        <td style="width: 50%"> {{ $parcel->cod_percent }} % </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">COD Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->cod_charge }}  </td>
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
                        <legend>Delivery Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address }} </td>
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
                                            $status     = "Pickup Request";
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
                                            $status     = "Deleted";
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
                                            $status     = "Assign for pickup";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                            }
                                            break;
                                        case 6 :
                                            $status     = "Way for pickup";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                            }
                                            break;
                                        case 7 :
                                            $status     ="Pickup Processing";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                            }
                                            break;
                                        case 8 :
                                            $status     = "Rider Way to Pickup";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_rider)){
                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                }
                                            }
                                            break;
                                        case 9 :
                                            $status     = "Pickup Rider Reject";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_rider)){
                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                }
                                            }
                                            break;
                                        case 10 :
                                            $status     = "Rider Pickedup";
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
                                            $status     = "Picked Up";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                            }
                                            break;
                                        case 12 :
                                            $status     = "In Transit";
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
                                            $status     = "Delivery Branch Cancel";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                                                }
                                            }
                                            break;
                                        case 14 :
                                            $status     = "At Destination Hub";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_branch)){
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                            }
                                            break;
                                        case 15 :
                                            $status     = "Delivery Branch Reject";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_branch)){
                                                    $to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
                                                }
                                            }
                                            break;
                                        case 16 :
                                            $status     = "Assign for Delivery";
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
                                            $status     = "Rider Accept";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                            }
                                            break;
                                        case 20 :
                                            $status     = "Rider Reject";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                            }
                                            break;
                                        case 21 :
                                            $status     = "Rider Delivery";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                            }
                                            break;
                                        case 22 :
                                            $status     = "Partial Delivered Request by Rider";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                            }
                                            break;
                                        case 23 :
                                            $status     = "Reschedule Request";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name." (Reschedule Date : " .\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
                                                }
                                            }
                                            break;
                                        case 24 :
                                            $status     = "Cancelled Requested";
                                            if(!empty($parcelLog->admin)){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->delivery_rider)){
                                                    $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
                                                }
                                            }
                                            break;
                                        case 25 :
                                        
                                        
                                            if($parcelLog->delivery_type == 1){
                                                $status  = "Delivered Approved by Branch";
                                                $class        = "success";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                }
                                                elseif($parcelLog->delivery_branch){
                                                    $to_user    = !empty($parcelLog->delivery_branch)? "Delivery Branch : ".$parcelLog->delivery_branch->name : "";
                                                }
                                    
                                            }
                                            elseif($parcelLog->delivery_type == 2){
                                                $status  = "Partial Delivered Approved by Branch";
                                                $class        = "success";
                                    
                                    
                                            }
                                            elseif($parcelLog->delivery_type == 3){
                                                $status  = "Rescheduled Approved";
                                                $class        = "success";
                                            }
                                            elseif($parcelLog->delivery_type == 4){
                                                $status  = "Cancelled Verified";
                                                $class        = "success";
                                            } else{
                                                $status  = "Delivery Rider Run Complete(unknown)";
                                                $class        = "success";
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
                                            $status     = "Return Branch Received";
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
                                            $status     = "Assing For Return";
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
                                            $status     = "Returned Processing";
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
                                            $status     = "Return on way to Merchant";
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
                                @if($status!="Delivery Run Start" 
                                && $status!="Delivery Run Rider Accept" 
                                && $status!="Delivery Run Rider Reject" 
                                && $status!="Pickup Run Cancel" 
                                 && $status!="Rider Pickedup"
                                && $status!="Rider Accept"
                                && $status!="Rider Reject" 
                                && $status!="Rider Delivery" 
                                && $status!="Rider Reschedule" 
                                && $status!="Rider Picked Up"
                                && $status!="Return Branch Run Create"
                                && $status!="Return Rider Reject"
                                && $status!="Return Branch Assign"

                                
                                )
                                <tr>
                                    <td > {{ $loop->iteration }} </td>
                                    <td >
                                        {{ \Carbon\Carbon::parse($parcelLog->date)->format('d M Y') }}
                                    </td>
                                    <td >
                                        {{ \Carbon\Carbon::parse($parcelLog->time)->format('h:i:s A') }}
                                    </td>
                                    <td > {{ $status }} </td>
                                    <td > {{ $to_user }} <b style="color: red;">@if($parcelLog->note) ({{$parcelLog->note}}) @endif</b> </td>
                                    <td > {{ $from_user }} </td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </fieldset>
                </div>
            @endif

            
        </div>
    </div>
</div>
