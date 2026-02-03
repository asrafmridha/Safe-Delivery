@extends('layouts.branch_layout.branch_layout')
@push('style_css')
    <style>
        .form-control {
            display: block;
            width: 100%;
            height: calc(1.6rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            box-shadow: inset 0 0 0 transparent;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-group {
            margin-bottom: .3rem;
        }

        .table td,
        .table th {
            padding: .30rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        #rider{
           pointer-events: none;
           opacity: 0.5;
        }

        .cod_area {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Booking Parcel</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('branch.bookingParcel.create') }}">Booking
                                Parcel</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content" id="preview_file">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Booking New Parcel </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card card-primary">
                                <form role="form" enctype="multipart/form-data" id="myForm"
                                    onsubmit="return submit_data();">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="booking_parcel_type">Parcel Booking Type <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" name="booking_parcel_type"
                                                        id="booking_parcel_type">
                                                        {{--<option value="general">General</option>--}}
                                                        <option value="cash">Cash</option>
                                                        <option value="to_pay">To Pay</option>
                                                        <option value="credit">Credit</option>
                                                        <option value="condition">Condition</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="merchant_id">Merchant</label>
                                                    <select class="form-control select2" name="merchant_id"
                                                        id="merchant_id" onchange="select_merchant_data()">
                                                        <option value="0">Select Merchant</option>
                                                        @foreach ($merchants as $merchant)
                                                            <option value="{{ $merchant->id }}"
                                                                data-address="{{ $merchant->address }}"
                                                                data-contact_number="{{ $merchant->contact_number }}">
                                                                {{ $merchant->name.'-'.$merchant->m_id }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="pickup">Pickup</label>
                                                    <select class="form-control select2" name="pickup"
                                                        id="pickup" onchange="check_pickup(this.value)">
                                                        <option value="no">No</option>
                                                        <option value="yes">Yes</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="rider_id">Rider</label>
                                                    <div id="rider">
                                                        <select class="form-control select2" name="rider_id"
                                                            id="rider_id">
                                                            <option value="0">Select Rider</option>
                                                            @foreach ($riders as $rider)
                                                                <option value="{{ $rider->id }}">{{ $rider->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <fieldset>
                                                    <legend>Sender Information</legend>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sender_phone">Contact Number <span class="text-danger">*</span></label>
                                                                <input type="text" name="sender_phone" id="sender_phone"
                                                                       value="{{ old('sender_phone') }}" class="form-control"
                                                                       placeholder="Contact Number">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="sender_address">Address <span class="text-danger">*</span></label>
                                                                <input type="text" name="sender_address" id="sender_address"
                                                                    value="{{ old('sender_address') }}" class="form-control"
                                                                    placeholder="Sender Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sender_name">Name</label>
                                                                <input type="text" name="sender_name" id="sender_name"
                                                                       value="{{ old('sender_name') }}" class="form-control"
                                                                       placeholder="Sender Name">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="sender_nid">National ID</label>
                                                                <input type="text" name="sender_nid" id="sender_nid"
                                                                    value="{{ old('sender_nid') }}" class="form-control"
                                                                    placeholder="Sender National ID">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="sender_division_id">Division</label>
                                                                <select class="form-control select2" name="sender_division_id"
                                                                    id="sender_division_id">
                                                                    <option value="0">Select Division</option>
                                                                    @foreach ($divisions as $division)
                                                                        <option value="{{ $division->id }}">{{ $division->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="sender_district_id">District</label>
                                                                <select class="form-control select2" name="sender_district_id"
                                                                    id="sender_district_id">
                                                                    <option value="0">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="sender_thana_id">Upazila/Thana</label>
                                                                <select class="form-control select2" name="sender_thana_id"
                                                                    id="sender_thana_id">
                                                                    <option value="0">Select Upazila/Thana</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="sender_area_id">Area</label>
                                                                <select class="form-control select2" name="sender_area_id"
                                                                    id="sender_area_id">
                                                                    <option value="0">Select Area</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-12">
                                                <fieldset>
                                                    <legend>Receiver Information</legend>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="receiver_phone">Contact Number <span class="text-danger">*</span></label>
                                                                <input type="text" name="receiver_phone" id="receiver_phone"
                                                                       value="{{ old('receiver_phone') }}" class="form-control"
                                                                       placeholder="Receiver Number">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="receiver_name">Name</label>
                                                                <input type="text" name="receiver_name" id="receiver_name"
                                                                       value="{{ old('receiver_name') }}" class="form-control"
                                                                       placeholder="Receiver Name">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="receiver_address">Address <span class="text-danger">*</span></label>
                                                                <input type="text" name="receiver_address" id="receiver_address"
                                                                    value="{{ old('receiver_address') }}" class="form-control"
                                                                    placeholder="Receiver Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="receiver_division_id">Division</label>
                                                                <select class="form-control select2" name="receiver_division_id" style="width: 100%"
                                                                    id="receiver_division_id">
                                                                    <option value="0">Select Division</option>
                                                                    @foreach ($divisions as $division)
                                                                        <option value="{{ $division->id }}">{{ $division->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="receiver_district_id">District</label>
                                                                <select class="form-control select2" name="receiver_district_id"
                                                                    id="receiver_district_id">
                                                                    <option value="0">Select District</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="receiver_thana_id">Upazila/Thana</label>
                                                                <select class="form-control select2" name="receiver_thana_id"
                                                                    id="receiver_thana_id">
                                                                    <option value="0">Select Upazila/Thana</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="receiver_area_id">Area</label>
                                                                <select class="form-control select2" name="receiver_area_id"
                                                                    id="receiver_area_id">
                                                                    <option value="0">Select Area</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="col-md-12">
                                                <fieldset>
                                                    <legend>Destination Branch & Parcel Type</legend>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="receiver_branch_id">Destination Branch <span class="text-danger">*</span></label>
                                                                <select class="form-control select2" name="receiver_branch_id"
                                                                    id="receiver_branch_id" onchange="check_branch_services(this)">
                                                                    <option value="0">Select Branch</option>
                                                                    {{-- @php ($branche->id == 0)?'disabled':'' @endphp --}}
                                                                    @foreach ($branches as $branch)
                                                                        @if ($branch->id != auth()->guard('branch')->user()->branch_id )
                                                                            <option value="{{ $branch->id }}" data-condition="{{ $branch->cod_status }}" data-general="{{ $branch->general_status }}">{{ $branch->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="delivery_type">Delivery Type <span class="text-danger">*</span></label>
                                                                <div id="d_type">
                                                                    <select class="form-control select2" name="delivery_type"
                                                                        id="delivery_type">
                                                                        <option value="0">Select Type</option>
                                                                        <option value="od">Office Delivery (OD)</option>
                                                                        <option value="tod">Transit Office Delivery (TOD)</option>
                                                                        <option value="hd">Home Delivery (HD)</option>
                                                                        <option value="thd">Transit Home Delivery (THD)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="note">Note</label>
                                                                <input type="text" name="note" id="note" value="{{ old('note') }}"
                                                                    class="form-control" placeholder="Enter note">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-12">
                                                <fieldset>
                                                    <legend>Item Information</legend>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="category_id">Item Category</label>
                                                                <select class="form-control select2" name="category_id"
                                                                    id="category_id">
                                                                    <option value="0">Select Item Category</option>
                                                                    <option value="others">Manual Category</option>
                                                                    @foreach ($categories as $categorie)
                                                                        <option value="{{ $categorie->id }}">{{ $categorie->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group" id="dynamic_item">
                                                                <label for="item_id">Item</label>
                                                                <select class="form-control select2" name="item_id" id="item_id">
                                                                    <option value="0">Select Item</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group" style="display: none;" id="manual_item">
                                                                <label for="item_name">Item Name</label>
                                                                <input type="text" name="item_name" id="item_name"
                                                                value="" class="form-control"
                                                                placeholder="Item Name">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="unit_name">Unit</label>
                                                                <div id="unit_div">
                                                                    <select class="form-control select2" name="unit_name"
                                                                        id="unit_name">
                                                                        <option value="0">Select Unit</option>
                                                                        @foreach ($units as $unit)
                                                                            <option value="{{ $unit->id }}">{{ $unit->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="unit_price">Unit Price</label>
                                                                <input type="text" name="unit_price" id="unit_price"
                                                                    value="" class="form-control"
                                                                    placeholder="Unite Price">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label for="quantity">Quantity</label>
                                                                <input type="text" name="quantity" id="quantity"
                                                                    value="" class="form-control"
                                                                    placeholder="quantity">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1">
                                                            <div class="form-group" style="margin-top: 4px">
                                                                <label for="add_button">&nbsp;</label>
                                                                <button type="button" name="add_button" id="add_button"
                                                                    class="btn btn-sm btn-info btn-block">Add
                                                                    Item</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12" id="item_list" class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>SL. No.</th>
                                                            <th>Category Name</th>
                                                            <th>Item Name</th>
                                                            <th>Unit</th>
                                                            <th>Unit Price</th>
                                                            <th>Quantity</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr >
                                                            <td colspan="3">
                                                                <table class="table cod_area">

                                                                    <tr>
                                                                        <th style="width: 30%">Cod Percent </th>
                                                                        <td style="width: 5%"> : </td>
                                                                        <td style="width: 65%">
                                                                            <span id="view_cod_percent">
                                                                                @php
                                                                                    /* $cod_percent = ($merchant->cod_charge)? $merchant->cod_charge :0;
                                                                                    echo $cod_percent; */
                                                                                @endphp
                                                                               1% (COD Charge 1% for any amount.)
                                                                            </span>
                                                                        <input type="hidden" id="confirm_cod_percent" name="cod_percent" value="2">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Collection Amount</th>
                                                                        <td> : </td>
                                                                        <td>
                                                                            <input type="text" name="collection_amount" id="collection_amount" placeholder="Enter Collection Amount" onkeyUp="calculate_cod_charge(this.value)" class="form-control">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Cod Charge</th>
                                                                        <td> : </td>
                                                                        <td>
                                                                            <span id="view_cod_charge">0.00</span>
                                                                            <input type="hidden" id="confirm_cod_charge" name="cod_amount" value="0">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td colspan="3"></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style_css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function() {

            $("#booking_parcel_type").on('change', function () {
                var type    = $(this).val();
                if('condition' == type) {
                    $(".cod_area").css("display", "block");
                }else{
                    $(".cod_area").css("display", "none");
                }

                if('cash' != type) {
                    $("#paid_amount").attr("readonly", true).val(0);
                }else{
                    $("#paid_amount").attr("readonly", false).val("");
                }
            });

            $('#sender_division_id').on('change', function() {
                var division_id = $("#sender_division_id option:selected").val();
                $("#sender_district_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        division_id: division_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('district.districtByDivision') }}",
                    success: function(response) {
                        $("#sender_district_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#sender_district_id').on('change', function() {
                var district_id = $("#sender_district_id option:selected").val();
                $("#sender_thana_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        district_id: district_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('upazila.districtOption') }}",
                    success: function(response) {
                        $("#sender_thana_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#sender_thana_id').on('change', function() {
                var upazila_id = $("#sender_thana_id option:selected").val();
                $("#sender_area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        upazila_id: upazila_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('area.areaOption') }}",
                    success: function(response) {
                        $("#sender_area_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#receiver_division_id').on('change', function() {
                var division_id = $("#receiver_division_id option:selected").val();
                $("#receiver_district_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        division_id: division_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('district.districtByDivision') }}",
                    success: function(response) {
                        $("#receiver_district_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#receiver_district_id').on('change', function() {
                var district_id = $("#receiver_district_id option:selected").val();
                $("#receiver_thana_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        district_id: district_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('upazila.districtOption') }}",
                    success: function(response) {
                        $("#receiver_thana_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#receiver_thana_id').on('change', function() {
                var upazila_id = $("#receiver_thana_id option:selected").val();
                $("#receiver_area_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        upazila_id: upazila_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('area.areaOption') }}",
                    success: function(response) {
                        $("#receiver_area_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#category_id').on('change', function() {
                var category_id = $("#category_id option:selected").val();
                if(category_id == 'others'){
                    $('#manual_item').show();
                    $('#dynamic_item').hide();

                    if (unit_name == 0 || unit_name == '') {
                        $("#unit_name").css('border-color', 'red');
                        return false;
                    } else {
                        $("#unit_name").css('border-color', '#ced4da');
                    }
                    $("#unit_price").attr('readonly', false);
                    $("#unit_div").css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                    return false;
                }else{
                    $('#dynamic_item').show();
                    $('#manual_item').hide();

                    $("#unit_price").val("").attr('readonly', true);
                    $("#unit_name").val("0").change();
                    $("#item_name").val("");
                    $("#quantity").val("");
                    $("#unit_div").css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                }
                $("#item_id").val(0).change().attr('disabled', true);
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        item_cat_id: category_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('ItemCategory.getItemByCategory') }}",
                    success: function(response) {
                        $("#item_id").html(response.option).attr('disabled', false);
                    }
                })
            });

            $('#add_button').on('click', function() {
                var delivery_type = $("#delivery_type").val();
                var pickup        = $("#pickup").val();
                var booking_parcel_type        = $("#booking_parcel_type").val();
                if (delivery_type == 0) {
                    toastr.error('Please Select Delivery Type');
                    $("#select2-delivery_type-container").parent().css('border-color', 'red');
                    return false;
                } else {
                    $("#select2-delivery_type-container").parent().css('border-color', '#ced4da');
                }

                var item_id = $("#item_id option:selected").val();

                var category_id = $("#category_id option:selected").val();
                var item_name = $("#item_name").val();
                var unit_name = $("#unit_name option:selected").text();
                var unit_price = $("#unit_price").val();
                if(category_id == 'others'){
                    if (item_name == '') {
                        $("#item_name").css('border-color', 'red');
                        return false;
                    } else {
                        $("#item_name").css('border-color', '#ced4da');
                    }

                    if (unit_price == 0 || unit_price == '') {
                        $("#unit_price").css('border-color', 'red');
                        return false;
                    } else {
                        $("#unit_price").css('border-color', '#ced4da');
                    }
                }else{
                    if (item_id == 0) {
                        $("#select2-item_id-container").parent().css('border-color', 'red');
                        return false;
                    } else {
                        $("#select2-item_id-container").parent().css('border-color', '#ced4da');
                    }
                }

                var quantity = $("#quantity").val();
                if (quantity == 0 || quantity == '') {
                    $("#quantity").css('border-color', 'red');
                    return false;
                } else {
                    $("#quantity").css('border-color', '#ced4da');
                }
                $.ajax({
                    cache: false,
                    type: "POST",
                    //dataType: "JSON",
                    data: {
                        category_id: category_id,
                        item_id: item_id,
                        item_name: item_name,
                        unit_name: unit_name,
                        unit_price: unit_price,
                        quantity: quantity,
                        delivery_type: delivery_type,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('branch.item.addCartItem') }}",
                    success: function(response) {
                        $("#item_list").html(response).attr('disabled', false);
                        $("#category_id").val("0").change();
                        $("#item_id").val("0").change();
                        $("#unit_name").val("0").change();
                        $("#unit_price").val('');
                        $("#item_name").val("");
                        $("#quantity").val("");

                        $("#d_type").css({
                            'pointer-events': 'none',
                            'opacity': '0.5'
                        });

                        if("yes" == pickup) {
                            $("#pickup_charge").attr("readonly", false);
                        }

                        if("condition" == booking_parcel_type) {
                            $(".cod_area").css("display", "block");
                        }

                        if('cash' != booking_parcel_type) {
                            $("#paid_amount").attr("readonly", true).val(0);
                        }else{
                            $("#paid_amount").attr("readonly", false).val("");
                        }
                    }
                })
            });


            $(document).on('click', '.remove_cart', function() {
                var id = $(this).data('id');
                $.ajax({
                    cache: false,
                    type: "POST",
                    //dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: "{{ route('branch.item.removeCartItem') }}",
                    success: function(response) {
                        $("#item_list").html(response).attr('disabled', false);
                    }
                })
            });
        }

        function discount_percent_calculate(value) {

            $('#discount_percent').css('border-color', '#ced4da');
            $('#pickup_charge').val("");
            $('#total_payable').val("");

            var grand_amount = returnNumber($('#grand_amount').val());
            var paid_amount = returnNumber($('#paid_amount').val());
            paid_amount = paid_amount || 0;
            if (paid_amount != "" && paid_amount != 0) {
                $('#paid_amount').val("");
            }

            if (value != "" && value != 0) {
                var percent_amount = returnNumber((returnNumber(grand_amount) * returnNumber(value))/ 100);
                var net_amount = returnNumber(grand_amount) - returnNumber(percent_amount);

                if(percent_amount <= grand_amount) {
                    $('#discount_amount').val(returnNumber(percent_amount).toFixed(4)).attr("readonly", true).css("pointer-events", "none");
                    $('#net_amount').val(returnNumber(net_amount).toFixed(4));
                    $('#due_amount').val(returnNumber(net_amount).toFixed(4));
                }else{
                    $('#discount_amount').val(returnNumber(percent_amount).toFixed(4)).attr("readonly", true).css("pointer-events", "none");
                    $('#net_amount').val("");
                    $('#due_amount').val("");
                    $('#discount_percent').css('border-color', 'red');
                }

            } else {
                $("#discount_amount").val("").attr("readonly", false).css("pointer-events", "auto");
                $('#net_amount').val(returnNumber(grand_amount).toFixed(4));
                $('#due_amount').val(returnNumber(grand_amount).toFixed(4));
            }

        }

        function discount_calculate(value) {
            $('#discount_amount').css('border-color', '#ced4da');
            $('#pickup_charge').val("");
            $('#total_payable').val("");

            var grand_amount = returnNumber($('#grand_amount').val());
            if (value <= grand_amount) {
                var paid_amount = returnNumber($('#paid_amount').val());
                paid_amount = paid_amount || 0;

                if(paid_amount != "" && paid_amount != 0){
                    $('#paid_amount').val("");
                }

                if ((value != "" && value != 0)&& (grand_amount != '' && grand_amount != 0)) {
                    var net_amount = returnNumber(grand_amount) - returnNumber(value);
                    var percentage = returnNumber((returnNumber(value) / returnNumber(grand_amount)) * 100);
                    $("#discount_percent").val(returnNumber(percentage).toFixed(4)).attr("readonly", true).css("pointer-events", "none");
                    $('#net_amount').val(returnNumber(net_amount).toFixed(4));
                    $('#due_amount').val(returnNumber(net_amount).toFixed(4));
                } else {
                    $("#discount_percent").val("").attr("readonly", false).css("pointer-events", "auto");
                    $('#net_amount').val(returnNumber(grand_amount).toFixed(4));
                    $('#due_amount').val(returnNumber(grand_amount).toFixed(4));
                }
            } else {
                $("#discount_percent").val("").attr("readonly", false).css("pointer-events", "auto");
                $('#net_amount').val("");
                $('#due_amount').val("");
                $('#discount_amount').css('border-color', 'red');
            }

        }

        function pickup_charge_calculate(value){
            var net_amount = returnNumber($('#net_amount').val());
            var paid_amount = returnNumber($('#paid_amount').val());
            paid_amount = paid_amount || 0;

            if(paid_amount != "" && paid_amount != 0){
                $('#paid_amount').val("");
            }
            if(value != "" && value != 0) {
                var total_amount = returnNumber(net_amount) + returnNumber(value);
                $('#total_payable').val(returnNumber(total_amount).toFixed(4));
                $('#due_amount').val(returnNumber(total_amount).toFixed(4));
            }else{
                $('#total_payable').val("");
                $('#due_amount').val(returnNumber(net_amount).toFixed(4));
            }
        }

        function paid_calculate(value) {
            $('#paid_amount').css('border-color', '#ced4da');
            var pickup_charge = returnNumber($('#pickup_charge').val());
            pickup_charge = pickup_charge || 0;
            var net_amount = returnNumber($('#net_amount').val()) + returnNumber(pickup_charge);
            if (value <= net_amount) {
                if (value != '' && (net_amount != '' && net_amount != 0)) {
                    var due_amount = returnNumber(net_amount) - returnNumber(value);
                    $('#due_amount').val(returnNumber(due_amount).toFixed(4));
                } else {
                    $('#due_amount').val(returnNumber(net_amount).toFixed(4));
                }
            } else {
                $('#due_amount').val(returnNumber(0).toFixed(4));
                $('#paid_amount').css('border-color', 'red');
            }
        }

        function submit_data() {
            event.preventDefault();

            var sdistrict_name = $('#sender_district_id option:selected').text();
            var rdistrict_name = $('#receiver_district_id option:selected').text();
            //alert([sdistrict_name, rdistrict_name]); return false;

            var booking_parcel_type = $('#booking_parcel_type').val();
            if (booking_parcel_type == '') {
                toastr.error('Please Select Booking Type');
                $('#booking_parcel_type').css('border-color', 'red');
                return false
            } else {
                $('#booking_parcel_type').css('border-color', '#ced4da');
            }
            var sender_phone = $('#sender_phone').val();
            if (sender_phone == '') {
                toastr.error('Please Enter Sender Phone Number');
                $('#sender_phone').css('border-color', 'red');
                return false
            } else {
                $('#sender_phone').css('border-color', '#ced4da');
            }

            var sender_address = $('#sender_address').val();
            if (sender_address == '') {
                toastr.error('Please Enter Sender Address');
                $('#sender_address').css('border-color', 'red');
                return false
            } else {
                $('#sender_address').css('border-color', '#ced4da');
            }

            var receiver_phone = $('#receiver_phone').val();
            if (receiver_phone == '') {
                toastr.error('Please Enter Receiver Phone Number');
                $('#receiver_phone').css('border-color', 'red');
                return false
            } else {
                $('#receiver_phone').css('border-color', '#ced4da');
            }

            var receiver_address = $('#receiver_address').val();
            if (receiver_address == '') {
                toastr.error('Please Enter Receiver Address');
                $('#receiver_address').css('border-color', 'red');
                return false
            } else {
                $('#receiver_address').css('border-color', '#ced4da');
            }

            var receiver_branch_id = $('#receiver_branch_id option:selected').val();
            if (receiver_branch_id == 0) {
                toastr.error('Please Select Destination Branch');
                return false;
            }

            var delivery_type = $('#delivery_type option:selected').val();
            if (delivery_type == 0) {
                toastr.error('Please Select Delivery Type');
                return false;
            }

            var total_amount = $('#total_amount').val();
            if (total_amount == 0) {
                toastr.error('Please Add Item');
                $('#total_amount').css('border-color', 'red');
                return false
            } else {
                $('#total_amount').css('border-color', '#ced4da');
            }

            var net_amount = returnNumber($('#net_amount').val());
            if (net_amount < 1 && net_amount == "") {
                toastr.error("Net amount can't be null or 0!");
                $('#net_amount').css('border-color', 'red');
                return false
            } else {
                $('#net_amount').css('border-color', '#ced4da');
            }

            var paid_amount = returnNumber($('#paid_amount').val());
            var pickup_charge_amnt = returnNumber($('#pickup_charge').val());
            pickup_charge_amnt  = pickup_charge_amnt || 0;

            var net_total_amount = returnNumber(net_amount) + returnNumber(pickup_charge_amnt);
            if (paid_amount > net_total_amount) {
                toastr.error("Paid amount can't be greater than Net Total Amount!");
                $('#paid_amount').css('border-color', 'red');
                return false
            } else {
                $('#paid_amount').css('border-color', '#ced4da');
            }

            var collection_amount = returnNumber($('#collection_amount').val());
            var booking_type      = $('#booking_parcel_type').val();
            if (collection_amount > 0 && booking_type != "condition") {
                toastr.error("Collection amount allowed only COD booking type. Please, change this booking type!");
                $('#collection_amount').css('border-color', 'red');
                return false
            }else if(collection_amount == "" && booking_type == "condition"){
                toastr.error("Collection amount can't null for this booking type!");
                $('#collection_amount').css('border-color', 'red');
                return false
            } else {
                $('#collection_amount').css('border-color', '#ced4da');
            }

            if("cash" == booking_type && (paid_amount == "" || paid_amount < net_total_amount))
            {
                toastr.error("Paid amount can't be null or less than net total amount!");
                $('#paid_amount').css('border-color', 'red');
                return false;
            }else{
                $('#paid_amount').css('border-color', '#ced4da');
            }

            var pickup      = $('#pickup').val();
            var pickup_charge = returnNumber($('#pickup_charge').val());
            if (pickup == "yes" && pickup_charge == "") {
                toastr.error("Pickup charge can't be null");
                $('#pickup_charge').css('border-color', 'red');
                return false
            } else {
                $('#pickup_charge').css('border-color', '#ced4da');
            }

            var rider = $('#rider_id').val();
            if (pickup == "yes" && rider == "0") {
                toastr.error("Please select rider!");
                $('#rider').css('border-color', 'red');
                return false
            } else {
                $('#rider').css('border-color', '#ced4da');
            }

            $.ajax({
                url: "{{ route('branch.bookingParcel.store') }}",
                type: "POST",
                dataType: "json",
                data: $('#myForm').serialize() + "&sdistrict_name="+sdistrict_name+"&rdistrict_name="+rdistrict_name,
                beforeSend: function() {
                    $('#msg').html('<span class="text-info">Loading response...</span>');
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Parcel Booking Successfully');
                        window.location = "{{ route('branch.bookingParcel.index') }}";
                        //$('#preview_file').html('');
                    } else if (data.success == false) {

                        var getError = data.errors;
                        console.log(data.errors);
                        //                             alert(data.errors.sender_name);
                        var message = "";

                        if (getError.booking_parcel_type) {
                            message = getError.booking_parcel_type[0];
                            toastr.error(message);
                        }
                        if (getError.sender_phone) {
                            message = getError.sender_phone[0];
                            toastr.error(message);
                        }
                        if (getError.sender_address) {
                            message = getError.sender_address[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_phone) {
                            message = getError.receiver_phone[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_address) {
                            message = getError.receiver_address[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_branch_id) {
                            message = getError.receiver_branch_id[0];
                            toastr.error(message);
                        }
                        if (getError.delivery_type) {
                            message = getError.delivery_type[0];
                            toastr.error(message);
                        }
                        if (getError.total_amount) {
                            message = getError.total_amount[0];
                            toastr.error(message);
                        }
                        if (getError.net_amount) {
                            message = getError.net_amount[0];
                            toastr.error(message);
                        }
                        //                             toastr.error(message);

                    }
                }
            });
        }

        function check_pickup(pickup){

            var net_amount = returnNumber($('#net_amount').val());
            if(pickup == 'yes'){
                $("#rider").css({
                    'pointer-events': 'auto',
                    'opacity': '1'
                });
                $("#pickup_charge").attr("readonly", false).val("");
                $('#total_payable').val("");
            }else{
                $("#rider").css({
                    'pointer-events': 'none',
                    'opacity': '0.5'
                });
                $("#rider_id").val("0").change();
                $("#pickup_charge").val("").attr("readonly", true);
                $('#total_payable').val("");
                $('#due_amount').val(net_amount);
            }
        }

        function check_branch_services(object){
            var id = $(object).attr('id');
            var condition   = $('#'+id+' option:selected').data('condition');
            var general     = $('#'+id+' option:selected').data('general');
            var service_type = $("#booking_parcel_type").val();

            if(condition == 0 && service_type == "condition") {
                toastr.error('This branch not allowed for COD Booking!');
                $(object).val("0").change();
                return false;
            }else if(general == 0 && service_type == "general") {
                toastr.error('This branch not allowed for General Booking!');
                $(object).val("0").change();
                return false;
            }else{
                return true;
            }
        }

        function select_merchant_data(){
            var merchant = $('#merchant_id option:selected');
            var id   = $(merchant).val();
            var merchant_name   = $(merchant).text().trim();
            var address   = $(merchant).data('address');
            var contact_number   = $(merchant).data('contact_number');
            if(id != 0){
                $('#sender_name').val(merchant_name).attr('readonly', true);
                $('#sender_address').val(address).attr('readonly', true);
                $('#sender_phone').val(contact_number).attr('readonly', true);
            }else{
                $('#sender_name').val('').attr('readonly', false);
                $('#sender_address').val('').attr('readonly', false);
                $('#sender_phone').val('').attr('readonly', false);
            }
        }

        function calculate_cod_charge(value){
            var value   = returnNumber(value);
            var cod_amount = 0;
//            if(value > 1000){
//                value = returnNumber(value - 1000);
//                cod_amount = returnNumber(((value/100)*1)+20);
//            }
//            else if(value == 0){
//                cod_amount = 0;
//            }
//            else{
//                cod_amount = 20;
//            }
            cod_amount = returnNumber((value/100)*1);
            // console.log(value,cod_amount);

            $('#view_cod_charge').html(returnNumber(cod_amount).toFixed(2));
            $('#confirm_cod_charge').val(returnNumber(cod_amount).toFixed(2));
        }

        function createForm() {
            let district_id = $('#district_id').val();
            if (district_id == '0') {
                toastr.error("Please Select District..");
                return false;
            }
            let upazila_id = $('#upazila_id').val();
            if (upazila_id == '0') {
                toastr.error("Please Select Upazila..");
                return false;
            }
        }

    </script>
@endpush
