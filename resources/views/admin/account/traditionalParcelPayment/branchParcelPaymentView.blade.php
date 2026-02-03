<div class="modal-header bg-default">
    <h4 class="modal-title">Booking Parcel Payment Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Payment  Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Consignment </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPayment->bill_no }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Create Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPayment->created_at)->format('d/m/Y H:i:s') }} </td>
                            </tr>

                            @if($parcelPayment->status != 1)
                            <tr>
                                <th style="width: 40%">Action Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPayment->updated_at)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Total Payment Parcel </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPayment->payment_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Payment Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ number_format((float) $parcelPayment->total_amount,2, '.', '') }} </td>
                            </tr>

                            @if($parcelPayment->status != 1)
                                <tr>
                                    <th style="width: 40%">Total Received Payment Parcel </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcelPayment->receive_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Total Received Payment Amount </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ number_format($parcelPayment->received_amount,2) }} </td>
                                </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                @switch($parcelPayment->payment_status)
                                    @case(1) <div class="badge badge-success"> Send Request </div>  @break
                                    @case(2) <div class="badge badge-success"> Request Accept </div>  @break
                                    @case(0) <div class="badge badge-danger"> Request Reject </div>  @break
                                    @default
                                @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Payment Note </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPayment->payment_note }} </td>
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
                                        <td style="width: 50%"> {{ $parcelPayment->branch->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact Number </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelPayment->branch->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelPayment->branch->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>

                            {{--@if(!is_null($parcelPayment->admin))--}}
                                {{--<fieldset>--}}
                                    {{--<legend>Admin </legend>--}}
                                    {{--<table class="table table-style">--}}
                                        {{--<tr>--}}
                                            {{--<th style="width: 40%"> Name </th>--}}
                                            {{--<td style="width: 10%"> : </td>--}}
                                            {{--<td style="width: 50%"> {{ $parcelPayment->admin->name }} </td>--}}
                                        {{--</tr>--}}
                                    {{--</table>--}}
                                {{--</fieldset>--}}
                            {{--@endif--}}
                        </div>
                    </div>
                    @if($parcelPayment->booking_parcel_payment_logs->count() > 0)
                    <fieldset>
                        <legend>Payment Parcel</legend>
                        <table class="table table-style table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center">C/N No </th>
                                    <th width="10%" class="text-center">Payment Receive</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parcelPayment->booking_parcel_payment_logs as $parcel_payment_log)
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }} </td>
                                    <td class="text-center"> {{ $parcel_payment_log->booking_parcels->parcel_code }} </td>
                                    <td class="text-center"> {{ $parcel_payment_log->booking_parcel_payment_details->payment_receive_type }} </td>
                                    <td class="text-center">
                                        @switch($parcel_payment_log->payment_status)
                                            @case(1) Send Request  @break
                                            @case(2) Request Accept @break
                                            @case(0) Request Reject @break
                                            @default  @break
                                        @endswitch
                                    </td>
                                    <td class="text-center"> {{ number_format($parcel_payment_log->booking_parcel_payment_details->total_amount, 2, '.', '') }} </td>
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
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>
