
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Vehicle</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
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
                        <h3 class="card-title"> Vehicles List </h3>

                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="6%" class="text-center"> SL </th>
                                    <th width="5%" class="text-center">  Vehicle Name </th>
                                    <th width="10%" class="text-center"> Vehicle Sl No </th>
                                     <th width="10%" class="text-center"> Vehicle No </th>
                                    <th width="15%" class="text-center"> Vehicle Driver Name </th>
                                    <th width="14%" class="text-center"> Vehicle Driver Phone </th>
                                    <th width="14%" class="text-center"> Vehicle Root </th>
                                    <th width="7%" class="text-center"> Status </th>
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
                ajax: '{!! route('branch.vehicle.getVehicles') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'name', name: 'name' , class : "text-center"},
                    { data: 'vehicle_sl_no', name: 'vehicle_sl_no' , class : "text-center"},
                    { data: 'vehicle_no', name: 'vehicle_no' , class : "text-center"},
                    { data: 'vehicle_driver_name', name: 'vehicle_driver_name' , class : "text-center"},
                    { data: 'vehicle_driver_phone', name: 'vehicle_driver_phone' , class : "text-center"},
                    { data: 'vehicle_road', name: 'vehicle_road' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                ]
            });


        }
    </script>
@endpush

