<div class="modal-header bg-default">
    <h4 class="modal-title">Complete Request </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmCompletePickupRequest', $parcelPickupRequest->id) }}" id="confirmCompleteRequest" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <fieldset>
                        <legend>Parcel Pickup Request Information</legend>
                        <table class="table table-style">

                            <tr>
                                <th style="width: 40%">Total Parcel  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    <input type="hidden" id="total_parcel" value="{{ $parcelPickupRequest->total_parcel }}">
                                    {{ $parcelPickupRequest->total_parcel }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Complete Parcel</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    <input type="hidden" id="pickup_request_id" value="{{ $parcelPickupRequest->id }}">
                                    <input type="number" name="total_complete_parcel" id="total_complete_parcel" value="">
                                </td>
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

        var pickup_request_id           = $("#pickup_request_id").val();
        var total_complete_parcel       = $("#total_complete_parcel").val();
        var total_parcel                = $("#total_parcel").val();

        // if(total_complete_parcel == 0 || total_complete_parcel == "") {
        //     toastr.error("Please fill up complete parcel");
        //     return false;
        // }

//        if(total_complete_parcel > total_parcel) {
//            toastr.error("Complete Parcel can't be greater then total parcel");
//            return false;
//        }

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                pickup_request_id       : pickup_request_id,
                total_complete_parcel   : total_complete_parcel,
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
                    if(getError.total_complete_parcel){
                        message = getError.total_complete_parcel[0];
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
