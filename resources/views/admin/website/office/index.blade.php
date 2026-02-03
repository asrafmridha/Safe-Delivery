
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Offices</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Offices</li>
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
                        <h3 class="card-title"> Offices List </h3>
                        <a href="{{ route('admin.office.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Office
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="7%" class="text-center"> SL </th>
                                    <th width="17%" class="text-center"> Name </th>
                                    <th width="17%" class="text-center"> Contact Number </th>
                                    <th width="17%" class="text-center"> Email </th>
                                    <th width="20%" class="text-center"> Address </th>
                                    <th width="7%" class="text-center"> Status </th>
                                    <th width="15%" class="text-center"> Action </th>
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

@push('style_css')

@endpush
@push('script_js')
  <script>
    window.onload = function(){

        var table = $('#yajraDatatable').DataTable({
            language : {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.office.getOffices') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'contact_number', name: 'contact_number' , class : "text-center"},
                { data: 'email', name: 'email', class : "text-center" },
                { data: 'address', name: 'address' , class : "text-center"},
                { data: 'status', name: 'status', class : "text-center" },
                { data: 'action', name: 'action', orderable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.updateStatus', function(){
            var status_object = $(this);
            var office_id  = status_object.attr('office_id');
            var status    = status_object.attr('status');
            var url       = "{{ route('admin.office.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        office_id: office_id,
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
            var office_id   = status_object.attr('office_id');
            var url         = "{{ route('admin.office.delete') }}";

            var sttaus = confirm("Are you sure delete this office?");

            if(sttaus) {

                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        office_id: office_id,
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
    }
  </script>
@endpush

