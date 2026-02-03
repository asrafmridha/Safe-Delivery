
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Transport Income Expense List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Transport Income Expenses List</li>
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
                        <h3 class="card-title">Transport Income Expense List </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-4">
                                <label for="vehicle_id">Vehicle </label>
                                <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Branch  </option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" >{{ $vehicle->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"  value="{{ date('Y-m-d') }}"/>
                            </div>
                            <div class="col-md-3">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ date('Y-m-d') }}"/>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
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
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Date </th>
                                    <th width="20%" class="text-center"> Vehicle </th>
                                    <th width="15%" class="text-center"> To </th>
                                    <th width="15%" class="text-center"> From </th>
                                    <th width="10%" class="text-center"> Received Amount</th>
                                    <th width="10%" class="text-center"> Due Amount </th>
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

        function load_data(vehicle_id = '', from_date = '', to_date = ''){
            var table = $('#yajraDatatable').DataTable({
                language : {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url:'{!! route('admin.account.getTransportIncomeExpenseList') !!}',
                    data:{
                        vehicle_id      : vehicle_id,
                        from_date       : from_date,
                        to_date         : to_date,
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false , searchable: false, class : "text-center"},
                    { data: 'date', name: 'date' , class : "text-center"},
                    { data: 'vehicle_name', name: 'vehicle_name' , class : "text-center"},
                    { data: 'to_destination', name: 'to_destination', class : "text-center" },
                    { data: 'from_destination', name: 'from_destination', class : "text-center" },
                    { data: 'received_amount', name: 'received_amount', class : "text-center" },
                    { data: 'due_amount', name: 'due_amount', class : "text-center" },
                    { data: 'action', name: 'action', orderable: false , searchable: false , class : "text-center"}
                ]
            });
        }

        $('#filter').click(function(){
            var vehicle_id   = $('#vehicle_id option:selected').val();
            var from_date   = $('#from_date').val();
            var to_date     = $('#to_date').val();

            $('#yajraDatatable').DataTable().destroy();
            load_data(vehicle_id, from_date, to_date);
        });

        $(document).on('click', '#refresh', function(){
            $('#yajraDatatable').DataTable().destroy();
            load_data('', '', '');
        });


        $('#yajraDatatable').on('click', '.view-modal', function(){
            var transport_income_expense_id = $(this).attr('transport_income_expense_id');
            var url = "{{ route('admin.account.viewTransportIncomeExpense', ":transport_income_expense_id") }}";
            url = url.replace(':transport_income_expense_id', transport_income_expense_id);
            $('#showResult').html('');
            if(transport_income_expense_id.length != 0){
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

