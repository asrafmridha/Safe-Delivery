<div class="modal-header bg-default">
    <h4 class="modal-title">Delivery Complete Parcel </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('rider.parcel.confirmParcelDeliveryComplete') }}" id="confirmParcelDeliveryComplete" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <input type="hidden" name="parcel_id" id="parcel_id" value="{{ $parcel->id }}">
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
                                <th style="width: 40%">Collection Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                            </tr>
                        </table>
                    </fieldset>
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
                <div class="col-md-6">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="delivery_type"> Delivery Type </label>
                                    <select name="delivery_type" id="delivery_type" class="form-control select2" style="width: 100%" onchange="return returnDeliveryProcess()">
                                        <option value="0">Select Delivery Type</option>
                                        <option value="21">Complete Delivery</option>
                                        <option value="22">Partial Delivery</option>
                                        <option value="23">Reschedule Delivery</option>
                                        <option value="24">Return Delivery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" id="customer_collect_amount_div" style="display:none">
                                <div class="form-group" >
                                    <label for="customer_collect_amount"> Confirm Amount </label>
                                    <input type="number" name="customer_collect_amount" id="customer_collect_amount" class="form-control" placeholder="0.00" >
                                    <input type="hidden" name="total_collect_amount" id="total_collect_amount" value="{{ $parcel->total_collect_amount }}" >
                                </div>
                            </div>
                            <div class="col-md-12" id="parcel_code_div" style="display:none">
                                <div class="form-group">
                                    <label for="parcel_code"> CODE </label>
{{--                                    <input type="number" name="parcel_code" id="parcel_code" class="form-control" placeholder="CODE" onfocusout="return returnConfirmParcelCodeCheck()">--}}
                                    <input type="number" name="parcel_code" id="parcel_code" class="form-control" placeholder="CODE" >
                                </div>
                            </div>
                            <div class="col-md-12" id="reschedule_date_div" style="display:none">
                                <div class="form-group">
                                    <label for="reschedule_date"> Reschedule Date </label>
                                    <input type="date" name="reschedule_date" id="reschedule_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" >
                                </div>
                            </div>
                            <div class="col-md-12" id="parcel_note_dive" style="display:none">
                                <div class="form-group">
                                    <label for="parcel_note"> Note </label>
                                    <input type="text" name="parcel_note" id="parcel_note" class="form-control" placeholder="Note">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success" id="confirm-btn" disabled>Confirm</button>
                    <button type="reset" class="btn btn-primary">Reset</button>
                </div>
            </div>
        </div>
        <div class="card-footer">
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

    function returnDeliveryProcess(){
        var total_collect_amount    = returnNumber($("#total_collect_amount").val());
        var delivery_type           = $("#delivery_type option:selected").val();
        switch(delivery_type){
            case '21' :
                $("#customer_collect_amount_div").show(300);
                $("#customer_collect_amount").prop({'readonly':true, 'required':true}).val({{ $parcel->total_collect_amount }});

                $("#parcel_code_div").show(300);
                // $("#parcel_code").val('').prop('required',true);

                $("#reschedule_date_div").hide(300);
                $("#reschedule_date").prop('required',false);

                $("#parcel_note_dive").hide(300);
                $("#parcel_note").prop('required',false);

                $("#confirm-btn").prop('disabled',false);

                // $("#confirm-btn").prop('disabled',true);
                break;

            case '22' :
                $("#customer_collect_amount_div").show(300);

                if(total_collect_amount == 0){
                    $("#customer_collect_amount").prop({'readonly':true, 'required':false}).val(0);
                }else{
                    $("#customer_collect_amount").prop({'readonly':false, 'required':false}).val(0);
                }

                $("#parcel_code_div").show(300);
                // $("#parcel_code").val('').prop('required',true);

                $("#reschedule_date_div").hide(300);
                $("#reschedule_date").prop('required',false);

                $("#parcel_note_dive").show(300);
                $("#parcel_note").prop('required',false);

                $("#confirm-btn").prop('disabled',false);
                // $("#confirm-btn").prop('disabled',true);
                break;

            case '23' :
                $("#customer_collect_amount_div").hide(300);
                $("#customer_collect_amount").prop('required',false).val(0);

                $("#parcel_code_div").hide(300);
                $("#parcel_code").val('').prop('required',false);

                $("#reschedule_date_div").show(300);
                $("#reschedule_date").prop('required',true);

                $("#parcel_note_dive").show(300);
                $("#parcel_note").prop('required',false);

                $("#confirm-btn").prop('disabled',false);
                break;

            case '24' :
                $("#customer_collect_amount_div").hide(300);
                $("#customer_collect_amount").prop('required',false).val(0);

                $("#parcel_code_div").hide(300);
                $("#parcel_code").val('').prop('required',false);

                $("#reschedule_date_div").hide(300);
                $("#reschedule_date").prop('required',false);

                $("#parcel_note_dive").show(300);
                $("#parcel_note").prop('required',true);

                $("#confirm-btn").prop('disabled',false);
                break;

            default :
                $("#customer_collect_amount_div").hide(300);
                $("#customer_collect_amount").attr('required',false);

                $("#parcel_code_div").hide(300);
                $("#parcel_code").val('').attr('required',false);

                $("#reschedule_date_div").hide(300);
                $("#reschedule_date").attr('required',false);

                $("#parcel_note_dive").hide(300);
                $("#parcel_note").attr('required',false);

                $("#confirm-btn").prop('disabled',false);
                // $("#confirm-btn").prop('disabled',true);
                break;

        }
    }


    function returnConfirmParcelCodeCheck(){
        let parcel_code     = $('#parcel_code').val();
        var parcel_id       = $("#parcel_id").val();

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                parcel_code : parcel_code,
                parcel_id : parcel_id,
                _token  : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : '{{ route('rider.parcel.returnConfirmParcelCode') }}',
            success   : function(response){
                if(response.success){
                    toastr.success("Parcel CODE Matched");
                    $("#confirm-btn").prop('disabled',false);
                }
                else{
                    toastr.error("Parcel CODE does not Matched");
                    $("#confirm-btn").prop('disabled',true);
                }
            }
        })

    }

    function createForm(object){
        event.preventDefault();

        var parcel_id                   = $('#parcel_id').val();
        var delivery_type               = $("#delivery_type option:selected").val();
        var customer_collect_amount     = returnNumber($('#customer_collect_amount').val());
        var total_collect_amount        = returnNumber($('#total_collect_amount').val());
        var parcel_code                 = $('#parcel_code').val();
        var reschedule_date             = $('#reschedule_date').val();
        var parcel_note                 = $('#parcel_note').val();
        // alert(parcel_id+' '+delivery_type+' '+total_collect_amount+' '+customer_collect_amount+' '+parcel_code+' '+reschedule_date+' '+parcel_note);

        // Complete Delivery
        /*if(delivery_type == '21'){
            if(parcel_code.length != 6){
                toastr.error("Parcel CODE does not Matched");
                return false;
            }
            if(customer_collect_amount != total_collect_amount){
                toastr.error("Collection Amount Not Match..");
                return false;
            }
        }*/
        // Partial Delivery
       /* else if(delivery_type == '22'){
            if(parcel_code.length != 6){
                toastr.error("Parcel CODE does not Matched");
                return false;
            }
        }*/
        // Reschedule
       /* else if(delivery_type == '23'){
            if(reschedule_date.length == 0){
                toastr.error("Please Select Reschedule Date");
                return false;
            }
        }*/

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                parcel_id               : parcel_id,
                delivery_type           : delivery_type,
                customer_collect_amount : customer_collect_amount,
                total_collect_amount    : total_collect_amount,
                parcel_code             : parcel_code,
                reschedule_date         : reschedule_date,
                parcel_note             : parcel_note,
                _token                  : "{{ csrf_token() }}"
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
