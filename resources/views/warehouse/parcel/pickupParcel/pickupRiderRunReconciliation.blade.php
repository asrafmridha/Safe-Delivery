<div class="modal-header bg-default">
    <h4 class="modal-title">Rider Run Reconciliation </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmPickupRiderRunReconciliation', $riderRun->id) }}" id="confirmAssignDeliveryBranch" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <input type="hidden" name="total_run_complete_parcel" id="total_run_complete_parcel" value="{{ $riderRun->total_run_complete_parcel }}">
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
                                    <td style="width: 50%" id="view_total_run_complete_parcel"> {{ $riderRun->total_run_complete_parcel }} </td>
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
                                    <th colspan="3">
                                        <textarea name="run_note" class="form-control" placeholder="Rider Run Not">{{ $riderRun->note }}</textarea>
                                    </th>
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
                            <legend>Pickup Run Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center">Order ID </th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Complete Time </th>
                                        <th width="10%" class="text-center">Merchant Name</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="15%" class="text-center">Status</th>
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
                                            @if($rider_run_detail->status == 6)
                                            {{ \Carbon\Carbon::parse($rider_run_detail->complete_date_time)->format('d/m/Y H:i:s') }} <br>
                                            @endif
                                        </td>
                                        <td class="text-center"> {{ $rider_run_detail->parcel->merchant->name }} </td>
                                        <td class="text-center"> {{ $rider_run_detail->parcel->customer_name }} </td>
                                        <td class="text-center">
                                            <select name="status[]"  class="form-control select2 rider_run_status" style="width: 100%" onchange="return rider_run_status()">
                                                <option value="7" @if($rider_run_detail->status == 7) selected ="" @endif >Run Complete</option>
                                                <option value="5" @if($rider_run_detail->status != 6 && $rider_run_detail->status != 7) selected ="" @endif >Run Reject</option>
                                                <option value="6" @if($rider_run_detail->status == 6) selected ="" @endif >Run Reschedule</option>
                                            </select>
                                            <input type="hidden" name="rider_run_details_id" class="rider_run_details_id" value="{{$rider_run_detail->id }}">
                                            <input type="hidden" name="parcel_id" class="parcel_id" value="{{$rider_run_detail->parcel_id }}">
                                        </td>
                                        <td class="text-center">
                                            <textarea name="complete_note[]" class="form-control complete_note" placeholder="Complete Not"> {{ $rider_run_detail->complete_note }}</textarea>
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
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>


    function rider_run_status(){
        var complete = 0;
        var s = $('.rider_run_status option:selected').map(function(){
            if(this.value == 7){
                complete++;
            }
        }).get();
        $("#view_total_run_complete_parcel").html(complete);
        $("#total_run_complete_parcel").val(complete);
    }

    function createForm(object){
        event.preventDefault();

        let run_note                    = $('#run_note').val();
        var rider_run_id                = $("#rider_run_id").val();
        var total_run_complete_parcel   = $("#total_run_complete_parcel").val();

        var rider_run_details_id        = $('.rider_run_details_id').map(function(){
                return this.value;
        }).get();

        var rider_run_status        = $('.rider_run_status').map(function(){
                return this.value;
        }).get();

        var complete_note        = $('.complete_note').map(function(){
                return this.value;
        }).get();

        var parcel_id        = $('.parcel_id').map(function(){
                return this.value;
        }).get();


        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                total_run_complete_parcel   : total_run_complete_parcel,
                rider_run_id                : rider_run_id,
                run_note                    : run_note,
                rider_run_details_id        : rider_run_details_id,
                rider_run_status            : rider_run_status,
                complete_note               : complete_note,
                parcel_id               : parcel_id,
                _token                      : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();
                    setTimeout(function(){$('#viewModal').modal('hide')},1000);
                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.run_note){
                        message = getError.run_note[0];
                    }
                    if(getError.rider_run_id){
                        message = getError.rider_run_id[0];
                    }
                    if(getError.total_run_complete_parcel){
                        message = getError.total_run_complete_parcel[0];
                    }
                    else{
                        message = getError;
                    }
                    toastr.error(message);
                }
            }
        })

    }

</script>
