
@extends('layouts.branch_layout.branch_layout')

@section('content')

<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">All Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">All Parcels List</li>
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
                        <h3 class="card-title"> All Parcels List </h3>

                        <div class="row" style="margin-top: 40px">

                            {{--<div class="col-md-3">--}}
                                {{--<label for="rider_id">Rider</label>--}}
                                {{--<select name="rider_id" id="rider_id" class="form-control select2" style="width: 100%" >--}}
                                    {{--<option value="0" >Select Rider </option>--}}
                                    {{--@foreach ($riders as $rider)--}}
                                        {{--<option value="{{ $rider->id }}" > {{ $rider->name }} </option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label for="run_status">Run Status</label>--}}
                                {{--<select name="run_status" id="run_status" class="form-control select2" style="width: 100%" >--}}
                                    {{--<option value="0" >Select Run Status </option>--}}
                                    {{--<option value="1" >Run Create </option>--}}
                                    {{--<option value="2" >Run Start </option>--}}
                                    {{--<option value="3" >Run Cancel </option>--}}
                                    {{--<option value="4" >Run Complete </option>--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            <div class="col-md-10">
                                 <form id="filterForm">
                                <div class="row">
                                    
                                    <div class="col-sm-12 col-md-3">
                                        <label for="parcel_status">Parcel Status</label>
                                        <select name="parcel_status" id="parcel_status" class="form-control select2" style="width: 100%" >
                                            <option value="0">Select Parcel Status </option>
                                            {{--<option value="1">Complete Delivery </option>--}}
                                            {{--<option value="2">Partial Delivery </option>--}}
                                            {{--<option value="3">Return Parcel </option>--}}
                                            {{--<option value="4">Waiting For Pickup</option>--}}
                                            {{--<option value="5">Waiting For Delivery </option>--}}
                                            {{--<option value="6">Cancel Parcel </option>--}}

                                            <option value="1">Delivered</option>
                                            <option value="2">Delivery Pending</option>
                                            <option value="3">Canceled</option>
                                            <option value="4">Paid</option>
                                            <option value="5">Payment Pending</option>
                                            <option value="6">Returned </option>
                                            <option value="7">Pickup Request </option>
                                            <option value="10">Branch Transfer </option>
                                            <option value="8">At Delivery Hub</option>
                                            <option value="9">Picked up </option>
                                            <option value="11">Rider Delivered</option>
                                            <option value="12">Rescheduled</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-12 col-md-3">
                                        <label for="merchant_id"> Merchant</label>
                                        <select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" required>
                                            <option value="0">Select Company</option>
                                            @foreach ($merchants as $merchant)
                                                <option value="{{ $merchant->id }}">{{ $merchant->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                      <div class="col-sm-12 col-md-4" style="margin-top: 20px">
                                        <input type="text" name="parcel_invoice" id="parcel_invoice"
                                               class="form-control" placeholder="Enter Parcel Invoice / Order ID / C. Number "
                                               style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                            padding: 3px 0px 3px 3px;
                                            margin: 5px 1px 3px 0px;
                                            border: 1px solid rgb(62, 196, 118);">
                                    </div>

                                    <div class="col-sm-12 col-md-3">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value=""/>
                                    </div>


                                  

                                    <!--<div class="col-sm-12 col-md-3">-->
                                       
                                    <!--</div>-->


                                    <!--<div class="col-sm-12 col-md-4" style="margin-top: 20px">-->
                                    <!--    <input type="text" name="merchant_order_id" id="merchant_order_id"-->
                                    <!--           class="form-control" placeholder="Enter Merchant Order ID"-->
                                    <!--           style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);-->
                                    <!--        padding: 3px 0px 3px 3px;-->
                                    <!--        margin: 5px 1px 3px 0px;-->
                                    <!--        border: 1px solid rgb(62, 196, 118);">-->
                                    <!--</div>-->

                                    <!--<div class="col-sm-12 col-md-4" style="margin-top: 20px">-->
                                    <!--    <input type="text" name="customer_contact_number"-->
                                    <!--           id="customer_contact_number" class="form-control"-->
                                    <!--           placeholder="Customer Number"-->
                                    <!--           style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);-->
                                    <!--        padding: 3px 0px 3px 3px;-->
                                    <!--        margin: 5px 1px 3px 0px;-->
                                    <!--        border: 1px solid rgb(62, 196, 118);">-->
                                    <!--</div>-->




                                </div>
                                 </form>
                                
                            </div>
                            <div class="col-sm-12 col-md-2" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" name="printBtn" id="printBtn" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                                
                                 <form action="{{route('branch.parcel.excelAllParcelList')}}" method="post">
                                            @csrf
                                            <input type="hidden" id="ex_parcel_status" name="ex_parcel_status" value="0">
                                            <input type="hidden" id="ex_merchant_id" name="ex_merchant_id" value="0">
                                            <input type="hidden" id="ex_delivery_branch_id" name="ex_delivery_branch_id" value="0">
                                            <input type="hidden" id="ex_parcel_invoice" name="ex_parcel_invoice" value="">
                                            <input type="hidden" id="ex_from_date" name="ex_from_date" value="">
                                            <input type="hidden" id="ex_to_date" name="ex_to_date" value="">
                                            <button type="submit" class="btn btn-primary mt-4">
                                                <i class="fas fa-file-excel"></i>
                                            </button>
                                        </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="yajraDatatable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center"> SL</th>
                                    <th class="text-center"> Invoice</th>
                                    <th class="text-center"> Status</th>
                                    <th class="text-center"> Parcel</th>
                                    <th class="text-center"> Company</th>
                                    <th class="text-center"> Customer</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center"> Remarks / Notes</th>
                                    <th class="text-center"> Payment Status</th>
                                    <th class="text-center"> Return Status</th>
                                    <th class="text-center" > Action</th>
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
        th, td p{
            margin-bottom: 0;
            white-space: nowrap;
        }
        th, td .parcel_status{
            white-space: nowrap;
        }

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
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>
<script>
    window.onload = function(){

        load_data();

        function load_data(parcel_status = "",parcel_invoice = "", merchant_order_id = "", customer_contact_number = "", merchant_id="", from_date="", to_date ="") {
            var table = $('#yajraDatatable').DataTable({
               
                pageLength: 50,
                lengthMenu: [[50,100, 200, 500, -1], [50, 100, 200, 500, 'All']],
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('branch.parcel.getAllParcelList') !!}',
                    data: {
                        parcel_status: parcel_status,
                        merchant_id: merchant_id,
                        from_date: from_date,
                        to_date: to_date,
                        parcel_invoice: parcel_invoice,
                        merchant_order_id: merchant_order_id,
                        customer_contact_number: customer_contact_number,
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false, class: "text-center"},
                    {data: 'parcel_invoice', name: 'parcel_invoice'},
                    {data: 'parcel_status', name: 'parcel_status', searchable: false, class: "text-center"},
                    {data: 'parcel_info', name: 'parcel_info'},
                    {data: 'company_info', name: 'company_info'},
                    {data: 'customer_info', name: 'customer_info'},
                    {data: 'amount', name: 'amount'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'payment_status', name: 'payment_status', searchable: false, class: "text-center"},
                    {data: 'return_status', name: 'return_status', searchable: false, class: "text-center"},
                    {data: 'action', name: 'action', orderable: false, searchable: false, class: "text-center"}
                ],
                order: [[1, 'DESC']],
                scrollY:        "400px",
                scrollX:        true,
                scrollCollapse: true,
                createdRow: function ( row, data, index ) {
                    // $('td', row).eq(4).addClass(`bg-${data['parcel_color']}`);
                },
            });
        }

        $('#filter').click(function(){
            var parcel_status  = $('#parcel_status option:selected').val();
            var merchant_id    = $('#merchant_id option:selected').val();
            var from_date   = $('#from_date').val();
            // console.log('from_date');
            var to_date     = $('#to_date').val();
            var parcel_invoice = $('#parcel_invoice').val();
            var merchant_order_id = $('#merchant_order_id').val();
            var customer_contact_number = $('#customer_contact_number').val();

            $('#ex_parcel_status').val(parcel_status);
            $('#ex_merchant_id').val(merchant_id);
            $('#ex_from_date').val(from_date);
            $('#ex_to_date').val(to_date);
            $('#ex_parcel_invoice').val(parcel_invoice);
            $('#ex_merchant_order_id').val(merchant_order_id);




            $('#yajraDatatable').DataTable().destroy();
            load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number, merchant_id, from_date, to_date);
        });
        
        $('#filterForm').submit(function() {
            var parcel_status  = $('#parcel_status option:selected').val();
            var merchant_id    = $('#merchant_id option:selected').val();
            var from_date   = $('#from_date').val();
            // console.log('from_date');
            var to_date     = $('#to_date').val();
            var parcel_invoice = $('#parcel_invoice').val();
            var merchant_order_id = $('#merchant_order_id').val();
            var customer_contact_number = $('#customer_contact_number').val();

            $('#ex_parcel_status').val(parcel_status);
            $('#ex_merchant_id').val(merchant_id);
            $('#ex_from_date').val(from_date);
            $('#ex_to_date').val(to_date);
            $('#ex_parcel_invoice').val(parcel_invoice);
            $('#ex_merchant_order_id').val(merchant_order_id);




            $('#yajraDatatable').DataTable().destroy();
            load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number, merchant_id, from_date, to_date);
             return false;
        });

        $(document).on('click', '#refresh', function(){
            $("#parcel_status").val("0").trigger('change');
            $("#merchant_id").val("0").trigger('change');
            $("#from_date").val("");
            $("#to_date").val("");
            $("#parcel_invoice").val("");
            $("#merchant_order_id").val("");
            $("#customer_contact_number").val("");
            $('#yajraDatatable').DataTable().destroy();
            load_data('', '');
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

            var parcel_status           = $('#parcel_status option:selected').val();
            var merchant_id             = $('#merchant_id option:selected').val();
            var from_date               = $('#from_date').val();
            var to_date                 = $('#to_date').val();
            var parcel_invoice = $('#parcel_invoice').val();
            var merchant_order_id = $('#merchant_order_id').val();
            var customer_contact_number = $('#customer_contact_number').val();

            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printAllParcelList') !!}',
                data: {
                    parcel_status   : parcel_status,
                    merchant_id     : merchant_id,
                    from_date       : from_date,
                    to_date         : to_date,
                    parcel_invoice: parcel_invoice,
                    merchant_order_id: merchant_order_id,
                    customer_contact_number: customer_contact_number,
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

    }
  </script>
@endpush

