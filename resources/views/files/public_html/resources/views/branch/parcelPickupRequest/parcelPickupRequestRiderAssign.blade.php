<div class="modal-header bg-default">
    <h4 class="modal-title">Assign Rider </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmPickupRequestAssignRider', $parcelPickupRequest->id) }}" id="confirmAssignRider" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <fieldset>
                        <legend>Parcel Pickup Request Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Rider </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    <select name="rider_id" id="rider_id" class="form-control select2" style="width: 100%" >
                                        <option value="0" >Select Rider </option>
                                        @foreach ($riders as $rider)
                                            {{--@if($rider->rider_runs->count() == 0)--}}
                                            <option
                                                    value="{{ $rider->id }}"
                                                    riderContactNumber="{{ $rider->contact_number }}"
                                                    riderAddress="{{ $rider->address }}"
                                            > {{ $rider->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Invoice </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    <input type="hidden" name="pickup_request_id" id="pickup_request_id" value="{{ $parcelPickupRequest->id }}">
                                    {{ $parcelPickupRequest->pickup_request_invoice }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    @switch($parcelPickupRequest->status)
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
                                        <span class='text-bold text-primary' style='font-size:16px;'>Rider Assigned</span>
                                        @break
                                        @case(5)
                                        <span class='text-bold text-success' style='font-size:16px;'>Request Completed</span>
                                        @break
                                        @default

                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Contact Number</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->address }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPickupRequest->date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Parcel  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->total_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Note  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->note }} </td>
                            </tr>
                        </table>
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

    function createForm(object){
        event.preventDefault();

        var rider_id                    = $("#rider_id option:selected").val();
        var rider_name                    = $("#rider_id option:selected").text();
        var rider_phone                    = $("#rider_id option:selected").attr('riderContactNumber');


        var pickup_request_id           = $("#pickup_request_id").val();

        if(rider_id == 0 || rider_id == "") {
            toastr.error("Please Select Rider");
            return false;
        }

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                pickup_request_id       : pickup_request_id,
                rider_id                : rider_id,
                rider_name              : rider_name,
                rider_phone             : rider_phone,
                _token                  : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();

                    setTimeout(function(){
                        $('#viewModal').modal('hide');
                        window.location.reload();
                    },1000);
                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.rider_id){
                        message = getError.rider_id[0];
                    }
                    if(getError.pickup_request_id){
                        message = getError.pickup_request_id[0];
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
