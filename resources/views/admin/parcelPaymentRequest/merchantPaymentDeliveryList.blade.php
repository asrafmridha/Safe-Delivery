@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Merchant Delivery Payment List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Delivery Payments List</li>
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
                        <h3 class="card-title">Merchant Delivery Payment List </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="merchant_id">Merchant </label>
                                <select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Merchant  </option>
                                    @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" >{{ $merchant->company_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status">Delivery Payment Type </label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Delivery Payment Type </option>
                                    <option value="1" >Delivery Payment Send Merchant </option>
                                    <option value="2" >Merchant Accept </option>
                                    <option value="3" >Merchant Reject </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"/>
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control"/>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" id="printBtn" class="btn btn-primary" data-toggle="modal" data-target="#printViewList">
                                    Print
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="20%" class="text-center"> Merchant Company</th>
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

            <div class="modal fade" id="printModal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" id="showPrintResult">

                    </div>
                </div>
            </div>

            <div class="modal fade" id="printViewList">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" id="showBookingParcelList">

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

        function load_data(merchant_id = '', status = '', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!! route('admin.parcel.getMerchantPaymentDeliveryList') !!}',
                    data:{
                        merchant_id   : merchant_id,
                        status      : status,
                        from_date   : from_date,
                        to_date     : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'merchant_payment_invoice', name: 'merchant_payment_invoice' , class : "text-center"},
                    { data: 'merchant.company_name', name: 'merchant.company_name' , class : "text-center"},
                    { data: 'total_payment_parcel', name: 'total_payment_parcel', class : "text-center" },
                    { data: 'total_payment_received_parcel', name: 'total_payment_received_parcel', class : "text-center" },
                    { data: 'total_payment_amount', name: 'total_payment_amount', class : "text-center" },
                    { data: 'total_payment_received_amount', name: 'total_payment_received_amount', class : "text-center" },
                    { data: 'status', name: 'status' , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $('#filter').click(function(){
            var merchant_id   = $('#merchant_id option:selected').val();
            var status      = $('#status option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(merchant_id, status, from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $('#yajraDatatable').DataTable().destroy();
            load_data('', '', '', '');
        });


        $('#yajraDatatable').on('click', '.view-modal', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('admin.parcel.viewMerchantDeliveryPayment', ":parcel_delivery_payment_id") }}";
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


        $('#yajraDatatable').on('click', '.print-modal', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('admin.parcel.printMerchantDeliveryPayment', ":parcel_delivery_payment_id") }}";
            url = url.replace(':parcel_delivery_payment_id', parcel_delivery_payment_id);
            $('#showPrintResult').html('');
            if(parcel_delivery_payment_id.length != 0){
                $.ajax({
                    cache   : false,
                    type    : "GET",
                    error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                    url : url,
                    success : function(response){
                        $('#showPrintResult').html(response);
                    },
                })
            }
        });

        $('#yajraDatatable').on('click', '.merchant-delivery-payment-accept', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('admin.parcel.merchantDeliveryPaymentAccept', ":parcel_delivery_payment_id") }}";
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

        $('#yajraDatatable').on('click', '.delete-btn', function() {
            var parcel_delivery_payment_id = $(this).attr('merchant_payment_id');
            var url         = "{{ route('admin.parcel.merchantDeliveryPaymentDelete') }}";

            var sttaus = confirm("Are you sure delete this merchant delivery payment?");

            if(sttaus) {
                $.ajax({
                    cache: false,
                    type: "DELETE",
                    dataType: "JSON",
                    data: {
                        parcel_delivery_payment_id: parcel_delivery_payment_id,
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
                });
            }
        });


        $('#yajraDatatable').on('click', '.accept-branch-delivery-payment', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('admin.account.acceptBranchDeliveryPayment', ":parcel_delivery_payment_id") }}";
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
                        $('.select2').select2();
                    },
                })
            }
        });

        $('#yajraDatatable').on('click', '.reject-branch-delivery-payment', function(){
            var parcel_delivery_payment_id = $(this).attr('parcel_delivery_payment_id');
            var url = "{{ route('admin.account.rejectBranchDeliveryPayment', ":parcel_delivery_payment_id") }}";
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
                        $('.select2').select2();
                    },
                })
            }
        });

        $(document).on("click", "#printBtn", function () {
            var merchant_id   = $('#merchant_id option:selected').val();
            var status      = $('#status option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            var curr_date   = new Date().toISOString().slice(0, 10);

            $.ajax({
                url: "{{ route('admin.parcel.printMerchantDeliveryPaymentList') }}",
                type: "POST",
                data: {
                    merchant_id:merchant_id,
                    status:status,
                    from_date:from_date,
                    to_date:to_date,
                },
                success: function(response) {
                    $("#showBookingParcelList").html(response);
                }
            });
        });

    }

    {{--function printDiv(divID)--}}
    {{--{--}}
        {{--var divToPrint=document.getElementById(divID);--}}

        {{--var newWin=window.open('','Print-Window');--}}

        {{--newWin.document.open();--}}

        {{--newWin.document.write('<html><link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}"><link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}"><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');--}}

        {{--newWin.document.close();--}}
{{--//        newWin.addEventListener('afterprint', (event) => {--}}
{{--//                newWin.close();--}}
{{--//        });--}}
        {{--newWin.addEventListener('afterprint', function () {--}}
            {{--newWin.close();--}}
        {{--});--}}
    {{--}--}}

  </script>
@endpush

