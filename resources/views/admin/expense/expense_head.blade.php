@extends('layouts.admin_layout.admin_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Income/Expense Heads</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Income/Expense Head</li>
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
                            <h3 class="card-title"> Income/Expense Head List </h3>
                            <a href="{{ route('admin.expenseHead.create') }}" class="btn btn-success float-right">
                                <i class="fa fa-plus"></i> Add Income/Expense Head
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center"> SL </th>
                                        <th width="25%" class="text-center"> Name </th>
                                        <th width="10%" class="text-center"> Status </th>
                                        <th width="20%" class="text-center"> Action </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title">View Service Area </h4>
                                <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="showResult">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')

@endpush
@push('script_js')
    <script>
        window.onload = function() {

            var table = $('#yajraDatatable').DataTable({
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: '{!!  route('admin.expenseHead.getHeads') !!}',
                columns: [
                    { data: 'DT_RowIndex',orderable: false,searchable: false,class: "text-center" },
                    { data: 'name',name: 'name',className: "text-center" },
                    { data: 'status',name: 'status',className: "text-center" },
                    { data: 'action', name: 'action', orderable: false, class: "text-center" }
                ]
            });


            $('#yajraDatatable').on('click', '.updateStatus', function() {
                var status_object = $(this);
                var id = status_object.attr('id');
                //alert(id);
                var status = status_object.attr('status');
                var url = "{{ route('admin.expenseHead.updateStatus') }}";

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
                var url = "{{ route('admin.expenseHead.delete') }}";
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
        }

    </script>
@endpush
