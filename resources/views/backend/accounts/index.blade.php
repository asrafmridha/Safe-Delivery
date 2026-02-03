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
                            <li class="breadcrumb-item active" aria-current="page">Account list</li>
                            @if(check_permission('account create'))
                                <a href="{{route('account.create')}}" class="btn btn-primary ml-auto">
                                    Create Account
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Account</legend>
        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section">
                <table class="table table-bordered table-striped datatable table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Country</th>
                        <th>Bank Name</th>
                        <th>Bank Branch</th>
                        <th>Account Name</th>
                        <th>Account Number</th>
                        <th>Status</th>
                        <th>Route</th>
                        <th>Beneficiary Address</th>
                        <th>Beneficiary City</th>
                        <th>Beneficiary Swift Code</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('account.datatable') }}",
                columns: [
                    {data: 'DT_RowIndex', name: '#', orderable: false, searchable: false},
                    {data: 'country.name', name: 'country.name',class:"text-center"},
                    {data: 'bank_name', name: 'bank_name',class:"text-center"},
                    {data: 'bank_branch', name: 'bank_branch',class:"text-center"},
                    {data: 'account_name', name: 'account_name',class:"text-center"},
                    {data: 'account_number', name: 'account_number',class:"text-center"},
                    {data: 'status', name: 'status',class:"text-center"},
                    {data: 'route', name: 'route',class:"text-center"},
                    {data: 'beneficiary_address', name: 'beneficiary_address',class:"text-center"},
                    {data: 'beneficiary_city', name: 'beneficiary_city',class:"text-center"},
                    {data: 'beneficiary_swift_code', name: 'beneficiary_swift_code',class:"text-center"},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class:"text-center"},
                ]
            });
        });
    </script>
@endsection
@section('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection
