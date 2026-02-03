@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant Bulk Parcel Import</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant Bulk Parcel Import</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Merchant Bulk Parcel Import</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class=" col-md-12 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('branch.parcel.merchantBulkParcelImportEntry') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <table class="table table-bordered">
                                            <tr>
                                                <th> SL</th>
                                                <th>Order Id</th>
                                                <th>Merchant Id</th>
                                                <th> Customer Name</th>
                                                <th> Customer Number</th>
                                                <th> Customer Address</th>
                                                <th> Customer Area</th>
                                                <th> Product Details</th>
                                                <th> Weight</th>
                                                <!-- <th> Item Type</th>
                                                    <th> Service Type</th> -->
                                                <th> Collection Amount</th>
                                                <th> Remarks</th>
                                            </tr>
                                            @foreach ($import_parcels['parcel'] as $key => $import_parcel)
                                                @php
                                                    $tdColor = '';
                                                    if ($import_parcel['weight_package_id'] == 0 || $import_parcel['weight_package_id'] == null) {
                                                        $tdColor = 'bg-warning ';
                                                    }

                                                    if ($import_parcel['district_id'] == 0 || $import_parcel['area_id'] == 0) {
                                                        $tdColor = 'bg-danger ';
                                                    }

                                                @endphp

{{--                                                 @dd($import_parcels)--}}


                                                <tr class="{{ $tdColor }} ">
                                                    <td class="text-center">{{ $key + 1 }}</td>

                                                    <input hidden type="text" class="form-control"
                                                        name="parcel[{{ $key }}][rider_id]"
                                                        value="{{ $import_parcel['rider_id'] }}">

                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][merchant_order_id]"
                                                            value="{{ $import_parcel['merchant_order_id'] }}"
                                                            placeholder="Order ID">
                                                    </td>

                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][m_id]"
                                                            value="{{ $import_parcel['m_id'] }}" placeholder="Merchant Id">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][customer_name]"
                                                            value="{{ $import_parcel['customer_name'] }}"
                                                            placeholder="Customer Name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][customer_contact_number]"
                                                            value="{{ $import_parcel['customer_contact_number'] }}"
                                                            placeholder="Customer Number">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][customer_address]"
                                                            value="{{ $import_parcel['customer_address'] }}"
                                                            placeholder="Customer Address">
                                                    </td>
                                                    <style>
                                                        span.select2-selection--multiple[aria-expanded=true] {
                                                            border-color: blue !important;
                                                        }
                                                    </style>
                                                    <td>
                                                        <select id="parcel[{{ $key }}][area_id]"
                                                            class="form-control select2 "
                                                            name="parcel[{{ $key }}][area_id]"
                                                            style="width: 150px;" required>
                                                            <option value="">Select Area</option>

                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}"
                                                                    {{ $import_parcel['area_id'] == $area->id ? 'selected' : '' }}>
                                                                    {{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][product_details]"
                                                            value="{{ $import_parcel['product_details'] }}"
                                                            placeholder="Product Details">
                                                    </td>
                                                    <td>
                                                        <select id="parcel[{{ $key }}][weight_package_id]"
                                                            class="form-control select2 "
                                                            name="parcel[{{ $key }}][weight_package_id]"
                                                            style="width: 150px;" required>
                                                            <option value="">Select Weight</option>
                                                            @foreach ($weight_packages as $weight_package)
                                                                <option value="{{ $weight_package->id }}"
                                                                    {{ $import_parcel['weight_package_id'] == $weight_package->id ? 'selected' : '' }}>
                                                                    {{ $weight_package->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>


                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][total_collect_amount]"
                                                            value="{{ $import_parcel['total_collect_amount'] }}"
                                                            placeholder="Collection Amount">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="parcel[{{ $key }}][parcel_note]"
                                                            value="{{ $import_parcel['parcel_note'] }}"
                                                            placeholder="Remark">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Import Submit</button>
                                            <a href="{{ route('branch.parcel.merchantBulkParcelImport.reset') }}"
                                                class="btn btn-primary text-white">Reset</a>
                                        </div>
                                    </form>
                                </div>
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
            $('#rider_id').on('change', function() {
                var rider = $("#rider_id option:selected");
                var rider_id = rider.val();

                if (rider_id == 0) {
                    $("#view_rider_name").html('Not Confirm');
                    $("#view_rider_contact_number").html('Not Confirm');
                    $("#view_rider_address").html('Not Confirm');
                } else {
                    $("#view_rider_name").html(rider.text());
                    $("#view_rider_contact_number").html(rider.attr('riderContactNumber'));
                    $("#view_rider_address").html(rider.attr('riderAddress'));
                }
            });
        }

        function createForm() {
            let rider_id = $('#rider_id').val();
            if (rider_id == '0') {
                toastr.error("Please Select Rider ..");
                return false;
            }
        }
    </script>
@endpush
