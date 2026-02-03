
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Complete Return Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Complete Return Parcels List</li>
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
                        <h3 class="card-title"> Complete Return Parcels List </h3>
                        {{--<a href="{{ route('branch.parcel.returnRiderRunGenerate') }}" class="btn btn-success float-right">--}}
                            {{--<i class="fa fa-pencil-alt"></i> Generate Return Rider Run--}}
                        {{--</a>--}}
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajraDatatable" class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Invoice</th>
                                        <th width="12%" class="text-center"> Company Name </th>
                                        <th width="12%" class="text-center"> Merchant Number </th>
                                        <th width="12%" class="text-center"> Merchant Address </th>
                                        <th width="8%" class="text-center"> Upazila </th>
                                        <th width="10%" class="text-center"> Area </th>
                                        <th width="7%" class="text-center"> Charge </th>
                                        <th width="14%" class="text-center"> Status </th>
                                        <th width="22%" class="text-center"> Action </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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

    /*
    div.container {
        width: 80%;
    }
    */
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
            ajax: '{!! route('branch.parcel.getCompleteReturnParcelList') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'parcel_invoice', name: 'parcel_invoice' , class : "text-center"},
                { data: 'merchant.company_name', name: 'merchant.company_name' , class : "text-center"},
                { data: 'merchant.contact_number', name: 'merchant.contact_number' , class : "text-center"},
                { data: 'merchant.address', name: 'merchant.address' , class : "text-center"},
                { data: 'upazila.name', name: 'upazila.name', class : "text-center" },
                { data: 'area.name', name: 'area.name', class : "text-center" },
                { data: 'total_charge', name: 'total_charge', class : "text-center" },
                { data: 'status', name: 'status' , searchable: false, class : "text-center" },
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ],
            order:[[1, 'DESC']]
        });

        $(document).on('click', '#printBtn', function () {
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printCompleteReturnParcelList') !!}',
                data: {},
                dataType: 'html',
                success: function (html) {
                    w = window.open(window.location.href, "_blank");
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

    }
  </script>
@endpush

