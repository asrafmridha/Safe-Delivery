<div class="modal-header bg-default">
    <h4 class="modal-title">Reject Branch Delivery Payment </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('admin.account.confirmRejectBranchDeliveryPayment', $parcelDeliveryPayment->id) }}" id="confirmAcceptBranchDeliveryPayment" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">

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
                                                @case(1)
                                                    Paid Request
                                                    @break
                                                @case(2)
                                                    Paid Accept
                                                    @break
                                                @case(3)
                                                    Paid Reject
                                                    @break
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
                        @if($parcelDeliveryPaymentDetails->count() > 0)
                        <fieldset>
                            <legend>Pickup Run Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center"># </th>
                                        <th width="15%" class="text-center">Invoice </th>
                                        <th width="15%" class="text-center">Merchant Order  </th>
                                        <th width="20%" class="text-center">Merchant Name</th>
                                        <th width="20%" class="text-center">Customer Name</th>
                                        <th width="20%" class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parcelDeliveryPaymentDetails as $parcelDeliveryPaymentDetail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcelDeliveryPaymentDetail->parcel->parcel_invoice }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcelDeliveryPaymentDetail->parcel->merchant_order_id }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcelDeliveryPaymentDetail->parcel->merchant->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $parcelDeliveryPaymentDetail->parcel->customer_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($parcelDeliveryPaymentDetail->amount,2) }}
                                            <input type="hidden" name="parcel_id[]" class="parcel_id" value="{{$parcelDeliveryPaymentDetail->parcel_id }}">
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

    function createForm(object){
        event.preventDefault();
        var note                            = $("#note").val();

        var parcel_id        = $('.parcel_id').map(function(){
                return this.value;
        }).get();

        $.ajax({
            cache     : false,
            type      : "POST",
            dataType  : "JSON",
            data      : {
                note         : note,
                parcel_id    : parcel_id,
                '_method'    : 'PATCH',
                _token       : "{{ csrf_token() }}"
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
                    toastr.error(getError);
                }
            }
        })

    }

</script>
