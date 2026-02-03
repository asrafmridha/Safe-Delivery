<div class="modal-header bg-default">
    <h4 class="modal-title">Received Branch Transfer Received </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmReceivedBranchTransferReceived', $deliveryBranchTransfer->id) }}" id="confirmAssignDeliveryBranch" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <input type="hidden" name="total_transfer_received_parcel" id="total_transfer_received_parcel" value="{{ $deliveryBranchTransfer->total_transfer_parcel }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Transfer Information</legend>
                        <div class="row">
                            <div class="col-md-6">
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Consignment </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $deliveryBranchTransfer->run_invoice }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Create Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->create_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>

                                @if($deliveryBranchTransfer->cancel_date_time)
                                <tr>
                                    <th style="width: 40%">Cancel Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->cancel_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                @if($deliveryBranchTransfer->reject_date_time)
                                <tr>
                                    <th style="width: 40%">Complete Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($deliveryBranchTransfer->reject_date_time)->format('d/m/Y H:i:s') }} </td>
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
                                    <td style="width: 50%" id="view_total_transfer_received_parcel"> {{ $deliveryBranchTransfer->total_transfer_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Status </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                    @switch($deliveryBranchTransfer->status)
                                        @case(1)
                                            <div class="badge badge-success">Transfer Create </div>
                                            @break
                                        @case(2)
                                            <div class="badge badge-success">Transfer Cancel </div>
                                            @break
                                        @case(3)
                                            <div class="badge badge-danger " >Transfer Received </div>
                                            @break
                                        @case(4)
                                            <div class="badge badge-success">Transfer Reject </div>
                                            @break
                                        @default
                                    @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th >Received Date</th>
                                    <th colspan="2">
                                        <input type="date" name="received_date" id="received_date" class="form-control" value="{{ \Carbon\Carbon::parse()->now()->format('Y-m-d') }}" required>
                                    </th>
                                </tr>
                                <tr>
                                    <th >Note </th>
                                    <th colspan="2">
                                        <textarea name="transfer_note" id="transfer_note" class="form-control" placeholder="Transfer Transfer Note">{{ $deliveryBranchTransfer->note }}</textarea>
                                    </th>
                                </tr>
                            </table>
                            </div>

                            <div class="col-md-6">
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
                            <legend>Transfer  Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center">Order ID </th>
                                        <th width="10%" class="text-center">Merchant Order</th>
                                        <th width="10%" class="text-center">Merchant Name</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="15%" class="text-center">ChangeStatus</th>
                                        <th width="15%" class="text-center">Note </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveryBranchTransfer->delivery_branch_transfer_details as $delivery_branch_transfer_detail)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration }} </td>
                                        <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->parcel_invoice }} </td>
                                        <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->merchant_order_id }} </td>
                                        <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->merchant->name }} </td>
                                        <td class="text-center"> {{ $delivery_branch_transfer_detail->parcel->customer_name }} </td>
                                        <td class="text-center">
                                            <select name="delivery_branch_transfer_status[]"  class="form-control select2 delivery_branch_transfer_status" style="width: 100%" onchange="return delivery_branch_transfer_status()">
                                                <option value="3">Transfer Received</option>
                                                <option value="4">Transfer Reject</option>
                                            </select>
                                            <input type="hidden" name="delivery_branch_transfer_details_id[]" class="delivery_branch_transfer_details_id" value="{{$delivery_branch_transfer_detail->id }}">
                                            <input type="hidden" name="parcel_id[]" class="parcel_id" value="{{$delivery_branch_transfer_detail->parcel_id }}">
                                        </td>
                                        <td class="text-center">
                                            <textarea name="received_note[]" class="form-control received_note" placeholder="Received Note"> {{ $delivery_branch_transfer_detail->note }}</textarea>
                                        </td>
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
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">Confirm </button>
            <button type="reset" class="btn btn-primary">Reset</button>
        </div>
    </form>

</div>
<div class="modal-footer">
    <button  type="button" id="viewModalCloseBtn"  class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>

</style>

<script>

    function delivery_branch_transfer_status(){
        var complete = 0;
        var s = $('.delivery_branch_transfer_status option:selected').map(function(){
                if(this.value == 3){
                    complete++;
                }
        }).get();
        $("#view_total_transfer_received_parcel").html(complete);
        $("#total_transfer_received_parcel").val(complete);
    }

    function createForm(object){
        event.preventDefault();

        let received_date                           = $('#received_date').val();
        let transfer_note                           = $('#transfer_note').val();
        var delivery_branch_transfer_id             = $("#delivery_branch_transfer_id").val();
        var total_transfer_received_parcel          = $("#total_transfer_received_parcel").val();

        var delivery_branch_transfer_status         = $('.delivery_branch_transfer_status  option:selected').map(function(){
                return this.value;
        }).get();

        var received_note                           = $('.received_note').map(function(){
                return this.value;
        }).get();

        var delivery_branch_transfer_details_id    = $('.delivery_branch_transfer_details_id').map(function(){
                return this.value;
        }).get();

        var parcel_id        = $('.parcel_id').map(function(){
                return this.value;
        }).get();


        $.ajax({
            cache     : false,
            type      : "PATCH",
            dataType  : "JSON",
            data      : {
                received_date                       : received_date,
                transfer_note                       : transfer_note,
                delivery_branch_transfer_id         : delivery_branch_transfer_id,
                total_transfer_received_parcel      : total_transfer_received_parcel,
                delivery_branch_transfer_status     : delivery_branch_transfer_status,
                delivery_branch_transfer_details_id     : delivery_branch_transfer_details_id,
                received_note                       : received_note,
                parcel_id                           : parcel_id,
                _token                              : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();

                    setTimeout(function(){
                        $('#viewModal').modal('hide');
                        $('#viewModalCloseBtn').click();
                    },1000);
                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.received_date){
                        message += getError.received_date[0];
                    }
                    if(getError.transfer_note){
                        message += getError.transfer_note[0];
                    }

                    if(getError.total_transfer_received_parcel){
                        message += getError.total_transfer_received_parcel[0];
                    }
                    if(getError.delivery_branch_transfer_details_id){
                        message += getError.delivery_branch_transfer_details_id[0];
                    }
                    if(getError.delivery_branch_transfer_status){
                        message += getError.delivery_branch_transfer_status[0];
                    }
                    if(getError.received_note){
                        message += getError.received_note[0];
                    }
                    message += getError;

                    toastr.error(message);
                }
            }
        })

    }

</script>
