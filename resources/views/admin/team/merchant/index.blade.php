
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Merchants</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchants</li>
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
                        <h3 class="card-title"> Merchants List </h3>
                        @if(auth()->guard('admin')->user()->type == 1)
                            <a href="{{ route('admin.merchant.create') }}" class="btn btn-success float-right">
                                <i class="fa fa-pencil-alt"></i> Add Merchant
                            </a>
                            <a href="{{ route('admin.merchant.merchantBulkImport') }}" class="btn btn-info float-right mr-2">
                                <i class="fa fa-pencil-alt"></i> Merchant Bulk Import
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
                                        <th class="text-center"> SL </th>
                                        <th class="text-center"> ID </th>
                                        <th class="text-center"> Joining Date </th>
                                        <th class="text-center"> Company Name </th>
                                        <th class="text-center"> Name </th>
                                        <th class="text-center"> Email </th>
                                        <th class="text-center"> Contact Number </th>
                                        <th class="text-center"> Branch </th>
                                        <th class="text-center"> COD </th>
                                        <th class="text-center"> Area </th>
{{--                                        <th class="text-center"> Upazila </th>--}}
                                        <th class="text-center"> District </th>
                                        <th class="text-center"> Status </th>
                                        <th class="text-center"> Action </th>
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
                      <h4 class="modal-title">View Merchant </h4>
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
            
             pageLength: 25,
                    lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'All']],
            language : {
                
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.merchant.getMerchants') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'm_id', name: 'm_id' , class : "text-center"},
                 { data: 'created_at', name: 'created_at' , class : "text-center"},
                { data: 'company_name', name: 'company_name' , class : "text-center"},
                { data: 'name', name: 'name' , class : "text-center"},
                { data: 'email', name: 'email' , class : "text-center"},
                { data: 'contact_number', name: 'contact_number' , class : "text-center"},
                { data: 'branch_name', name: 'branch_name' , class : "text-center"},
                { data: 'cod_charge', name: 'cod_charge' , class : "text-center"},
                { data: 'area.name', name: 'area.name' , class : "text-center", render: (data, type, row) =>  (row && row.area)? data : "--"},
                { data: 'district.name', name: 'district.name' , class : "text-center", render: (data, type, row) =>  (row && row.district)? data : "--"},
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ],
            order: [[1, 'DESC']]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var merchant_id = $(this).attr('merchant_id');
            var url = "{{ route('admin.merchant.show', ":merchant_id") }}";
            url = url.replace(':merchant_id', merchant_id);
            $('#showResult').html('');
            if(merchant_id.length != 0){
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
            var merchant_id     = status_object.attr('merchant_id');
            var status          = status_object.attr('status');
            var url             = "{{ route('admin.merchant.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        merchant_id: merchant_id,
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
            var merchant_id   = status_object.attr('merchant_id');
            var url         = "{{ route('admin.merchant.delete') }}";

            var sttaus = confirm("Are you sure delete this merchant?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        merchant_id: merchant_id,
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
                url: '{!! route('admin.merchant.printMerchants') !!}',
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


    function fullImage(domId)
    {
        //alert(domId);
        $("#"+domId).css({'height': 'auto', 'max-width':'100%'});
    }

    function smallImage(domId)
    {
        //alert(domId);
        $("#"+domId).css({'height': '100px', 'width':'auto'});
    }
  </script>
@endpush

