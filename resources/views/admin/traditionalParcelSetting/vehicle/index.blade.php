
@extends('layouts.admin_layout.admin_layout')
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Vehicle</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Vehicles</li>
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
                        <h3 class="card-title"> Vehicles </h3>
                        <a href="{{ route('admin.vehicle.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Vehicle
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
                                    <th width="5%" class="text-center">  Vehicle Name </th>
                                    <th width="10%" class="text-center"> Vehicle Sl No </th>
                                     <th width="10%" class="text-center"> Vehicle No </th>
                                    <th width="10%" class="text-center"> Vehicle Driver Name </th>
                                    <th width="10%" class="text-center"> Vehicle Driver Phone </th>
                                    <th width="10%" class="text-center"> Vehicle Root </th>
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
        window.onload = function(){

            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.vehicle.getVehicles') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'name', name: 'name' , class : "text-center"},
                    { data: 'vehicle_sl_no', name: 'vehicle_sl_no' , class : "text-center"},
                    { data: 'vehicle_no', name: 'vehicle_no' , class : "text-center"},
                    { data: 'vehicle_driver_name', name: 'vehicle_driver_name' , class : "text-center"},
                    { data: 'vehicle_driver_phone', name: 'vehicle_driver_phone' , class : "text-center"},
                    { data: 'vehicle_road', name: 'vehicle_road' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $('#yajraDatatable').on('click', '.updateStatus', function(){
                var status_object   = $(this);
                var vehicle_id     = status_object.attr('vehicle_id');
                var status          = status_object.attr('status');
                var url             = "{{ route('admin.vehicle.updateStatus') }}";

                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "JSON",
                    data      : {
                        vehicle_id: vehicle_id,
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
                var vehicle_id   = status_object.attr('vehicle_id');
                var url         = "{{ route('admin.vehicle.delete') }}";

                $.ajax({
                    cache       : false,
                    type        : "DELETE",
                    dataType    : "JSON",
                    data        : {
                        vehicle_id: vehicle_id,
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
            $(document).on('click', '#printBtn', function(){
                $.ajax({
                    type: 'GET',
                    url: '{!! route('admin.vehicle.printVehicles') !!}',
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

