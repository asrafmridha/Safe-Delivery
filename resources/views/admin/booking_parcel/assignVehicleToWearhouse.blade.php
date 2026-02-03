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
                    <h1 class="m-0 text-dark">Parcel Assign Vehicle To Wearhouse</h1>
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
                    <form role="form" action="{{ route('admin.operationBookingParcel.confirmWarehouseAssign') }}" id="myForm" method="POST" enctype="multipart/form-data" onsubmit="return submit_data()">
                        @csrf
                        <input type="hidden" name="total_assign_parcel" id="total_assign_parcel" value="0" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <table class="table ">
                                            <tr>
                                                <th >Vehicle</th>
                                                <td style="width: 5%"> : </td>
                                                <td>
                                                    <select name="vehicle_id" id="vehicle_id" onchange="booking_parcel_list()" class="form-control select2" style="width: 100%" >
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
                                                <th >Booking Branch</th>
                                                <td style="width: 5%"> : </td>
                                                <td>
                                                    <select name="booking_branch_id" id="booking_branch_id" onchange="booking_parcel_list()" class="form-control select2" style="width: 100%" >
                                                        <option value="0" >Select Branch </option>
                                                        <option value="all" > All Branch </option>
                                                        <?php
                                                        if(count($branches) > 0) {
                                                            foreach ($branches as $branche) {
                                                                echo '<option value="'.$branche->id.'">'.$branche->name.'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th >Assign Warehouse</th>
                                                <td style="width: 5%"> : </td>
                                                <td>
                                                    <select name="warehouse_id" id="warehouse_id" class="form-control select2" style="width: 100%" >
                                                        <option value="0" >Select Warehouse </option>
                                                        <?php
                                                        if(count($warehouses) > 0) {
                                                            foreach ($warehouses as $warehouse) {
                                                                echo '<option value="'.$warehouse->id.'" data-type="'.$warehouse->type.'">'.$warehouse->name.'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" name="warehouse_type" id="warehouse_type" value="">
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
                                                                <input type="checkbox"  id="checkAll" >
                                                                &nbsp; All
                                                            </th>
                                                            <th width="20%" class="text-center">Parcel No </th>
                                                            <th width="15%" class="text-center">Sender Contact</th>
                                                            <th width="15%" class="text-center">Receiver Branch</th>
                                                            <th width="15%" class="text-center">Net Amount</th>
                                                            <th width="15%" class="text-center">Delivery Type</th>
                                                            <th width="8%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="show_booking_parcel_list">
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
                    $("#warehouse_type").val('');
                } else{
                    $("#warehouse_type").val(warehouse.data('type'));
                }

            });

            $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
                var checked_count   = $(".parcel_item").filter(':checked').length;
                $("#view_total_assign_parcel").html(checked_count);
                $("#total_assign_parcel").val(checked_count);
            });

            $(document).on("click change", ".parcel_item", function () {
                var checked_count   = $(".parcel_item").filter(':checked').length;
                $("#view_total_assign_parcel").html(checked_count);
                $("#total_assign_parcel").val(checked_count);
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
                    url       : "{{ route('admin.operationBookingParcel.rejectParcelFromVehicle') }}",
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

        function booking_parcel_list(){
            var booking_branch_id  = $("#booking_branch_id option:selected").val();
            if(booking_branch_id == 0){
                toastr.error("Please Select Booking Branch");
                $("#show_booking_parcel_list").html('');
                return false;
            }
            var vehicle_id  = $("#vehicle_id option:selected").val();
            if(vehicle_id == 0){
                toastr.error("Please Select Vehicle");
                $("#show_booking_parcel_list").html('');
                return false;
            }
            $.ajax({
                cache     : false,
                dataType      : "json",
                type      : "POST",
                data      : {
                    booking_branch_id  : booking_branch_id,
                    vehicle_id : vehicle_id,
                    _token  : "{{ csrf_token() }}"
                },
                url       : "{{ route('admin.operationBookingParcel.getParcelListForVehicleToWareHouseAssign') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_booking_parcel_list").html(response);
                }
            });
        }

        /** Assign Parcel Branch to Warehouse */
        function submit_data() {
            event.preventDefault();

            var vehicle_id = $('#vehicle_id').val();
            if (vehicle_id == 0) {
                toastr.error('Please Select Vehicle');
                $('#vehicle_id').css('border-color', 'red');
                return false
            } else {
                $('#vehicle_id').css('border-color', '#ced4da');
            }

            var warehouse_id = $('#warehouse_id').val();
            if (warehouse_id == 0) {
                toastr.error('Please Select Warehouse');
                $('#warehouse_id').css('border-color', 'red');
                return false
            } else {
                $('#warehouse_id').css('border-color', '#ced4da');
            }

            var parcel_item = $("#total_assign_parcel").val();
            if (parcel_item == 0) {
                toastr.error('Please Assign minimum 1 parcel!');
                return false
            } else {
                $('#total_assign_parcel').css('border-color', '#ced4da');
            }

            $.ajax({

                url: "{{ route('admin.operationBookingParcel.confirmWarehouseAssign') }}",
                type: "POST",
                dataType: "json",
                data: $('#myForm').serialize(),
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },

                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Warehouse assign Successfully');
                        window.location = "{{ route('admin.operationBookingParcel.assignVehicleToWarehouse') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {
                        toastr.error('Warehouse assign Failed');
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

    </script>
@endpush
