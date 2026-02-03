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
                            <li class="breadcrumb-item active" aria-current="page">Client list</li>
                            @if(check_permission('client create'))
                                <a href="{{route('client.create')}}" class="btn btn-primary ml-auto">
                                    Create Client
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Clients</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive" style="white-space: nowrap">
                <table class="table table-bordered  table-striped datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Country</th>
                        <th>Address</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    <tr>
                        <th class="text-right" scope="row" colspan="4">Totals</th>
                        <td id="total_balance"></td>
                        <td colspan="3"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
@endsection
@section('script')
    <!-- data table -->
    {{--<script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/dataTables.bootstrap4.min.js')}}"></script>
--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, 150, -1],
                    [10, 25, 50, 100, 150, 'All'],
                ],
                ajax: "{{ route('client.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {
                        data: 'balance',
                        name: 'balance',
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if ( parseFloat(cellData) < 0 ) {
                                $(td).addClass('text-danger')
                            }
                        }
                    },
                    {data: 'country', name: 'country'},
                    {data: 'address', name: 'address'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "drawCallback": function (settings) {
                    let api = this.api();
                    let data = api.rows().data();
                    let total_amount = 0;
                    for (let i = 0; i < data.length; i++) {
                        // console.log(data[i]['balance'].replaceAll(',',''))
                        total_amount += parseFloat(data[i]['balance'].replaceAll(",",""));
                    }
                    document.getElementById('total_balance').innerHTML = numberWithCommas(total_amount);
                },
            });
        });
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{-- <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

@endsection
