@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Generate Pathao Order </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Generate Pathao Order</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <form role="form" action="{{ route('branch.parcel.confirmPathaoOrderGenerate') }}" method="POST"
                          enctype="multipart/form-data" onsubmit="return createForm()">
                        @csrf
                        <input type="hidden" name="total_run_parcel" id="total_run_parcel" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Delivery Run</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table ">
@php
    $pathao_stores = get_pathao_stores();
@endphp


<tr>
    <th>Pathao Store</th>
    <td colspan="2">
        <select name="store_id" id="store_id"
                class="form-control select2" style="width: 100%">
            <option value="0">Select Store</option>

            @foreach ($pathao_stores as $pathao_store)
                <option value="{{ $pathao_store['store_id'] }}"
                        storeName="{{ $pathao_store['store_name'] }}"
                        storeAddress="{{ $pathao_store['store_address'] }}"
                        cityId="{{ $pathao_store['city_id'] }}"
                        zoneId="{{ $pathao_store['zone_id'] }}"
                        hubId="{{ $pathao_store['hub_id'] }}"
                        {{ (isset($area->pathao_store_id) && $area->pathao_store_id == $pathao_store['store_id']) ? 'selected' : '' }}>
                    {{ $pathao_store['store_name'] }}
                </option>
            @endforeach

        </select>
    </td>
</tr>


                                                <tr>
                                                    <th>Rider</th>
                                                    <td colspan="2">
                                                        <select name="rider_id" id="rider_id"
                                                                class="form-control select2" style="width: 100%">
                                                            <option value="0">Select Rider</option>
                                                            @foreach ($riders as $rider)
                                                                    <option value="{{ $rider->id }}"
                                                                            riderContactNumber="{{ $rider->contact_number }}"
                                                                            riderAddress="{{ $rider->address }}"> {{ $rider->name }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <th>Date</th>
                                                    <td colspan="2">
                                                        <input type="date" name="date" id="date"
                                                               value="{{ date('Y-m-d') }}" class="form-control "
                                                               required>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width: 40%"> Total Run Parcel</th>
                                                    <td style="width: 5%"> :</td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_run_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="note" id="note" class="form-control"
                                                                  placeholder="Delivery Rider Run Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset id="div_rider_run_parcel" style="display: none">
                                                <legend>Delivery Run Parcel</legend>
                                                <div class="row">
                                                    <div class="col-sm-12" id="show_rider_run_parcel">

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


                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12 row" style="margin-top: 20px;">
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{--<input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode" onkeypress="return scanParcelAdd(event)" style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                    {{--padding: 3px 0px 3px 3px;--}}
                                    {{--margin: 5px 1px 3px 0px;--}}
                                    {{--border: 1px solid rgb(62, 196, 118);">--}}
                                    <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control"
                                           placeholder="Enter Parcel Invoice Barcode" style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="merchant_order_id" id="merchant_order_id"
                                           class="form-control" placeholder="Enter Merchant Order ID"
                                           onkeypress="return add_parcel(event)"
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
                            <table class="table table-bordered table-striped table-responsive"
                                   style="background-color:white;width: 100%">
                                <thead>
                                <tr style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                    <th colspan="12" class="text-left">
                                        <button type="button" id="addParcelInvoice" class="btn btn-info">Add Parcel to
                                            Run
                                        </button>
                                    </th>
                                </tr>
                                <tr style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                    <th width="5%" class="text-center">
                                        All <br>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th width="5%" class="text-center">Invoice</th>
                                    <th width="10%" class="text-center">Merchant Order</th>
                                    <th width="15%" class="text-center">Company Name</th>
                                    <th width="15%" class="text-center">Merchant Name</th>
                                    <th width="10%" class="text-center">Merchant Number</th>
                                    <th width="15%" class="text-center">Customer Name</th>
                                    <th width="15%" class="text-center">Customer Number</th>
                                    <th width="10%" class="text-center">Customer Address</th>

                                    <th class="text-center"> Collection Amount</th>
                                    {{--                                <th class="text-center"> COD Charge</th>--}}
                                    <th class="text-center"> Total Charge</th>
                                </tr>
                                </thead>
                                <tbody id="show_parcel">
                                @if($parcels->count() > 0)
                                    @foreach($parcels as $parcel)
                                        <tr style="background-color: #f4f4f4;">
                                            <td class="text-center">
                                                <input type="checkbox" id="checkItem" class="parcelId"
                                                       value="{{ $parcel->id }}">
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->parcel_invoice }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->merchant_order_id ?? "---" }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->merchant->company_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->merchant->name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->merchant->contact_number }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->customer_name }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->customer_contact_number }}
                                            </td>
                                            <td class="text-center">
                                                {{ $parcel->customer_address }}
                                            </td>
                                            <td class="text-center">{{$parcel->total_collect_amount}}</td>
                                            {{--                                        <td class="text-center" >{{$parcel->cod_charge}}</td>--}}
                                            <td class="text-center">{{$parcel->total_charge}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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

        // import {log} from "../../../../../public/plugins/pdfmake/pdfmake";


        window.onload = function () {
            /* function getPathaoZone(){
                 var city_id   = $("#city_id option:selected").val();
                 $.ajax({
                     cache     : false,
                     type      : "POST",
                     data      : {
                         city_id : city_id,
                         _token  : "{{ csrf_token() }}"
                },
                url       : "{{ route('getPathaoZone') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    // $("#zone_id").html(response).attr('disabled', false);
                    $("#zone_id").html(response);

                    console.log(response)
                }
            });
        }*/


            $('#city_id').on('change', function () {
                var city_id = $("#city_id option:selected").val();
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        city_id: city_id,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('getPathaoZone') }}",
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#zone_id").html(response).attr('disabled', false);
                        // console.log(response)
                    }
                });
            });
            $('#zone_id').on('change', function () {
                var zone_id = $("#zone_id option:selected").val();
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        zone_id: zone_id,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('getPathaoArea') }}",
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#area_id").html(response).attr('disabled', false);
                        // console.log(response)
                    }
                });
            });
            $('#addParcelInvoice').on('click', function () {
                var parcel_invoices = $('.parcelId:checkbox:checked').map(function () {
                    return this.value;
                }).get();
                console.log(parcel_invoices);

                if (parcel_invoices.length == 0) {
                    toastr.error("Please Select Parcel Invoice ");
                    return false;
                }
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        parcel_invoices: parcel_invoices,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('branch.parcel.deliveryRiderRunParcelAddCart') }}",
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#show_rider_run_parcel").html(response);
                        $("#div_rider_run_parcel").show();
                        $('input:checkbox').prop('checked', false);
                        return false;
                    }
                });

            });

            $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
            });

            $("#parcel_invoice").on("trigger change", function () {

                var invoice_id = $(this).val();

                var invoice_ids = [invoice_id];

                if (invoice_id != "") {

                    $.ajax({
                        cache: false,
                        type: "POST",
                        data: {
                            parcel_invoices: invoice_ids,
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('branch.parcel.deliveryRiderRunParcelAddCart') }}",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        success: function (response) {
                            $("#show_rider_run_parcel").html(response);
                            $("#div_rider_run_parcel").show();
                            $("#parcel_invoice").val("");
                            return false;
                        }
                    });
                }


            });

        }


        setInterval(function () {
            var cart_total_item = returnNumber($("#cart_total_item").val());
            $("#view_total_run_parcel").html(cart_total_item);
            $("#total_run_parcel").val(cart_total_item);
        }, 300);

        function add_parcel(event) {
            if (event.which == 13) {
                parcelResult();
                return false;
            }
        }


        function delete_parcel(itemId) {
            $.ajax({
                cache: false,
                type: 'POST',
                data: {
                    itemId: itemId,
                    _token: "{{ csrf_token() }}",
                },
                url: "{{ route('branch.parcel.deliveryRiderRunParcelDeleteCart') }}",
                error: function (xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function (response) {
                    $('#show_rider_run_parcel').html(response);
                }
            });
        }

        function parcelResult() {
            var parcel_invoice = $("#parcel_invoice").val();
            var merchant_order_id = $("#merchant_order_id").val();
            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    parcel_invoice: parcel_invoice,
                    merchant_order_id: merchant_order_id,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('branch.parcel.returnDeliveryRiderRunParcel') }}",
                error: function (xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function (response) {
                    $("#show_parcel").html(response);

                    $("#parcel_invoice_barcode").val('');
                    $("#parcel_invoice").val('');
                    $("#merchant_order_id").val('');
                    return false;
                }
            });
        }


        function createForm() {

            let rider_id = $('#rider_id').val();
            if (rider_id == '0') {
                toastr.error("Please Select Rider..");
                return false;
            }

            let total_run_parcel = returnNumber($('#total_run_parcel').val());
            if (total_run_parcel == 0) {
                toastr.error("Please Enter Delivery Run Parcel..");
                return false;
            }
        }

    </script>
@endpush
