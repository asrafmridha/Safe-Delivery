<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.bookingParcel.confirmDeliveryBookingParcel', $booking_parcel->id) }}" id="confirmParcelDeliveryComplete" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Parcel Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%">Invoice </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->parcel_code }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Booking Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($booking_parcel->booking_date)->format('d/m/Y') }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Delivery Type </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        @php
                                            if($booking_parcel->delivery_type == 'hd'){
                                                    echo 'Home Delivery';
                                            }elseif($booking_parcel->delivery_type == 'thd'){
                                                echo 'Transit Home Delivery';
                                            }elseif($booking_parcel->delivery_type == 'od'){
                                                echo 'Office Delivery';
                                            }elseif($booking_parcel->delivery_type == 'tod'){
                                                echo 'Transit Office Delivery';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Branch </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_branch->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Branch </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_branch->name }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <fieldset>
                            <legend>Receiver Information </legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%">Receiver Name </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Phone </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_phone }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_address }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Divisiuon </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_division->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver District </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_district->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Upazila/Thana </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_upazila->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Receiver Area </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->receiver_area->name }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <fieldset>
                            <legend>Sender Information </legend>
                            <table class="table table-style">

                                <tr>
                                    <th style="width: 40%">Sender Name </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Phone </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_phone }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender NID </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_nid }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_address }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Divisiuon </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_division->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender District </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_district->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Upazila/Thana </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_upazila->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Area </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_area->name }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="col-md-12">
                        <fieldset>
                            <legend>Payment Information</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%">Total Amount </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->total_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Vat Amount({{ number_format($booking_parcel->vat_percent,2) }} %) </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->vat_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Discount Total </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->discount_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Grand Total </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->grand_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Net Amount </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->net_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Paid Amount </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->paid_amount,2) }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Due Amount </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" class="text-right"> {{ number_format($booking_parcel->due_amount,2) }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="delivery_type"> Delivery Type </label>
                                        <select name="delivery_type" id="delivery_type" class="form-control select2" style="width: 100%" onchange="return returnDeliveryProcess()">
                                            <option value="0">Select Delivery Type</option>
                                            <option value="1">Complete Delivery</option>
                                            <option value="2">Return Delivery</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" id="due_amount_div" style="display:none">
                                    <div class="form-group" >
                                        <label for="due_amount"> Due Amount </label>
                                        <input type="number" name="due_amount" id="due_amount" class="form-control" placeholder="0.00" value="{{ $booking_parcel->due_amount }}" readonly step="any">
                                    </div>
                                </div>
                                <div class="col-md-12" id="customer_due_amount_div" style="display:none">
                                    <div class="form-group" >
                                        <label for="customer_due_amount"> Confirm Due Amount </label>
                                        <input type="number" name="customer_due_amount" id="customer_due_amount" class="form-control" placeholder="0.00" step="any">
                                    </div>
                                </div>

                                <input type="hidden" name="booking_parcel_type" id="booking_parcel_type" value="{{ $booking_parcel->booking_parcel_type }}" >
                                <div class="col-md-12" id="collection_amount_div" style="display:none">
                                    <div class="form-group" >
                                        <label for="collection_amount"> Collection Amount </label>
                                        <input type="number" name="collection_amount" id="collection_amount" class="form-control" placeholder="0.00" value="{{ $booking_parcel->collection_amount }}" readonly step="any">
                                    </div>
                                </div>
                                <div class="col-md-12" id="customer_collected_amount_div" style="display:none">
                                    <div class="form-group" >
                                        <label for="customer_collected_amount"> Confirm Collection Amount </label>
                                        <input type="number" name="customer_collected_amount" id="customer_collected_amount" class="form-control" placeholder="0.00" step="any">
                                    </div>
                                </div>
                                <div class="col-md-12" id="parcel_note_dive" style="display:none">
                                    <div class="form-group">
                                        <label for="parcel_note"> Note </label>
                                        <input type="text" name="parcel_note" id="parcel_note" class="form-control" placeholder="Booking Note">
                                    </div>
                                </div>

                                <div class="col-md-12 text-center" style="margin-top: 30px">
                                    <button type="submit" class="btn btn-success" id="confirm-btn" disabled>Confirm</button>
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>


                <div class="col-md-10 offset-md-1">
                    <fieldset>
                        <legend>Item Information </legend>
                        <table class="table table-style table-bordered">
                            <tr>
                                <th>SL. No.</th>
                                <th>Item Category </th>
                                <th>Item Name </th>
                                <th>Unit </th>
                                <th>Unit Rate </th>
                                <th>Quantity </th>
                                <th>Total Rate </th>
                            </tr>
                            @foreach ($booking_parcel->booking_items as $booking_item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                    <td>{{ $booking_item->unit_price }}</td>
                                    <td>{{ $booking_item->quantity }}</td>
                                    <td>{{ $booking_item->total_item_price }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </fieldset>
                </div>
            </div>

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

    $("#customer_due_amount").keyup(function(){
        customer_calculation();
    });

    $("#customer_collected_amount").keyup(function(){
        customer_calculation();
    });

    function customer_calculation(){
        var customer_due_amount         = returnNumber($('#customer_due_amount').val());
        var due_amount                  = returnNumber($('#due_amount').val());
        var customer_collected_amount   = returnNumber($('#customer_collected_amount').val());
        var collection_amount           = returnNumber($('#collection_amount').val());

        if(customer_due_amount == due_amount && customer_collected_amount == collection_amount){
            $("#confirm-btn").prop('disabled',false);
        }
        else{
            $("#confirm-btn").prop('disabled',true);
        }
        // console.log(customer_due_amount, due_amount);
    }

    function returnDeliveryProcess(){
        var booking_parcel_type     = $("#booking_parcel_type").val();
        var delivery_type           = $("#delivery_type option:selected").val();
        var due_amount              = returnNumber($('#due_amount').val());

        switch(delivery_type){
            case '1' :
                $("#due_amount_div").show(300);
                $("#customer_due_amount_div").show(300);
                $("#customer_due_amount").prop('required',true).val(0);

                $("#parcel_note_dive").show(300);
                $("#parcel_note").prop('required',false);

                if(booking_parcel_type == 'condition'){
                    $("#collection_amount_div").show(300);
                    $("#customer_collected_amount_div").show(300);
                    $("#customer_collected_amount").prop('required',true).val(0);
                }

                if(due_amount == 0 && booking_parcel_type == 'general'){
                    $("#confirm-btn").prop('disabled',false);
                }else{
                    $("#confirm-btn").prop('disabled',true);
                }
                break;
            case '2' :
                $("#due_amount_div").hide(300);
                $("#customer_due_amount_div").hide(300);
                $("#customer_due_amount").prop('required',false).val(0);

                $("#collection_amount_div").hide(300);
                $("#customer_collected_amount_div").hide(300);
                $("#customer_collected_amount").prop('required',false).val(0);

                $("#parcel_note_dive").show(300);
                $("#parcel_note").prop('required',true);

                $("#confirm-btn").prop('disabled',false);
                break;

            default :
                $("#due_amount_div").hide(300);
                $("#customer_due_amount_div").hide(300);
                $("#customer_due_amount").attr('required',false);

                $("#collection_amount_div").hide(300);
                $("#customer_collected_amount_div").hide(300);
                $("#customer_collected_amount").prop('required',false).val(0);

                $("#parcel_note_dive").hide(300);
                $("#parcel_note").attr('required',false);

                $("#confirm-btn").prop('disabled',true);
                break;

        }
    }

    function createForm(object){
        event.preventDefault();

        var delivery_type               = $("#delivery_type option:selected").val();
        var booking_parcel_type         = $("#booking_parcel_type").val();
        var customer_due_amount         = returnNumber($('#customer_due_amount').val());
        var due_amount                  = returnNumber($('#due_amount').val());
        var customer_collected_amount   = returnNumber($('#customer_collected_amount').val());
        var collection_amount           = returnNumber($('#collection_amount').val());
        var booking_parcel_note         = $('#parcel_note').val();

        if(delivery_type == '1'){
            if(customer_due_amount != due_amount){
                toastr.error("Due Amount Not Match..");
                return false;
            }
            if(booking_parcel_type == 'condition' && customer_collected_amount != collection_amount){
                toastr.error("Collection Amount Not Match..");
                return false;
            }
        }
        // Reschedule
        else if(delivery_type == '2'){
            if(booking_parcel_note.length == 0){
                toastr.error("Please Enter Booking Parcel Return Note");
                return false;
            }
        }

        $.ajax({
            cache     : false,
            type      : "PATCH",
            dataType  : "JSON",
            data      : {
                delivery_type               : delivery_type,
                customer_due_amount         : customer_due_amount,
                due_amount                  : due_amount,
                customer_collected_amount   : customer_collected_amount,
                collection_amount           : collection_amount,
                booking_parcel_note         : booking_parcel_note,
                booking_parcel_type         : booking_parcel_type,
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
