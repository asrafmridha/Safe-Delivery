@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Account Entry List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Account Entry</li>
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
                            <h3 class="card-title"> Account Entry List </h3>
                            <a href="{{ route('admin.expense-create') }}" class="btn btn-success float-right">
                                <i class="fa fa-pencil-alt"></i> Add Entry
                            </a>

                        </div>



                        <div class="card-header">

                            <div class="row input-daterange" style="margin-top: 40px">

                                <div class="col-md-3">
                                    <label for="status">Accounts Type </label>
                                    <select name="type" id="type" class="form-control select2" style="width: 100%">
                                        <option value="0">Select Type</option>
                                        <option value="1">Expense</option>
                                        <option value="2">Income</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="expense_head_id">Account Heard </label>
                                    <select name="expense_head_id" id="expense_head_id" class="form-control select2"
                                        style="width: 100%">
                                        <option value="0">Select Account Heard </option>
                                        @foreach ($heads as $head)
                                            <option value="{{ $head->id }}">{{ $head->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="" />
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="" />
                                </div>
                                <div class="col-md-2" style="margin-top: 20px">
                                    <button type="button" name="filter" id="filter" class="btn btn-success">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>


                        </div>


                        <div class="card-body"  id="filterExpense">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="6%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Date</th>
                                        <th width="10%" class="text-center"> Type</th>
                                        <th width="15%" class="text-center"> Expense Head Name </th>
                                        <th width="10%" class="text-center"> Note </th>
                                        <th width="10%" class="text-right"> Amount </th>
                                        <th width="7%" class="text-center"> Status </th>
                                        <th width="12.5%" class="text-center"> Action </th>
                                    </tr>
                                </thead>



                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_js')
    <script>
        window.onload = function() {

            var table = $('#yajraDatatable').DataTable({
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.getExpense') !!}',
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date',
                        class: "text-center"
                    },
                    {
                        data: 'type',
                        name: 'type',
                        class: "text-center"
                    },
                    {
                        data: 'expense_heads.name',
                        name: 'expense_heads.name',
                        class: "text-center"
                    },
                    {
                        data: 'note',
                        name: 'note',
                        class: "text-center"
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        class: "text-center"
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        class: "text-center"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: "text-center"
                    }
                ]
            });

            $('#yajraDatatable').on('click', '.updateStatus', function() {
                var status_object = $(this);
                var id = status_object.attr('id');
                var status = status_object.attr('status');
                var url = "{{ route('admin.expense.updateStatus') }}";

                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: url,
                    success: function(response) {
                        if (response.success) {
                            if (response.status == 1) {
                                status_object.removeClass("text-danger");
                                status_object.addClass("text-success");
                                status_object.html("Active");
                                status_object.attr("status", 0);
                            } else {
                                status_object.removeClass("text-success");
                                status_object.addClass("text-danger");
                                status_object.html("Inactive");
                                status_object.attr("status", 1);
                            }
                            toastr.success(response.success);
                        } else {
                            toastr.error(response.error);
                        }
                    }
                })
            });

            $('#yajraDatatable').on('click', '.delete-btn', function() {
                var status_object = $(this);
                var id = status_object.attr('id');
                var url = "{{ route('admin.expense.delete') }}";

                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: url,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success);
                            $('#yajraDatatable').DataTable().ajax.reload();
                        } else {
                            toastr.error(response.error);
                        }
                    }
                })
            });



            $("#filter").on("click", function() {
                var expense_head_id = $("#expense_head_id").val();
                var type = $("#type").val();
                var from_date = $("#from_date").val();
                var to_date = $("#to_date").val();

                if (type != "" || expense_head_id != "" || from_date != "" || to_date != "") {
                    $.ajax({
                        cache: false,
                        url: "{{ route('admin.filter.expense') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            type: type,
                            expense_head_id: expense_head_id,
                            from_date: from_date,
                            to_date: to_date
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        success: function(response) {
                            $("#filterExpense").html("");
                            $("#filterExpense").html(response);
                        }
                    })
                } else {
                    toastr.error("Please filled any one field");
                }
            });
        }
    </script>
@endpush
