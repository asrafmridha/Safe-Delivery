@extends('layouts.branch_layout.branch_layout')

@push('style_css')
    <style>
        .search_area .select2-selection.select2-selection--single {
            padding-top: 10px;
            height: 38px !important;
            font-size: 18px;
            box-shadow: 0 0 5px rgb(62, 196, 118);
            border: 1px solid rgb(62, 196, 118) !important;
        }

        .search_area .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
    </style>
@endpush
@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Booking Parcel Payment Forward </h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Booking Parcel payment Forward </li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" id="paymentForwardForm" action="{{ route('branch.bookingParcelPayment.confirmPaymentForwardToAccounts') }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="total_assign_parcel" id="total_assign_parcel" value="0" >
                    <div class="row">
                        {{--<div class="col-md-12 row search_area" style="margin-top: 20px;">--}}
                            {{----}}
                            {{--<div class="col-md-2">--}}
                                {{--<button type="button" class="btn btn-info btn-block" onclick="return parcelResult()" style="margin-top: 3px;">--}}
                                    {{--Search--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="col-md-12" style="margin-top: 20px;">

                                <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
                                    <thead>
                                        <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th colspan="7" class="text-left">
                                                <button type="button" id="paymentForward" class="btn btn-info float-right" onclick="return submit_data()">Forward Payment</button>
                                            </th>
                                        </tr>
                                        <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th width="10%" class="text-center">
                                                Select All <br>
                                                <input type="checkbox"  id="checkAll" >
                                            </th>
                                            <th width="15%" class="text-center">Payment Date </th>
                                            <th width="15%" class="text-center">Parcel No</th>
                                            <th width="15%" class="text-center">Payment Receive</th>
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
            var checked_count   = $("input.paymentId").filter(':checked').length;
            $("#total_assign_parcel").val(checked_count);
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


            var checked_count   = $("input.paymentId").filter(':checked').length;
            $("#total_assign_parcel").val(checked_count);

        });

    }

    function submit_data() {
        event.preventDefault();

        var parcel_item = $("#total_assign_parcel").val();
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


//    function createForm(){
//        let vehicle_id = $('#vehicle_id').val();
//        if(vehicle_id == '0'){
//            toastr.error("Please Select Vehicle..");
//            return false;
//        }
//
//        let total_assign_parcel = returnNumber($('#total_assign_parcel').val());
//        if(total_assign_parcel == 0){
//            toastr.error("Please Booking Parcel add for assign..");
//            return false;
//        }
//    }

  </script>
@endpush
