@extends('layouts.admin_layout.admin_layout')

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
                    <h1 class="m-0 text-dark">Branch Assign Vehicle Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Branch Assign Vehicle Parcel List </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" action="{{ route('branch.bookingParcel.confirmAssignVehicleBookingParcel') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                        @csrf
                        <input type="hidden" name="total_assign_parcel" id="total_assign_parcel" value="0" >
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Assign Warehouse </legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table ">
                                                <tr>
                                                    <th >Warehouse</th>
                                                    <td colspan="2">
                                                        <select name="warehouse_id" id="warehouse_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0" >Select Warehouse </option>
                                                            <?php
                                                            if(count($warehouses) > 0) {
                                                                foreach ($warehouses as $warehouse) {
                                                                    echo '<option value="'.$warehouse->id.'" data-type="'.$warehouse->wh_type.'">'.$warehouse->wh_name.'</option>';
                                                                }
                                                            }
                                                            ?>

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
                                                    <th style="width: 40%">Warehouse Type</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="warehouse_type">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Total Parcel</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_assign_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="note" id="note" class="form-control" placeholder=" Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset id="div_rider_run_parcel" style="display: none">
                                                <legend>Assign Warehouse Parcel </legend>
                                                <div class="row">
                                                    <div class="col-sm-12" id="show_vehicle_assign_parcel">

                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">Assign</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </fieldset>
                            </div>

                            {{--<div class="col-md-12 row search_area" style="margin-top: 20px;">--}}
                                {{--<div class="col-md-4">--}}
                                {{--<div class="form-group">--}}
                                {{--<input type="text" name="parcel_no" id="parcel_no" class="form-control" placeholder="Enter Parcel No" onkeypress="return add_parcel(event)"--}}
                                {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                {{--padding: 3px 0px 3px 3px;--}}
                                {{--margin: 0px;--}}
                                {{--border: 1px solid rgb(62, 196, 118);">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<input type="text" name="parcel_no" id="parcel_no" class="form-control" placeholder="Enter Parcel No"--}}
                                               {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                    {{--padding: 3px 0px 3px 3px;--}}
                                    {{--margin: 0px;--}}
                                    {{--border: 1px solid rgb(62, 196, 118);">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group" style="margin-bottom: 0">--}}
                                        {{--<input type="text" name="merchant_order_id" id="merchant_order_id" class="form-control" placeholder="Enter Delivery Type"--}}
                                        {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                        {{--padding: 3px 0px 3px 3px;--}}
                                        {{--margin: 0;--}}
                                        {{--border: 1px solid rgb(62, 196, 118);">--}}

                                        {{--<select name="receiver_branch_id" id="receiver_branch_id" class="form-control select2"--}}
                                                {{--style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);--}}
                                        {{--padding: 3px 0px 3px 3px;--}}
                                        {{--margin: 0;--}}
                                        {{--border: 1px solid rgb(62, 196, 118);">--}}
                                            {{--<option value="0" >Select Branch </option>--}}
                                            {{--<?php--}}
{{--//                                            if(count($receiver_branches) > 0) {--}}
{{--//                                                foreach ($receiver_branches as $branch) {--}}
{{--//                                                    echo '<option value="'.$branch->receiver_branch_id.'">'.$branch->receiver_branch->name.'</option>';--}}
{{--//                                                }--}}
{{--//                                            }--}}
                                            {{--?>--}}

                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
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
                                            <button type="button" id="addParcelCode" class="btn btn-info">Add Parcel to Warehouse</button>
                                        </th>
                                    </tr>
                                    <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th width="10%" class="text-center">
                                            Select All <br>
                                            <input type="checkbox"  id="checkAll" >
                                        </th>
                                        <th width="15%" class="text-center">Parcel No </th>
                                        <th width="15%" class="text-center">Sender Contact</th>
                                        <th width="15%" class="text-center">Receiver Branch</th>
                                        <th width="15%" class="text-center">Net Amount</th>
                                        <th width="15%" class="text-center">Delivery Type</th>
                                    </tr>
                                    </thead>
                                    <tbody id="show_parcel">
                                    @if($bookingParcel->count() > 0)
                                        @foreach($bookingParcel as $parcel)

                                            <?php
                                            switch ($parcel->booking_parcels->delivery_type) {
                                                //                case 'hd':$delivery_type  = "Home Delivery"; $class="success"; break;
                                                //                case 'thd':$delivery_type  = "Transit Home Delivery"; $class="info"; break;
                                                //                case 'od':$delivery_type  = "Office Delivery"; $class="primary"; break;
                                                //                case 'tod':$delivery_type  = "Transit Office Delivery"; $class="warning"; break;
                                                case 'hd':$delivery_type  = "HD"; $class="success"; break;
                                                case 'thd':$delivery_type  = "THD"; $class="info"; break;
                                                case 'od':$delivery_type  = "OD"; $class="primary"; break;
                                                case 'tod':$delivery_type  = "TOD"; $class="warning"; break;
                                                default:$delivery_type = "None"; $class = "danger";break;
                                            }
                                            ?>
                                            <tr style="background-color: #f4f4f4;">
                                                <td class="text-center" >
                                                    <input type="checkbox" id="checkItem"  class="bookingId"  value="{{ $parcel->id }}" >
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->booking_parcels->parcel_code }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->booking_parcels->sender_phone }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->booking_parcels->receiver_branch->name }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $parcel->booking_parcels->net_amount }}
                                                </td>
                                                <td class="text-center" >
                                                    {{ $delivery_type }}
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
            $('#warehouse_id').on('change', function(){
                var warehouse   = $("#warehouse_id option:selected");
                var warehouse_id   = warehouse.val();

                if(warehouse_id == 0 ){
                    $("#warehouse_type").html('Not Confirm');
                } else{
                    $("#warehouse_type").html(warehouse.data('type'));
                }

            });


            {{--$('#addParcelCode').on('click', function(){--}}
                {{--var parcel_codes = $('.bookingId:checkbox:checked').map(function() {--}}
                    {{--return this.value;--}}
                {{--}).get();--}}
                {{--if(parcel_codes.length == 0){--}}
                    {{--toastr.error("Please Select Parcel No ");--}}
                    {{--return false;--}}
                {{--}--}}
                {{--$.ajax({--}}
                    {{--cache     : false,--}}
                    {{--type      : "POST",--}}
                    {{--data      : {--}}
                        {{--parcel_codes : parcel_codes,--}}
                        {{--_token  : "{{ csrf_token() }}"--}}
                    {{--},--}}
                    {{--url       : "{{ route('branch.bookingParcel.assignParcelAddCart') }}",--}}
                    {{--error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },--}}
                    {{--success   : function(response){--}}
                        {{--$("#show_vehicle_assign_parcel").html(response);--}}
                        {{--$("#div_rider_run_parcel").show();--}}
                        {{--$('input:checkbox').prop('checked', false);--}}
                        {{--return false;--}}
                    {{--}--}}
                {{--});--}}

            {{--});--}}

            $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
            });

        }


        setInterval(function(){
            var cart_total_item = returnNumber($("#cart_total_item").val());
            $("#view_total_assign_parcel").html(cart_total_item);
            $("#total_assign_parcel").val(cart_total_item);
        }, 300);

        //    function add_parcel(event){
        //        if(event.which == 13) {
        //            parcelResult();
        //
        //            return false;
        //        }
        //    }

        function delete_parcel(itemId){

            $.ajax({
                cache    : false,
                type     : 'POST',
                data     : {
                    itemId           : itemId,
                    _token          : "{{ csrf_token() }}",
                },
                url      : "{{ route('branch.bookingParcel.assignParcelDeleteCart') }}",
                error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success : function (response){
                    $('#show_vehicle_assign_parcel').html(response);
                }
            });
        }

        function parcelResult(){

            //var parcel_invoice_barcode  = $("#parcel_invoice_barcode").val();
            var parcel_no          = $("#parcel_no").val();
            var rbranch_id       = $("#receiver_branch_id").val();


            $.ajax({
                cache     : false,
                type      : "POST",
                data      : {
                    parcel_no       : parcel_no,
                    rbranch_id      : rbranch_id,
                    _token  : "{{ csrf_token() }}"
                },
                url       : "{{ route('branch.bookingParcel.filterAssignData') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_parcel").html(response);

//                $("#parcel_invoice_barcode").val('');
                    $("#parcel_no").val('');
                    $("#receiver_branch_id").val('');
                    return false;
                }
            });
        }


        function createForm(){
            let vehicle_id = $('#vehicle_id').val();
            if(vehicle_id == '0'){
                toastr.error("Please Select Vehicle..");
                return false;
            }

            let total_assign_parcel = returnNumber($('#total_assign_parcel').val());
            if(total_assign_parcel == 0){
                toastr.error("Please Booking Parcel add for assign..");
                return false;
            }
        }

    </script>
@endpush
