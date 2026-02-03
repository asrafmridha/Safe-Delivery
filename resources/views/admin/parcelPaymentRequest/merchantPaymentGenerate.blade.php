@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Merchant Parcel Payment </h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Parcel Payment </li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('admin.parcel.confirmParcelPaymentGenerate') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
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
                                            {{--<tr>--}}
                                                {{--<th >Merchant </th>--}}
                                                {{--<td colspan="2">--}}
                                                    {{--<select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" >--}}
                                                        {{--<option value="0" >Select Merchant Company </option>--}}
                                                        {{--@foreach ($merchants as $merchant)--}}
                                                            {{--<option--}}
                                                                {{--value="{{ $merchant->id }}"--}}
                                                                {{--merchantName="{{ $merchant->name }}"--}}
                                                                {{--merchantContactNumber="{{ $merchant->contact_number }}"--}}
                                                                {{--merchantAddress="{{ $merchant->address }}"--}}
                                                                {{--> {{ $merchant->company_name }} </option>--}}
                                                        {{--@endforeach--}}
                                                    {{--</select>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}

                                            <input type="hidden" name="payment_request_id" id="payment_request_id" value="{{ $parcelPaymentRequest->id }}">
                                            <input type="hidden" name="merchant_id" id="merchant_id" value="{{ $parcelPaymentRequest->merchant_id }}">
                                            <tr>
                                                <th >Date</th>
                                                <td colspan="2">
                                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control " required>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th style="width: 40%">Merchant Name</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_name">{{ $parcelPaymentRequest->merchant->name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Merchant Contact Number</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_contact_number">{{ $parcelPaymentRequest->merchant->contact_number }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Merchant Address</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_address">{{ $parcelPaymentRequest->merchant->address }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th style="width: 40%"> Merchant Request Amount</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_request_amount"> {{ $parcelPaymentRequest->request_amount }} </span>
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
                                                <th > Payment Type</th>
                                                <td colspan="2">
                                                    <select name="paid_payment_type" id="paid_payment_type" class="form-control">
                                                        <?php
                                                            $payment_methods = [
                                                                '0' => 'Select Payment Type',
                                                                '1' => 'Cash',
                                                                '2' => 'Bank',
                                                                '3' => 'Bkash',
                                                                '4' => 'Rocket',
                                                                '5' => 'Nagad'
                                                            ];

                                                            foreach ($payment_methods as $k=>$v) {

                                                                $selected = ($k == $parcelPaymentRequest->request_payment_type) ? "selected" : "";
                                                                echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th > Transfer Reference</th>
                                                <td colspan="2">
                                                   <input type="text" name="transfer_reference" id="transfer_reference" class="form-control" placeholder="Transfer Reference" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <textarea name="note" id="note" class="form-control" placeholder="Delivery Payment Note "></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset id="div_parcel" style="display: none">
                                            <legend>Delivery Payment Parcel </legend>
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
                                        <th colspan="12" class="text-left">
                                            <button type="button" id="addParcelInvoice" class="btn btn-info">Add Payment Amount</button>
                                        </th>
                                    </tr>
                                    <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th width="5%" class="text-center">
                                            All <br>
                                            <input type="checkbox"  id="checkAll" >
                                        </th>
                                        <th width="10%" class="text-center">Invoice </th>
                                        <th width="10%" class="text-center">Merchant Order  </th>
                                        <th width="10%" class="text-center">Company Name</th>
                                        <th width="10%" class="text-center">Contact Number</th>
                                        <th width="10%" class="text-center">Customer</th>
                                        <th width="10%" class="text-center">Collected</th>
                                        <th width="10%" class="text-center">Weight Charge </th>
                                        <th width="10%" class="text-center">Delivery </th>
                                        <th width="10%" class="text-center">COD Charge</th>
                                        <th width="10%" class="text-center">Payable</th>
                                    </tr>
                                </thead>
                                <tbody id="show_payment_parcel">
                                    @if($parcels->count() > 0)
                                        @foreach($parcels as $parcel)
                                            @if(in_array($parcel->id, json_decode($parcelPaymentRequest->parcel_ids)))
                                                @php
                                                    $payable_amount = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge - $parcel->cod_charge;
                                                @endphp
                                                <tr style="background-color: #f4f4f4;">
                                                    <td class="text-center" >
                                                        <input type="checkbox" id="checkItem"  class="parcelId"  value="{{ $parcel->id }}" >
                                                    </td>
                                                    <td class="text-center" >
                                                        {{ $parcel->parcel_invoice }}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{ $parcel->merchant_order_id }}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{ $parcel->merchant->company_name }}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{ $parcel->merchant->contact_number }}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{ $parcel->customer_name }} <br>
                                                        {{ $parcel->customer_contact_number }}
                                                    </td>
                                                    <td class="text-right" >
                                                        {{ number_format($parcel->customer_collect_amount,2) }}
                                                    </td>
                                                    <td class="text-right" >
                                                        {{ number_format($parcel->weight_package_charge,2) }}  <br>
                                                        ({{ $parcel->weight_package->name }})
                                                    </td>
                                                    <td class="text-right" >
                                                        {{ number_format($parcel->delivery_charge,2) }}  <br>
                                                    </td>
                                                    <td class="text-right" >
                                                        {{ number_format($parcel->cod_charge,2) }}
                                                    </td>
                                                    <td class="text-right" >
                                                        {{ number_format($payable_amount,2) }}
                                                    </td>
                                                </tr>
                                            @endif
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

        {{--$('#merchant_id').on('change', function(){--}}
            {{--var merchant   = $("#merchant_id option:selected");--}}
            {{--var merchant_id   = merchant.val();--}}

            {{--if(merchant_id == 0 ){--}}
                {{--$("#view_merchant_name").html('Not Confirm');--}}
                {{--$("#view_merchant_contact_number").html('Not Confirm');--}}
                {{--$("#view_merchant_address").html('Not Confirm');--}}
            {{--} else{--}}
                {{--$("#view_merchant_name").html(merchant.attr('merchantName'));--}}
                {{--$("#view_merchant_contact_number").html(merchant.attr('merchantContactNumber'));--}}
                {{--$("#view_merchant_address").html(merchant.attr('merchantAddress'));--}}
            {{--}--}}
            {{--$.ajax({--}}
                {{--cache     : false,--}}
                {{--type      : "POST",--}}
                {{--data      : {--}}
                    {{--_token  : "{{ csrf_token() }}"--}}
                    {{--},--}}
                {{--url       : "{{ route('admin.account.merchantDeliveryPaymentParcelClearCart') }}",--}}
                {{--error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },--}}
                {{--success   : function(response){--}}
                    {{--$("#show_cart_parcel").html(response);--}}
                    {{--return false;--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}


        $('#addParcelInvoice').on('click', function(){
            var parcel_invoices = $('.parcelId:checkbox:checked').map(function() {
                return this.value;
            }).get();
            if(parcel_invoices.length == 0){
                toastr.error("Please Select Parcel Invoice ");
                return false;
            }

            var merchant_id       = $("#merchant_id").val();
//            if(merchant_id != ""){
//                toastr.error("Please Select Merchant");
//                return false;
//            }

            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    merchant_id     : merchant_id,
                    parcel_invoices : parcel_invoices,
                    _token  : "{{ csrf_token() }}"
                },
                url       : "{{ route('admin.parcel.merchantDeliveryPaymentParcelAddCart') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_cart_parcel").html(response);
                    $("#div_parcel").show();
                    $('input:checkbox').prop('checked', false);
                    return false;
                }
            });

        });

        $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
        });
    }

    setInterval(function(){
        var cart_total_item     = returnNumber($("#cart_total_item").val());
        var cart_total_amount   = returnNumber($("#cart_total_amount").val());
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
            url      : "{{ route('admin.account.merchantDeliveryPaymentParcelDeleteCart') }}",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            success : function (response){
                $('#show_cart_parcel').html(response);
            }
        });
    }

    function parcelResult(){
        var parcel_invoice    = $("#parcel_invoice").val();
        var merchant_order_id = $("#merchant_order_id").val();
        var merchant_id       = $("#merchant_id option:selected").val();

        if(merchant_id == 0){
            toastr.error("Please Select Merchant");
            return false;
        }
        $.ajax({
            cache     : false,
            type      : "POST",
            data      : {
                merchant_id             : merchant_id,
                parcel_invoice          : parcel_invoice,
                merchant_order_id       : merchant_order_id,
                _token  : "{{ csrf_token() }}"
                },
            url       : "{{ route('admin.account.returnMerchantDeliveryPaymentParcel') }}",
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            success   : function(response){
                $("#show_payment_parcel").html(response);
                $("#parcel_invoice").val('');
                $("#merchant_order_id").val('');
                return false;
            }
        });
    }


    function createForm(){
        let total_payment_parcel = returnNumber($('#total_payment_parcel').val());
        if(total_payment_parcel == 0){
            toastr.error("Please Enter Delivery Payment Parcel..");
            return false;
        }
    }

  </script>
@endpush
