<div class="modal-header bg-default">
    <h4 class="modal-title">Accept Branch Parcel Payment </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('admin.account.traditional.confirmAcceptBranchParcelPayment', $parcelPayment->id) }}" id="confirmAcceptBranchPayment" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">

        <input type="hidden" name="total_payment_received_parcel" id="total_payment_received_parcel" value="0">
        <input type="hidden" name="total_payment_received_amount" id="total_payment_received_amount" value="0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Parcel Payment Information</legend>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Consignment </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelPayment->bill_no }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPayment->created_at)->format('d/m/Y H:i:s') }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Status </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            @switch($parcelPayment->payment_status)
                                                @case(1) Paid Request @break
                                                @case(2) Paid Accept @break
                                                @case(0) Paid Reject @break
                                                @default
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Parcel </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcelPayment->payment_parcel }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ number_format($parcelPayment->total_amount,2) }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Received Parcel </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            <span id="view_total_payment_received_parcel">{{ $parcelPayment->payment_parcel }}</span>
                                         </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Payment Received Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            <span id="view_total_payment_received_amount">{{ number_format($parcelPayment->total_amount,2) }}</span>
                                         </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <textarea name="note" id="note" class="form-control" placeholder="Delivery Payment Note ">{{ $parcelPayment->note }}</textarea>
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
                                            <td style="width: 50%"> {{ $parcelPayment->branch->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelPayment->branch->address }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%">Branch User </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $parcelPayment->branch_user->name }} </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        @if($parcelPayment->booking_parcel_payment_logs->count() > 0)
                        <fieldset>
                            <legend>Pickup Run Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"># </th>
                                        <th width="10%" class="text-center">C/N No </th>
                                        <th width="10%" class="text-center">Payment Receive  </th>
                                        <th width="10%" class="text-center">Amount</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="25%" class="text-center">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelPayment->booking_parcel_payment_logs as $booking_parcel_payment_logs)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $booking_parcel_payment_logs->booking_parcels->parcel_code }}
                                        </td>
                                        <td class="text-center">
                                            {{ $booking_parcel_payment_logs->booking_parcel_payment_details->payment_receive_type }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($booking_parcel_payment_logs->booking_parcel_payment_details->total_amount,2) }}
                                        </td>
                                        <td class="text-center">
                                            <select name="status[]"
                                                class="form-control select2 parcel_payment_detail_status"
                                                style="width: 100%"
                                                onchange="return parcel_payment_detail_status()">
                                                <option value="2" booking_parcel_payment_logs_id="{{ $booking_parcel_payment_logs->id }}">Accept</option>
                                                <option value="0" >Reject</option>
                                            </select>
                                            <input type="hidden" name="parcel_payment_log_id[]" class="parcel_payment_log_id" value="{{$booking_parcel_payment_logs->id }}">
                                            <input type="hidden" name="parcel_payment_detail_id[]" class="parcel_payment_detail_id" value="{{$booking_parcel_payment_logs->payment_details_id }}">
                                            <input type="hidden" name="amount[]" class="amount" id="amount{{ $booking_parcel_payment_logs->id }}" value="{{ $booking_parcel_payment_logs->booking_parcel_payment_details->total_amount }}">
                                        </td>
                                        <td class="text-center">
                                            <textarea name="payment_note[]" class="form-control payment_note" placeholder="Parcel Payment Note"></textarea>
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


    function parcel_payment_detail_status(){
        var complete    = 0;
        var amount      = 0;

        var s = $('.parcel_payment_detail_status option:selected').map(function(){
            if(this.value == 2){
                var booking_parcel_payment_logs_id = this.getAttribute('booking_parcel_payment_logs_id');
                var parcel_amount = returnNumber($("#amount"+booking_parcel_payment_logs_id).val());
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

        var parcel_payment_detail_status        = $('.parcel_payment_detail_status').map(function(){
                return this.value;
        }).get();

        var parcel_payment_log_id        = $('.parcel_payment_log_id').map(function(){
                return this.value;
        }).get();

        var parcel_payment_detail_id        = $('.parcel_payment_detail_id').map(function(){
                return this.value;
        }).get();

        var amount        = $('.amount').map(function(){
                return this.value;
        }).get();

        var payment_note        = $('.payment_note').map(function(){
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
                parcel_payment_detail_status    : parcel_payment_detail_status,
                parcel_payment_log_id           : parcel_payment_log_id,
                parcel_payment_detail_id        : parcel_payment_detail_id,
                amount                          : amount,
                payment_note                    : payment_note,
                '_method'                       : 'PATCH',
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
