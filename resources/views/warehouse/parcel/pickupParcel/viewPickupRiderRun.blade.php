<div class="modal-header bg-default">
    <h4 class="modal-title">Rider Run Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Rider Run  Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Consignment </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $riderRun->run_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Create Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->create_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>

                            @if($riderRun->start_date_time)
                            <tr>
                                <th style="width: 40%">Start Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->start_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            @if($riderRun->cancel_date_time)
                            <tr>
                                <th style="width: 40%">Cancel Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->cancel_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            @if($riderRun->complete_date_time)
                            <tr>
                                <th style="width: 40%">Complete Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($riderRun->complete_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Total Run </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $riderRun->total_run_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Run Complete </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $riderRun->total_run_complete_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                @switch($riderRun->status)
                                    @case(1)
                                        <div class="badge badge-success">Run Create </div>
                                        @break
                                    @case(2)
                                        <div class="badge badge-success">Run Start </div>
                                        @break
                                    @case(3)
                                        <div class="badge badge-danger " >Run Cancel </div>
                                        @break
                                    @case(4)
                                        <div class="badge badge-success">Run Complete </div>
                                        @break
                                    @default
                                @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Rider Run Note </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $riderRun->note }} </td>
                            </tr>
                        </table>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Rider Information </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $riderRun->rider->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact Number </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $riderRun->rider->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $riderRun->rider->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                    @if($riderRun->rider_run_details->count() > 0)
                    <fieldset>
                        <legend>Delivery Run Parcel</legend>
                        <table class="table table-style table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center">Order ID </th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Complete Time </th>
                                    <th width="10%" class="text-center">Merchant Name</th>
                                    <th width="15%" class="text-center">Merchant Number</th>
                                    <th width="15%" class="text-center">Customer Name</th>
                                    <th width="15%" class="text-center">Complete Note </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riderRun->rider_run_details as $rider_run_detail)
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }} </td>
                                    <td class="text-center"> {{ $rider_run_detail->parcel->parcel_invoice }} </td>
                                    <td class="text-center">
                                        @switch($rider_run_detail->status)
                                            @case(1) Run Create  @break
                                            @case(2) Run Start @break
                                            @case(3) Run Cancel @break
                                            @case(4) Rider Accept @break
                                            @case(5) Rider Reject @break
                                            @case(6) Rider Reschedule @break
                                            @case(7) Rider Complete @break
                                            @default  @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @if($rider_run_detail->status == 7)
                                        {{ \Carbon\Carbon::parse($rider_run_detail->complete_date_time)->format('d/m/Y H:i:s') }} <br>
                                        @endif
                                    </td>
                                    <td class="text-center"> {{ $rider_run_detail->parcel->merchant->name }} </td>
                                    <td class="text-center"> {{ $rider_run_detail->parcel->merchant->contact_number }} </td>
                                    <td class="text-center"> {{ $rider_run_detail->parcel->customer_name }} </td>
                                    <td class="text-center"> {{ $rider_run_detail->complete_note }} </td>
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
