@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rider Payment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rider.payment') }}">Rider Payment List</a>
                        </li>
                        <li class="breadcrumb-item active">Payment</li>
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
                            <h3 class="card-title">Make New Payment </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1">
                                <div class="card card-primary">
                                    <form role="form" action=""
                                          method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="branch_id">Branch</label>
                                                        <select name="branch_id" id="branch_id"
                                                                class="form-control select2" style="width: 100%">
                                                            <option value="0">Select Branch</option>
                                                            @foreach ($branches as $branch)
                                                                <option
                                                                    value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="rider_id">Rider</label>
                                                        <select name="rider_id" id="rider_id"
                                                                class="form-control select2" style="width: 100%">
                                                            <option value="0">Select Rider</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="salary_amount">Salary</label>
                                                        <input type="text" name="salary_amount" id="salary_amount"
                                                               value="{{ old('salary_amount')??0 }}"
                                                               class="form-control" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_parcel">Total Parcel</label>
                                                        <input type="number" name="total_parcel" id="total_parcel"
                                                               value="{{ old('total_parcel')??0 }}" class="form-control"
                                                               required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="par_parcel_commission">Par Parcel Commission</label>
                                                        <input type="number" name="par_parcel_commission"
                                                               id="par_parcel_commission"
                                                               value="{{ old('par_parcel_commission') ?? 0 }}"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_parcel_commission">Total Parcel
                                                            Commission</label>
                                                        <input type="number" name="total_parcel_commission"
                                                               id="total_parcel_commission"
                                                               value="{{ old('total_parcel_commission')??0 }}"
                                                               class="form-control" required readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_km">Total KM</label>
                                                        <input type="number" name="total_km" id="total_km"
                                                               value="{{ old('total_km')??0 }}" class="form-control"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="par_km_commission">Par KM Commission</label>
                                                        <input type="number" name="par_km_commission"
                                                               id="par_km_commission"
                                                               value="{{ old('par_km_commission') ?? 0 }}"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_km_commission">Total KM Commission</label>
                                                        <input type="number" name="total_km_commission"
                                                               id="total_km_commission"
                                                               value="{{ old('total_km_commission')??0 }}"
                                                               class="form-control" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_weight">Total Weight</label>
                                                        <input type="number" name="total_weight" id="total_weight"
                                                               value="{{ old('total_weight')??0 }}" class="form-control"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="par_weight_commission">Par Weight Commission</label>
                                                        <input type="number" name="par_weight_commission"
                                                               id="par_weight_commission"
                                                               value="{{ old('par_weight_commission') ?? 0 }}"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_weight_commission">Total Weight
                                                            Commission</label>
                                                        <input type="number" name="total_weight_commission"
                                                               id="total_weight_commission"
                                                               value="{{ old('total_weight_commission')??0 }}"
                                                               class="form-control" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="payment_month">Salary Month</label>
                                                        <input type="month" name="payment_month" id="payment_month"
                                                               value="{{ old('payment_month')?? date("Y-m") }}"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="paid_amount">Paid Amount</label>
                                                        <input type="number" step="any" name="paid_amount"
                                                               id="paid_amount" value="{{ old('paid_amount') }}"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="total_salary">Total Salary</label>
                                                        <input type="number" name="total_salary" id="total_salary"
                                                               value="{{ old('total_salary')??0 }}" class="form-control"
                                                               required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
        window.onload = function () {
            $('#branch_id').on('change', function () {
                var branch_id = $("#branch_id option:selected").val();
                $("#rider_id").val(0).change().attr('disabled', true);
                if (branch_id != "" && branch_id != 0) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            branch_id: branch_id,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: "{{ route('admin.rider.payment.getRiderByBranch') }}",
                        success: function (response) {
                            $("#rider_id").html(response.option).attr('disabled', false);
                        }
                    });
                }
            });
            $('#rider_id').on('change', function () {
                var rider_id = $("#rider_id option:selected").val();
                var payment_month = $("#payment_month").val();
                if (rider_id != "" && rider_id != 0) {
                    // var staff_salary   = $("#rider_id option:selected").data('salary');
                    $.ajax({
                        cache: false,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            rider_id: rider_id,
                            payment_month: payment_month,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: "{{ route('admin.rider.payment.getRiderById') }}",
                        success: function (response) {
                            $("#salary_amount").val(response.rider.salary);
                            $("#total_parcel").val(response.rider.total_parcel);
                            calculate_total_salary();
                        }
                    });
                } else {
                    $("#salary_amount").val(0);
                }
            });
            $('#payment_month').on('change', function () {
                var rider_id = $("#rider_id option:selected").val();
                var payment_month = $("#payment_month").val();
                if (rider_id != "" && rider_id != 0) {
                    // var staff_salary   = $("#rider_id option:selected").data('salary');
                    $.ajax({
                        cache: false,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            rider_id: rider_id,
                            payment_month: payment_month,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: "{{ route('admin.rider.payment.getRiderById') }}",
                        success: function (response) {
                            $("#salary_amount").val(response.rider.salary);
                            $("#total_parcel").val(response.rider.total_parcel);
                            calculate_total_salary();
                        }
                    });
                } else {
                    $("#salary_amount").val(0);
                }
            });
            $('#par_parcel_commission').keyup(function () {
                var par_parcel_commission = $("#par_parcel_commission").val();
                var total_parcel = $("#total_parcel").val();
                var total_parcel_commission = par_parcel_commission * total_parcel;
                $("#total_parcel_commission").val(total_parcel_commission);
                calculate_total_salary();
            });
            $('#total_km').keyup( function () {
                var par_km_commission = $("#par_km_commission").val();
                var total_km = $("#total_km").val();
                var total_km_commission = par_km_commission * total_km;
                $("#total_km_commission").val(total_km_commission);
                calculate_total_salary();
            });
            $('#par_km_commission').keyup( function () {
                var par_km_commission = $("#par_km_commission").val();
                var total_km = $("#total_km").val();
                var total_km_commission = par_km_commission * total_km;
                $("#total_km_commission").val(total_km_commission);
                calculate_total_salary();
            });
            $('#total_weight').keyup( function () {
                var par_weight_commission = $("#par_weight_commission").val();
                var total_weight = $("#total_weight").val();
                var total_weight_commission = par_weight_commission * total_weight;
                $("#total_weight_commission").val(total_weight_commission);
                calculate_total_salary();
            });
            $('#par_weight_commission').keyup( function () {
                var par_weight_commission = $("#par_weight_commission").val();
                var total_weight = $("#total_weight").val();
                var total_weight_commission = par_weight_commission * total_weight;
                $("#total_weight_commission").val(total_weight_commission);
                calculate_total_salary();
            });
            function calculate_total_salary(){
                var salary_amount = returnNumber($("#salary_amount").val());
                var total_parcel_commission = returnNumber($("#total_parcel_commission").val());
                var total_km_commission = returnNumber($("#total_km_commission").val());
                var total_weight_commission = returnNumber($("#total_weight_commission").val());
                var total_salary = salary_amount + total_parcel_commission + total_km_commission + total_weight_commission;
                $("#total_salary").val(total_salary);
                $("#paid_amount").val(total_salary);
            }
        }
    </script>
@endpush
