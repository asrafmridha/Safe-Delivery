<div class="modal-header bg-default">
    <h4 class="modal-title">Merchant Delivery Payment Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Merchant Delivery Payment  Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Payment ID </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant_payment_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Create Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelMerchantDeliveryPayment->date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>

                            @if($parcelMerchantDeliveryPayment->status != 1)
                            <tr>
                                <th style="width: 40%">Paid Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelMerchantDeliveryPayment->action_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Total Parcel </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->total_payment_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ number_format($parcelMerchantDeliveryPayment->total_payment_amount,2) }} </td>
                            </tr>

                            <!--@if($parcelMerchantDeliveryPayment->status != 1)-->
                            <!--    <tr>-->
                            <!--        <th style="width: 40%">Total Received Payment Parcel </th>-->
                            <!--        <td style="width: 10%"> : </td>-->
                            <!--        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->total_payment_received_parcel }} </td>-->
                            <!--    </tr>-->
                            <!--    <tr>-->
                            <!--        <th style="width: 40%">Total Received Payment Amount </th>-->
                            <!--        <td style="width: 10%"> : </td>-->
                            <!--        <td style="width: 50%"> {{ number_format($parcelMerchantDeliveryPayment->total_payment_received_amount,2) }} </td>-->
                            <!--    </tr>-->
                            <!--@endif-->

                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                @switch($parcelMerchantDeliveryPayment->status)
                                    @case(1) <div class="badge badge-success"> Send Request </div>  @break
                                    @case(2) <div class="badge badge-success"> Request Accept </div>  @break
                                    @case(3) <div class="badge badge-danger"> Request Reject </div>  @break
                                    @default
                                @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Transfer Reference </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->transfer_reference }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Note </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->note }} </td>
                            </tr>
                        </table>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Merchant Information </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact Number </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>

                            @if(!is_null($parcelMerchantDeliveryPayment->admin))
                                <fieldset>
                                    <legend>Admin </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->admin->name }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            @endif
                        </div>
                    </div>
                    @if($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details->count() > 0)
                    <fieldset>
                        <legend>Delivery Payment Parcel</legend>
                        <table class="table table-responsive table-style table-striped">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center"> SL </th>
                                    <th width="7%" class="text-center">Invoice </th>
                                    <th width="5%" class="text-center">Order ID </th>
                                    <th width="5%" class="text-center">Status</th>
                                    <th width="8%" class="text-center">Customer Name</th>
                                    <th width="8%" class="text-center">Customer Number</th>
                                    <th width="8%" class="text-center">Amount to be Collect</th>
                                    <th width="8%" class="text-center">Collected</th>
                                    <th width="8%" class="text-center"> Weight Charge</th>
                                    <th width="8%" class="text-center"> COD Charge</th>
                                    <th width="8%" class="text-center">Delivery</th>
                                    <th width="8%" class="text-center">Return </th>
                                    <th width="8%" class="text-center">Total Charge </th>
                                    <th width="8%" class="text-center">Paid Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $parcel_merchant_delivery_payment_detail)
                                
                                @php
                                    $parcelStatus = returnParcelStatusNameForMerchant($parcel_merchant_delivery_payment_detail->parcel->status, $parcel_merchant_delivery_payment_detail->parcel->delivery_type, $parcel_merchant_delivery_payment_detail->parcel->payment_type);
                                    
                                @endphp
                                
                                
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }} </td>
                                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice }} </td>
                                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->merchant_order_id }} </td>
                                    <td class="text-center">
                                       
                                        {{$parcelStatus['status_name']}}
                                    </td>
                                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->customer_name }} </td>
                                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->parcel->total_collect_amount,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->collected_amount,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->weight_package_charge,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->cod_charge,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->delivery_charge,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->return_charge,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->parcel->total_charge,2) }} </td>
                                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->paid_amount,2) }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </fieldset>
                    @endif
                </fieldset>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>

</style>

<script>

</script>
