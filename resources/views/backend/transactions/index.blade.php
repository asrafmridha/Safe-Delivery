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
                            <li class="breadcrumb-item active" aria-current="page">All Transaction</li>
                            @if(check_permission('transaction create'))
                                <a href="{{route('transaction.create')}}" class="btn btn-primary ml-auto">
                                    Create Transaction
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">All Transaction</legend>
{{--        <form action="{{route('transaction.filter')}}" method="post">--}}
{{--            @csrf--}}
            <div class="row">
                <div class="col-md-11">
                    <div class="row ">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="filter_status">Select Status</label>
                                <select name="status" id="filter_status"
                                        class="form-control select2 @error('status') is-invalid @enderror">
                                    <option value="">Select status</option>
                                    <option value="0">Pending</option>
                                    <option value="3">Order</option>
                                    <option value="1">Approved</option>
                                    <option value="4">Completed</option>
                                    <option value="2">Rejected</option>
                                </select>
                                @error('status')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="created_by">Select Created User</label>
                                <select name="created_by" id="created_by"
                                        class="form-control select2 @error('created_by') is-invalid @enderror">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                                @error('created_by')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="currency_id">Select Currency</label>
                                <select name="currency_id" id="currency_id"
                                        class="form-control select2 @error('currency_id') is-invalid @enderror">
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option
                                            value="{{$currency->id}}">{{$currency->code.' - '.$currency->name}}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client_id">Select Client</label>
                                <select name="client_id" id="client_id"
                                        class="form-control select2 @error('client_id') is-invalid @enderror">
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option
                                            value="{{$client->id}}">{{$client->name.' - ('.$client->country->name.")"}}</option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        {{--<div class="col-md-2">
                            <label for="transaction_type">Transaction Type</label>
                            <select id="transaction_type" name="transaction_type"
                                    class="form-control @error('transaction_type') is-invalid @enderror">
                                <option value="">All Type</option>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                            </select>
                            @error('transaction_type')
                            <div class="text-danger font-italic">
                                <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                            </div>
                            @enderror
                        </div>--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date"
                                       class="form-control @error('from_date') is-invalid @enderror">
                                @error('from_date')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date"
                                       class="form-control @error('to_date') is-invalid @enderror">
                                @error('to_date')
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 mt-5">
                    <button type="button" name="refresh" id="refresh" class="btn btn-info">Refresh</button>
                    <button type="submit" id="filter" class="btn btn-primary btn-sm">Filter</button>
                </div>
            </div>
{{--        </form>--}}
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive" style="white-space: nowrap">
                <table class="table table-bordered table-striped datatable" id="yajraDatatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Supplier Name</th>
                        <th>Client Name</th>
                        <th>Created By</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>B.Rate</th>
                        <th>S.Rate</th>
                        <th>Profit</th>
                        <th>Remarks</th>
                        <th>S/L</th>
                        <th>Beneficiary</th>
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
        window.onload = function () {
            load_data();
            function load_data(filter_status = "", created_by = "", currency_id = "", client_id = "", from_date = "", to_date = "") {
                var table = $('.datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    /* pageLength: 50,
                     lengthMenu: [
                         [50, 100, 150, -1],
                         [50, 100, 150, 'All'],
                     ],*/
                    {{--ajax: "{{ route('transaction.datatable') }}",--}}
                    ajax: {
                        url: '{!! route('transaction.datatable') !!}',
                        data: {
                            status: filter_status,
                            created_by: created_by,
                            currency_id: currency_id,
                            client_id: client_id,
                            from_date: from_date,
                            to_date: to_date
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                        {data: 'date', name: 'date', class: "text-center"},
                        {data: 'transaction_no', name: 'transaction_no', class: "text-center"},
                        {
                            data: 'supplier.name',
                            name: 'supplier.name',
                            class: "text-center",
                            render: (data, type, row) => (row && row.supplier && row.supplier.name) ? data : "--"
                        },
                        {
                            data: 'client.name',
                            name: 'client.name',
                            class: "text-center",
                            render: (data, type, row) => (row && row.client && row.client.name) ? data : "--"
                        },
                        {
                            data: 'created_user.name',
                            name: 'created_user.name',
                            class: "text-center",
                            render: (data, type, row) => (row && row.created_user && row.created_user.name) ? data : "--"
                        },
                        {data: 'currency.code', name: 'currency.code', class: "text-center"},
                        {data: 'amount', name: 'amount', class: "text-center"},
                        {data: 'b_rate', name: 'b_rate', class: "text-center"},
                        {data: 's_rate', name: 's_rate', class: "text-center"},
                        {data: 'profit', name: 'profit', class: "text-center"},
                        {data: 'remarks', name: 'remarks', class: "text-center"},
                        {data: 'sl', name: 'sl', class: "text-center"},
                        {data: 'beneficiary', name: 'beneficiary', class: "text-center"},
                        {data: 'status', name: 'status', class: "text-center"},
                        {data: 'action', name: 'action', orderable: false, searchable: false, class: "text-center"},
                    ]
                });
            }

            $('#filter').click(function () {
                var filter_status = $('#filter_status option:selected').val();
                var created_by = $('#created_by option:selected').val();
                var currency_id = $('#currency_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var client_id = $('#client_id').val();
                // console.log(filter_status);

                $('#yajraDatatable').DataTable().destroy();
                load_data(filter_status, created_by, currency_id, client_id, from_date, to_date);
            });
            $(document).on('click', '#refresh', function () {
                $("#filter_status").val("").trigger('change');
                $("#created_by").val("").trigger('change');
                $("#currency_id").val("").trigger('change');
                $("#client_id").val("");
                $("#from_date").val("");
                $("#to_date").val("");
                $('#yajraDatatable').DataTable().destroy();
                load_data('', '');
            });

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

            $(document).on('click', '.print-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.print', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {},
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href, "_blank");
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
