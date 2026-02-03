
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Branch Parcel Payment List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Branch Parcel Payments List</li>
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
                        <h3 class="card-title">Branch Parcel Payment List </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="branch_id">Branch </label>
                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Branch  </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" >{{ $branch->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status">Parcel Payment Type </label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Delivery Payment Type </option>
                                    <option value="1" >Send Request </option>
                                    <option value="2" >Request Accept </option>
                                    <option value="3" >Request Cancel </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value=""/>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="10%" class="text-center"> Branch </th>
                                    <th width="10%" class="text-center"> Payment Parcel </th>
                                    <th width="10%" class="text-center"> Received Payment Parcel</th>
                                    <th width="10%" class="text-center"> Payment Amount </th>
                                    <th width="10%" class="text-center"> Received Payment Amount</th>
                                    <th width="10%" class="text-center"> Date</th>
                                    <th width="10%" class="text-center"> Status </th>
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

        load_data();

        function load_data(branch_id = '', status = '', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    type: "POST",
                    url:'{!! route('admin.account.traditional.branchParcelPaymentList') !!}',
                    data:{
                        _token      : "{{ csrf_token() }}",
                        branch_id   : branch_id,
                        status      : status,
                        from_date   : from_date,
                        to_date     : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'bill_no', name: 'bill_no' , class : "text-center"},
                    { data: 'branch.name', name: 'branch.name' , class : "text-center"},
                    { data: 'payment_parcel', name: 'payment_parcel', class : "text-center" },
                    { data: 'receive_parcel', name: 'receive_parcel', class : "text-center" },
                    { data: 'total_amount', name: 'total_amount', class : "text-center" },
                    { data: 'receive_amount', name: 'receive_amount', class : "text-center" },
                    { data: 'payment_date', name: 'payment_date' , class : "text-center"},
                    { data: 'payment_status', name: 'payment_status' , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $('#filter').click(function(){
            var branch_id   = $('#branch_id option:selected').val();
            var status      = $('#status option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(branch_id, status, from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $('#yajraDatatable').DataTable().destroy();
            load_data('', '', '', '');
        });



        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_payment_id = $(this).attr('parcel_payment_id');
            var url = "{{ route('admin.account.traditional.viewBranchParcelPayment', ":parcel_payment_id") }}";
            url = url.replace(':parcel_payment_id', parcel_payment_id);
            $('#showResult').html('');
            if(parcel_payment_id.length != 0){
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


        $('#yajraDatatable').on('click', '.accept-branch-parcel-payment', function(){
            var parcel_payment_id = $(this).attr('parcel_payment_id');
            var url = "{{ route('admin.account.traditional.acceptBranchParcelPayment', ":parcel_payment_id") }}";
            url = url.replace(':parcel_payment_id', parcel_payment_id);
            $('#showResult').html('');
            if(parcel_payment_id.length != 0){
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

        $('#yajraDatatable').on('click', '.reject-branch-parcel-payment', function(){
            var parcel_payment_id = $(this).attr('parcel_payment_id');
            var url = "{{ route('admin.account.traditional.rejectBranchParcelPayment', ":parcel_payment_id") }}";
            url = url.replace(':parcel_payment_id', parcel_payment_id);
            $('#showResult').html('');
            if(parcel_payment_id.length != 0){
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

