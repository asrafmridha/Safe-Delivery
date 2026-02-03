@extends('layouts.backend')

@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Rejected Transaction</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Rejected Transaction</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section">
                <table class="table table-bordered table-striped  datatable" id="yajraDatatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Client Name</th>
                        <th>User Name</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>BDT Amount</th>
                        <th>Transaction Type</th>
                        <th>Payment Type</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>


    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="showResult">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                /* pageLength: 50,
                 lengthMenu: [
                     [50, 100, 150, -1],
                     [50, 100, 150, 'All'],
                 ],*/
                ajax: "{{ route('transaction.rejected.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'transaction_no', name: 'transaction_no'},
                    {data: 'client.name', name: 'client.name',render: (data, type, row) =>  (row && row.client && row.client.name)? data : "--"},
                    {data: 'user.name', name: 'user.name',render: (data, type, row) =>  (row && row.user && row.user.name)? data : "--"},
                    {data: 'currency.code', name: 'currency'},
                    {data: 'amount', name: 'amount'},
                    {data: 'rate', name: 'rate'},
                    {data: 'bdt_amount', name: 'bdt_amount'},
                    {data: 'transaction_type', name: 'transaction_type'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
        window.onload = function () {
            $('#yajraDatatable').on('click', '.view-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.view', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            $('#showResult').html(response);
                        },
                    })
                }
            });

            $('#yajraDatatable').on('click', '.status-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.change.status', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            $('#showResult').html(response);
                        },
                    })
                }
            });
        }
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{-- <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

@endsection
