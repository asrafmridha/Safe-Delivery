
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Team Members</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Team Members</li>
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
                        <h3 class="card-title"> Team Members List </h3>
                        <a href="{{ route('admin.teamMember.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Team Member
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center"> SL </th>
                                    <th width="20%" class="text-center"> Name </th>
                                    <th width="20%" class="text-center"> Image </th>
                                    <th width="20%" class="text-center"> Designation </th>
                                    <th width="10%" class="text-center"> Status </th>
                                    <th width="20%" class="text-center"> Action </th>
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
                      <h4 class="modal-title">View Team Member </h4>
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
            ajax: '{!! route('admin.teamMember.getTeamMembers') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'image', name: 'image', orderable: false , class : "text-center"},
                { data: 'designation_name', name: 'designation_name', class : "text-center" },
                { data: 'status', name: 'status', class : "text-center" },
                { data: 'action', name: 'action', orderable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var team_member_id = $(this).attr('team_member_id');
            var url = "{{ route('admin.teamMember.show', ":team_member_id") }}";
            url = url.replace(':team_member_id', team_member_id);
            $('#showResult').html('');
            if(team_member_id.length != 0){
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
            var status_object = $(this);
            var team_member_id  = status_object.attr('team_member_id');
            var status    = status_object.attr('status');
            var url       = "{{ route('admin.teamMember.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        team_member_id: team_member_id,
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
            var team_member_id   = status_object.attr('team_member_id');
            var url         = "{{ route('admin.teamMember.delete') }}";

            var sttaus = confirm("Are you sure delete this team member?");

            if(sttaus) {

                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        team_member_id: team_member_id,
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

