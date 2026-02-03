
@extends('layouts.branch_layout.branch_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pending/Reschedule Delivery  Parcel List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Pending/Reschedule Delivery Parcels List</li>
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
                        <h3 class="card-title"> Pending/Reschedule Delivery Parcels List </h3>
                        <a href="{{ route('branch.parcel.deliveryRiderRunGenerate') }}" class="btn btn-success float-right">
                            <i class="fa fa-pencil-alt"></i> Generate Delivery Rider Run
                        </a>


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
                        <table id="yajraDatatable" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Date</th>
                                    <th width="10%" class="text-center"> Invoice</th>
                                    <th width="10%" class="text-center"> Parcel Code</th>
                                    <th width="10%" class="text-center"> Company Name</th>
                                    <th width="12%" class="text-center"> Customer Name </th>
                                    <th width="12%" class="text-center"> Customer Address </th>
                                    <th width="8%" class="text-center"> Customer Number </th>
                                    <th width="10%" class="text-center"> Area </th>
                                    <th width="7%" class="text-center"> Collection Amount </th>
                                    <th width="7%" class="text-center"> COD Charge </th>
                                    <th width="7%" class="text-center"> Delivery Charge </th>
                                    <th width="7%" class="text-center"> Weight Package Charge </th>
                                    <th width="7%" class="text-center"> Total Charge </th>
                                    <th width="14%" class="text-center"> Status </th>
                                    <th width="22%" class="text-center"> Action </th>
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
                    url: '{!! route('branch.parcel.getRescheduleDeliveryParcelList') !!}',
                    data: {
                        from_date: from_date,
                        to_date: to_date
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'reschedule_parcel_date', name: 'reschedule_parcel_date' , class : "text-center"},
                    { data: 'parcel_invoice', name: 'parcel_invoice' , class : "text-center"},
                    { data: 'parcel_code', name: 'parcel_code' , class : "text-center"},
                    { data: 'merchant.company_name', name: 'merchant.company_name' , class : "text-center"},
                    { data: 'customer_name', name: 'customer_name' , class : "text-center"},
                    { data: 'customer_address', name: 'customer_address' , class : "text-center"},
                    { data: 'customer_contact_number', name: 'customer_contact_number', class : "text-center" },
                    { data: 'area.name', name: 'area.name', class : "text-center" },
                    { data: 'total_collect_amount', name: 'total_collect_amount', class : "text-center" },
                    { data: 'cod_charge', name: 'cod_charge', class : "text-center" },
                    { data: 'delivery_charge', name: 'delivery_charge', class : "text-center" },
                    { data: 'weight_package_charge', name: 'weight_package_charge', class : "text-center" },
                    { data: 'total_charge', name: 'total_charge', class : "text-center" },
                    { data: 'status', name: 'status' , searchable: false, class : "text-center" },
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ],
                order: [[1, 'DESC']]
            });
        }

        $(document).on('click', '#printBtn', function(){
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();
            $.ajax({
                type: 'GET',
                url: '{!! route('branch.parcel.printRescheduleDeliveryParcelList') !!}',
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

