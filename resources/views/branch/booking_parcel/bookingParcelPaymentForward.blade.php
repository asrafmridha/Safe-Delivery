@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Parcel Payment Generate</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Parcel Payment Generate </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="paymentForwardForm" action="{{ route('branch.bookingParcelPayment.confirmPaymentForwardToAccounts') }}" method="POST" enctype="multipart/form-data" onsubmit="return submit_data()">
                        @csrf
                        <input type="hidden" name="total_payment_parcel" id="total_payment_parcel" value="0" >
                        <input type="hidden" name="total_payment_amount" id="total_payment_amount" value="0" >
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Parcel Payment</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tr>
                                                    <th >Date</th>
                                                    <td colspan="2">
                                                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control " required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Total Payment Parcel</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_payment_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Total Payment Amount</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_payment_amount"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="payment_note" id="payment_note" class="form-control" placeholder="Parcel Payment Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset id="div_parcel" style="display: none">
                                                <legend>Parcel Payment List </legend>
                                                <div class="row">
                                                    <div class="col-sm-12" id="show_cart_parcel">

                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">Generate</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </fieldset>
                            </div>

                            {{--<div class="col-md-12 row" style="margin-top: 20px;">--}}
                                {{--<div class="col-md-5">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode" onkeypress="return add_parcel(event)"--}}
                                               {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                    {{--padding: 3px 0px 3px 3px;--}}
                                    {{--margin: 5px 1px 3px 0px;--}}
                                    {{--border: 1px solid rgb(62, 196, 118);">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-5">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<input type="text" name="merchant_order_id" id="merchant_order_id" class="form-control" placeholder="Enter Merchant Order ID"--}}
                                               {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                    {{--padding: 3px 0px 3px 3px;--}}
                                    {{--margin: 5px 1px 3px 0px;--}}
                                    {{--border: 1px solid rgb(62, 196, 118);">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-2">--}}
                                    {{--<button type="button" class="btn btn-info btn-block" onclick="return parcelResult()">--}}
                                        {{--Search--}}
                                    {{--</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="col-md-12" style="margin-top: 20px;">
                                <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
                                    <thead>
                                    <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th colspan="7" class="text-left">
                                            <button type="button" id="addParcelInvoice" class="btn btn-info">Add Parcel for Payment</button>
                                        </th>
                                    </tr>
                                    <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th width="10%" class="text-center">
                                            All <br>
                                            <input type="checkbox"  id="checkAll" >
                                        </th>
                                        <th width="15%" class="text-center">Date </th>
                                        <th width="15%" class="text-center">C/N No </th>
                                        <th width="15%" class="text-center">Payment Receive </th>
                                        <th width="15%" class="text-center">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody id="show_parcel">
                                    @if($bookingParcelPayment->count() > 0)
                                        @php $i = 0; @endphp
                                        @foreach($bookingParcelPayment as $payment)

                                            <?php
                                            $i++;
                                            switch ($payment->payment_receive_type) {
                                                case 'booking':$payment_type  = "Booking"; $class="success"; break;
                                                case 'delivery':$payment_type  = "Delivery"; $class="info"; break;
                                                default:$payment_type = "None"; $class = "danger";break;
                                            }
                                            ?>
                                            <tr style="background-color: #f4f4f4;">
                                                <td class="text-center" >
                                                    <input type="checkbox" name="payment_id[]" id="checkItem_{{ $i }}"  class="paymentId"  value="{{ $payment->id }}" >
                                                </td>
                                                <td class="text-center" >
                                                    {{ $payment->payment_date }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $payment->booking_parcels->parcel_code }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $payment_type }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $payment->total_amount }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>
        .table td, .table th {
            padding: .1rem !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>

        window.onload = function(){
            $("#checkAll").change(function() {
                if (this.checked) {
                    $(".paymentId").each(function() {
                        this.checked=true;
                    });
                } else {
                    $(".paymentId").each(function() {
                        this.checked=false;
                    });
                }
//                var checked_count   = $("input.paymentId").filter(':checked').length;
//                $("#total_assign_parcel").val(checked_count);
            });

            $(".paymentId").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 0;

                    $(".paymentId").each(function() {
                        if (!this.checked)
                            isAllChecked = 1;
                    });

                    if (isAllChecked == 0) {
                        $("#checkAll").prop("checked", true);
                    }
                }
                else {
                    $("#checkAll").prop("checked", false);
                }

            });


            $('#addParcelInvoice').on('click', function(){
                var parcel_invoices = $('.paymentId:checkbox:checked').map(function() {
                    return this.value;
                }).get();
                if(parcel_invoices.length == 0){
                    toastr.error("Please Select Parcel Invoice ");
                    return false;
                }
                $.ajax({
                    cache     : false,
                    type      : "POST",
                    data      : {
                        parcel_invoices : parcel_invoices,
                        _token  : "{{ csrf_token() }}"
                    },
                    url       : "{{ route('branch.bookingParcelPayment.paymentParcelAddCart') }}",
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    success   : function(response){
                        $("#show_cart_parcel").html(response);
                        $("#div_parcel").show();
                        $('input:checkbox').prop('checked', false);
                        return false;
                    }
                });

            });

        }

        setInterval(function(){
            var cart_total_item     = returnNumber($("#cart_total_item").val());
            var cart_total_amount   = returnNumber($("#cart_total_amount").val()).toFixed(2);
            $("#view_total_payment_parcel").html(cart_total_item);
            $("#total_payment_parcel").val(cart_total_item);

            $("#view_total_payment_amount").html(cart_total_amount);
            $("#total_payment_amount").val(cart_total_amount);
        }, 300);

        function add_parcel(event){
            if(event.which == 13) {
                parcelResult();
                return false;
            }
        }

        function delete_parcel(itemId){
            $.ajax({
                cache    : false,
                type     : 'POST',
                data     : {
                    itemId           : itemId,
                    _token          : "{{ csrf_token() }}",
                },
                url      : "{{ route('branch.bookingParcelPayment.paymentParcelDeleteCart') }}",
                error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success : function (response){
                    $('#show_cart_parcel').html(response);
                }
            });
        }

        function parcelResult(){
            var parcel_invoice          = $("#parcel_invoice").val();
            var merchant_order_id       = $("#merchant_order_id").val();
            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    parcel_invoice          : parcel_invoice,
                    merchant_order_id       : merchant_order_id,
                    _token  : "{{ csrf_token() }}"
                },
                url       : "{{ route('branch.parcel.returnDeliveryPaymentParcel') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_parcel").html(response);

                    $("#parcel_invoice_barcode").val('');
                    $("#parcel_invoice").val('');
                    $("#merchant_order_id").val('');
                    return false;
                }
            });
        }

        function submit_data() {
            event.preventDefault();

            var parcel_item = $("#total_payment_parcel").val();
            if (parcel_item == 0) {
                toastr.error('Please select minimum 1 payment for forward!');
                return false;
            } else {
                $('#total_assign_parcel').css('border-color', '#ced4da');
            }

            $.ajax({

                url: "{{ route('branch.bookingParcelPayment.confirmPaymentForwardToAccounts') }}",
                type: "POST",
                dataType: "json",
                data: $('#paymentForwardForm').serialize(),
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Payment forward Successfully!');
                        window.location = "{{ route('branch.bookingParcelPayment.paymentForwardToAccounts') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {
                        toastr.error('Payment forward Failed');
                        var getError = data.errors;
                        console.log(data.errors);
                        //                             alert(data.errors.sender_name);
                        var message = "";

                        if (getError.payment_item) {
                            message = getError.payment_item;
                            toastr.error(message);
                        }
                        if (getError.total_assign_parcel) {
                            message = getError.total_assign_parcel[0];
                            toastr.error(message);
                        }
                        //                             toastr.error(message);

                    }
                }
            });
        }

//        function createForm(){
//            let total_payment_parcel = returnNumber($('#total_payment_parcel').val());
//            if(total_payment_parcel == 0){
//                toastr.error("Please Enter Delivery Payment Parcel..");
//                return false;
//            }
//        }

    </script>
@endpush
