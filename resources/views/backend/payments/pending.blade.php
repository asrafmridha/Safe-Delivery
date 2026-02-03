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
                            <li class="breadcrumb-item active" aria-current="page">Pending Transaction</li>
                            @if(check_permission('payment create'))
                                <a href="{{route('payment.create')}}" class="btn btn-primary ml-auto">
                                    Create Payment
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Pending Payment</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive" style="white-space: nowrap">
                <table class="table table-bordered table-striped datatable" id="yajraDatatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Payment No</th>
                        <th>Client Name</th>
                        <th>Supplier Name</th>
                        <th>Created By</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
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
                ajax: "{{ route('payment.pending.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'date', name: 'date', class : "text-center"},
                    {data: 'payment_no', name: 'payment_no', class : "text-center"},
                    {data: 'client.name', name: 'client.name', class : "text-center",render: (data, type, row) =>  (row && row.client && row.client.name)? data : "--"},
                    {data: 'supplier.name', name: 'supplier.name',  class : "text-center",render: (data, type, row) =>  (row && row.supplier && row.supplier.name)? data : "--"},
                    {data: 'created_user.name', name: 'created_user.name',  class : "text-center",render: (data, type, row) =>  (row && row.created_user && row.created_user.name)? data : "--"},
                    {data: 'amount', name: 'amount', class : "text-center"},
                    {data: 'payment_method.name', name: 'payment_method.name',  class : "text-center",render: (data, type, row) =>  (row && row.payment_method && row.payment_method.name)? data : "--"},
                    {data: 'remarks', name: 'remarks', class : "text-center"},
                    {data: 'status', name: 'status', class : "text-center"},
                    {data: 'action', name: 'action', orderable: false, searchable: false, class : "text-center"},
                ]
            });
        });
        window.onload = function () {
            $('#yajraDatatable').on('click', '.view-modal', function () {
                var payment_id = $(this).attr('payment_id');
                var url = "{{ route('payment.view', ":payment_id") }}";
                url = url.replace(':payment_id', payment_id);
                $('#showResult').html('');
                if (payment_id.length != 0) {
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

            $(document).on('click', '.print-modal', function(){
                var payment_id = $(this).attr('payment_id');
                var url = "{{ route('payment.print', ":payment_id") }}";
                url = url.replace(':payment_id', payment_id);
                $.ajax({
                    type: 'GET',
                    url : url,
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
