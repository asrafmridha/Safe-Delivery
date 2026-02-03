<div class="modal-header bg-default">
    <h4 class="modal-title">Delivery Branch Transfer Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Delivery Branch Transfer  Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Consignment </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $deliveryBranchTransfer->delivery_transfer_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Create Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->create_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>

                            @if($deliveryBranchTransfer->reject_date_time)
                            <tr>
                                <th style="width: 40%">Reject Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->reject_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif

                            @if($deliveryBranchTransfer->received_date_time)
                            <tr>
                                <th style="width: 40%">Cancel Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->received_date_time)->format('d/m/Y H:i:s') }} </td>
                            </tr>
                            @endif
                            <tr>
                                <th style="width: 40%">Total Transfer </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $deliveryBranchTransfer->total_transfer_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Transfer Received </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $deliveryBranchTransfer->total_run_complete_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                @switch($deliveryBranchTransfer->status)
                                    @case(1) <div class="badge badge-success">Transfer Create </div> @break
                                    @case(2) <div class="badge badge-danger">Transfer Cancel </div> @break
                                    @case(3) <div class="badge badge-success " >Transfer Received </div> @break
                                    @case(3) <div class="badge badge-danger " >Transfer Reject </div> @break
                                    @default
                                @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Note</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $deliveryBranchTransfer->note }} </td>
                            </tr>
                        </table>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>To Branch Information </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->to_branch->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact Number </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->to_branch->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->to_branch->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset>
                                <legend>From Branch Information </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->from_branch->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact Number </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->from_branch->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->from_branch->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                    @if($deliveryBranchTransfer->delivery_branch_transfer_details->count() > 0)
                    <fieldset>
                        <legend>Delivery Transfer Parcel</legend>
                        <table class="table table-style table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="15%" class="text-center">Order ID </th>
                                    <th width="15%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Merchant Name</th>
                                    <th width="15%" class="text-center">Merchant Number</th>
                                    <th width="15%" class="text-center">Customer Name</th>
                                    <th width="15%" class="text-center">Complete Note </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveryBranchTransfer->delivery_branch_transfer_details as $delivery_branch_transfer_detail)
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }} </td>
                                    <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->parcel_invoice }} </td>
                                    <td class="text-center">
                                        @switch($delivery_branch_transfer_detail->status)
                                            @case(1) Transfer Create  @break
                                            @case(2) Transfer Cancel @break
                                            @case(3) Transfer Received @break
                                            @case(4) Transfer Reject @break
                                            @default  @break
                                        @endswitch
                                    </td>
                                    <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->merchant->name }} </td>
                                    <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->merchant->contact_number }} </td>
                                    <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->customer_name }} </td>
                                    <td class="text-center"> {{ $delivery_branch_transfer_detail->note }} </td>
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
