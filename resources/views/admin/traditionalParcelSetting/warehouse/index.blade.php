
@extends('layouts.admin_layout.admin_layout')
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Warehouse</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Warehouses</li>
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
                        <h3 class="card-title"> Warehouses </h3>
                        <a href="{{ route('admin.warehouse.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Warehouse
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center"> SL </th>
                                    <th width="30%" class="text-center">  Warehouse Name </th>
                                    <th width="30%" class="text-center"> Warehouse Root </th>
                                    <th width="10%" class="text-center"> Status </th>
                                    <th width="20%" class="text-center"> Action </th>
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
        window.onload = function(){

            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.warehouse.getWarehouses') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'name', name: 'name' , class : "text-center"},
                    { data: 'type', name: 'type' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $('#yajraDatatable').on('click', '.updateStatus', function(){
                var status_object   = $(this);
                var warehouse_id     = status_object.attr('warehouse_id');
                var status          = status_object.attr('status');
                var url             = "{{ route('admin.warehouse.updateStatus') }}";

                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "JSON",
                    data      : {
                        warehouse_id: warehouse_id,
                        status: status,
                        _token : "{{ csrf_token() }}"
                    },
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url       : url,
                    success   : function(response){
                        if(response.success){
                            if(response.status == 1){
                                status_object.removeClass("text-danger");
                                status_object.addClass("text-success");
                                status_object.html("Active");
                                status_object.attr("status", 0);
                            }
                            else{
                                status_object.removeClass("text-success");
                                status_object.addClass("text-danger");
                                status_object.html("Inactive");
                                status_object.attr("status", 1);
                            }
                            toastr.success(response.success);
                        }
                        else{
                            toastr.error(response.error);
                        }
                    }
                })
            });

            $('#yajraDatatable').on('click', '.delete-btn', function(){
                var status_object = $(this);
                var warehouse_id   = status_object.attr('warehouse_id');
                var url         = "{{ route('admin.warehouse.delete') }}";

                $.ajax({
                    cache       : false,
                    type        : "DELETE",
                    dataType    : "JSON",
                    data        : {
                        warehouse_id: warehouse_id,
                        _token : "{{ csrf_token() }}"
                    },
                    error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url       : url,
                    success   : function(response){
                        if(response.success){
                            toastr.success(response.success);
                            $('#yajraDatatable').DataTable().ajax.reload();
                        }
                        else{
                            toastr.error(response.error);
                        }
                    }
                })
            });
        }
    </script>
@endpush

