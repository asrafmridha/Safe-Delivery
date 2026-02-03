<div class="modal-header bg-default">
    <h4 class="modal-title">Pickup Rider Assign </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmAssignPickupRider') }}" id="confirmAssignPickupRider" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <input type="hidden" name="parcel_id" id="parcel_id" value="{{ $parcel->id }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
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
                        </table>

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
                            </table>
                        </fieldset>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Parcel Charge </legend>
                                    <table class="table table-style">
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
                                    <legend>Customer Information</legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%">Customer Name</th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Customer Contact </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Customer Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Pickup Rider Information</legend>

                            <div class="form-group">
                                <label for="pickup_rider_id"> Rider </label>
                                <select name="pickup_rider_id" id="pickup_rider_id" class="form-control select2" style="width: 100%" onchange="return returnBranchResult()">
                                <option value="0">Select Rider</option>
                                @foreach ($riders as $rider)
                                    <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                                @endforeach
                                </select>
                            </div>

                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Name</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_name">Not Confirm </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Contact Number </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_contact_number">Not Confirm </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_address">Not Confirm </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">District </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_district">Not Confirm </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Thana/Upazila</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_upazila">Not Confirm </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Area</th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="view_pickup_rider_area">Not Confirm </span>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success">Submit</button>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function returnBranchResult(){
        var rider_id   = $("#pickup_rider_id option:selected").val();
        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                rider_id : rider_id,
                _token  : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : "{{ route('rider.riderResult') }}",
            success   : function(response){
                if(response.success){
                    $("#view_pickup_rider_name").html(response.rider.name);
                    $("#view_pickup_rider_contact_number").html(response.rider.contact_number);
                    $("#view_pickup_rider_address").html(response.rider.address);
                    $("#view_pickup_rider_district").html(response.rider.district.name);
                    $("#view_pickup_rider_upazila").html(response.rider.upazila.name);
                    $("#view_pickup_rider_area").html(response.rider.area.name);
                }
                else{
                    $("#view_pickup_rider_name").html("Not Confirm ");
                    $("#view_pickup_rider_contact_number").html("Not Confirm ");
                    $("#view_pickup_rider_address").html("Not Confirm ");
                    $("#view_pickup_rider_district").html("Not Confirm ");
                    $("#view_pickup_rider_upazila").html("Not Confirm ");
                    $("#view_pickup_rider_area").html("Not Confirm ");
                }
            }
        })
    }


    function createForm(object){
        event.preventDefault();

        let rider_id = $('#pickup_rider_id').val();
        if(rider_id == '0'){
            toastr.error("Please Select Pickup Rider..");
            return false;
        }
        var parcel_id = $("#parcel_id").val();

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                rider_id : rider_id,
                parcel_id : parcel_id,
                _token  : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();
                    setTimeout(function(){$('#modal').modal('hide')},1000);

                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.rider_id){
                        message = getError.rider_id[0];
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
