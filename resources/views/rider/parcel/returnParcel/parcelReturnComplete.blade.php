<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Return Complete</h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('rider.parcel.confirmParcelReturnComplete', $parcel->id) }}" id="confirmParcelReturnComplete" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <legend>Parcel Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Invoice </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Weight Package </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Delivery Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                            </tr>

                            @if($parcel->cod_charge != 0 && $parcel->total_collect_amount)
                            <tr>
                                <th style="width: 40%">COD Percent </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->cod_percent }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">COD Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->cod_charge }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Collection Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                            </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Total Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->total_charge }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset>
                        <legend>Merchant Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Merchant Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Contact </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->merchant->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="form-group">
                                <label for="note"> Note </label>
                                <textarea name="note" id="note" class="form-control" placeholder="Pickup Compete Note">{{ $parcel->parcel_note }}</textarea>
                            </div>
                        </fieldset>
                    </div>
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

        let note = $('#note').val();

        $.ajax({
            cache     : false,
            type      : "PATCH",
            dataType  : "JSON",
            data      : {
                note : note,
                _token  : "{{ csrf_token() }}"
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
                    if(getError.note){
                        message = getError.note[0];
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
