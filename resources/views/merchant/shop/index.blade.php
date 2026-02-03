
@extends('layouts.merchant_layout.merchant_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Merchant Shops</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Shops</li>
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
                        <h3 class="card-title"> Merchant Shop List </h3>
                        <a href="{{ route('merchant.shop.create') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Add Merchant Shop
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="6%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Shop Name </th>
                                    <th width="10%" class="text-center"> Shop Address </th>
                                    <th width="7%" class="text-center"> Status </th>
                                    <th width="17.5%" class="text-center"> Action </th>
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
                      <h4 class="modal-title">View Merchant Shop</h4>
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
            ajax: '{!! route('merchant.shop.getShops') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'shop_name', name: 'shop_name' , class : "text-center"},
                { data: 'shop_address', name: 'shop_address' , class : "text-center"},
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ],
            order: [[1, 'ASC']]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var shop_id = $(this).attr('shop_id');
            var url = "{{ route('merchant.shop.show', ":shop_id") }}";
            url = url.replace(':shop_id', shop_id);
            $('#showResult').html('');
            if(shop_id.length != 0){
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
            var shop_id     = status_object.attr('shop_id');
            var status          = status_object.attr('status');
            var url             = "{{ route('merchant.shop.updateStatus') }}";

            $.ajax({
                cache     : false,
                type      : "POST",
                dataType  : "JSON",
                data      : {
                        shop_id: shop_id,
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
            var shop_id   = status_object.attr('shop_id');
            var url         = "{{ route('merchant.shop.delete') }}";

            var sttaus = confirm("Are you sure delete this shop?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        shop_id: shop_id,
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

