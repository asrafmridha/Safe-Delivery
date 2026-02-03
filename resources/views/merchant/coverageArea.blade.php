
@extends('layouts.merchant_layout.merchant_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Coverage Areas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Coverage Areas</li>
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
                        <h3 class="card-title"> Coverage Areas List </h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="yajraDatatable" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="20%" class="text-center"> Area </th>
                                    <th width="10%" class="text-center"> Post Code </th>
{{--                                    <th width="20%" class="text-center"> Thana/Upazila </th>--}}
                                    <th width="15%" class="text-center"> District </th>
                                    <th width="15%" class="text-center"> Service Area </th>
                                    <th width="15%" class="text-center"> COD % </th>
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
            ajax: '{!! route('merchant.getCoverageAreas') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'name', name: 'name', class : "text-center" },
                { data: 'post_code', name: 'post_code', class : "text-center" },
                // { data: 'upazila.name', name: 'upazila.name', class : "text-center" },
                { data: 'district.name', name: 'district.name', class : "text-center"},
                { data: 'district.service_area.name', name: 'district.service_area.name', class : "text-center",render: (data, type, row) =>  (row && row.district && row.district.service_area && row.district.service_area.name)? data : "--"},
                { data: 'district.service_area.cod_charge', name: 'district.service_area.cod_charge', class : "text-center",render: (data, type, row) =>  (row && row.district && row.district.service_area && row.district.service_area.cod_charge)? data : "--"},
            ]
        });
    }
  </script>
@endpush

