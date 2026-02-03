
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Staffs</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Staffs</li>
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
                        <h3 class="card-title"> Staff List </h3>
                        <a href="{{ route('admin.staff.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Staff
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="6%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Name </th>
                                        <th width="10%" class="text-center"> Contact Number </th>
                                        <th width="10%" class="text-center"> Designation </th>
                                        <th width="10%" class="text-center"> Salary </th>
                                        <th width="10%" class="text-center"> Branch </th>
                                        <th width="7%" class="text-center"> Status </th>
                                        <th width="15%" class="text-center"> Action </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="viewModal">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-primary">
                      <h4 class="modal-title">View Staff </h4>
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

@push('style_css')
    <style>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            margin: 0 auto;
        }

        /*
        div.container {
            width: 80%;
        }
        */
    </style>
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
            ajax: '{!! route('admin.account.getStaffs') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'phone', name: 'phone' , class : "text-center"},
                { data: 'designation', name: 'designation' , class : "text-center"},
                { data: 'salary', name: 'salary' , class : "text-center"},
                { data: 'branch.name', name: 'branch.name' , class : "text-center"},
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var staff_id = $(this).attr('staff_id');
            var url = "{{ route('admin.staff.show', ":staff_id") }}";
            url = url.replace(':staff_id', staff_id);
            $('#showResult').html('');
            if(staff_id.length != 0){
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
            var staff_id        = status_object.attr('staff_id');
            var status          = status_object.attr('status');
            var url             = "{{ route('admin.account.staffUpdateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        staff_id: staff_id,
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
            var staff_id   = status_object.attr('staff_id');
            var url         = "{{ route('admin.account.staffDelete') }}";

            var sttaus = confirm("Are you sure delete this staff?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        staff_id: staff_id,
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

