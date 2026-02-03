@extends('layouts.branch_layout.branch_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Booking Parcel Payment Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Booking Parcels Payment Report</li>
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
                            <h3 class="card-title"> Booking Parcels Payment Report </h3>
                            {{--<a href="{{ route('branch.bookingParcel.create') }}" class="btn btn-success float-right">--}}
                                {{--<i class="fa fa-pencil-alt"></i> Add New Booking--}}
                            {{--</a>--}}
                        </div>
                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th  class="text-center"> SL </th>
                                        <th  class="text-center"> ID </th>
                                        <th  class="text-center"> Date</th>
                                        <th  class="text-center"> Parcel No</th>
                                        <th  class="text-center"> Payment Receive </th>
                                        <th  class="text-center"> Amount </th>
                                        <th  class="text-center"> Status </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                {{--<div class="modal fade" id="viewModal">--}}
                    {{--<div class="modal-dialog modal-xl">--}}
                        {{--<div class="modal-content" id="showResult">--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>

    {{--<div class="modal fade" id="printBarcode">--}}
        {{--<div class="modal-dialog modal-xl">--}}
            {{--<div class="modal-content" id="showBookingItem">--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
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
                ajax: '{!!  route('branch.bookingParcelPayment.getBookingParcelPaymentList') !!}',
                order: [ [1, 'desc'] ],
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'id', name: 'id', searchable: false, visible: false},
                    { data: 'payment_date', name: 'payment_date' , class : "text-center"},
                    { data: 'booking_parcels.parcel_code', name: 'booking_parcels.parcel_code' , class : "text-center"},
                    { data: 'payment_receive_type', name: 'payment_receive_type' , class : "text-center"},
                    { data: 'total_amount', name: 'total_amount' , class : "text-center"},
                    { data: 'status', name: 'status' , class : "text-center"},
//                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

        }

    </script>
@endpush
