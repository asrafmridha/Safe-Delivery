@extends('layouts.warehouse_layout.warehouse_layout')

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

        #to_destination {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12" style="border-bottom:2px #ccc dotted;">
                    <div class="form-group clearfix">
                        <div class="icheck-success d-inline">
                          <input type="radio" name="type" id="radioSuccess1" value="receive" checked>
                          <label for="radioSuccess1">
                              Receive Booking Parcel
                          </label>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="icheck-success d-inline">
                          <input type="radio" name="type" id="radioSuccess2" value="assign">
                          <label for="radioSuccess2">
                              Assign Booking Parcel
                          </label>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>

    <span id="type_receive" style="display:block;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12" style="border-bottom:2px #ccc dotted;">
                        <h1 class="m-0 text-dark"> Booking Parcel Receive In Warehouse</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" id="myReceiveForm" action="{{ route('warehouse.operationBookingParcel.confirmWarehouseReceived') }}" method="POST" enctype="multipart/form-data" onsubmit="return receive_submit_data()">
                            @csrf
                            <input type="hidden" name="total_assign_parcel" id="total_receive_parcel" value="0" >
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <table class="table ">
                                                <tr>
                                                    <th >Receive Warehouse</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <input type="text" name="receive_warehouse" id="receive_warehouse" value="{{ $warehouseUser->warehouse->name }}" class="form-control" readonly>
                                                        <input type="hidden" name="receive_warehouse_id" id="receive_warehouse_id" value="{{ $warehouseUser->warehouse_id }}">
                                                        <input type="hidden" name="receive_warehouse_type" id="receive_warehouse_type" value="{{ $warehouseUser->warehouse->warehouse_type }}">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th >From Vehicle</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <select name="receive_vehicle_id" id="receive_vehicle_id" onchange="booking_parcel_list_receive()" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Vehicle</option>
                                                            <?php
                                                            if(count($vehicles) > 0) {
                                                                foreach ($vehicles as $vehicle) {
                                                                    echo '<option value="'.$vehicle->id.'">'.$vehicle->vehicle_name.' ('.$vehicle->vehicle_no.')</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width: 40%"> Total Parcel</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_receive_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="note" id="note" class="form-control" placeholder=" Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>

                                            <button type="submit" class="btn btn-success">Receive</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-sm-12 text-center" style="border-bottom:2px #17a2b8 dotted; letter-spacing: 1px;"><big><strong>Booking Parcel List</strong></big></div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered"  style="background-color:white;width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="10%" class="text-center">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="checkbox" id="checkAllReceive">
                                                                        <label for="checkAllReceive">
                                                                            All
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th width="20%" class="text-center">Parcel No </th>
                                                                <th width="15%" class="text-center">Sender Contact</th>
                                                                <th width="15%" class="text-center">Receiver Branch</th>
                                                                <th width="15%" class="text-center">Net Amount</th>
                                                                <th width="15%" class="text-center">Delivery Type</th>
                                                                <th width="8%" class="text-center">Action</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="show_booking_parcel_list_for_receive">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </span>

    <span id="type_assign" style="display:none;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12" style="border-bottom:2px #ccc dotted;">
                        <h1 class="m-0 text-dark"> Booking Parcel Assign In Vehicle & Warehouse</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" id="myAssignForm" action="{{ route('warehouse.operationBookingParcel.confirmAssignVehicleOrWarehouse') }}" method="POST" enctype="multipart/form-data" onsubmit="return assign_submit_data()">
                            @csrf
                            <input type="hidden" name="total_assign_parcel" id="total_assign_parcel" value="0" >
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <table class="table ">
                                                <tr>
                                                    <th >Destination Branch</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-success d-inline">
                                                              <input type="radio" name="destination_branch" id="destination_branch_yes" value="yes">
                                                              <label for="destination_branch_yes">
                                                                  Yes
                                                              </label>
                                                            </div>
                                                            <div class="icheck-success d-inline">
                                                              <input type="radio" name="destination_branch" id="destination_branch_no" value="no" checked>
                                                              <label for="destination_branch_no">
                                                                  No
                                                              </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th >From Warehouse</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <input type="text" name="from_warehouse" id="from_warehouse" value="{{ $warehouseUser->warehouse->name }}" class="form-control" readonly>
                                                        <input type="hidden" name="from_warehouse_id" id="from_warehouse_id" value="{{ $warehouseUser->warehouse_id }}">
                                                        <input type="hidden" name="from_warehouse_type" id="from_warehouse_type" value="{{ $warehouseUser->warehouse->warehouse_type }}">
                                                    </td>
                                                </tr>

                                                <tr id="to_destination">
                                                    <th >To Destination Branch</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <select name="to_destination_branch_id" id="to_destination_branch_id" onchange="booking_parcel_list_assign('no')" class="form-control select2" style="width: 100%" >
                                                            <option value="0" >Select Destination Branch </option>
                                                            <?php
                                                            if(count($receiver_branches) > 0) {
                                                                foreach ($receiver_branches as $rbranch) {
                                                                    echo '<option value="'.$rbranch->receiver_branch_id.'" >'.$rbranch->receiver_branch->name.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th >Assign Vehicle</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <select name="assign_vehicle_id" id="assign_vehicle_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0">Select Vehicle</option>
                                                            <?php
                                                            if(count($vehicles) > 0) {
                                                                foreach ($vehicles as $vehicle) {
                                                                    echo '<option value="'.$vehicle->id.'">'.$vehicle->name.' ('.$vehicle->vehicle_no.')</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr id="to_warehouse">
                                                    <th >Assign Warehouse</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td>
                                                        <select name="assign_warehouse_id" id="assign_warehouse_id" class="form-control select2" style="width: 100%" >
                                                            <option value="0" >Select Assign Warehouse </option>
                                                            @if (count($warehouses) > 0)
                                                                @foreach ($warehouses as $warehouse)
                                                                    @if ($warehouse->id != $warehouseUser->warehouse_id)
                                                                        <option value="{{ $warehouse->id }}" data-type="{{ $warehouse->type }}">{{ $warehouse->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <input type="hidden" name="assign_warehouse_type" id="assign_warehouse_type" value="">
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
                                                        <textarea name="note" id="assign_note" class="form-control" placeholder=" Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>

                                            <button type="submit" class="btn btn-success">Assign</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-sm-12 text-center" style="border-bottom:2px #17a2b8 dotted; letter-spacing: 1px;"><big><strong>Booking Parcel List</strong></big></div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered"  style="background-color:white;width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="10%" class="text-center">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="checkbox" id="checkAllAssign">
                                                                        <label for="checkAllAssign">
                                                                            All
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th width="20%" class="text-center">Parcel No </th>
                                                                <th width="15%" class="text-center">Sender Contact</th>
                                                                <th width="15%" class="text-center">Receiver Branch</th>
                                                                <th width="15%" class="text-center">Net Amount</th>
                                                                <th width="15%" class="text-center">Delivery Type</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="show_booking_parcel_list_for_assign">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </span>
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
            $('input[name="type"]').on('click', function(){
                var type   = $(this).val();
                if(type == 'receive'){
                    $("#type_receive").show(500);
                    $("#type_assign").hide(500);
                } else if(type == 'assign'){
                    $("#type_assign").show(500);
                    $("#type_receive").hide(500);
                }

                $("#view_total_receive_parcel").html(0);
                $("#total_receive_parcel").val('');
            });

            $('#checkAllReceive').click(function () {
                $('input:checkbox').prop('checked', this.checked);

                var checked_count   = $(".parcel_item").filter(':checked').length;
                $("#view_total_receive_parcel").html(checked_count);
                $("#total_receive_parcel").val(checked_count);
            });

            $(document).on("click change", ".parcel_item", function () {
                var checked_count   = $(".parcel_item").filter(':checked').length;
                $("#view_total_receive_parcel").html(checked_count);
                $("#total_receive_parcel").val(checked_count);
            });


            /** Assign Part **/
            $('input[name="destination_branch"]').on('click', function(){
                var destination_branch   = $(this).val();
                if(destination_branch == 'yes'){
                    $("#to_destination").show();
                    $("#to_warehouse").hide();
                    $("#assign_warehouse_id").val(0).change();
                    $("#assign_vehicle_id").val(0).change();
                    $("#from_warehouse_id").attr("onchange", "booking_parcel_list_assign('yes')").val(0).change();
                    $("#to_destination_branch_id").attr("onchange", "booking_parcel_list_assign('yes')").val(0).change();

                } else if(destination_branch == 'no'){
                    $("#to_destination").hide();
                    $("#to_warehouse").show();
                    $("#from_warehouse_id").attr("onchange", "booking_parcel_list_assign('no')").val(0).change();
                    $("#to_destination_branch_id").attr("onchange", "booking_parcel_list_assign('no')").val(0).change();
                    $("#assign_warehouse_id").val(0).change();
                    $("#assign_vehicle_id").val(0).change();

                }
                $("#view_total_assign_parcel").html(0);
                $("#total_assign_parcel").val('');
            });

            $('#checkAllAssign').click(function () {
                $('input:checkbox').prop('checked', this.checked);

                var checked_count   = $(".assign_parcel_item").filter(':checked').length;
                $("#view_total_assign_parcel").html(checked_count);
                $("#total_assign_parcel").val(checked_count);
            });

            $(document).on("click change", ".assign_parcel_item", function () {
                var checked_count   = $(".assign_parcel_item").filter(':checked').length;
                $("#view_total_assign_parcel").html(checked_count);
                $("#total_assign_parcel").val(checked_count);
            });

            $("#assign_warehouse_id").on("change", function () {
               var warehouse_id     = $(this).val();
               var field_id         = $(this).attr("id");
               var warehouse_type     = $("#"+field_id+" option:selected").data('type');
               if(warehouse_id == 0) {
                   $("#assign_warehouse_type").val('');
               }else{
                   $("#assign_warehouse_type").val(warehouse_type);
               }
            });


            /** Parcel Reject */
            $(document).on("click", ".rejectParcel", function () {

                var check = confirm("Are you sure reject this parcel?");
                if(check != true) {
                    return false;
                }
                var booking_id  = $(this).data("parcel_id");
                var field_id    = $(this).attr('id');

                if(booking_id == "") {
                    toastr.error("Something went wrong, please try again!");
                    return false;
                }
                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "json",
                    data      : {
                        booking_id  : booking_id,
                        _token  : "{{ csrf_token() }}"
                    },
                    url       : "{{ route('warehouse.operationBookingParcel.rejectParcelFromWarehouse') }}",
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    success   : function(response){
                        console.log(response);
                        if(response.success == true){
                            toastr.success('Parcel rejected successfully!');
                            $("#"+field_id).parents('tr').remove();
                        }else{
                            toastr.success('Parcel reject failed!');
                        }
                    }
                });
            });
        }

        function booking_parcel_list_receive(){
            $('#checkAllReceive').prop('checked', false);
            $("#show_booking_parcel_list_for_receive").html(`<tr> <td colspan="7" style="text-align: center;">Loading </td></tr>`);
            var vehicle_id  = $("#receive_vehicle_id option:selected").val();
            if(vehicle_id == 0){
                toastr.error("Please Select Vehicle");
                $("#show_booking_parcel_list_for_receive").html('');
                return false;
            }
            $.ajax({
                cache     : false,
                dataType  : "json",
                type      : "POST",
                data      : {
                    vehicle_id  : vehicle_id,
                    _token      : "{{ csrf_token() }}"
                },
                url       : "{{ route('warehouse.operationBookingParcel.getParcelListForVehicleToWarehouseReceive') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_booking_parcel_list_for_receive").html(response);
                }
            });
        }


        function booking_parcel_list_assign(value){
            $('#checkAllAssign').prop('checked', false);
            $("#show_booking_parcel_list_for_assign").html(`<tr><td colspan="6" style="text-align: center;"> Loading... </td></tr>`);

            var warehouse_id  = $("#from_warehouse_id").val();
            var receiver_branch_id  = $("#to_destination_branch_id option:selected").val();

            if(value == 'yes') {
                if(receiver_branch_id == 0){
                    toastr.error("Please Select Destination Branch");
                    $("#show_booking_parcel_list_for_assign").html('');
                    return false;
                }
            }else{
                receiver_branch_id = '';
            }

            $.ajax({
                cache     : false,
                dataType  : "json",
                type      : "POST",
                data      : {
                    warehouse_id  : warehouse_id,
                    receiver_branch_id : receiver_branch_id,
                    _token          : "{{ csrf_token() }}"
                },
                url       : "{{ route('warehouse.operationBookingParcel.getParcelListForWarehouseToVehicleWarehouseAssign') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_booking_parcel_list_for_assign").html(response);
                }
            });
        }


        /** Receive Warehouse */
        function receive_submit_data() {
            event.preventDefault();

            var vehicle_id = $('#receive_vehicle_id').val();
            if (vehicle_id == 0) {
                toastr.error('Please Select Vehicle');
                $('#receive_vehicle_id').css('border-color', 'red');
                return false
            } else {
                $('#receive_vehicle_id').css('border-color', '#ced4da');
            }

            var warehouse_id = $('#receive_warehouse_id').val();
            if (warehouse_id == 0) {
                toastr.error('Please Select Warehouse');
                $('#receive_warehouse_id').css('border-color', 'red');
                return false
            } else {
                $('#receive_warehouse_id').css('border-color', '#ced4da');
            }

            var parcel_item = $("#total_receive_parcel").val();
            if (parcel_item == 0) {
                toastr.error('Please Assign minimum 1 parcel!');
                return false
            } else {
                $('#total_receive_parcel').css('border-color', '#ced4da');
            }

            $.ajax({

                url: "{{ route('warehouse.operationBookingParcel.confirmWarehouseReceived') }}",
                type: "POST",
                dataType: "json",
                data: $('#myReceiveForm').serialize(),
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },

                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Warehouse Parcel Receive Successfully');
                        window.location = "{{ route('warehouse.operationBookingParcel.bookingParcelOperation') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {
                        toastr.error('Warehouse Parcel Receive Failed');
                        var getError = data.errors;
                        console.log(data.errors);
                        //                             alert(data.errors.sender_name);
                        var message = "";

                        if (getError.parcel_item) {
                            message = getError.parcel_item;
                            toastr.error(message);
                        }
                        if (getError.vehicle_id) {
                            message = getError.vehicle_id[0];
                            toastr.error(message);
                        }
                        if (getError.warehouse_id) {
                            message = getError.warehouse_id[0];
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

        /** Assign Vehicle Warehouse */
        function assign_submit_data() {
            event.preventDefault();
            var destination_branch = $('input[name="destination_branch"]:checked').val();
            var warehouse = $('#assign_warehouse_id');
            var warehouse_id = warehouse.val();
            var vehicle_id = $('#assign_vehicle_id').val();

            if(destination_branch == "yes") {
                if (vehicle_id == 0) {
                    toastr.error('Please Select Assign Vehicle');
                    $('#assign_vehicle_id').css('border-color', 'red');
                    return false
                } else {
                    $('#assign_vehicle_id').css('border-color', '#ced4da');
                }
            }
            else{
                if (vehicle_id == 0) {
                    toastr.error('Please Select Assign Vehicle');
                    $('#assign_vehicle_id').css('border-color', 'red');
                    return false
                } else {
                    $('#assign_vehicle_id').css('border-color', '#ced4da');
                }
                if (warehouse_id == 0) {
                    toastr.error('Please Select Assign Warehouse');
                    $('#assign_warehouse_id').css('border-color', 'red');
                    return false
                } else {
                    $('#assign_warehouse_id').css('border-color', '#ced4da');
                }
            }

            var parcel_item = $("#total_assign_parcel").val();
            if (parcel_item == 0) {
                toastr.error('Please Assign minimum 1 parcel!');
                return false
            } else {
                $('#total_assign_parcel').css('border-color', '#ced4da');
            }

            $.ajax({
                url: "{{ route('warehouse.operationBookingParcel.confirmAssignVehicleOrWarehouse') }}",
                type: "POST",
                dataType: "json",
                data: $('#myAssignForm').serialize(),
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },

                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Parcel Assign Successfully');
                        window.location = "{{ route('warehouse.operationBookingParcel.bookingParcelOperation') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {
                        toastr.error('Parcel Assign Failed');
                        var getError = data.errors;
                        console.log(data.errors);
                        //                             alert(data.errors.sender_name);
                        var message = "";

                        if (getError.parcel_item) {
                            message = getError.parcel_item;
                            toastr.error(message);
                        }
                        if (getError.assign_vehicle_id) {
                            message = getError.vehicle_id[0];
                            toastr.error(message);
                        }
                        if (getError.assign_warehouse_id) {
                            message = getError.warehouse_id[0];
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

    </script>
@endpush
