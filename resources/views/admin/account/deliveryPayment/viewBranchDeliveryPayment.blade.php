<div class="modal-header bg-default">
    <h4 class="modal-title">Branch Delivery PaymentView </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Delivery Payment Information</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Consignment </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelDeliveryPayment->payment_invoice }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Create Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelDeliveryPayment->date_time)->format('d/m/Y H:i:s') }} </td>
                                    </tr>

                                    @if($parcelDeliveryPayment->status != 1)
                                    <tr>
                                        <th style="width: 40%">Action Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelDeliveryPayment->action_date_time)->format('d/m/Y H:i:s') }} </td>
                                    </tr>
                                    @endif

                                    <tr>
                                        <th style="width: 40%">Total Payment Parcel </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelDeliveryPayment->total_run_parcel }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ number_format($parcelDeliveryPayment->total_payment_amount,2) }} </td>
                                    </tr>

                                    @if($parcelDeliveryPayment->status != 1)
                                        <tr>
                                            <th style="width: 40%">Total Received Payment Parcel </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->total_payment_received_parcel }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Total Received Payment Amount </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ number_format($parcelDeliveryPayment->total_payment_received_amount,2) }} </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th style="width: 40%">Status </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                        @switch($parcelDeliveryPayment->status)
                                            @case(1) <div class="badge badge-success"> Send Request </div>  @break
                                            @case(2) <div class="badge badge-success"> Request Accept </div>  @break
                                            @case(3) <div class="badge badge-danger"> Request Reject </div>  @break
                                            @default
                                        @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Delivery Payment Note </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelDeliveryPayment->note }} </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Branch Information </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact Number </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch->contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch->address }} </td>
                                        </tr>
                                    </table>
                                </fieldset>

                                @if(!is_null($parcelDeliveryPayment->admin))
                                    <fieldset>
                                        <legend>Admin </legend>
                                        <table class="table table-style">
                                            <tr>
                                                <th style="width: 40%"> Name </th>
                                                <td style="width: 10%"> : </td>
                                                <td style="width: 50%"> {{ $parcelDeliveryPayment->admin->name }} </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                @endif
                            </div>
                        </div>

                        @if($parcelDeliveryPayment->parcel_delivery_payment_details->count() > 0)
                        <fieldset>
                            <legend>Delivery Payment Parcel</legend>
                            <table class="table table-style table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center">Invoice </th>
                                        <th width="10%" class="text-center">Order ID </th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Company Name</th>
                                        <th width="10%" class="text-center">Merchant Number</th>
                                        <th width="10%" class="text-center">Merchant Address</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="10%" class="text-center">Amount </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelDeliveryPayment->parcel_delivery_payment_details as $parcel_delivery_payment_detail)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration }} </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->parcel_invoice }} </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->merchant_order_id ?? "---" }} </td>
                                        <td class="text-center">
                                            @switch($parcel_delivery_payment_detail->status)
                                                @case(1) Send Request  @break
                                                @case(2) Request Accept @break
                                                @case(3) Request Reject @break
                                                @default  @break
                                            @endswitch
                                        </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->merchant->company_name }} </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->merchant->contact_number }} </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->merchant->address }} </td>
                                        <td class="text-center"> {{ $parcel_delivery_payment_detail->parcel->customer_name }} </td>
                                        <td class="text-center"> {{ number_format($parcel_delivery_payment_detail->parcel->customer_collect_amount,2) }} </td>
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


