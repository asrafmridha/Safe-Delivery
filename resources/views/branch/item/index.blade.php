
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Item</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Item</li>
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
                        <h3 class="card-title"> Item List </h3> 
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="6%" class="text-center"> SL </th>
                                    <th width="5%" class="text-center">  Item Name </th>
                                    <th width="10%" class="text-center"> Item Category</th>
                                     <th width="10%" class="text-center"> Unit </th>
                                    <th width="10%" class="text-center"> OD Rate </th>
                                    <th width="12%" class="text-center"> HD Rate </th>
                                    <th width="15%" class="text-center"> Transit OD </th>
                                    <th width="15%" class="text-center"> Transit HD </th>
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
                ajax: '{!! route('branch.item.getItem') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'item_name', name: 'item_name' , class : "text-center"},
                    { data: 'item_categories.name', name: 'item_categories.name' , class : "text-center"},
                    { data: 'units.name', name: 'units.name' , class : "text-center"},
                    { data: 'od_rate', name: 'od_rate' , class : "text-center"},
                    { data: 'hd_rate', name: 'hd_rate' , class : "text-center"},
                    { data: 'transit_od', name: 'transit_od' , class : "text-center"},
                    { data: 'transit_hd', name: 'transit_hd' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"}
                ]
            }); 
        }
    </script>
@endpush

