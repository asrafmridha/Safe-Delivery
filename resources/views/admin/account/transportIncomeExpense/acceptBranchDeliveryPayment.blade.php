<div class="modal-header bg-default">
    <h4 class="modal-title">Accept Branch Delivery Payment </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('admin.account.confirmAcceptBranchDeliveryPayment', $parcelDeliveryPayment->id) }}" id="confirmAcceptBranchDeliveryPayment" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">

        <input type="hidden" name="total_payment_received_parcel" id="total_payment_received_parcel" value="0">
        <input type="hidden" name="total_payment_received_amount" id="total_payment_received_amount" value="0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Delivery Payment Information</legend>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Consignment </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelDeliveryPayment->payment_invoice }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelDeliveryPayment->date_time)->format('d/m/Y H:i:s') }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Status </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            @switch($parcelDeliveryPayment->status)
                                                @case(1) Paid Request @break
                                                @case(2) Paid Accept @break
                                                @case(3) Paid Reject @break
                                                @default
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Parcel </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelDeliveryPayment->total_payment_parcel }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ number_format($parcelDeliveryPayment->total_payment_amount,2) }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Received Parcel </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            <span id="view_total_payment_received_parcel">{{ $parcelDeliveryPayment->total_payment_parcel }}</span>
                                         </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Received Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            <span id="view_total_payment_received_amount">{{ number_format($parcelDeliveryPayment->total_payment_amount,2) }}</span>
                                         </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <textarea name="note" id="note" class="form-control" placeholder="Delivery Payment Note ">{{ $parcelDeliveryPayment->note }}</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <fieldset>
                                    <legend>Branch </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%">Name </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch->address }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Branch User </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelDeliveryPayment->branch_user->name }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        @if($parcelDeliveryPayment->parcel_delivery_payment_details->count() > 0)
                        <fieldset>
                            <legend>Pickup Run Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"># </th>
                                        <th width="10%" class="text-center">Invoice </th>
                                        <th width="10%" class="text-center">Merchant Order  </th>
                                        <th width="15%" class="text-center">Merchant Name</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="10%" class="text-center">Amount</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="25%" class="text-center">Not</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelDeliveryPayment->parcel_delivery_payment_details as $parcel_delivery_payment_detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcel_delivery_payment_detail->parcel->parcel_invoice }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcel_delivery_payment_detail->parcel->merchant_order_id }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcel_delivery_payment_detail->parcel->merchant->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcel_delivery_payment_detail->parcel->customer_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($parcel_delivery_payment_detail->amount,2) }}
                                        </td>
                                        <td class="text-center">
                                            <select name="status[]"
                                                class="form-control select2 parcel_delivery_payment_detail_status"
                                                style="width: 100%"
                                                onchange="return parcel_delivery_payment_detail_status()">
                                                <option value="2" parcel_delivery_payment_details_id="{{ $parcel_delivery_payment_detail->id }}">Accept</option>
                                                <option value="3" >Reject</option>
                                            </select>
                                            <input type="hidden" name="parcel_delivery_payment_detail_id[]" class="parcel_delivery_payment_detail_id" value="{{$parcel_delivery_payment_detail->id }}">
                                            <input type="hidden" name="parcel_id[]" class="parcel_id" value="{{$parcel_delivery_payment_detail->parcel_id }}">
                                            <input type="hidden" name="amount[]" class="amount" id="amount{{ $parcel_delivery_payment_detail->id }}" value="{{ $parcel_delivery_payment_detail->amount }}">
                                        </td>
                                        <td class="text-center">
                                            <textarea name="detail_note[]" class="form-control detail_note" placeholder="Delivery Payment Note"></textarea>
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


    function parcel_delivery_payment_detail_status(){
        var complete    = 0;
        var amount      = 0;

        var s = $('.parcel_delivery_payment_detail_status option:selected').map(function(){
            if(this.value == 2){
                var parcel_delivery_payment_details_id = this.getAttribute('parcel_delivery_payment_details_id');
                var parcel_amount = returnNumber($("#amount"+parcel_delivery_payment_details_id).val());
                amount      += parcel_amount;
                complete++;
            }
        }).get();

        $("#total_payment_received_parcel").val(complete);
        $("#view_total_payment_received_parcel").html(complete);

        $("#total_payment_received_amount").val(amount);
        $("#view_total_payment_received_amount").html(amount.toFixed(2));

    }

    function createForm(object){
        event.preventDefault();

        let total_payment_received_parcel   = $('#total_payment_received_parcel').val();
        var total_payment_received_amount   = $("#total_payment_received_amount").val();
        var note                            = $("#note").val();

        var parcel_delivery_payment_detail_status        = $('.parcel_delivery_payment_detail_status').map(function(){
                return this.value;
        }).get();

        var parcel_delivery_payment_detail_id        = $('.parcel_delivery_payment_detail_id').map(function(){
                return this.value;
        }).get();

        var parcel_id        = $('.parcel_id').map(function(){
                return this.value;
        }).get();

        var amount        = $('.amount').map(function(){
                return this.value;
        }).get();

        var detail_note        = $('.detail_note').map(function(){
                return this.value;
        }).get();


        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                total_payment_received_parcel   : total_payment_received_parcel,
                total_payment_received_amount   : total_payment_received_amount,
                note                            : note,
                parcel_delivery_payment_detail_status   : parcel_delivery_payment_detail_status,
                parcel_delivery_payment_detail_id       : parcel_delivery_payment_detail_id,
                parcel_id                               : parcel_id,
                amount                          : amount,
                detail_note                     : detail_note,
                '_method'           : 'PATCH',
                _token                          : "{{ csrf_token() }}"
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
                    if(getError.total_payment_received_parcel){
                        message += getError.total_payment_received_parcel[0];
                    }
                    if(getError.total_payment_received_amount){
                        message += getError.total_payment_received_amount[0];
                    }
                    if(getError.note){
                        message += getError.note[0];
                    }
                    else{
                        message += getError;
                    }
                    toastr.error(message);
                }
            }
        })

    }

</script>
