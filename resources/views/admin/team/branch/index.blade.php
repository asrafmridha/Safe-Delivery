
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Branches</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Branches</li>
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
                        <h3 class="card-title"> Branches List </h3>
                        @if(auth()->guard('admin')->user()->type == 1)
                            <a href="{{ route('admin.branch.create') }}" class="btn btn-success float-right">
                                <i class="fa fa-pencil-alt"></i> Add Branch
                            </a>
                        @endif
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="6%" class="text-center"> SL </th>
                                    <th width="15%" class="text-center"> Name </th>
                                    <th width="10%" class="text-center"> Email </th>
                                    <th width="15%" class="text-center"> Address </th>
                                    <th width="10%" class="text-center"> Type </th>
                                    <th width="15%" class="text-center"> Parent </th>
                                    <th width="15%" class="text-center"> District </th>
                                    <th width="10%" class="text-center"> Area </th>
                                    <th width="7%" class="text-center"> Status </th>
                                    <th width="17.5%" class="text-center"> Action </th>
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
                      <h4 class="modal-title">View Branch </h4>
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
//            deferRender: true,
            ajax: '{!! route('admin.branch.getBranches') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'email', name: 'email' , class : "text-center"},
                { data: 'address', name: 'address' , class : "text-center"},
                { data: 'type', name: 'type' , class : "text-center"},
                { data: 'parent_id', name: 'parent_id' , class : "text-center"},
                { data: 'district.name', name: 'district.name' , class : "text-center", render: (data, type, row) =>  (row && row.district)? data : "--" },
                { data: 'area.name', name: 'area.name' , class : "text-center", render: (data, type, row) =>  (row && row.area)? data : "--" },
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var branch_id = $(this).attr('branch_id');
            var url = "{{ route('admin.branch.show', ":branch_id") }}";
            url = url.replace(':branch_id', branch_id);
            $('#showResult').html('');
            if(branch_id.length != 0){
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
            var branch_id     = status_object.attr('branch_id');
            var status          = status_object.attr('status');
            var url             = "{{ route('admin.branch.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        branch_id: branch_id,
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
            var branch_id   = status_object.attr('branch_id');
            var url         = "{{ route('admin.branch.delete') }}";

            var sttaus = confirm("Are you sure delete this branch?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        branch_id: branch_id,
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
                url: '{!! route('admin.branch.printBranches') !!}',
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

