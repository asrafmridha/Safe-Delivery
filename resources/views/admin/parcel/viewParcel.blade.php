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
                                        <td style="width: 10%"> :</td>
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
                                        <th style="width: 40%">Weight Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->weight_package_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Delivery Charge</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
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
                                        <td style="width: 50%; white-space: break-spaces;"> {{ $parcel->pickup_address }} </td>
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
                                        <td style="width: 50%; white-space: break-spaces;"> {{ $parcel->customer_address }} </td>
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
                                    <td style="width: 50%; white-space: break-spaces;"> {{ $parcel->pickup_branch->address }} </td>
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
{{--                                <tr>--}}
{{--                                    <th style="width: 40%"> Address</th>--}}
{{--                                    <td style="width: 10%"> :</td>--}}
{{--                                    <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>--}}
{{--                                </tr>--}}
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
                                    <td style="width: 50%;white-space: break-spaces;"> {{ $parcel->delivery_branch->address }} </td>
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
{{--                                <tr>--}}
{{--                                    <th style="width: 40%"> Address</th>--}}
{{--                                    <td style="width: 10%"> :</td>--}}
{{--                                    <td style="width: 50%"> {{ $parcel->delivery_rider->address }} </td>--}}
{{--                                </tr>--}}
                            </table>
                        </fieldset>
                    </div>
                @endif
                
                
                      @if(!empty($parcel->return_branch))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Return Branch Information</legend>
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
                                    <td style="width: 50%;white-space: break-spaces;"> {{ $parcel->return_branch->address }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                @endif

                @if(!empty($parcel->return_rider))
                    <div class="col-md-6">
                        <fieldset>
                            <legend>Return Rider Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Return Date</th>
                                    <td style="width: 10%"> :</td>
                                    <td style="width: 50%"> {{ ($parcel->return_rider_date) ? \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') : \Carbon\Carbon::parse($parcel->parcel_date)->format('d/m/Y') }} </td>
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
{{--                                <tr>--}}
{{--                                    <th style="width: 40%"> Address</th>--}}
{{--                                    <td style="width: 10%"> :</td>--}}
{{--                                    <td style="width: 50%"> {{ $parcel->return_rider->address }} </td>--}}
{{--                                </tr>--}}
                            </table>
                        </fieldset>
                    </div>
                @endif
            </div>
            
            
            
                        @if(!empty($parcel->rider_run_detail->count() > 0) )



                <div class="col-md-12">
                    <fieldset>
                        <legend>Rider Run Log</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 5%"> #</th>
                                <th style="width: 10%"> Created</th>
                                <th style="width: 10%"> Complete</th>
                                <th style="width: 25%"> Run ID</th>
                                <th style="width: 25%"> Rider</th>
                            </tr>

                            <!-- For Merchant Payment -->


                                @foreach ($parcel->rider_run_detail as $rider_run_detail)
                                    @php
                                    $rider_run = $rider_run_detail->rider_run;
                                       //dd($rider_run->rider->name);
                                    @endphp
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($rider_run->create_date_time)->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($rider_run->complete_date_time)->format('d/m/Y H:i:s') }}
                                        </td>
                                    
                                        <td>
                                            {{ $rider_run->run_invoice }}
                                        </td>
                                        
                                        <td>
                                            {{ $rider_run->rider->name }}
                                        </td>
                                     
                                    </tr>
                                @endforeach
                        </table>
                    </fieldset>
                </div>
            @endif

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

                            @if(!empty($parcelMerchantPaymentDeltails->count() > 0))

                                @foreach ($parcelMerchantPaymentDeltails as $parcelMerchantPaymentDeltail)
                                    @php
                                        $to_user    = "";
                                        $from_user  = "";
                                        $status     = "";

                                        $merchant_name = ($parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant) ? $parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant->company_name : "Default";
                                        $admin_name  = ($parcelMerchantPaymentDeltail->admin) ? $parcelMerchantPaymentDeltail->admin->name : "Default";

                                        switch($parcelMerchantPaymentDeltail->status){


                                            case 1 :
                                                $status     = "Accounts send paid Request";
                                                $to_user    = "Admin : ".$admin_name;
                                                $from_user  = "Merchant : ".$merchant_name;
                                                break;
                                            case 2 :
                                                $status     = "Paid";
                                                $to_user    = "Admin : ".$admin_name;
                                                $from_user  = "Merchant : ".$merchant_name;
                                                break;
                                            case 3 :
                                                $status     = "Merchant Paid Request Reject";
                                                $to_user    = "Admin : ".$admin_name;
                                                $from_user  = "Merchant : ".$merchant_name;
                                                break;
                                        }

                                    @endphp
                                    <tr>
                                        <td> {{ $loop->iteration }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('H:i:s') }}
                                        </td>
                                        <td> {{ $status ." (".$parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant_payment_invoice.")"}} </td>
                                        <td> {{ $to_user }} </td>
                                        <td> {{ $from_user }} </td>
                                    </tr>
                                @endforeach
                            @endif

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
                                        <td> {{ $loop->iteration + $mpayment}} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('H:i:s') }}
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


        <!-- Parcel Logs -->
            @if(!empty($parcelLogs->count() > 0))
            <div class="col-md-12 mt-4">
    <fieldset class="border p-4 rounded shadow-sm">
        <legend class="w-auto px-3 text-primary" style="font-size: 1.5rem; font-weight: bold;">📦 Parcel Log</legend>
        <div class="timeline">
            @foreach ($parcelLogs as $parcelLog)
                @php
                    $parcelLogStatus = returnParcelLogStatusNameForAdmin($parcelLog, $parcel->delivery_type);
                    $to_user = $parcelLogStatus['to_user'];
                    $from_user = $parcelLogStatus['from_user'];
                    $status = $parcelLogStatus['status_name'];
                    $statusColor = match ($status) {
                        'Pickup Request' => 'success',
                        'Parcel Hold' => 'warning',
                        'Deleted' => 'danger',
                        'Re-schedule Pickup' => 'warning',
                        'Assign for Pickup' => 'success',
                        'Rider Assign For Pick' => 'success',
                        'Pickup Run Cancel' => 'warning',
                        'On the way to Pickup' => 'info',
                        'Pickup Reject' => 'warning',
                        'Rider Picked' => 'success',
                        'Picked Up' => 'success',
                        'On the Way To Delivery Hub' => 'primary',
                        'Hub Transfer Cancel' => 'warning',
                        'At Delivery Hub' => 'success',
                        'Delivery Hub Reject' => 'warning',
                        'Assign For Delivery' => 'success',
                        'Out For Delivery' => 'success',
                        'Delivery Run Cancel' => 'warning',
                        'On The Way To Delivery' => 'primary',
                        'Delivery Rider Reject' => 'warning',
                        'Rider Delivered' => 'success',
                        'Rider Partial Delivered' => 'success',
                        'Rider Rescheduled' => 'success',
                        'Rider Return' => 'warning',
                        'Delivered' => 'success',
                        'Partial Delivered' => 'success',
                        'Rescheduled' => 'success',
                        'Cancelled' => 'danger',
                        'On the Way To Returned Hub' => 'info',
                        'Return Transfer Cancel' => 'warning',
                        'At Returned Hub' => 'success',
                        'Return Transfer Reject' => 'danger',
                        'Assign for Return' => 'success',
                        'Return Run Cancel' => 'warning',
                        'Out For Return' => 'success',
                        'Return Run Rider Reject' => 'warning',
                        'Rider Return Parcel' => 'success',
                        'Returned' => 'success',
                        default => 'secondary',
                    };

                @endphp
                <div class="timeline-item">
                    <div class="timeline-badge bg-{{ $statusColor }}"></div>
                    <div class="timeline-content">
                      <div class="row align-items-center">
    <div class="col-12 col-md-auto">
        <h5 class="status-title text-{{ $statusColor }} mb-2 mb-md-0">
            <span class="badge bg-{{ $statusColor }} text-uppercase px-3 py-1">{{ $status }}</span>
        </h5>
    </div>
    <div class="col-12 col-md">
        <p class="text-muted mb-0">
            <strong></strong> {{ $to_user ?? 'N/A' }} |
            <strong></strong> {{ $from_user ?? 'N/A' }}
        </p>
    </div>
</div>


                        <small class="text-secondary">
                            {{ \Carbon\Carbon::parse($parcelLog->date . ' ' . $parcelLog->time)->format('Y-m-d h:i:s A') }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    </fieldset>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
        margin-top: 20px;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: 12px;
        height: 100%;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-badge {
        position: absolute;
        left: 0;
        top: 0;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #6c757d;
    }

    .timeline-content {
        padding-left: 40px;
    }

    .status-title {
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .timeline-content small {
        display: block;
        margin-top: 5px;
    }
</style>

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
