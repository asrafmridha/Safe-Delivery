@extends('layouts.admin_layout.admin_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Booking Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Booking Parcels List</li>
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
                            <h3 class="card-title"> Booking Parcels List </h3>
                            <a href="javascript:void(0);" id="printView" class="btn btn-primary float-right" data-toggle="modal" data-target="#printViewList" style="margin-right: 5px;">
                                <i class="fa fa-printer"></i> Print
                            </a>

                            <a href="javascript:void(0)" id="pdfDownload" data-type="pdf" class="btn btn-info float-right" style="margin-right: 5px;">
                                <i class=""></i> PDF
                            </a>

                            <a href="javascript:void(0)" id="excelDownload" data-type="excel" class="btn btn-success float-right" style="margin-right: 5px;">
                                <i class=""></i> Excel
                            </a>

                            <div class="row input-daterange" style="margin-top: 40px">
                                <div class="col-md-2">
                                    <label for="branch_id">Branch </label>
                                    <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                        <option value="" >Select Branch  </option>
                                        @php
                                            if(count($branches) > 0) {
                                                foreach ($branches as $branch) {
                                                    echo '<option value="'.$branch->id.'" >'.$branch->name.'</option>';
                                                }
                                            }
                                        @endphp

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="booking_parcel_type">Booking Type </label>
                                            <select name="booking_parcel_type" id="booking_parcel_type" class="form-control select2" style="width: 100%" >
                                                <option value="" >Select Booking Type  </option>
                                                <option value="cash" >Cash </option>
                                                <option value="to_pay" >To Pay </option>
                                                <option value="credit" >Credit</option>
                                                <option value="condition" >Condition </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="delivery_type">Delivery Type </label>
                                            <select name="delivery_type" id="delivery_type" class="form-control select2" style="width: 100%" >
                                                <option value="" >Select Delivery Type </option>
                                                <option value="od" >OD </option>
                                                <option value="tod" >TOD </option>
                                                <option value="hd" >HD</option>
                                                <option value="thd" >THD </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="status">Status </label>
                                    <select name="status" id="status" class="form-control select2" style="width: 100%" >
                                        <option value="" >Select Status </option>
                                        <option value="0" >Parcel Reject</option>
                                        <option value="1" >Confirmed Booking </option>
                                        <option value="2" >Vehicle Assigned </option>
                                        <option value="3" >Warehouse Assigned </option>
                                        <option value="4" >Warehouse received Parcel </option>
                                        <option value="5" >Warehouse to Warehouse Assigned </option>
                                        <option value="6" >On The Way to Destination </option>
                                        <option value="7" >Branch Received</option>
                                        <option value="8" >Delivery Complete </option>
                                        <option value="9" >Delivery Return </option>
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
                                <div class="col-md-1" style="margin-top: 20px">
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
                                        <th class="text-center"> SL </th>
                                        <th width="8%" class="text-center"> Date</th>
                                        <th class="text-center"> Parcel No</th>
                                        <th class="text-center"> Sender Contact </th>
                                        <th class="text-center"> Sender Branch </th>
                                        <th class="text-center"> Receiver Contact </th>
                                        <th class="text-center"> Receiver Branch </th>
                                        <th class="text-center"> Net Amount </th>
                                        <th class="text-center"> Delivery Type </th>
                                        <th class="text-center"> Status </th>
                                        <th class="text-center"> Action </th>
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
        window.onload = function() {

            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!!  route('admin.bookingParcel.getBookingList') !!}',
                    data:function (d) {
                        d.branch_id           = $("#branch_id").val();
                        d.booking_parcel_type = $("#booking_parcel_type").val();
                        d.delivery_type       = $("#delivery_type").val();
                        d.status              = $("#status").val();
                        d.from_date           = $("#from_date").val();
                        d.to_date             = $("#to_date").val();
                    }
                },
                order: [ [2, 'desc'] ],
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'booking_date', name: 'booking_date' , class : "text-center"},
                    { data: 'parcel_code', name: 'parcel_code' , class : "text-center"},
                    { data: 'sender_phone', name: 'sender_phone' , class : "text-center"},
                    { data: 'sender_branch.name', name: 'sender_branch.name' , class : "text-center"},
                    { data: 'receiver_phone', name: 'receiver_phone' , class : "text-center"},
                    { data: 'receiver_branch.name', name: 'receiver_branch.name' , class : "text-center"},
                    { data: 'net_amount', name: 'net_amount' , class : "text-center"},
                    { data: 'delivery_type', name: 'delivery_type' , class : "text-center"},
                    { data: 'status', name: 'status' , searchable: false , class : "text-center"},
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });

            $('#filter').click(function(event){
                event.preventDefault();
                table.draw('', true);
            });

            $(document).on('click', '#refresh', function(){
                $("#branch_id").val("").trigger("change");
                $("#booking_parcel_type").val("").trigger("change");
                $("#delivery_type").val("").trigger("change");
                $("#status").val("").trigger("change");
                $("#from_date").val("");
                $("#to_date").val("");
                table.ajax.reload('', true);
            });

            $('#yajraDatatable').on('click', '.view-modal', function() {
                var booking_id = $(this).attr('booking_id');
                var url = "{{ route('admin.bookingParcel.viewBookingParcel', ':booking_id') }}";
                url = url.replace(':booking_id', booking_id);
                $('#showResult').html('');
                if (booking_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showResult').html(response);
                        },

                    })
                }
            });

            $(document).on("click", "#excelDownload", function () {
                var branch_id   = $("#branch_id").val();
                var booking_type = $("#booking_parcel_type").val();
                var delivery_type = $("#delivery_type").val();
                var status = $("#status").val();
                var from_date  = $("#from_date").val();
                var to_date    = $("#to_date").val();

                var dowload_type = $(this).data('type');
                var curr_date   = new Date().toISOString().slice(0, 10);

                $.ajax({
                    xhrFields: {
                        responseType: 'blob',
                    },
                    url: "{{ route('bookingParcelExport') }}",
                    type: "GET",
                    data: {
                        branch_id:branch_id,
                        booking_type:booking_type,
                        delivery_type:delivery_type,
                        status:status,
                        from_date:from_date,
                        to_date:to_date,
                        download_type:dowload_type,
                    },
                    success: function(result, status, xhr) {

//                        console.log(result);
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'booking_parcel_list_'+curr_date+'.xlsx');

                        // The actual download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                    }
                });
            });

            $(document).on("click", "#pdfDownload", function () {
                var branch_id   = $("#branch_id").val();
                var booking_type = $("#booking_parcel_type").val();
                var delivery_type = $("#delivery_type").val();
                var status = $("#status").val();
                var from_date  = $("#from_date").val();
                var to_date    = $("#to_date").val();

                var dowload_type = $(this).data('type');
                var curr_date   = new Date().toISOString().slice(0, 10);

                $.ajax({
                    xhrFields: {
                        responseType: 'blob',
                    },
                    url: "{{ route('bookingParcelExport') }}",
                    type: "GET",
                    data: {
                        branch_id:branch_id,
                        booking_type:booking_type,
                        delivery_type:delivery_type,
                        status:status,
                        from_date:from_date,
                        to_date:to_date,
                        download_type:dowload_type,
                    },
                    success: function(result, status, xhr) {

//                        console.log(result);
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : 'booking_parcel_list_'+curr_date+'.pdf');

                        // The actual download
                        var blob = new Blob([result], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                    }
                });
            });

            $(document).on("click", "#printView", function () {
                var branch_id   = $("#branch_id").val();
                var booking_type = $("#booking_parcel_type").val();
                var delivery_type = $("#delivery_type").val();
                var status        = $("#status").val();
                var from_date  = $("#from_date").val();
                var to_date    = $("#to_date").val();

                var curr_date   = new Date().toISOString().slice(0, 10);

                $.ajax({
                    url: "{{ route('admin.bookingParcel.bookingParcelPrintList') }}",
                    type: "POST",
                    data: {
                        branch_id:branch_id,
                        booking_type:booking_type,
                        delivery_type:delivery_type,
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

    </script>
@endpush
