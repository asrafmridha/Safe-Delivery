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

    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Booking Percel</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('branch.bookingParcel.create') }}">Booking
                                Percel</a></li>
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
                            <h3 class="card-title">Booking New Percel </h3>
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
                                            <div class="col-md-12" style="border-bottom:2px #17a2b8 dotted;"><big>
                                                    <strong>Sender
                                                        Information</strong>
                                                </big></div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="sender_name" id="sender_name"
                                                        value="{{ old('sender_name') }}" class="form-control"
                                                        placeholder="Sender Name">
                                                </div>

                                                <div class="form-group">
                                                    <label for="name">Address</label>
                                                    <input type="text" name="sender_address" id="sender_address"
                                                        value="{{ old('sender_address') }}" class="form-control"
                                                        placeholder="Sender Address">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Contact Number</label>
                                                    <input type="text" name="sender_phone" id="sender_phone"
                                                        value="{{ old('sender_phone') }}" class="form-control"
                                                        placeholder="Contact Number">
                                                </div>

                                                <div class="form-group">
                                                    <label for="name">National ID</label>
                                                    <input type="text" name="sender_nid" id="sender_nid"
                                                        value="{{ old('sender_nid') }}" class="form-control"
                                                        placeholder="Sender National ID">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Division</label>
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
                                                    <label for="name">District</label>
                                                    <select class="form-control select2" name="sender_district_id"
                                                        id="sender_district_id">
                                                        <option value="0">Select District</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Upazila/Thana</label>
                                                    <select class="form-control select2" name="sender_thana_id"
                                                        id="sender_thana_id">
                                                        <option value="0">Select Upazila/Thana</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Area</label>
                                                    <select class="form-control select2" name="sender_area_id"
                                                        id="sender_area_id">
                                                        <option value="0">Select Area</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12" style="border-bottom:2px #17a2b8 dotted;">
                                                <big><strong>Receiver
                                                        Information</strong></big>
                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="receiver_name" id="receiver_name"
                                                        value="{{ old('receiver_name') }}" class="form-control"
                                                        placeholder="Receiver Name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Contact Number</label>
                                                    <input type="text" name="receiver_phone" id="receiver_phone"
                                                        value="{{ old('receiver_phone') }}" class="form-control"
                                                        placeholder="Receiver Number">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Address</label>
                                                    <input type="text" name="receiver_address" id="receiver_address"
                                                        value="{{ old('receiver_address') }}" class="form-control"
                                                        placeholder="Receiver Address">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Division</label>
                                                    <select class="form-control select2" name="receiver_division_id"
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
                                                    <label for="name">District</label>
                                                    <select class="form-control select2" name="receiver_district_id"
                                                        id="receiver_district_id">
                                                        <option value="0">Select District</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Upazila/Thana</label>
                                                    <select class="form-control select2" name="receiver_thana_id"
                                                        id="receiver_thana_id">
                                                        <option value="0">Select Upazila/Thana</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Area</label>
                                                    <select class="form-control select2" name="receiver_area_id"
                                                        id="receiver_area_id">
                                                        <option value="0">Select Area</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12" style="border-bottom:2px #17a2b8 dotted;">
                                                <big><strong>Destination Branch & Parcel Type</strong></big>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Destination Branch</label>
                                                    <select class="form-control select2" name="receiver_branch_id"
                                                        id="receiver_branch_id">
                                                        <option value="0">Select Branch</option>
                                                        @foreach ($branches as $branche)
                                                            <option value="{{ $branche->id }}">{{ $branche->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Delivery Type</label>
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
                                                    <label for="name">Note</label>
                                                    <input type="text" name="note" id="note" value="{{ old('note') }}"
                                                        class="form-control" placeholder="Enter note">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12" style="border-bottom:2px #17a2b8 dotted;">
                                                <big><strong>Item Information</strong></big>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Item Category</label>
                                                    <select class="form-control select2" name="category_id"
                                                        id="category_id">
                                                        <option value="0">Select Item Category</option>
                                                        @foreach ($categories as $categorie)
                                                            <option value="{{ $categorie->id }}">{{ $categorie->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Item</label>
                                                    <select class="form-control select2" name="item_id" id="item_id">
                                                        <option value="0">Select Item</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Quantity</label>
                                                    <input type="text" name="quantity" id="quantity"
                                                        value="{{ old('quantity') }}" class="form-control"
                                                        placeholder="quantity">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">&nbsp;</label>
                                                    <button type="button" name="add_button" id="add_button"
                                                        class="btn btn-sm btn-info form-control">Add
                                                        Item</button>
                                                </div>
                                            </div>

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
                if (delivery_type == 0) {
                    $("#select2-delivery_type-container").parent().css('border-color', 'red');
                    return false;
                } else {
                    $("#select2-delivery_type-container").parent().css('border-color', '#ced4da');
                }

                var item_id = $("#item_id option:selected").val();
                if (item_id == 0) {
                    $("#select2-item_id-container").parent().css('border-color', 'red');
                    return false;
                } else {
                    $("#select2-item_id-container").parent().css('border-color', '#ced4da');
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
                        item_id: item_id,
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
                        $("#item_id").val("0").change();

                        $("#d_type").css({
                            'pointer-events': 'none',
                            'opacity': '0.5'
                        });
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

        function discount_calculate(value) {
            $('#discount_amount').css('border-color', '#ced4da');
            var grand_amount = parseFloat($('#grand_amount').val());
            if (value <= grand_amount) {
                if (value != '' && (grand_amount != '' && grand_amount != 0)) {
                    var net_amount = parseFloat(grand_amount) - parseFloat(value);
                    $('#net_amount').val(net_amount);
                    $('#due_amount').val(net_amount);
                } else {
                    $('#net_amount').val(0);
                    $('#due_amount').val(0);
                }
            } else {
                $('#net_amount').val(0);
                $('#discount_amount').css('border-color', 'red');
            }

        }

        function paid_calculate(value) {
            $('#paid_amount').css('border-color', '#ced4da');
            var net_amount = $('#net_amount').val();
            if (value <= net_amount) {
                if (value != '' && (net_amount != '' && net_amount != 0)) {
                    var due_amount = parseFloat(net_amount) - parseFloat(value);
                    $('#due_amount').val(due_amount);
                } else {
                    $('#due_amount').val(net_amount);
                }
            } else {
                $('#due_amount').val(0);
                $('#paid_amount').css('border-color', 'red');
            }
        }

        function submit_data() {
            event.preventDefault();


            var sdistrict_name = $('#sender_district_id option:selected').text();
            var rdistrict_name = $('#receiver_district_id option:selected').text();
            //alert([sdistrict_name, rdistrict_name]); return false;

            var sender_name = $('#sender_name').val();
            if (sender_name == '') {
                toastr.error('Please Enter Sender Name');
                $('#sender_name').css('border-color', 'red');
                return false
            } else {
                $('#sender_name').css('border-color', '#ced4da');
            }

            var sender_phone = $('#sender_phone').val();
            if (sender_phone == '') {
                toastr.error('Please Enter Sender Phone Number');
                $('#sender_phone').css('border-color', 'red');
                return false
            } else {
                $('#sender_phone').css('border-color', '#ced4da');
            }

            var sender_division_id = $('#sender_division_id option:selected').val();
            if (sender_division_id == 0) {
                toastr.error('Please Select Sender Division');
                return false;
            }

            var sender_district_id = $('#sender_district_id option:selected').val();
            if (sender_district_id == 0) {
                toastr.error('Please Select Sender District');
                return false;
            }

            var sender_thana_id = $('#sender_thana_id option:selected').val();
            if (sender_thana_id == 0) {
                toastr.error('Please Select Sender Upazila/Thana');
                return false;
            }

            var sender_area_id = $('#sender_area_id option:selected').val();
            if (sender_area_id == 0) {
                toastr.error('Please Select Sender Area');
                return false;
            }

            var receiver_name = $('#receiver_name').val();
            if (receiver_name == '') {
                toastr.error('Please Enter Receiver Name');
                $('#receiver_name').css('border-color', 'red');
                return false
            } else {
                $('#receiver_name').css('border-color', '#ced4da');
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

            var receiver_division_id = $('#receiver_division_id option:selected').val();
            if (receiver_division_id == 0) {
                toastr.error('Please Select Receiver Division');
                return false;
            }

            var receiver_district_id = $('#receiver_district_id option:selected').val();
            if (receiver_district_id == 0) {
                toastr.error('Please Select Receiver District');
                return false;
            }

            var receiver_thana_id = $('#receiver_thana_id option:selected').val();
            if (receiver_thana_id == 0) {
                toastr.error('Please Select Receiver Upazila/Thana');
                return false;
            }

            var receiver_area_id = $('#receiver_area_id option:selected').val();
            if (receiver_area_id == 0) {
                toastr.error('Please Select Receiver Area');
                return false;
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

                        if (getError.sender_name) {
                            message = getError.sender_name[0];
                            toastr.error(message);
                        }
                        if (getError.sender_phone) {
                            message = getError.sender_phone[0];
                            toastr.error(message);
                        }
                        if (getError.sender_division_id) {
                            message = getError.sender_division_id[0];
                            toastr.error(message);
                        }
                        if (getError.sender_district_id) {
                            message = getError.sender_district_id[0];
                            toastr.error(message);
                        }
                        if (getError.sender_thana_id) {
                            message = getError.sender_thana_id[0];
                            toastr.error(message);
                        }
                        if (getError.sender_area_id) {
                            message = getError.sender_area_id[0];
                            toastr.error(message);
                        }
                        if (getError.sender_branch_id) {
                            message = getError.sender_branch_id[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_name) {
                            message = getError.receiver_name[0];
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
                        if (getError.receiver_division_id) {
                            message = getError.receiver_division_id[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_district_id) {
                            message = getError.receiver_district_id[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_thana_id) {
                            message = getError.receiver_thana_id[0];
                            toastr.error(message);
                        }
                        if (getError.receiver_area_id) {
                            message = getError.receiver_area_id[0];
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
                        //                             toastr.error(message);

                    }
                }
            });
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
