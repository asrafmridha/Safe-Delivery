
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Warehouse Users</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Warehouse Users</li>
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
                        <h3 class="card-title"> Warehouse Users List </h3>
                        <a href="{{ route('admin.warehouseUser.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Warehouse User
                        </a>
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="6%" class="text-center"> SL </th>
                                    <th width="15%" class="text-center"> Name </th>
                                    <th width="10%" class="text-center"> Image </th>
                                    <th width="15%" class="text-center"> Email </th>
                                    <th width="20%" class="text-center"> Warehouse </th>
                                    <th width="10%" class="text-center"> Contact Number </th>
                                    <th width="7%" class="text-center"> Status </th>
                                    <th width="17.5%" class="text-center"> Action </th>
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
                      <h4 class="modal-title">View Warehouse User </h4>
                      <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="showResult">

                    </div>
                    <div class="modal-footer">
                      <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                    </div>
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
            ajax: '{!! route('admin.warehouseUser.getWarehouseUsers') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'image', name: 'image' , class : "text-center"},
                { data: 'email', name: 'email' , class : "text-center"},
                { data: 'warehouse.name', name: 'warehouse.name' , class : "text-center", render: (data, type, row) =>  (row && row.warehouse)? data : "--"},
                { data: 'contact_number', name: 'contact_number' , class : "text-center"},
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var warehouse_user_id = $(this).attr('warehouse_user_id');
            var url = "{{ route('admin.warehouseUser.show', ":warehouse_user_id") }}";
            url = url.replace(':warehouse_user_id', warehouse_user_id);
            $('#showResult').html('');
            if(warehouse_user_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showResult').html(response);
                    },
                })
            }
        });

        $('#yajraDatatable').on('click', '.updateStatus', function(){
            var status_object   = $(this);
            var warehouse_user_id     = status_object.attr('warehouse_user_id');
            var status          = status_object.attr('status');
            var url             = "{{ route('admin.warehouseUser.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        warehouse_user_id: warehouse_user_id,
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
            var warehouse_user_id   = status_object.attr('warehouse_user_id');
            var url         = "{{ route('admin.warehouseUser.delete') }}";

            var sttaus = confirm("Are you sure delete this warehouse user?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        warehouse_user_id: warehouse_user_id,
                        _token: "{{ csrf_token() }}"
                    },
                    error: function (xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    url: url,
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.success);
                            $('#yajraDatatable').DataTable().ajax.reload();
                        }
                        else {
                            toastr.error(response.error);
                        }
                    }
                })
            }
        });

        $(document).on('click', '#printBtn', function(){
            $.ajax({
                type: 'GET',
                url: '{!! route('admin.warehouseUser.printWarehouseUsers') !!}',
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
    }
  </script>
@endpush

