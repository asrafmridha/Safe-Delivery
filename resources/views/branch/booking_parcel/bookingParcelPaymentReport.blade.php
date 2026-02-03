@extends('layouts.branch_layout.branch_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Parcel Payment Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Parcel Payments Report</li>
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
                            <h3 class="card-title"> Parcel Payment Report </h3>
                            {{--<a href="{{ route('branch.bookingParcelPayment.paymentForwardToAccounts') }}" class="btn btn-success float-right">--}}
                                {{--<i class="fa fa-pencil-alt"></i> Parcel Payment--}}
                            {{--</a>--}}

                            <div class="row input-daterange" style="margin-top: 40px">
                                <div class="col-md-4">
                                    <label for="payment_receive_type">Payment Type </label>
                                    <select name="payment_receive_type" id="payment_receive_type" class="form-control select2" style="width: 100%" >
                                        <option value="" >Select Payment Type </option>
                                        <option value="booking" >Booking</option>
                                        <option value="delivery" >Delivery </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control" />
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"/>
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
                            <table id="parcelPaymentTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Date </th>
                                    <th width="10%" class="text-center"> C/N No </th>
                                    <th width="10%" class="text-center"> Payment Receive </th>
                                    <th width="10%" class="text-center"> Parcel Amount</th>
                                    <th width="10%" class="text-center"> Branch Amount</th>
                                    <th width="10%" class="text-center"> Forward Amount</th>
                                    <th width="10%" class="text-center"> Account Receive Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if(count($payment_details) > 0) {
                                            for($i=0; $i<count($payment_details); $i++) {
                                                echo html_entity_decode($payment_details[$i]);
                                            }
                                        }else{
                                            echo '<tr>
                                                    <td colspan="8" style="text-align:center"> No data available here!</td>
                                                </tr>';
                                        }
                                    @endphp

                                    <tr>
                                        <td colspan="4" class="text-center text-bold">Total </td>
                                        <td class="text-center text-bold">{{ number_format((float) $total_parcel_amount, 2, '.', '') }}</td>
                                        <td class="text-center text-bold">{{ number_format((float) $total_branch_amount, 2, '.', '') }}</td>
                                        <td class="text-center text-bold">{{ number_format((float) $total_forward_amount, 2, '.', '') }}</td>
                                        <td class="text-center text-bold">{{ number_format((float) $total_receive_amount, 2, '.', '') }}</td>
                                    </tr>

                                </tbody>
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

            $("#filter").on("click", function () {

                var payment_type = $("#payment_receive_type").val();
                var from_date    = $("#from_date").val();
                var to_date      = $("#to_date").val();

                if(payment_type != "" || from_date != "" || to_date != "") {
                    $.ajax({
                        cache: false,
                        url: "{{ route('branch.bookingParcelPayment.bookingParcelPaymentReportAjax') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            payment_receive_type:payment_type,
                            from_date:from_date,
                            to_date:to_date
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        success: function (response) {
                            $("#parcelPaymentTable tbody").html(response);
                        }
                    })
                }else{
                    toastr.error("Please filled any one field");
                }
            });

            $(document).on('click', '#printBtn', function(){
                var payment_type = $("#payment_receive_type").val();
                var from_date    = $("#from_date").val();
                var to_date      = $("#to_date").val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('branch.bookingParcelPayment.printBookingParcelPaymentReport') !!}',
                    data: {
                        payment_receive_type:payment_type,
                        from_date:from_date,
                        to_date:to_date
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

            $("#refresh").on("click", function(){
                $("#payment_receive_type").val("").trigger('change');
                $("#from_date").val("");
                $("#to_date").val("");

                window.location.reload();
            });
        }
    </script>
@endpush

