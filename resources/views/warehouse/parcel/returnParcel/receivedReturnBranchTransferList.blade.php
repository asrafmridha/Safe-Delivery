@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Received Return Branch Transfer List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Received Return Branch Transfers List</li>
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
                        <h3 class="card-title"> Received Return Branch Transfer List </h3>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="10%" class="text-center"> Branch Name </th>
                                    <th width="10%" class="text-center"> Branch Address </th>
                                    <th width="8%" class="text-center"> Branch Contact Number </th>
                                    <th width="8%" class="text-center"> Create Date </th>
                                    <th width="8%" class="text-center"> Received Date </th>
                                    <th width="10%" class="text-center"> Transfer Parcel </th>
                                    <th width="12%" class="text-center"> Received Parcel </th>
                                    <th width="6%" class="text-center"> Status </th>
                                    <th width="15%" class="text-center"> Action </th>
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
            ajax: '{!! route('branch.parcel.getReceivedReturnBranchTransferList') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'return_transfer_invoice', name: 'return_transfer_invoice' , class : "text-center"},
                { data: 'from_branch.name', name: 'from_branch.name' , class : "text-center"},
                { data: 'from_branch.address', name: 'from_branch.address' , class : "text-center"},
                { data: 'from_branch.contact_number', name: 'from_branch.contact_number' , class : "text-center"},
                { data: 'create_date_time', name: 'create_date_time', class : "text-center" },
                { data: 'received_date_time', name: 'received_date_time', class : "text-center" },
                { data: 'total_transfer_parcel', name: 'total_transfer_parcel', class : "text-center" },
                { data: 'total_transfer_received_parcel', name: 'total_transfer_received_parcel', class : "text-center" },
                { data: 'status', name: 'status', class : "text-center" },
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var return_branch_transfer_id = $(this).attr('return_branch_transfer_id');
            var url = "{{ route('branch.parcel.viewReceivedReturnBranchTransfer', ":return_branch_transfer_id") }}";
            url = url.replace(':return_branch_transfer_id', return_branch_transfer_id);
            $('#showResult').html('');

            if(return_branch_transfer_id.length != 0){
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

        $('#yajraDatatable').on('click', '.received-return-branch-transfer-received-btn', function(){
            var return_branch_transfer_id = $(this).attr('return_branch_transfer_id');

            var url = "{{ route('branch.parcel.receivedReturnBranchTransferReceived', ":return_branch_transfer_id") }}";
            url = url.replace(':return_branch_transfer_id', return_branch_transfer_id);
            $('#showResult').html('');
            if(return_branch_transfer_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showResult').html(response);
                        $('.select2').select2();
                    },
                })
            }
        });

        $('#yajraDatatable').on('click', '.received-return-branch-transfer-reject-btn', function(){
            var return_branch_transfer_id = $(this).attr('return_branch_transfer_id');

            var url = "{{ route('branch.parcel.receivedReturnBranchTransferReject', ":return_branch_transfer_id") }}";
            url = url.replace(':return_branch_transfer_id', return_branch_transfer_id);
            $('#showResult').html('');
            if(return_branch_transfer_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showResult').html(response);
                        $('.select2').select2();
                    },
                })
            }
        });


    }

  </script>
@endpush

