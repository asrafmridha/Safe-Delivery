
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pickup Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Pickup Parcels List</li>
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
                        <h3 class="card-title"> Pickup Parcels List</h3>
                        <a href="{{ route('branch.parcel.pickupRiderRunGenerate') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Generate Pickup Rider Run
                        </a>
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center">Date</th>
                                    <th width="10%" class="text-center"> Invoice</th>
                                    <th width="10%" class="text-center"> Status </th>
                                    <th width="12%" class="text-center"> Merchant Name</th>
                                    <th width="12%" class="text-center"> Merchant Contact Number </th>
                                    <th width="12%" class="text-center"> Customer Name</th>
                                    <th width="8%" class="text-center"> Pickup Address </th>
                                    <th width="8%" class="text-center"> District </th>
                                    <!--<th width="8%" class="text-center"> Area</th>-->
                                    <th width="7%" class="text-center"> Collectable Amount </th>
                                    <th width="10%" class="text-center"> Action </th>
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
    <style>
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            margin: 0 auto;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    window.onload = function(){

        var table = $('#yajraDatatable').DataTable({
            pageLength: 100,
            lengthMenu: [[100,200,500,-1],[100,200,500,'All']],
            language : {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('branch.parcel.getPickupParcelList') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'pickup_branch_date', name: 'pickup_branch_date' , class : "text-center"},
                { data: 'parcel_invoice', name: 'parcel_invoice' , class : "text-center"},
                { data: 'parcel_status', name: 'parcel_status' , searchable: false, class : "text-center" },
                { data: 'merchant.name', name: 'merchant.name' , class : "text-center", render: (data, type, row) =>  (row && row.merchant && row.merchant.company_name )? data : "--"},
                { data: 'merchant.contact_number', name: 'merchant.contact_number' , class : "text-center", render: (data, type, row) =>  (row && row.merchant && row.merchant.contact_number)? data : "--"},
                { data: 'customer_name', name: 'customer_name' , class : "text-center"},
                { data: 'pickup_address', name: 'pickup_address' , class : "text-center"},
                { data: 'district.name', name: 'district.name', class : "text-center" },
                //{ data: 'upazila.name', name: 'upazila.name', class : "text-center" },
               // {data: 'area.name', name: 'area.name', class: "text-center"},
                { data: 'total_collect_amount', name: 'total_collect_amount', class : "text-center" },
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ],
            createdRow: function ( row, data, index ) {
                $('td', row).eq(3).addClass(`bg-${data['parcel_color']}`);
            }
        });


        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_id = $(this).attr('parcel_id');
            var url = "{{ route('branch.parcel.viewParcel', ":parcel_id") }}";
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
            var url         = "{{ route('branch.parcel.delete') }}";

            if(confirm("Are you sure delete this parcel!")) {

                $.ajax({
                    cache       : false,
                    type        : "DELETE",
                    dataType    : "JSON",
                    data        : {
                        parcel_id: parcel_id,
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
                });
            }
        });

        $(document).on('click', '#printBtn', function(){
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printPickupParcelList') !!}',
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

