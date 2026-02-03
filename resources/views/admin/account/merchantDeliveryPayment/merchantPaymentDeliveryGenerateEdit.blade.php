@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Merchant Delivery Payment Edit</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Delivery Payment Edit</li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('admin.account.confirmMerchantPaymentDeliveryGenerateEdit', $parcelMerchantDeliveryPayment->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="total_payment_parcel" id="total_payment_parcel" value="0" >
                    <input type="hidden" name="total_payment_amount" id="total_payment_amount" value="0" >
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Delivery Payment</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <th >Merchant </th>
                                                <td colspan="2">
                                                    <select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" >
                                                        <option value="0" >Select Merchant </option>
                                                        <option value="{{ $parcelMerchantDeliveryPayment->merchant_id }}"
                                                            merchantContactNumber="{{ $parcelMerchantDeliveryPayment->merchant->contact_number }}"
                                                            merchantAddress="{{ $parcelMerchantDeliveryPayment->merchant->address }}">
                                                            {{ $parcelMerchantDeliveryPayment->merchant->name }} </option>
                                                        @foreach ($merchants as $merchant)
                                                            <option
                                                                value="{{ $merchant->id }}"
                                                                merchantContactNumber="{{ $merchant->contact_number }}"
                                                                merchantAddress="{{ $merchant->address }}"
                                                                > {{ $merchant->name }} </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
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
                                                    <span id="view_merchant_name">{{ $parcelMerchantDeliveryPayment->merchant->name }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Merchant Contact Number</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_contact_number">{{ $parcelMerchantDeliveryPayment->merchant->contact_number }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%">Merchant Address</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_merchant_address">{{ $parcelMerchantDeliveryPayment->merchant->address }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th style="width: 40%"> Total Payment Parcel</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_total_payment_parcel"> {{ $parcelMerchantDeliveryPayment->total_payment_parcel }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Total Payment Amount</th>
                                                <td style="width: 5%"> : </td>
                                                <td style="width: 55%">
                                                    <span id="view_total_payment_amount"> {{ number_format($parcelMerchantDeliveryPayment->total_payment_amount,2) }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th > Transfer Reference</th>
                                                <td colspan="2">
                                                    <input type="text" name="transfer_reference" id="transfer_reference" class="form-control" value="{{ $parcelMerchantDeliveryPayment->transfer_reference }}" placeholder="Transfer Reference" >
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <textarea name="note" id="note" class="form-control" placeholder="Delivery Payment Note ">{{ $parcelMerchantDeliveryPayment->note }}</textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset id="div_parcel" >
                                            <legend>Delivery Payment Parcel </legend>
                                            <div class="row">
                                                <div class="col-sm-12" id="show_cart_parcel">
                                                    @if(!empty($cart) )
                                                        <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
                                                            <thead>
                                                                <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                                                    <th width="10%" class="text-center">
                                                                        <i class="fa fa-trash " style="color:black"></i>
                                                                    </th>
                                                                    <th width="25%" class="text-center">Invoice </th>
                                                                    <th width="20%" class="text-center">Merchant Name</th>
                                                                    <th width="20%" class="text-center">Customer Name</th>
                                                                    <th width="25%" class="text-right">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($cart as $item)
                                                                    <tr style="background-color: #f4f4f4;">
                                                                        <td class="text-center" >
                                                                            <span style="cursor: pointer;" onclick="return delete_parcel({{ $item->id }})">
                                                                                <i class="fa fa-trash text-danger" style="color:black"></i>
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center" >
                                                                            {{ $item->attributes->parcel_invoice }}
                                                                        </td>
                                                                        <td class="text-center" >
                                                                            {{ $item->attributes->merchant_name }}
                                                                        </td>
                                                                        <td class="text-center" >
                                                                            {{ $item->attributes->customer_name }}
                                                                        </td>
                                                                        <td class="text-right" >
                                                                            {{ number_format($item->price,2) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden"  id="cart_total_item" value="{{ $totalItem }}">
                                                        <input type="hidden"  id="cart_total_amount" value="{{ $getTotal }}">
                                                    @endif
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success">Update </button>
                                    <button type="reset" class="btn btn-primary">Reset</button>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-12 row" style="margin-top: 20px;">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode" onkeypress="return add_parcel(event)"
                                    style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="merchant_order_id" id="merchant_order_id" class="form-control" placeholder="Enter Merchant Order ID"
                                    style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info btn-block" onclick="return parcelResult()">
                                    Search
                                </button>
                            </div>
                        </div>

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
                                        <th width="10%" class="text-center">Return Charge</th>
                                        <th width="10%" class="text-center">Payable</th>
                                    </tr>
                                </thead>
                                <tbody id="show_payment_parcel">
                                    @if($parcels->count() > 0)
                                        @foreach($parcels as $parcel)
                                            @php
                                                $returnCharge = 0;
                                                if($parcel->delivery_type == 4 || $parcel->delivery_type == 2){
                                                    $returnCharge = $parcel->merchant_service_area_return_charge;
                                                }
                                                $change         = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge ;
                                                $payable_amount = $change - $parcel->cod_charge - $returnCharge;
                                            @endphp
                                            <tr style="background-color: #f4f4f4;">
                                                <td class="text-center" >
                                                    <input type="checkbox" id="checkItem"  class="parcelId"
                                                    value="{{ $parcel->id }}" >
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
                                                    {{-- {{ number_format($parcel->cod_charge,2) }} --}}

                                                    <input type="number" id="cod_charge{{ $parcel->id }}"
                                                    value="{{ floatval($parcel->cod_charge) }}"
                                                    parcel_id="{{ $parcel->id }}"
                                                    class="form-control text-center cod_charge" step="any" />

                                                </td>
                                                <td class="text-right" >
                                                    <input type="number" id="return_charge{{ $parcel->id }}"
                                                    value="{{ $returnCharge }}"
                                                    parcel_id="{{ $parcel->id }}"
                                                    class="form-control text-center return_charge" step="any" />

                                                    <input type="hidden" id="total_charge_amount{{ $parcel->id }}"
                                                    value="{{ $change }}" />

                                                </td>
                                                <td class="text-right " id="view_total_charge_amount{{ $parcel->id }}">
                                                    {{ number_format($payable_amount,2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @if($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details->count() > 0)
                                        @foreach($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $parcel_merchant_delivery_payment_detail)
                                            @php
                                                $returnCharge = 0;

                                                $change         = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge ;
                                                $payable_amount = $change - $parcel->cod_charge - $parcel_merchant_delivery_payment_detail->cod_chargereturnCharge;
                                            @endphp

                                            <tr style="background-color: #f4f4f4;">
                                                <td class="text-center" >
                                                    <input type="checkbox" id="checkItem"  class="parcelId"  value="{{ $parcel_merchant_delivery_payment_detail->parcel->id }}" >
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->merchant_order_id }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->merchant->name }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->merchant->contact_number }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->customer_name }} <br>
                                                    {{ $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number }}
                                                </td>
                                                <td class="text-right" >
                                                    {{ number_format($parcel_merchant_delivery_payment_detail->collected_amount,2) }}
                                                </td>
                                                <td class="text-right" >
                                                    {{ number_format($parcel_merchant_delivery_payment_detail->delivery_charge,2) }} <br>
                                                    ({{ $parcel_merchant_delivery_payment_detail->parcel->weight_package->name }})
                                                </td>

                                                <td class="text-right" >
                                                    {{ number_format($parcel_merchant_delivery_payment_detail->paid_amount,2) }}
                                                </td>
                                                <td class="text-right" >
                                                    {{-- {{ number_format($parcel_merchant_delivery_payment_detail->cod_charge,2) }} --}}

                                                    <input type="number" id="cod_charge{{ $parcel->id }}"
                                                    value="{{ floatval($parcel_merchant_delivery_payment_detail->cod_charge) }}"
                                                    parcel_id="{{ $parcel->id }}"
                                                    class="form-control text-center cod_charge" step="any" />
                                                </td>
                                                <td class="text-right" >
                                                    <input type="number" id="return_charge{{ $parcel->id }}"
                                                    value="{{ floatval($returnCharge) }}"
                                                    parcel_id="{{ $parcel->id }}"
                                                    class="form-control text-center return_charge" step="any" />

                                                    <input type="hidden" id="total_charge_amount{{ $parcel->id }}"
                                                    value="{{ $change }}" />

                                                </td>
                                                <td class="text-right " id="view_total_charge_amount{{ $parcel->id }}">
                                                    {{ number_format($payable_amount,2) }}
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
    $("#merchant_id").val({{ $parcelMerchantDeliveryPayment->merchant_id }}).change();

    window.onload = function(){

        $('#merchant_id').on('change', function(){
            var merchant   = $("#merchant_id option:selected");
            var merchant_id   = merchant.val();

            if(merchant_id == 0 ){
                $("#view_merchant_name").html('Not Confirm');
                $("#view_merchant_contact_number").html('Not Confirm');
                $("#view_merchant_address").html('Not Confirm');
            } else{
                $("#view_merchant_name").html(merchant.text());
                $("#view_merchant_contact_number").html(merchant.attr('merchantContactNumber'));
                $("#view_merchant_address").html(merchant.attr('merchantAddress'));
            }
            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    _token  : "{{ csrf_token() }}"
                    },
                url       : "{{ route('admin.account.merchantDeliveryPaymentParcelClearCart') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_cart_parcel").html(response);
                    return false;
                }
            });
        });


        $('#addParcelInvoice').on('click', function(){
            var parcel_invoices = $('.parcelId:checkbox:checked').map(function() {
                return this.value;
            }).get();
            if(parcel_invoices.length == 0){
                toastr.error("Please Select Parcel Invoice ");
                return false;
            }

            var merchant_id       = $("#merchant_id option:selected").val();
            if(merchant_id == 0){
                toastr.error("Please Select Merchant");
                return false;
            }

            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    merchant_id     : merchant_id,
                    parcel_invoices : parcel_invoices,
                    _token  : "{{ csrf_token() }}"
                    },
                url       : "{{ route('admin.account.merchantDeliveryPaymentParcelAddCart') }}",
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
