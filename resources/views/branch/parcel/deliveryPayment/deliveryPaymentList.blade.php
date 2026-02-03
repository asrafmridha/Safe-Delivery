@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Delivery Payment List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Delivery Payments List</li>
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
                        <h3 class="card-title"> Delivery Payment List </h3>
                        <a href="{{ route('branch.parcel.deliveryPaymentGenerate') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Delivery Payment
                        </a>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-4">
                                <label for="status">Delivery Payment Type </label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                    <option value="" >Select Delivery Payment Type </option>
                                    <option value="1" >Send Request </option>
                                    <option value="2" >Request Accept </option>
                                    <option value="3" >Request Cancel </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                            </div>
                            <div class="col-md-3">
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
                                <button class="btn btn-primary" type="button" id="printBtn">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Date </th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="20%" class="text-center"> Admin </th>
                                    <th width="10%" class="text-center"> Payment Parcel </th>
                                    <th width="10%" class="text-center"> Received Payment Parcel</th>
                                    <th width="10%" class="text-center"> Payment Amount </th>
                                    <th width="10%" class="text-center"> Received Payment Amount</th>
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

        function load_data(status = '', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                pageLength: 100,
                lengthMenu: [[100,200,500,-1],[100,200,500,'All']],
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!! route('branch.parcel.getDeliveryPaymentList') !!}',
                    data:{
                        status      : status,
                        from_date   : from_date,
                        to_date     : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'date_time', name: 'date_time' , class : "text-center"},
                    { data: 'payment_invoice', name: 'payment_invoice' , class : "text-center"},
                    { data: 'admin.name', name: 'admin.name' , class : "text-center"},
                    { data: 'total_payment_parcel', name: 'total_payment_parcel', class : "text-center" },
                    { data: 'total_payment_received_parcel', name: 'total_payment_received_parcel', class : "text-center" },
                    { data: 'total_payment_amount', name: 'total_payment_amount', class : "text-center" },
                    { data: 'total_payment_received_amount', name: 'total_payment_received_amount', class : "text-center" },
                    { data: 'status', name: 'status' , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $(document).on('click', '#printBtn', function(){
            var status      = $('#status option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printDeliveryPaymentList') !!}',
                data: {
                    status   : status,
                    from_date   : from_date,
                    to_date     : to_date,
                },
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

        $('#filter').click(function(){
            var status      = $('#status option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(status, from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $("#status").val("").trigger('change');
            $("#from_date").val("");
            $("#to_date").val("");
            $('#yajraDatatable').DataTable().destroy();
            load_data('', '', '');
        });


        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('branch.parcel.viewDeliveryPayment', ":parcel_delivery_payment_id") }}";
            url = url.replace(':parcel_delivery_payment_id', parcel_delivery_payment_id);
            $('#showResult').html('');
            if(parcel_delivery_payment_id.length != 0){
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

        $(document).on('click', '.print-modal', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('branch.parcel.printDeliveryPayment', ":parcel_delivery_payment_id") }}";
            url = url.replace(':parcel_delivery_payment_id', parcel_delivery_payment_id);
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


        $('#yajraDatatable').on('click', '.run-start-btn', function(){
            var status_object = $(this);
            var rider_run_id   = status_object.attr('rider_run_id');
            var url         = "{{ route('branch.parcel.startDeliveryRiderRun') }}";

            $.ajax({
                cache       : false,
                type        : "POST",
                dataType    : "JSON",
                data        : {
                    rider_run_id   : rider_run_id,
                    _token      : "{{ csrf_token() }}"
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

        $('#yajraDatatable').on('click', '.run-cancel-btn', function(){
            var status_object = $(this);
            var rider_run_id   = status_object.attr('rider_run_id');
            var url         = "{{ route('branch.parcel.cancelDeliveryRiderRun') }}";

            $.ajax({
                cache       : false,
                type        : "POST",
                dataType    : "JSON",
                data        : {
                    rider_run_id   : rider_run_id,
                    _token      : "{{ csrf_token() }}"
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

        $('#yajraDatatable').on('click', '.rider-run-reconciliation', function(){
            var rider_run_id = $(this).attr('rider_run_id');
            var url = "{{ route('branch.parcel.deliveryRiderRunReconciliation', ":rider_run_id") }}";
            url = url.replace(':rider_run_id', rider_run_id);
            $('#showResult').html('');
            if(rider_run_id.length != 0){
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

