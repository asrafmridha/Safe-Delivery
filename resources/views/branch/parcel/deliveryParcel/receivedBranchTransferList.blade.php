
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Received Branch Transfer List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Received Branch Transfers List</li>
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
                        <h3 class="card-title"> Received Branch Transfer List </h3>
                        <button class="btn btn-primary mr-2 float-right" type="button" id="printBtn">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="12%" class="text-center"> Branch Name </th>
                                    <th width="12%" class="text-center"> Branch Address </th>
                                    <th width="8%" class="text-center"> Branch Contact Number </th>
                                    <th width="8%" class="text-center"> Create Date </th>
                                    <th width="8%" class="text-center"> Received Date </th>
                                    <th width="10%" class="text-center"> Transfer Parcel </th>
                                    <th width="12%" class="text-center"> Received Parcel </th>
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
            pageLength: 100,
            lengthMenu: [[100,200,500,-1],[100,200,500,'All']],
            language : {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('branch.parcel.getReceivedBranchTransferList') !!}',
            columns: [
                { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                { data: 'delivery_transfer_invoice', name: 'delivery_transfer_invoice' , class : "text-center"},
                { data: 'from_branch.name', name: 'from_branch.name' , class : "text-center", render: (data, type, row) =>  (row && row.from_branch && row.from_branch.name) ? data : "--"},
                { data: 'from_branch.address', name: 'from_branch.address' , class : "text-center", render: (data, type, row) =>  (row && row.from_branch && row.from_branch.address) ? data : "--"},
                { data: 'from_branch.contact_number', name: 'from_branch.contact_number' , class : "text-center", render: (data, type, row) =>  (row && row.from_branch && row.from_branch.contact_number) ? data : "--"},
                { data: 'create_date_time', name: 'create_date_time', class : "text-center" },
                { data: 'received_date_time', name: 'received_date_time', class : "text-center" },
                { data: 'total_transfer_parcel', name: 'total_transfer_parcel', class : "text-center" },
                { data: 'total_transfer_received_parcel', name: 'total_transfer_received_parcel', class : "text-center" },
                { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
            ]
        });

        $(document).on('click', '#printBtn', function(){
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printReceivedBranchTransferList') !!}',
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

        $(document).on('click', '.print-modal', function(){
            var delivery_branch_transfer_id = $(this).attr('delivery_branch_transfer_id');
            var url = "{{ route('branch.parcel.printReceivedBranchTransfer', ":delivery_branch_transfer_id") }}";
            url = url.replace(':delivery_branch_transfer_id', delivery_branch_transfer_id);
            $.ajax({
                type: 'GET',
                url : url,
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

        $('#yajraDatatable').on('click', '.view-modal', function(){
            var delivery_branch_transfer_id = $(this).attr('delivery_branch_transfer_id');
            var url = "{{ route('branch.parcel.viewReceivedBranchTransfer', ":delivery_branch_transfer_id") }}";
            url = url.replace(':delivery_branch_transfer_id', delivery_branch_transfer_id);
            $('#showResult').html('');

            if(delivery_branch_transfer_id.length != 0){
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

        $('#yajraDatatable').on('click', '.received-branch-transfer-received-btn', function(){
            var delivery_branch_transfer_id = $(this).attr('delivery_branch_transfer_id');

            var url = "{{ route('branch.parcel.receivedBranchTransferReceived', ":delivery_branch_transfer_id") }}";
            url = url.replace(':delivery_branch_transfer_id', delivery_branch_transfer_id);
            $('#showResult').html('');
            if(delivery_branch_transfer_id.length != 0){
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

        $('#yajraDatatable').on('click', '.received-branch-transfer-reject-btn', function(){
            var delivery_branch_transfer_id = $(this).attr('delivery_branch_transfer_id');

            var url = "{{ route('branch.parcel.receivedBranchTransferReject', ":delivery_branch_transfer_id") }}";
            url = url.replace(':delivery_branch_transfer_id', delivery_branch_transfer_id);
            $('#showResult').html('');
            if(delivery_branch_transfer_id.length != 0){
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

