
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Item</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
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
                        <a href="{{ route('admin.item.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Item
                        </a>
                        <button class="btn btn-info mr-2 float-right" data-toggle="modal" data-target="#viewModal">
                            <i class="fas fa-file-excel"></i> Import Execel
                        </button>
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
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
                                    <th width="10%" class="text-center"> HD Rate </th>
                                    <th width="10%" class="text-center"> Transit OD </th>
                                    <th width="10%" class="text-center"> Transit HD </th>
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


  <div class="modal fade" id="viewModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Excel Import </h4>
          <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="showResult">
            <form role="form" action="{{ route('admin.item.excelImportStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="item_name">Execel File</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="item_name"></label>
                    <button class="btn btn-sm btn-success" type="submit">Submit</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
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
                ajax: '{!! route('admin.item.getItems') !!}',
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false},
                    { data: 'item_name', name: 'item_name' , class : "text-center"},
                    { data: 'item_categories.name', name: 'item_categories.name' , class : "text-center"},
                    { data: 'units.name', name: 'units.name' , class : "text-center"},
                    { data: 'od_rate', name: 'od_rate' , class : "text-center"},
                    { data: 'hd_rate', name: 'hd_rate' , class : "text-center"},
                    { data: 'transit_od', name: 'transit_od' , class : "text-center"},
                    { data: 'transit_hd', name: 'transit_hd' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $('#yajraDatatable').on('click', '.updateStatus', function(){
                var status_object   = $(this);
                var item_id     = status_object.attr('item_id');
                var status          = status_object.attr('status');
                var url             = "{{ route('admin.item.updateStatus') }}";

                $.ajax({
                    cache     : false,
                    type      : "POST",
                    dataType  : "JSON",
                    data      : {
                        item_id: item_id,
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
                var item_id   = status_object.attr('item_id');
                var url         = "{{ route('admin.item.delete') }}";

                $.ajax({
                    cache       : false,
                    type        : "DELETE",
                    dataType    : "JSON",
                    data        : {
                        item_id: item_id,
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
                    url: '{!! route('admin.item.printItems') !!}',
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

