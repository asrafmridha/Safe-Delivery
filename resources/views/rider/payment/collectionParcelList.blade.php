
@extends('layouts.rider_layout.rider_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Collection Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('rider.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Collection Parcels List</li>
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
                        <h3 class="card-title">Collection Parcels List </h3>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="12%" class="text-center"> Invoice</th>
                                    <th width="12%" class="text-center"> Merchant Name </th>
                                    <th width="12%" class="text-center"> Customer Name </th>
                                    <th width="12%" class="text-center"> Customer Contact </th>
                                    <th width="12%" class="text-center"> Customer Address </th>
                                    <th width="8%" class="text-center"> Collection Amount </th>
                                    <th width="10%" class="text-center"> Status </th>
                                    {{--<th width="17%" class="text-center"> Action </th>--}}
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
            language : {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('rider.payment.getCollectionParcelList') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'parcel_invoice', name: 'parcel_invoice' , class : "text-center"},
                { data: 'merchant.name', name: 'merchant.name' , class : "text-center"},
                { data: 'customer_name', name: 'customer_name', class : "text-center" },
                { data: 'customer_contact_number', name: 'customer_contact_number', class : "text-center" },
                { data: 'customer_address', name: 'customer_address', class : "text-center" },
                { data: 'customer_collect_amount', name: 'customer_collect_amount' , class : "text-center"},
                { data: 'status', name: 'status' , searchable: false , class : "text-center"},
//                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });


        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_id = $(this).attr('parcel_id');
            var url = "{{ route('rider.parcel.viewParcel', ":parcel_id") }}";
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


    }
  </script>
@endpush

