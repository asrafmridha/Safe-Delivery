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
                    <h1 class="m-0 text-dark">Destination Branch Parcel Receive</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Destination Branch Parcel Receive</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" id="myReceiveForm" action="{{ route('branch.bookingParcel.confirmDestinationReceivedParcel') }}" method="POST" enctype="multipart/form-data" onsubmit="return receive_submit_data()">
                        @csrf
                        <input type="hidden" name="total_receive_parcel" id="total_receive_parcel" value="0" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <table class="table ">
                                            <tr>
                                                <th >Vehicle</th>
                                                <td style="width: 5%"> : </td>
                                                <td>
                                                    <select name="vehicle_id" id="vehicle_id" onchange="parcel_list()" class="form-control select2" style="width: 100%" >
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
                                                    <select name="booking_branch_id" id="booking_branch_id" onchange="parcel_list()" class="form-control select2" style="width: 100%" >
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

            $('#checkAll').click(function () {
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
                    url       : "{{ route('branch.bookingParcel.rejectParcelFromDestination') }}",
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

        function parcel_list(){
            var booking_branch_id  = $("#booking_branch_id option:selected").val();
            $("#show_booking_parcel_list").html('<tr><td colspan="6" style="text-align: center;"> Loading... </td></tr>');
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
                url       : "{{ route('branch.bookingParcel.getParcelListForDestinationBranchReceive') }}",
                error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                success   : function(response){
                    $("#show_booking_parcel_list").html(response);
                }
            });
        }


        /** Receive Parcel */
        function receive_submit_data() {
            event.preventDefault();

            var vehicle_id = $('#vehicle_id').val();
            if (vehicle_id == 0) {
                toastr.error('Please Select Vehicle');
                $('#vehicle_id').css('border-color', 'red');
                return false
            } else {
                $('#vehicle_id').css('border-color', '#ced4da');
            }

            var parcel_item = $("#total_receive_parcel").val();
            if (parcel_item == 0) {
                toastr.error('Please Assign minimum 1 parcel!');
                return false
            } else {
                $('#total_receive_parcel').css('border-color', '#ced4da');
            }

            $.ajax({

                url: "{{ route('branch.bookingParcel.confirmDestinationReceivedParcel') }}",
                type: "POST",
                dataType: "json",
                data: $('#myReceiveForm').serialize(),
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },

                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Parcel Receive Successfully');
                        window.location = "{{ route('branch.bookingParcel.receiveBookingParcel') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {
                        toastr.error('Parcel Receive Failed');
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
                        if (getError.total_receive_parcel) {
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
