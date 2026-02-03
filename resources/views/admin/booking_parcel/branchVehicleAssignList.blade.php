@extends('layouts.admin_layout.admin_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Branch Parcel Assign Vehicle List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Branch Parcel Assign Vehicle List</li>
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
                            <h3 class="card-title"> Branch Parcel Assign Vehicle List </h3>
                            {{--<a href="{{ route('branch.bookingParcel.create') }}" class="btn btn-success float-right">--}}
                                {{--<i class="fa fa-pencil-alt"></i> Add New Booking--}}
                            {{--</a>--}}
                        </div>
                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center"> SL </th>
                                        <th class="text-center"> Date </th>
                                        <th class="text-center"> Vehicle Sl No</th>
                                        <th class="text-center"> Vehicle No </th>
                                        <th class="text-center"> Sender Branch </th>
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
                ajax: '{!!  route('admin.operationBookingParcel.vehicleAssignList') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'created_at', name: 'created_at' , class : "text-center"},
                    { data: 'vehicles.vehicle_sl_no', name: 'vehicles.vehicle_sl_no' , class : "text-center"},
                    { data: 'vehicles.vehicle_no', name: 'vehicles.vehicle_no' , class : "text-center"},
                    { data: 'branches.name', name: 'branches.name' , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $('#yajraDatatable').on('click', '.view-modal', function() {
                var booking_id = $(this).attr('booking_id');
                var url = "{{ route('admin.bookingParcel.viewBookingParcel', ':booking_id') }}";
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
        }

    </script>
@endpush
