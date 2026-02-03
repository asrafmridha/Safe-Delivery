@extends('layouts.admin_layout.admin_layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Merchant Delivery Payment Statement</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Merchant Delivery Payment Statement</li>
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
                        <h3 class="card-title">Merchant Delivery Payment Statement </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="merchant_id">Merchant </label>
                                <select name="merchant_id" id="merchant_id" class="form-control select2"
                                    style="width: 100%">
                                     <option value="0">All Merchant </option>
                                    @foreach ($merchants as $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->company_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            {{--<div class="col-md-3">--}}
                            {{--<label for="status">Parcel Payment Type </label>--}}
                            {{--<select name="status" id="status" class="form-control select2" style="width: 100%" >--}}
                            {{--<option value="0" >Select Delivery Payment Type </option>--}}
                            {{--<option value="1" >Send Request </option>--}}
                            {{--<option value="2" >Request Accept </option>--}}
                            {{--<option value="3" >Request Cancel </option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            <div class="col-md-3">
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))))}}" />
                            </div>
                            <div class="col-md-3">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{date("Y-m-d")}}" />
                            </div>
                            <div class="col-md-3" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" name="print" id="print" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body" id="merchantDeliveryPaymentStatement">
                        <table id="merchantPaymentStatement" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center"> Date</th>
                                    <th width="10%" class="text-center"> Transaction ID </th>
                                    <th width="10%" class="text-center"> Parcel Invoice </th>
                                    <th width="10%" class="text-center"> Company Name </th>
                                    <th width="10%" class="text-center"> Merchant Name </th>
                                    <th width="10%" class="text-center"> Payment Amount</th>
                                    <th width="10%" class="text-center"> Delivery Charge</th>
                                    <th width="10%" class="text-center"> Payable Amount</th>
                                    <th width="10%" class="text-center"> Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $total_payment_amount = 0;
                                $total_charge_amount = 0;
                                $total_paid_amount = 0;
                                $total_pending_amount = 0;
                                $total_cancel_amount = 0;
                                $total_receive_amount = 0;

                                $old_payment_date = "";
                                $old_transaction_id = "";
                                if(count($merchant_payment_data) > 0) {
                                $i = 0;
                                foreach ($merchant_payment_data as $merchant_payment) {
                                $i++;
                                $payment_id = $merchant_payment->parcel_merchant_delivery_payment_id;

                                $payment_date = date("Y-m-d", strtotime($merchant_payment->created_at));
                                $payment_date_column = "";
                                if($payment_date != $old_payment_date) {
                                $dateRowCount = count($date_array[$payment_date]);
                                $payment_date_column = '<td rowspan="'.$dateRowCount.'"
                                    class="text-center align-middle">'.$payment_date.'</td>';
                                }

                                $transaction_id =
                                $merchant_payment->parcel_merchant_delivery_payment->merchant_payment_invoice;
                                $transaction_id_column = "";
                                if($transaction_id != $old_transaction_id) {
                                $tranRowCount = count($transaction_ids[$transaction_id]);
                                $transaction_id_column = '<td rowspan="'.$tranRowCount.'"
                                    class="text-center align-middle">'.$transaction_id.'</td>';
                                }

                                if($merchant_payment->status == 1) {
                                $status = "Request Send <br>
                                (".$merchant_payment->parcel_merchant_delivery_payment->created_at.")";
                                $total_pending_amount += $merchant_payment->paid_amount;
                                }elseif($merchant_payment->status == 2) {
                                $status = "Received <br>
                                (".$merchant_payment->parcel_merchant_delivery_payment->date_time.")";
                                $total_receive_amount += $merchant_payment->paid_amount;
                                }else {
                                $status = "Canceled <br>
                                (".$merchant_payment->parcel_merchant_delivery_payment->date_time.")";
                                $total_cancel_amount += $merchant_payment->paid_amount;
                                }

                                $total_payment_amount += $merchant_payment->collected_amount;
                                $total_charge_amount += ($merchant_payment->collected_amount -
                                $merchant_payment->paid_amount);
                                //$total_charge_amount += ($merchant_payment->cod_charge +$merchant_payment->delivery_charge + $merchant_payment->weight_package_charge + $merchant_payment->return_charge);
                                $total_paid_amount += $merchant_payment->paid_amount;
                                echo '<tr>
                                    '.$payment_date_column.'
                                    '.$transaction_id_column.'
                                    <td class="text-center">'.$merchant_payment->parcel->parcel_invoice.'</td>
                                    <td class="text-center">'.$merchant_payment->parcel->merchant->company_name.'</td>
                                    <td class="text-center">'.$merchant_payment->parcel->merchant->name.'</td>
                                    <td class="text-center">'.$merchant_payment->collected_amount.'</td>
                                    <td class="text-center">'.($merchant_payment->collected_amount -
                                        $merchant_payment->paid_amount).'</td>
                                    <td class="text-center">'.$merchant_payment->paid_amount.'</td>
                                    <td class="text-center">'.$status.'</td>
                                </tr>';


                                $old_payment_date = $payment_date;
                                $old_transaction_id = $transaction_id;
                                }

                                echo '<tr>
                                    <th colspan="5" style="text-align: right">Total Amount: </th>
                                    <th class="text-center">'.$total_payment_amount.' TK</th>
                                    <th class="text-center">'.$total_charge_amount.' TK</th>
                                    <th class="text-center">'.$total_paid_amount.' TK</th>
                                    <th class="text-center"></th>
                                </tr>';
                                }


                                @endphp
                            </tbody>

                        </table>
                        <h3>Merchant Receive Amount: <?php echo $total_receive_amount; ?> TK</h3>
                        <h3>Merchant Pending Amount: <?php echo $total_pending_amount; ?> TK</h3>
                        <h3>Merchant Cancel Amount: <?php echo $total_cancel_amount; ?> TK</h3>

                        {{--<table id="merchantPaymentStatement" class="table table-bordered table-striped">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                        {{--<th width="5%" class="text-center"> SL </th>--}}
                        {{--<th width="10%" class="text-center"> Date</th>--}}
                        {{--<th width="10%" class="text-center"> Consignment </th>--}}
                        {{--<th width="10%" class="text-center"> Merchant </th>--}}
                        {{--<th width="10%" class="text-center"> Parcel Invoice </th>--}}
                        {{--<th width="10%" class="text-center"> Collection Amount</th>--}}
                        {{--<th width="10%" class="text-center"> (-Charge Amount)</th>--}}
                        {{--<th width="10%" class="text-center"> Payable Amount</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}

                        {{--<tbody>--}}
                        {{--<tr>--}}
                        {{--<td colspan="8" style="font-size: 18px; font-weight: 600;">Accounts Payment Amount</td>--}}
                        {{--</tr>--}}
                        {{--@php--}}
                        {{--$total_payment_amount = 0;--}}
                        {{--$total_charge_amount  = 0;--}}
                        {{--$total_paid_amount  = 0;--}}
                        {{--if(count($merchant_payment_data) > 0) {--}}
                        {{--$i = 0;--}}
                        {{--foreach ($merchant_payment_data as $merchant_payment) {--}}
                        {{--$i++;--}}
                        {{--$payment_id = $merchant_payment->parcel_merchant_delivery_payment_id;--}}


                        {{--$total_payment_amount += $merchant_payment->collected_amount;--}}
                        {{--$total_charge_amount  += ($merchant_payment->collected_amount - $merchant_payment->paid_amount);--}}
                        {{--//$total_charge_amount  += ($merchant_payment->cod_charge + $merchant_payment->delivery_charge + $merchant_payment->weight_package_charge + $merchant_payment->return_charge);--}}
                        {{--$total_paid_amount    += $merchant_payment->paid_amount;--}}
                        {{--echo '<tr>--}}
                        {{--<td>'.$i.'</td>--}}
                        {{--<td>'.date("Y-m-d", strtotime($merchant_payment->created_at)).'</td>--}}
                        {{--<td>'.$merchant_payment->parcel_merchant_delivery_payment->merchant_payment_invoice.'</td>--}}
                        {{--<td>'.$merchant_payment->parcel->merchant->name.'</td>--}}
                        {{--<td>'.$merchant_payment->parcel->parcel_invoice.'</td>--}}
                        {{--<td>'.$merchant_payment->collected_amount.'</td>--}}
                        {{--<td>'.($merchant_payment->collected_amount - $merchant_payment->paid_amount).'</td>--}}
                        {{--<td>'.$merchant_payment->paid_amount.'</td>--}}
                        {{--</tr>';--}}
                        {{--}--}}

                        {{--echo '<tr>--}}
                        {{--<th colspan="5" style="text-align: right">Total Accounts Payment Amount: </th>--}}
                        {{--<th>'.$total_payment_amount.' TK</th>--}}
                        {{--<th>'.$total_charge_amount.' TK</th>--}}
                        {{--<th>'.$total_paid_amount.' TK</th>--}}
                        {{--</tr>';--}}
                        {{--}--}}


                        {{--@endphp--}}

                        {{--<tr>--}}
                        {{--<td colspan="8" style="font-size: 18px; font-weight: 600;">Merchant Receive Amount</td>--}}
                        {{--</tr>--}}
                        {{--@php--}}
                        {{--$total_receive_amount = 0;--}}
                        {{--$total_acc_payment_amount = 0;--}}
                        {{--$total_acc_charge_amount = 0;--}}
                        {{--if(count($merchant_payment_data) > 0) {--}}
                        {{--$i = 0;--}}
                        {{--foreach ($merchant_payment_data as $merchant_payment) {--}}
                        {{--$i++;--}}
                        {{--if($merchant_payment->status == 2) {--}}
                        {{--$payment_id = $merchant_payment->parcel_merchant_delivery_payment_id;--}}


                        {{--$total_acc_payment_amount += $merchant_payment->collected_amount;--}}
                        {{--$total_acc_charge_amount  += ($merchant_payment->collected_amount - $merchant_payment->paid_amount);--}}
                        {{--//$total_charge_amount  += ($merchant_payment->cod_charge + $merchant_payment->delivery_charge + $merchant_payment->weight_package_charge + $merchant_payment->return_charge);--}}
                        {{--$total_receive_amount    += $merchant_payment->paid_amount;--}}
                        {{--echo '<tr>--}}
                        {{--<td>'.$i.'</td>--}}
                        {{--<td>'.date("Y-m-d", strtotime($merchant_payment->created_at)).'</td>--}}
                        {{--<td>'.$merchant_payment->parcel_merchant_delivery_payment->merchant_payment_invoice.'</td>--}}
                        {{--<td>'.$merchant_payment->parcel->merchant->name.'</td>--}}
                        {{--<td>'.$merchant_payment->parcel->parcel_invoice.'</td>--}}
                        {{--<td>'.$merchant_payment->collected_amount.'</td>--}}
                        {{--<td>'.($merchant_payment->collected_amount - $merchant_payment->paid_amount).'</td>--}}
                        {{--<td>'.$merchant_payment->paid_amount.'</td>--}}
                        {{--</tr>';--}}
                        {{--}--}}
                        {{--}--}}

                        {{--echo '<tr>--}}
                        {{--<th colspan="5" style="text-align: right">Total Account Receive Amount: </th>--}}
                        {{--<th>'.$total_acc_payment_amount.' TK</th>--}}
                        {{--<th>'.$total_acc_charge_amount.' TK</th>--}}
                        {{--<th>'.$total_receive_amount.' TK</th>--}}
                        {{--</tr>';--}}
                        {{--}--}}


                        {{--@endphp--}}

                        {{--<tr>--}}
                        {{--<th colspan="3" style="font-size: 18px; text-align: center;">Total Account Balance Amount</th>--}}
                        {{--<th colspan="2" style="font-size: 18px; text-align: center;">{{ $total_paid_amount - $total_receive_amount . ' TK' }}
                        </th>--}}
                        {{--</tr>--}}
                        {{--</tbody>--}}
                        {{--</table>--}}
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
window.onload = function() {

    $("#filter").on("click", function() {

        var merchant_id = $("#merchant_id").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        if (merchant_id != "" || from_date != "" || to_date != "") {
            $.ajax({
                cache: false,
                url: "{{ route('admin.account.getMerchantPaymentDeliveryStatement') }}",
                type: "post",
                data: {
                    _token: "{{ csrf_token() }}",
                    merchant_id: merchant_id,
                    from_date: from_date,
                    to_date: to_date
                },
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $("#merchantDeliveryPaymentStatement").html(response);
                }
            })
        } else {
            toastr.error("Please filled any one field");
        }
    });

    $(document).on('click', '#print', function() {

        var merchant_id = $("#merchant_id").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        $.ajax({
            type: 'GET',
            url: '{!! route('admin.account.printMerchantPaymentDeliveryStatement') !!}',
            data: {
                    merchant_id: merchant_id,
                    from_date: from_date,
                    to_date: to_date
            },
            dataType: 'html',
            success: function(html) {
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(html);
                w.document.close();
                w.window.print();
                w.window.close();
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    });

    $("#refresh").on("click", function() {
        $("#branch_id").val("");
        $("#from_date").val("");
        $("#to_date").val("");

        window.location.reload();
    });
}
</script>
@endpush
