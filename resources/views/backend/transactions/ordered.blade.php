@extends('layouts.backend')

@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Ordered Transaction</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Ordered Transaction</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive" style="white-space: nowrap">
                <table class="table table-bordered table-striped datatable" id="yajraDatatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Supplier Name</th>
                        <th>Client Name</th>
                        <th>Created By</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>B.Rate</th>
                        <th>S.Rate</th>
                        <th>Profit</th>
                        <th>Remarks</th>
                        <th>S/L</th>
                        <th>Beneficiary</th>
                        <th>Status</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    <tr>
                        <th class="text-right" scope="row" colspan="7">Totals</th>
                        <td id="total_amount"></td>
                        <td colspan="2"></td>
                        <td id="total_profit"></td>
                        <td colspan="5"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>


    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="showResult">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                /* pageLength: 50,
                 lengthMenu: [
                     [50, 100, 150, -1],
                     [50, 100, 150, 'All'],
                 ],*/
                ajax: "{{ route('transaction.order.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'date', name: 'date', class : "text-center"},
                    {data: 'transaction_no', name: 'transaction_no', class : "text-center"},
                    {data: 'supplier.name', name: 'supplier.name',  class : "text-center",render: (data, type, row) =>  (row && row.supplier && row.supplier.name)? data : "--"},
                    {data: 'client.name', name: 'client.name', class : "text-center",render: (data, type, row) =>  (row && row.client && row.client.name)? data : "--"},
                    {data: 'created_user.name', name: 'created_user.name',  class : "text-center",render: (data, type, row) =>  (row && row.created_user && row.created_user.name)? data : "--"},
                    {data: 'currency.code', name: 'currency.code', class : "text-center"},
                    {data: 'amount', name: 'amount', class : "text-center"},
                    {data: 'b_rate', name: 'b_rate', class : "text-center"},
                    {data: 's_rate', name: 's_rate', class : "text-center"},
                    {data: 'profit', name: 'profit', class : "text-center"},
                    {data: 'remarks', name: 'remarks', class : "text-center"},
                    {data: 'sl', name: 'sl', class : "text-center"},
                    {data: 'beneficiary', name: 'beneficiary', class : "text-center"},
                    {data: 'status', name: 'status', class : "text-center"},
                    {data: 'action', name: 'action', orderable: false, searchable: false, class : "text-center"},
                ],
                "drawCallback": function (settings) {
                    let api = this.api();
                    let data = api.rows().data();
                    let total_amount = 0;
                    let total_profit = 0;
                    for (let i = 0; i < data.length; i++) {
                        total_amount += parseFloat(data[i]['amount'].replaceAll(",",""));
                        total_profit += parseFloat(data[i]['profit'].replaceAll(",",""));
                    }
                    document.getElementById('total_amount').innerHTML = numberWithCommas(total_amount);
                    document.getElementById('total_profit').innerHTML = numberWithCommas(total_profit) + " BDT";
                },
            });


        });
        window.onload = function () {
            $('#yajraDatatable').on('click', '.view-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.view', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
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

            $('#yajraDatatable').on('click', '.status-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.change.status', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            {{--$.getScript('{{asset('assets_new/js/jquery-2.1.4.min.js')}}', function() {--}}
                           {{-- $.getScript('{{asset('assets/backend/js/jquery.min.js')}}', function() {
                                console.debug('Script loaded.');
                            });--}}
                            $('#showResult').html(response);
                            // $('.select2').select2();

                            $('.select2').select2({
                                dropdownParent: $('.select2-dropdownParent')
                            });

                        },
                    })
                }
            });
        }
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{-- <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

@endsection
