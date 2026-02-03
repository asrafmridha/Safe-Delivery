<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Information</legend>

                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Default Information</legend>

                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Invoice</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Merchant Order ID</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->merchant_order_id ?? " --- " }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Date</th>
                                        <td style="width: 10%"> :
                                        <td style="width: 50%"> {{ ($parcel->delivery_date) ? \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->updated_at)->format('d/m/Y') }} </td>
                                        {{--<td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>--}}
                                    </tr>

                                    @if($parcel->service_type)
                                        <tr>
                                            <th style="width: 40%">Service Type</th>
                                            <td style="width: 10%"> :</td>
                                            <td style="width: 50%"> {{ optional($parcel->service_type)->title}} </td>
                                        </tr>
                                    @endif
                                    @if($parcel->item_type)
                                        <tr>
                                            <th style="width: 40%">Item Type</th>
                                            <td style="width: 10%"> :</td>
                                            <td style="width: 50%"> {{ optional($parcel->item_type)->title}} </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th style="width: 40%">Product Value  </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->product_value }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Product Brief</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->product_details ?? " --- " }} </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 40%">Remark  </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->parcel_note }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-6">

                            <fieldset>
                                <legend>Parcel Charge</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Weight Package</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Delivery Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Weight Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->weight_package_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">COD Percent</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->cod_percent }} %</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">COD Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->cod_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->total_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Collection Amount</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Collected Amount</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->customer_collect_amount }} </td>
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
                                        <th style="width: 40%"> Company</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->merchant->company_name }} </td>
                                    </tr>
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
                                    <tr>
                                        <th style="width: 40%"> Shop</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->merchant_shops->shop_name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Pickup Address</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->pickup_address }} </td>
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
                                    <tr>
                                        <th style="width: 40%"> District</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->district->name }} </td>
                                    </tr>
                                    {{-- <tr>
                                        <th style="width: 40%"> Thana/Upazila </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->upazila->name }} </td>
                                    </tr> --}}
                                    <tr>
                                        <th style="width: 40%"> Area</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->area->name }} </td>
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
                                    <th style="width: 40%"> Pickup Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->pickup_branch_date) ? \Carbon\Carbon::parse($parcel->pickup_branch_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->pickup_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->pickup_branch->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address</th>
                                    <td style="width: 10%"> :</td>
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
                                    <th style="width: 40%"> Pickup Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->pickup_rider_date) ? \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->pickup_rider->contact_number }} </td>
                                </tr>
                                <!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>
                            </tr>-->
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
                                    <th style="width: 40%"> Delivery Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->delivery_date) ? \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->delivery_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->delivery_branch->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address</th>
                                    <td style="width: 10%"> :</td>
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
                                    <th style="width: 40%"> Delivery Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->delivery_rider_date) ? \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->delivery_rider->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->delivery_rider->contact_number }} </td>
                                </tr>
                                <!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address }} </td>
                            </tr>-->
                            </table>
                        </fieldset>
                    </div>
                @endif
                
                 @if(!empty($parcel->return_branch))
                    <div class="col-md-6">
                        <fieldset>
                        <legend style="background-color: #eecccc  !important;">Return Branch Information</legend>                            
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Return Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->return_date) ? \Carbon\Carbon::parse($parcel->return_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->return_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->return_branch->contact_number }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Address</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->return_branch->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                @endif

                @if(!empty($parcel->return_rider))
                    <div class="col-md-6">
                        <fieldset>
                            <legend style="background-color: #eecccc  !important;">Return Rider Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Return Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->return_rider_date) ? \Carbon\Carbon::parse($parcel->return_rider_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->return_rider->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%"> Contact Number</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ $parcel->return_rider->contact_number }} </td>
                                </tr>
                                <!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address }} </td>
                            </tr>-->
                            </table>
                        </fieldset>
                    </div>
                @endif
                
                
                
                
            </div>
            <!-- Parcel Payment Log -->
            @if(!empty($parcelBranchPaymentDeltails->count() > 0) || !empty($parcelMerchantPaymentDeltails->count() > 0) )

                @php
                    $mpayment = $parcelMerchantPaymentDeltails->count();
                    $bpayment = $parcelBranchPaymentDeltails->count();
                @endphp

                <div class="col-md-12">
                    <fieldset>
                        <legend>Parcel Payment Log</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 5%"> #</th>
                                <th style="width: 10%"> Date</th>
                                <th style="width: 10%"> Time</th>
                                <th style="width: 25%"> Status</th>
                                <th style="width: 25%"> To (Action)</th>
                                <th style="width: 25%"> From</th>
                            </tr>

                            <!-- For Merchant Payment -->

                            {{--@if(!empty($parcelMerchantPaymentDeltails->count() > 0))--}}

                            {{--@foreach ($parcelMerchantPaymentDeltails as $parcelMerchantPaymentDeltail)--}}
                            {{--@php--}}
                            {{--$to_user    = "";--}}
                            {{--$from_user  = "";--}}
                            {{--$status     = "";--}}

                            {{--$merchant_name = ($parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant) ? $parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant->company_name : "Default";--}}
                            {{--$admin_name  = ($parcelMerchantPaymentDeltail->admin) ? $parcelMerchantPaymentDeltail->admin->name : "Default";--}}

                            {{--switch($parcelMerchantPaymentDeltail->status){--}}


                            {{--case 1 :--}}
                            {{--$status     = "Accounts send paid Request";--}}
                            {{--$to_user    = "Admin : ".$admin_name;--}}
                            {{--$from_user  = "Merchant : ".$merchant_name;--}}
                            {{--break;--}}
                            {{--case 2 :--}}
                            {{--$status     = "Merchant Paid Request Accept";--}}
                            {{--$to_user    = "Admin : ".$admin_name;--}}
                            {{--$from_user  = "Merchant : ".$merchant_name;--}}
                            {{--break;--}}
                            {{--case 3 :--}}
                            {{--$status     = "Merchant Paid Request Reject";--}}
                            {{--$to_user    = "Admin : ".$admin_name;--}}
                            {{--$from_user  = "Merchant : ".$merchant_name;--}}
                            {{--break;--}}
                            {{--}--}}

                            {{--@endphp--}}
                            {{--<tr>--}}
                            {{--<td > {{ $loop->iteration }} </td>--}}
                            {{--<td >--}}
                            {{--{{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('d/m/Y') }}--}}
                            {{--</td>--}}
                            {{--<td >--}}
                            {{--{{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('H:i:s') }}--}}
                            {{--</td>--}}
                            {{--<td > {{ $status }} </td>--}}
                            {{--<td > {{ $to_user }} </td>--}}
                            {{--<td > {{ $from_user }} </td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--@endif--}}

                            <!-- For Branch Payments -->
                            @if(!empty($parcelBranchPaymentDeltails->count() > 0))

                                @foreach ($parcelBranchPaymentDeltails as $parcelBranchPaymentDetail)
                                    @php
                                        $to_user    = "";
                                        $from_user  = "";
                                        $status     = "";

                                        $branch_name = ($parcelBranchPaymentDetail->parcel_delivery_payment->branch) ? $parcelBranchPaymentDetail->parcel_delivery_payment->branch->name : "Default";
                                        $branch_user = ($parcelBranchPaymentDetail->parcel_delivery_payment->branch_user) ? " (".$parcelBranchPaymentDetail->parcel_delivery_payment->branch_user->name.")" : " (Default)";
                                        $admin_name  = ($parcelBranchPaymentDetail->admin) ? $parcelBranchPaymentDetail->admin->name : "Default";

                                        switch($parcelBranchPaymentDetail->status){


                                            case 1 :
                                                $status     = "Branch send paid Request";
                                                $to_user    = "Branch : ".$branch_name.$branch_user;
                                                $from_user  = "Admin : ".$admin_name;
                                                break;
                                            case 2 :
                                                $status     = "Accounts Paid Request Accept";
                                                $to_user    = "Branch : ".$branch_name.$branch_user;
                                                $from_user  = "Admin : ".$admin_name;
                                                break;
                                            case 3 :
                                                $status     = "Accounts Paid Request Reject";
                                                $to_user    = "Branch : ".$branch_name.$branch_user;
                                                $from_user  = "Admin : ".$admin_name;
                                                break;
                                        }

                                    @endphp
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('h:i:s A') }}
                                        </td>
                                        <td> {{ $status ." (".$parcelBranchPaymentDetail->parcel_delivery_payment->payment_invoice.")" }} </td>
                                        <td> {{ $to_user }} </td>
                                        <td> {{ $from_user }} </td>
                                    </tr>
                                @endforeach
                            @endif

                        </table>
                    </fieldset>
                </div>
            @endif

            <!-- For Parcel Logs -->
            @if(!empty($parcelLogs->count() > 0))
                <div class="col-md-12">
                    <fieldset>
                        <legend>Parcel Log</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 3%"> #</th>
                                <th style="width: 7%"> Date</th>
                                <th style="width: 8%"> Time</th>
                                <th style="width: 25%"> Status</th>
                                <th style="width: 33%"> To (Action)</th>
                                <th style="width: 25%"> From</th>
                            </tr>
                            @foreach ($parcelLogs as $parcelLog)
                            
                                @php
                                
                                            //dd($parcelLogs);
                                    $to_user    = "";
                                    $from_user  = "";
                                    $status     = "";
                                    $delivery_rider_name = ($parcelLog->delivery_rider) ? $parcelLog->delivery_rider->name : "";

                                    $pickup_branch_name  = ($parcelLog->pickup_branch) ? $parcelLog->pickup_branch->name : "Default";
                                    $delivery_branch_name  = ($parcelLog->delivery_branch) ? $parcelLog->delivery_branch->name : "Default";
                                    $return_branch_name  = ($parcelLog->return_branch) ? $parcelLog->return_branch->name : "Default";

                                    $pickup_branch_user   = " (";
                                    $pickup_branch_user  .= ($parcelLog->pickup_branch_user) ? $parcelLog->pickup_branch_user->name : "General";
                                    $pickup_branch_user  .= ")";

                                    $delivery_branch_user   = " (";
                                    $delivery_branch_user  .= ($parcelLog->delivery_branch_user) ? $parcelLog->delivery_branch_user->name : "General";
                                    $delivery_branch_user  .= ")";
                                    
                                    $return_branch_user   = " (";
                                    $return_branch_user  .= ($parcelLog->return_branch_user) ? $parcelLog->return_branch_user->name : "General";
                                    $return_branch_user  .= ")";

                                    switch($parcelLog->status){
                                        case 1 :
                                            $status     = "Pickup Request";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                                $from_user  = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 2 :
                                            $status     = "Parcel Hold";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                            }
                                            break;
                                        case 3 :
                                            $status     = "Deleted";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                                            }
                                            break;
                                        case 4 :
                                            $status     = "Parcel Reschedule";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            }else{
                                                if(!empty($parcelLog->pickup_rider)){
                                                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                                }
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    .= "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                                }
                                            }
                                            break;
                                        case 5 :
                                            $status     = "Assign For Pickup";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                if(!empty($parcelLog->pickup_branch)){
                                                    $to_user    = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                                }
                                            }
                                            break;
                                        case 6 :
                                            $status     = "Assign Rider For Pickup";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 7 :
                                            $status     ="Pickup Run Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 8 :
                                            $status     = "Rider On The Way To Pickup";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                                            }
                                            break;
                                        case 9 :
                                            $status     = "Pickup Rider Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = (!empty($parcelLog->pickup_rider)) ? "Pickup Rider : ".$parcelLog->pickup_rider->name : '';
                                            }
                                            break;
                                        case 10 :
                                            $status     = "Rider Pickup";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = (!empty($parcelLog->pickup_rider)) ? "Pickup Rider : ".$parcelLog->pickup_rider->name : '';
                                            }
                                            break;
                                        case 11 :
                                            $status     = "Pickup";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 12 :
                                            $status     = "Assign Delivery Branch";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user      = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                                $from_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 13 :
                                            $status     = "Assign Delivery Branch Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 14 :
                                            $status     = "At Delivery Hub";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                                $from_user    = "From : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 15 :
                                            $status     = "Assign Delivery Branch Reject";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 16 :
                                            $status     = "Assign For Delivery";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 17 :
                                            $status     = "Assign Rider For Delivery";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 18 :
                                            $status     = "Delivery Run Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 19 :
                                            $status     = "Rider On The Way To Delivery";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name;
                                            }
                                            break;
                                        case 20 :
                                            $status     = "Delivery Rider Reject";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name;
                                            }
                                            break;
                                        case 21 :
                                            $status     = "Rider Delivered";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name;
                                            }
                                            break;
                                        case 22 :
                                            $status     = "Rider Partial Delivered";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name;
                                            }
                                            break;
                                        case 23 :
                                            $status     = "Rider Reschedule";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name." (Reschedule Date : " .\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
                                            }
                                            break;
                                        case 24 :
                                            $status     = "Rider Delivery Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Rider : ".$delivery_rider_name;
                                            }
                                            break;
                                        case 25 :
                                            $status     = "Delivery Run Complete";
                                             if($parcelLog->delivery_type == 1){
                                                 $status  = "Delivery Complete";
                                             } elseif($parcelLog->delivery_type == 2){
                                                 $status  = "Partial Delivery";
                                             } elseif($parcelLog->delivery_type == 3){
                                                 $status  = "Reschedule Delivery";
                                             } elseif($parcelLog->delivery_type == 4){
                                                 $status  = "Delivery Cancel";
                                             }
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                            }
                                            break;
                                        case 26 :
                                        
                                            $status     = "Delivery Branch Return Parcel to Return Branch";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Delivery Branch : ".$delivery_branch_name.$delivery_branch_user;
                                                $from_user    = "Return Branch : ".$pickup_branch_name.$pickup_branch_user;
                                            }
                                            break;
                                        case 27 :
                                            $status     = "At Return Branch";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Pickup Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 28 :
                                            $status     = "Return Branch Reject Return  Parcel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".optional($parcelLog->admin)->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 29 :
                                            $status     = "Assign For Return";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 30 :
                                            $status     = "Assign Rider For Return";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".optional($parcelLog->admin)->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 31 :
                                            $status     = "Return Branch Return Cancel";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".optional($parcelLog->admin)->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 32 :
                                            $status     = "On The Way To Return";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                            }
                                            break;
                                        case 33 :
                                            $status     = "Return Rider Reject";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".optional($parcelLog->admin)->name;
                                            } else{
                                                $to_user    = "Return Rider : ".$parcelLog->return_rider->name;
                                            }
                                            break;
                                        case 34 :
                                            $status     = "Rider Reject";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Return Rider : ".optional($parcelLog->return_rider)->name;
                                            }
                                            break;
                                        case 35 :
                                            $status     = "Return Rider Returen";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                        case 36 :
                                            $status     = "Returned";
                                            if($parcelLog->admin){
                                                $to_user    = "Admin : ".$parcelLog->admin->name;
                                            } else{
                                                $to_user    = "Return Branch : ".$return_branch_name.$return_branch_user;
                                            }
                                            break;
                                    }

                                @endphp
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelLog->date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelLog->time)->format('h:i:s A') }}

                                    </td>
                                    <td> {{ $status }} </td>
                                    <td> {{ $to_user }} <b style="color: red;">@if($parcelLog->note) ({{$parcelLog->note}}) @endif</b> </td>
                                    <td> {{ $from_user }} </td>
                                </tr>
                            @endforeach
                        </table>
                    </fieldset>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>
