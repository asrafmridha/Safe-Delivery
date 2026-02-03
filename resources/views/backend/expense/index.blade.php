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
                            <li class="breadcrumb-item active" aria-current="page">Expense list</li>
                            @if(check_permission('expense create'))
                                <a href="{{route('expense.create')}}" class="btn btn-primary ml-auto">
                                    Create Expense
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Expense</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section">
                <table class="table table-bordered table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Expense Head</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Attachment</th>
                        <th>Created By</th>
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
                ajax: "{{ route('expense.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'expense_head.title', name: 'expense_head.title', class : "text-center",render: (data, type, row) =>  (row && row.expense_head && row.expense_head.title)? data : "--"},
                    {data: 'amount', name: 'amount'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'attachment', name: 'attachment'},
                    {data: 'created_user.name', name: 'created_user.name', class : "text-center",render: (data, type, row) =>  (row && row.created_user && row.created_user.name)? data : "--"},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{-- <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

@endsection
