@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant Bulk Import</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant Bulk Import</li>
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
                            <h3 class="card-title">Merchant Bulk Import</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class=" col-md-12 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.merchant.merchantBulkImportCheck') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <table class="table table-bordered">
                                            <tr>
                                                <th> SL</th>
                                                <th>Merchant Id</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Company Name</th>
                                                <th>Address</th>
                                                <th>Business Address</th>
                                                <th>FB URL</th>
                                                <th>Web URL</th>
                                                <th>Contact Number</th>
                                                <th>District</th>
                                                <th>Area</th>
                                                <th>Branch</th>
                                            </tr>
                                            @foreach ($import_merchants as $key => $import_merchant)
                                                @php

                                                    if ($import_merchant['district_id'] == 0 || $import_merchant['area_id'] == 0 || $import_merchant['branch_id'] == 0) {
                                                        $tdColor = 'bg-danger ';
                                                    }

                                                @endphp




                                                <tr class="{{ $tdColor }} ">
                                                    <td class="text-center">{{ $key + 1 }}</td>

                                                    <td>
                                                        {{ $import_merchant['m_id'] }}
                                                        <input type="hidden"
                                                               name="merchant[{{ $key }}][m_id]"
                                                               value="{{ $import_merchant['m_id'] }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][name]"
                                                            value="{{ $import_merchant['name'] }}"
                                                            placeholder="Merchant Name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][email]"
                                                            value="{{ $import_merchant['email'] }}"
                                                            placeholder="Merchant Email">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][password]"
                                                            value="{{ $import_merchant['password'] }}"
                                                            placeholder="Merchant password">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][company_name]"
                                                            value="{{ $import_merchant['company_name'] }}"
                                                            placeholder="Merchant company name">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][address]"
                                                            value="{{ $import_merchant['address'] }}"
                                                            placeholder="Merchant address">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][business_address]"
                                                            value="{{ $import_merchant['business_address'] }}"
                                                            placeholder="Merchant business address">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][fb_url]"
                                                            value="{{ $import_merchant['fb_url'] }}"
                                                            placeholder="Merchant fb url">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][web_url]"
                                                            value="{{ $import_merchant['web_url'] }}"
                                                            placeholder="Merchant web url">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            name="merchant[{{ $key }}][contact_number]"
                                                            value="{{ $import_merchant['contact_number'] }}"
                                                            placeholder="Merchant contact number">
                                                    </td>

                                                    <style>
                                                        span.select2-selection--multiple[aria-expanded=true] {
                                                            border-color: blue !important;
                                                        }
                                                    </style>
                                                    <td>
                                                        <select id="merchant[{{ $key }}][district_id]"
                                                            class="form-control select2 "
                                                            name="merchant[{{ $key }}][district_id]"
                                                            style="width: 150px;" required>
                                                            <option value="">Select District</option>

                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    {{ $import_merchant['district_id'] == $district->id ? 'selected' : '' }}>
                                                                    {{ $district->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="merchant[{{ $key }}][area_id]"
                                                            class="form-control select2 "
                                                            name="merchant[{{ $key }}][area_id]"
                                                            style="width: 150px;" required>
                                                            <option value="">Select Area</option>

                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}"
                                                                    {{ $import_merchant['area_id'] == $area->id ? 'selected' : '' }}>
                                                                    {{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="merchant[{{ $key }}][branch_id]"
                                                            class="form-control select2 "
                                                            name="merchant[{{ $key }}][branch_id]"
                                                            style="width: 150px;" required>
                                                            <option value="">Select branche</option>

                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}"
                                                                    {{ $import_merchant['branch_id'] == $branch->id ? 'selected' : '' }}>
                                                                    {{ $branch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>



                                                </tr>
                                            @endforeach
                                        </table>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Import Submit</button>
                                            <a href="{{ route('admin.merchant.merchantBulkImportReset') }}"
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
