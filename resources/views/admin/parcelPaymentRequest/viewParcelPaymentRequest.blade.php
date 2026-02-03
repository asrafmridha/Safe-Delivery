<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Payment Request Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <fieldset>
                    <legend>Parcel Payment Request Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Invoice </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->pickup_request_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    @switch($parcelPaymentRequest->status)
                                        @case(1)
                                            <span class='text-bold text-warning' style='font-size:16px;'>Requested</span>
                                            @break
                                        @case(2)
                                            <span class='text-bold text-success' style='font-size:16px;'>Accepted</span>
                                            @break
                                        @case(3)
                                        <span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>
                                            @break
                                        @case(4)
                                        <span class='text-bold text-primary' style='font-size:16px;'>Payment Generated</span>
                                            @break
                                        @case(5)
                                        <span class='text-bold text-success' style='font-size:16px;'>Paid</span>
                                            @break
                                        @default

                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->merchant->branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Contact Number</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->merchant->branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->merchant->branch->address }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->merchant->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPaymentRequest->date)->format('d/m/Y') }} </td>
                            </tr>

                            @switch($parcelPaymentRequest->request_payment_type)
                                @case(1)
                                <tr>
                                    <th style="width: 40%">Request Payment Method </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> Cash </td>
                                </tr>
                                @break
                                @case(2)
                                <tr>
                                    <th style="width: 40%">Request Payment Method </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> Bank </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Bank Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->bank_name }} </td>
                                </tr>

                                <tr>
                                    <th style="width: 40%">Bank Account No </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->bank_account_no }} </td>
                                </tr>

                                <tr>
                                    <th style="width: 40%">Bank Account Name </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->bank_account_name }} </td>
                                </tr>

                                <tr>
                                    <th style="width: 40%">Routing No </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->routing_no }} </td>
                                </tr>
                                @break
                                @case(3)
                                <tr>
                                    <th style="width: 40%">Request Payment Method </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> Bkash </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Bkash Number</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->bkash_number }} </td>
                                </tr>
                                @break
                                @case(4)
                                <tr>
                                    <th style="width: 40%">Request Payment Method </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> Rocket </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Rocket Number</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->rocket_number }} </td>
                                </tr>
                                @break
                                @case(5)
                                <tr>
                                    <th style="width: 40%">Request Payment Method </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> Nagad </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Nagad Number</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPaymentRequest->nagad_number }} </td>
                                </tr>
                                @break
                                @default

                            @endswitch
                            <tr>
                                <th style="width: 40%">Note  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPaymentRequest->note }} </td>
                            </tr>
                        </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>
