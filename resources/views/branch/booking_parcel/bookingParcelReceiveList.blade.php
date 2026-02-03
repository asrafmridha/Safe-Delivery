@extends('layouts.branch_layout.branch_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Received Booking Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Received Booking Parcels List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Received  Booking Parcels List </h3>
                            <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center"> SL </th>
                                        <th width="8%" class="text-center"> Date </th>
                                        <th class="text-center"> C/N No</th>
                                        <th class="text-center"> Booking Type </th>
                                        <th class="text-center"> Sender Contact </th>
                                        <th class="text-center"> Sender Branch </th>
                                        <th class="text-center"> Receiver Contact </th>
                                        <th class="text-center"> Net Amount </th>
                                        <th class="text-center"> Delivery Type </th>
                                        <th class="text-center"> Status </th>
                                        <th class="text-center"> Action </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="showResult">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="printBarcode">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="showBookingItem">

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

            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: '{!!  route('branch.bookingParcel.getBookingParcelReceiveList') !!}',
                order: [ [2, 'desc'] ],
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'booking_date', name: 'booking_date' , class : "text-center"},
                    { data: 'parcel_code', name: 'parcel_code' , class : "text-center"},
                    { data: 'booking_parcel_type', name: 'booking_parcel_type' , class : "text-center"},
                    { data: 'sender_phone', name: 'sender_phone' , class : "text-center"},
                    { data: 'sender_branch.name', name: 'sender_branch.name' , class : "text-center"},
                    { data: 'receiver_phone', name: 'receiver_phone' , class : "text-center"},
                    { data: 'net_amount', name: 'net_amount' , class : "text-center"},
                    { data: 'delivery_type', name: 'delivery_type' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $(document).on('click', '#printBtn', function(){
                $.ajax({
                    type: 'GET',
                    url: '{!! route('branch.bookingParcel.printBookingParcelReceiveList') !!}',
                    data: {},
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href,"_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('#yajraDatatable').on('click', '.print-modal', function() {
                var booking_id = $(this).attr('booking_id');
                var url = "{{ route('branch.bookingParcel.bookingParcelItems', ':booking_id') }}";
                url = url.replace(':booking_id', booking_id);
                $('#showResult').html('');
                if (booking_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showBookingItem').html(response);
                        },

                    })
                }
            });

            $('#yajraDatatable').on('click', '.view-modal', function() {
                var booking_id = $(this).attr('booking_id');
                var url = "{{ route('branch.bookingParcel.viewBookingParcel', ':booking_id') }}";
                url = url.replace(':booking_id', booking_id);
                $('#showResult').html('');
                if (booking_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showResult').html(response);
                        },

                    })
                }
            });

            $('#yajraDatatable').on('click', '.delivery-modal', function() {
                var booking_id = $(this).attr('booking_id');
                var url = "{{ route('branch.bookingParcel.deliveryBookingParcel', ':booking_id') }}";
                url = url.replace(':booking_id', booking_id);
                $('#showResult').html('');
                if (booking_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showResult').html(response);
                            $('.select2').select2();
                        },

                    })
                }
            });
        }

    </script>
@endpush
