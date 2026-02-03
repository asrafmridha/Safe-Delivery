@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Transport Income Expense Generate</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Transport Income Expense Generate</li>
        </ol>
        </div>
    </div>
    </div>
</div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Transport Income Expense Generate</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <form role="form" action="{{ route('admin.account.confirmTransportIncomeExpenseGenerate') }}" method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <fieldset >
                                                        <legend>Vehicle </legend>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="vehicle_id">Vehicle </label>
                                                                    <select name="vehicle_id" id="vehicle_id" class="form-control select2" style="width: 100%" >
                                                                        <option value="0" data-charge="0">Select Vehicle </option>
                                                                        @foreach ($vehicles as $vehicle)
                                                                            <option value="{{ $vehicle->id }}"
                                                                                    data-vehicle_sl_no="{{ $vehicle->vehicle_sl_no }}"
                                                                                    data-vehicle_no="{{ $vehicle->vehicle_no }}"
                                                                                    data-name="{{ $vehicle->name }}"
                                                                                    data-vehicle_driver_name="{{ $vehicle->vehicle_driver_name }}"
                                                                                    data-vehicle_driver_phone="{{ $vehicle->vehicle_driver_phone }}"
                                                                                >
                                                                                {{ $vehicle->vehicle_sl_no }} {{ $vehicle->vehicle_no }}  / {{ $vehicle->name }} / {{ $vehicle->vehicle_driver_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="date">Date </label>
                                                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control" placeholder="Transport Date " required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="driver_name">Driver Name </label>
                                                                    <input type="text" name="driver_name" id="driver_name" value="" class="form-control" placeholder="Driver Name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="vehicle_driver_phone">Driver Phone </label>
                                                                    <input type="text" name="vehicle_driver_phone" id="vehicle_driver_phone" value="" class="form-control" placeholder="Driver Phone" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>


                                                <div class="col-md-12">
                                                    <fieldset >
                                                        <legend>KM </legend>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="starting_km">Starting KM</label>
                                                                    <input type="number" name="starting_km" id="starting_km" value="" class="form-control" placeholder="Starting KM" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="ending_km">Ending KM</label>
                                                                    <input type="number" name="ending_km" id="ending_km" value="" class="form-control" placeholder="Ending KM" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="total_km">Total KM</label>
                                                                    <input type="number" name="total_km" id="total_km" value="" class="form-control" placeholder="Total KM" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset >
                                                        <legend>Destination </legend>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="to_destination">To Destination </label>
                                                                    <textarea type="text" name="to_destination" id="to_destination" value="" class="form-control" placeholder="To Destination  " required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="from_destination">From Destination </label>
                                                                    <textarea type="text" name="from_destination" id="from_destination" value="" class="form-control" placeholder="From Destination " required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset >
                                                        <legend>Trip </legend>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="advance_trip_amount">Advance Trip Amount</label>
                                                                    <input type="number" name="advance_trip_amount" id="advance_trip_amount" value="" class="form-control" placeholder="Advance Trip Amount" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="up_trip_amount">Up Trip Amount </label>
                                                                    <input type="number" name="up_trip_amount" id="up_trip_amount" value="" class="form-control" placeholder="Up Trip Amount" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="down_to_amount">Down Trip Amount</label>
                                                                    <input type="number" name="down_to_amount" id="down_to_amount" value="" class="form-control" placeholder="Down Trip Amount" required >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="total_trip_amount">Total Trip Amount </label>
                                                                    <input type="number" name="total_trip_amount" id="total_trip_amount" value="" class="form-control" placeholder="Total Trip Amount" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset >
                                                        <legend>Income </legend>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="all_expense_amount">All Expense</label>
                                                                    <input type="number" name="all_expense_amount" id="all_expense_amount" value="" class="form-control" placeholder="All Expense" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="all_income_amount">All Income</label>
                                                                    <input type="number" name="all_income_amount" id="all_income_amount" value="" class="form-control" placeholder="All Income" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="all_net_income">All Net Income </label>
                                                                    <input type="number" name="all_net_income" id="all_net_income" value="" class="form-control" placeholder="All Net Income" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="received_amount">Received Amount</label>
                                                        <input type="number" name="received_amount" id="received_amount" value="" class="form-control" placeholder="Received Amount" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="due_amount">Due Amount</label>
                                                        <input type="number" name="due_amount" id="due_amount" value="" class="form-control" placeholder="Due Amount" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="remark">Remark </label>
                                                        <textarea type="text" name="remark" id="remark" value="" class="form-control" placeholder="Remark" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection

@push('style_css')
<style>
    .table td, .table th {
        padding: .1rem !important;
    }
</style>
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script>

    window.onload = function(){
        $('#vehicle_id').on('change', function(){

            var vehicle         = $("#vehicle_id option:selected");
            var vehicle_id      = vehicle.val();
            var vehicle_sl_no   = vehicle.data('vehicle_sl_no');
            var vehicle_no      = vehicle.data('vehicle_no');
            var name            = vehicle.data('name');
            var vehicle_driver_name = vehicle.data('vehicle_driver_name');
            var vehicle_driver_phone = vehicle.data('vehicle_driver_phone');
            if(vehicle_id != 0){
                $("#driver_name").val(vehicle_driver_name);
                $("#vehicle_driver_phone").val(vehicle_driver_phone);
            }
        });

        $('#starting_km').keyup(function(){
            calculation_km();
        });
        $('#ending_km').keyup(function(){
            calculation_km();
        });

    }

    function calculation_km(){
        var starting_km     = returnNumber($("#starting_km").val());
        var ending_km       = returnNumber($("#ending_km").val());
        var total_km        = ending_km - starting_km;
        $("#total_km").val(total_km);
    }

    function createForm(){
        let vehicle_id = $('#vehicle_id').val();
        if(vehicle_id == '0'){
            toastr.error("Please Select Vehicle..");
            return false;
        }

    }

  </script>
@endpush
