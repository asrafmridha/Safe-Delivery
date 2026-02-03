
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Parcels List</li>
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
                        <h3 class="card-title"> Parcels List </h3>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Invoice</th>
                                    <th width="10%" class="text-center"> Customer Name </th>
                                    <th width="10%" class="text-center"> Customer Contact </th>
                                    <th width="10%" class="text-center"> Customer Address </th>
                                    <th width="8%" class="text-center"> District </th>
                                    <th width="8%" class="text-center"> Area </th>
                                    <th width="8%" class="text-center"> Total Charge </th>
                                    <th width="11%" class="text-center"> Status </th>
                                    <th width="20%" class="text-center"> Action </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="viewModal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" id="showResult">

                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('style_css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function(){
            var table = $('#yajraDatatable').DataTable({
                processing: true,
                serverSide: true,
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: '{!! route('admin.parcel.getParcelList') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'parcel_invoice', name: 'parcel_invoice' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'customer_contact_number', name: 'customer_contact_number' },
                    { data: 'customer_address', name: 'customer_address' },
                    { data: 'district.name', name: 'district.name' },
                    { data: 'area.name', name: 'area.name' },
                    { data: 'total_charge', name: 'total_charge' },
                    { data: 'status', name: 'status' , searchable: false },
                    { data: 'action', name: 'action', orderable: false , searchable: false, class : "text-center" }
                ],
                order: [[1, 'DESC']]
            });

            $('#yajraDatatable').on('click', '.view-modal', function(){
                var parcel_id = $(this).attr('parcel_id');
                var url = "{{ route('admin.parcel.viewParcel', ":parcel_id") }}";
                url = url.replace(':parcel_id', parcel_id);
                $('#showResult').html('');
                if(parcel_id.length != 0){
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


            $('#yajraDatatable').on('click', '.delete-btn', function(){
                var status_object = $(this);
                var parcel_id   = status_object.attr('parcel_id');

                var sttaus = confirm("Are you sure delete this parcel?");

                if(sttaus) {

                    var url = "{{ route('admin.parcel.deleteParcel') }}";

                    $.ajax({
                        cache: false,
                        type: "DELETE",
                        dataType: "JSON",
                        data: {
                            parcel_id: parcel_id,
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
                    });
                }

            });
        }
    </script>
@endpush

