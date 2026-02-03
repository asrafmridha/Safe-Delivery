@extends('layouts.merchant_layout.merchant_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Parcels List</li>
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
                            <h3 class="card-title"> Parcels List </h3>
                            
                          <div class="card-body">  <a href="{{ route('merchant.parcel.merchantBulkParcelImport') }}"
                               class="btn btn-success float-right" style="margin-right: 20px;">
                                <i class="fas fa-file-excel"></i> Merchant Bulk Parcel Import
                            </a>
                            </div>
                            

                            <div class="row input-daterange" style="margin-top: 40px">
                                <div class="col-sm-12 col-md-2">
                                    <label for="parcel_status">Parcel Status</label>
                                    <select name="parcel_status" id="parcel_status" class="form-control select2"
                                            style="width: 100%">
                                        <option value="0">Select Parcel Status</option>
                                        {{--<option value="1">Complete Delivery </option>--}}
                                        {{--<option value="2">Partial Delivery </option>--}}
                                        {{--<option value="3">Return Parcel </option>--}}
                                        {{--<option value="4">Waiting For Pickup</option>--}}
                                        {{--<option value="5">Waiting For Delivery </option>--}}
                                        {{--<option value="6">Cancel Parcel </option>--}}

                                        <option value="1">Delivered</option>
                                        <option value="2">Delivery Pending</option>
                                        <option value="3">Cancelled </option>
                                        <option value="4">Paid</option>
                                        <option value="5">Unpaid</option>
                                        <option value="6">Return Complete</option>
                                        <option value="7">Pickup Pending</option>
                                        <option value="8">Rescheduled Delivery</option>

                                    </select>
                                </div>
                                
                                
                                <div class="col-sm-12 col-md-4" style="margin-top: 20px">
                                    <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control"
                                           placeholder="Enter Parcel Invoice / Order ID / C. Number"
                                           style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                                
                                
                                
                                <!--<div class="col-sm-12 col-md-2" style="margin-top: 20px">-->
                                <!--    <input type="text" name="merchant_order_id" id="merchant_order_id"-->
                                <!--           class="form-control" placeholder="Enter Merchant Order ID"-->
                                <!--           style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);-->
                                <!--    padding: 3px 0px 3px 3px;-->
                                <!--    margin: 5px 1px 3px 0px;-->
                                <!--    border: 1px solid rgb(62, 196, 118);">-->
                                <!--</div>-->
                                
                                
                                <!--<div class="col-sm-12 col-md-2" style="margin-top: 20px">-->
                                <!--    <input type="text" name="customer_contact_number" id="customer_contact_number"-->
                                <!--           class="form-control" placeholder="Customer Number"-->
                                <!--           style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);-->
                                <!--    padding: 3px 0px 3px 3px;-->
                                <!--    margin: 5px 1px 3px 0px;-->
                                <!--    border: 1px solid rgb(62, 196, 118);">-->
                                <!--</div>-->
                                
                                
                                <div class="col-sm-12 col-md-3">
                                    <label for="to_date">Date</label>
                                    <div class="input-group">
                                        <input type="date" name="from_date" id="from_date" class="form-control"/>
                                        <input type="date" name="to_date" id="to_date" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3" style="margin-top: 20px">
                                    <button type="button" name="filter" id="filter" class="btn btn-success">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" name="printBtn" id="printBtn" class="btn btn-primary">
                                        <i class="fas fa-print"></i>
                                    </button>
                                       <form action="{{route('merchant.parcel.excelAllParcelList')}}" method="post">
                                            @csrf
                                            <input type="hidden" id="ex_parcel_status" name="ex_parcel_status" value="0">
                                            <input type="hidden" id="ex_parcel_invoice" name="ex_parcel_invoice" value="">
                                            <input type="hidden" id="ex_from_date" name="ex_from_date" value="">
                                            <input type="hidden" id="ex_to_date" name="ex_to_date" value="">
                                            <button type="submit" class="btn btn-primary mt-4">
                                                <i class="fas fa-file-excel"></i>
                                            </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="yajraDatatable" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="4%"> SL</th>
                                        <th class="text-center" width="8%"> Invoice</th>
                                        <th class="text-center" width="10%"> Status</th>
                                        <th class="text-center" width="16%"> Parcel</th>
                                        <!--<th class="text-center"> Company</th>-->
                                        <th class="text-center" width="20%"> Customer</th>
                                        <th class="text-center" width="10%">Amount</th>
                                        <th class="text-center" width="12%"> Remarks / Notes</th>
                                        <th class="text-center" width="10%"> Payment/Return Status</th>
                                        <!--<th class="text-center"> Return Status</th>-->
                                        <th class="text-center" width="10%"> Action</th>
                                        <th class="text-center" width="10%">
                                            <button type="button" id="printMultiple" class="btn btn-primary btn-sm">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button type="button" id="checkAll" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                          
                                        </form>

                                        </th>

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
        th, td p {
            margin-bottom: 0;
            white-space: nowrap;
        }

        th, td .parcel_status {
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
        window.onload = function () {
            load_data();

            function load_data(parcel_status = '', parcel_invoice = '', merchant_order_id = '', customer_contact_number = '', from_date = '', to_date = '') {
                var table = $('#yajraDatatable').DataTable({
                    pageLength: 50,
                    lengthMenu: [[50, 100, 200, 500, -1], [50, 100, 200, 500, 'All']],
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('merchant.parcel.getParcelList') !!}',
                        data: {
                            parcel_status: parcel_status,
                            parcel_invoice: parcel_invoice,
                            merchant_order_id: merchant_order_id,
                            customer_contact_number: customer_contact_number,
                            from_date: from_date,
                            to_date: to_date,
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', orderable: false, searchable: false, class: "text-center"},
                        {data: 'parcel_invoice', name: 'parcel_invoice'},
                        {data: 'parcel_status', name: 'parcel_status', searchable: false, class: "text-center"},
                        {data: 'parcel_info', name: 'parcel_info'},
                        // {data: 'company_info', name: 'company_info'},
                        {data: 'customer_info', name: 'customer_info'},
                        {data: 'amount', name: 'amount'},
                        {data: 'remarks', name: 'remarks'},
                        {data: 'payment_status', name: 'payment_status', searchable: false, class: "text-center"},
                        // {data: 'return_status', name: 'return_status', searchable: false, class: "text-center"},
                        {data: 'action', name: 'action', orderable: false, searchable: false, class: "text-center"},
                        {data: 'print', name: 'print', orderable: false, searchable: false, class: "text-center"}
                    ],
                    createdRow: function (row, data, index) {
                        // $('td', row).eq(4).addClass(`bg-${data['parcel_color']}`);
                    },
                    scrollY: "400px",
                    scrollX: true,
                    scrollCollapse: true,
                    // paging:         false,
                    columnDefs: [
                        // { width: 2000, targets: 15 }
                    ],
                    fixedColumns: true,
                    order: [[1, 'DESC']]
                });
            }

            $(document).on('click', '#checkAll', function () {
                var checkboxes = document.getElementsByClassName('print-check');
                for (var checkbox of checkboxes) {
                    $(checkbox).prop('checked', true);
                }
            });

            $(document).on('click', '#printMultiple', function () {
                var parcel_ids = $(".print-check:checkbox:checked").map(function(){
                    return $(this).val();
                }).get();
                console.log(parcel_ids);
                /*
                var url = '{!! route('merchant.parcel.printParcelMultiple') !!}';
                var data =  {
                    parcel_ids: parcel_ids,
                };

                $.post(url, function (parcel_ids) {
                    var w = window.open('about:blank');
                    w.document.open();
                    w.document.write(data);
                    w.document.close();
                });*/

                $.ajax({
                    type: 'POST',
                    url: '{!! route('merchant.parcel.printParcelMultiple') !!}',
                    data: {
                        parcel_ids: parcel_ids,
                    },
                    dataType: 'html',
                    success: function (html) {
                        console.log(html)
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(html);
                        // w.document.close();
                        // w.window.print();
                        // w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });
            $(document).on('click', '#printBtn', function () {

                var parcel_status = $('#parcel_status option:selected').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $.ajax({
                    type: 'GET',
                    url: '{!! route('merchant.parcel.printParcelList') !!}',
                    data: {
                        parcel_status: parcel_status,
                        parcel_invoice: parcel_invoice,
                        merchant_order_id: merchant_order_id,
                        customer_contact_number: customer_contact_number,
                        from_date: from_date,
                        to_date: to_date,
                    },
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


            $('#filter').click(function () {
                var parcel_status = $('#parcel_status option:selected').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $('#yajraDatatable').DataTable().destroy();
                load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number, from_date, to_date);
            });



            $('#filter').click(function () {
                var parcel_status = $('#parcel_status option:selected').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                
                
                $('#ex_parcel_status').val(parcel_status);
                $('#ex_from_date').val(from_date);
                $('#ex_to_date').val(to_date);
                $('#ex_parcel_invoice').val(parcel_invoice);
                

                $('#yajraDatatable').DataTable().destroy();
                load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number, from_date, to_date);
            });



            $(document).on('click', '#refresh', function () {
                $("#parcel_status").val("0").trigger('change');
                $("#parcel_invoice").val("");
                $("#merchant_order_id").val("");
                $("#customer_contact_number").val("");
                $("#from_date").val("");
                $("#to_date").val("");
                $('#yajraDatatable').DataTable().destroy();
                load_data();
            });


            $('#yajraDatatable').on('click', '.view-modal', function () {
                var parcel_id = $(this).attr('parcel_id');
                var url = "{{ route('merchant.parcel.viewParcel', ":parcel_id") }}";
                url = url.replace(':parcel_id', parcel_id);
                $('#showResult').html('');
                if (parcel_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            $('#showResult').html(response);
                        },
                    })
                }
            });

            $('#yajraDatatable').on('click', '.pickup-hold', function () {
                var status_object = $(this);
                var parcel_id = status_object.attr('parcel_id');
                var url = "{{ route('merchant.parcel.parcelHold') }}";

                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        parcel_id: parcel_id,
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
                        } else {
                            toastr.error(response.error);
                        }
                    }
                })
            });

            $('#yajraDatatable').on('click', '.pickup-start', function () {
                var status_object = $(this);
                var parcel_id = status_object.attr('parcel_id');
                var url = "{{ route('merchant.parcel.parcelStart') }}";

                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        parcel_id: parcel_id,
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
                        } else {
                            toastr.error(response.error);
                        }
                    }
                })
            });

            $('#yajraDatatable').on('click', '.pickup-cancel', function () {
                var status_object = $(this);
                var parcel_id = status_object.attr('parcel_id');
                var url = "{{ route('merchant.parcel.parcelCancel') }}";

                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        parcel_id: parcel_id,
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
                        } else {
                            toastr.error(response.error);
                        }
                    }
                })
            });

        }
    </script>
@endpush

