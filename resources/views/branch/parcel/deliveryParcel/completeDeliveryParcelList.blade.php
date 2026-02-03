
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Complete Delivery Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Complete Delivery Parcels List</li>
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
                        <h3 class="card-title"> Complete Delivery Parcels List </h3>

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

                            <div class="col-md-5">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                            </div>
                            <div class="col-md-5">
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
                        <div class="table-responsive">
                            <table id="yajraDatatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
<!--
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="10%" class="text-center"> Date</th>
                                        <th width="10%" class="text-center"> Invoice</th>
                                        <th width="10%" class="text-center"> Parcel Code</th>
                                        <th width="10%" class="text-center"> Company Name</th>
                                        <th width="12%" class="text-center"> Customer Name </th>
                                        <th width="12%" class="text-center"> Customer Address </th>
                                        <th width="8%" class="text-center"> Upazila </th>
                                        <th width="10%" class="text-center"> Area </th>
                                        <th width="7%" class="text-center"> Collection Amount </th>
                                        <th width="7%" class="text-center"> COD Charge </th>
                                        <th width="7%" class="text-center"> Delivery Charge </th>
                                        <th width="7%" class="text-center"> Weight Package Charge </th>
                                        <th width="7%" class="text-center"> Total Charge </th>
                                        <th width="14%" class="text-center"> Status </th>
                                        <th width="22%" class="text-center"> Action </th>

-->

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
                                        <th class="text-center"> Action</th>
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
<script>
    window.onload = function(){

        load_data();

        function load_data(from_date="", to_date ="") {
            var table = $('#yajraDatatable').DataTable({
                pageLength: 100,
                lengthMenu: [[100,200,500,-1],[100,200,500,'All']],
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('branch.parcel.getCompleteDeliveryParcelList') !!}',
                    data: {
                        from_date: from_date,
                        to_date: to_date
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

        $(document).on('click', '#printBtn', function(){
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printCompleteDeliveryParcelList') !!}',
                data: {
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
//            var run_status  = $('#run_status option:selected').val();
//            var rider_id    = $('#rider_id option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $("#from_date").val("");
            $("#to_date").val("");
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

    }
  </script>
@endpush

