@extends('layouts.branch_layout.branch_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Item Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Item Category</li>
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
                            <h3 class="card-title"> Item Category List </h3>
                        </div>
                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="10%" class="text-center"> SL </th>
                                        <th width="35%" class="text-center"> Name </th>
                                        <th width="35%" class="text-center"> Details </th>
                                        <th width="10%" class="text-center"> Status </th>
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
                ajax: '{!!  route('branch.itemCategory.getItemCategories') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false, class: "text-center"},
                    { data: 'name', name: 'name', className: "text-center" },
                    { data: 'details', name: 'details', className: "text-center" },
                    { data: 'status', name: 'status', class: "text-center" }
                ]
            });

        }

    </script>
@endpush
