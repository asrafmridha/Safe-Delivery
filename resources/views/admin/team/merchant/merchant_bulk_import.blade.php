@extends('layouts.admin_layout.admin_layout')


@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant Bulk Parcel Import</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
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
                            <div class="offset-md-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.merchant.merchantBulkImport') }}"
                                          method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                        @csrf
                                        <div class="card-body row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="file">Import File <code>(.csv)</code></label>
                                                    <input type="file" name="file" id="file" class="form-control"
                                                           required>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ asset('format/Merchant-Bulk-Format.xlsx') }}"
                                                   class="btn btn-success btn-block mt-4" download="">
                                                    <i class="fas fa-file-excel"></i> Merchant Bulk Import
                                                    Format
                                                </a>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Import Submit</button>
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
    </div>
@endsection

@push('style_css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function () {
            $('#rider_id').on('change', function () {
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
