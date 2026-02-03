@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Branch Delivery Payment Statement</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Branch Delivery Payment Statement</li>
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
                        <h3 class="card-title">Branch Delivery Payment Statement </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="branch_id">Branch </label>
                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                    <option value="" >Select Branch  </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" >{{ $branch->name }} </option>
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
                                <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                            </div>
                            <div class="col-md-3">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value=""/>
                            </div>
                            <div class="col-md-3" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body" id="branchDeliveryPaymentStatement">
                        <table id="deliveryPaymentStatement" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="10%" class="text-center"> Date</th>
                                <th width="10%" class="text-center"> Branch</th>
                                <th width="10%" class="text-center"> Transaction ID </th>
                                <th width="10%" class="text-center"> Parcel Invoice </th>
                                <th width="10%" class="text-center"> Company Name </th>
                                <th width="10%" class="text-center"> Parcel Price</th>
                                <th width="10%" class="text-center"> Delivery Charge</th>
                                <th width="10%" class="text-center"> Total Amount</th>
                                <th width="10%" class="text-center"> Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_receive_amount = 0;
                                $total_pending_amount = 0;

                                $total_parcel_amount = 0;
                                $total_delivery_charge = 0;
                                $total_payment_amount = 0;


                                $old_delivery_date = '';
                                $old_payment_invoice = '';

                                if(count($parcel_payment_data) > 0) {
                                    $i = 0;
                                    foreach ($parcel_payment_data as $parcel_delivery) {
                                        $i++;

                                        $delivery_date = date("Y-m-d", strtotime($parcel_delivery->created_at));
                                        $rowCount = count($date_array[$delivery_date]);
                                        $delivery_date_column ="";
                                        if($delivery_date != $old_delivery_date) {
                                            $delivery_date_column = '<td rowspan="'.$rowCount.'" class="text-center align-middle">'.$delivery_date.'</td>';
                                        }

                                        $payment_invoice = $parcel_delivery->parcel_delivery_payment->payment_invoice;
                                        $pInvRowCount   = count($pinvoice_array[$payment_invoice]);
                                        $payment_invoice_column ="";
                                        if($payment_invoice != $old_payment_invoice) {
                                            $payment_invoice_column = '<td rowspan="'.$pInvRowCount.'" class="text-center align-middle">'.$payment_invoice.'</td>';
                                        }

                                        $payment_id = $parcel_delivery->parcel_delivery_payment_id;

                                        if($parcel_delivery->status == 1) {
                                            $status = "Pending";
                                            $total_pending_amount += $parcel_delivery->amount;
                                        }elseif($parcel_delivery->status == 2) {
                                            $status = "Received <br> (".$parcel_delivery->parcel_delivery_payment->date_time.")";
                                            $total_receive_amount += $parcel_delivery->amount;
                                        }else {
                                            $status = "Rejected";
                                            $total_pending_amount += $parcel_delivery->amount;
                                        }

                                        $total_parcel_amount += $parcel_delivery->parcel->total_collect_amount;
                                        $total_delivery_charge += $parcel_delivery->parcel->total_charge;
                                        $total_payment_amount += $parcel_delivery->amount;


                                        echo '<tr>
                                                '.$delivery_date_column.'
                                                <td class="text-center">'.$parcel_delivery->parcel_delivery_payment->branch->name.'</td>
                                                '.$payment_invoice_column.'
                                                <td class="text-center">'.$parcel_delivery->parcel->parcel_invoice.'</td>
                                                <td class="text-center">'.$parcel_delivery->parcel->merchant->company_name.'</td>
                                                <td class="text-center">'. number_format($parcel_delivery->parcel->total_collect_amount,2).'</td>
                                                <td class="text-center">'. number_format($parcel_delivery->parcel->total_charge,2).'</td>
                                                <td class="text-center">'. number_format($parcel_delivery->amount,2).'</td>
                                                <td class="text-center">'.$status.'</td>
                                              </tr>';


                                        $old_delivery_date = $delivery_date;
                                        $old_payment_invoice = $payment_invoice;
                                    }

                                    echo '<tr>
                                            <th colspan="5" style="text-align: right">Total Amount: </th>
                                            <th class="text-center">'.$total_parcel_amount.' TK</th>
                                            <th class="text-center">'.$total_delivery_charge.' TK</th>
                                            <th class="text-center">'.$total_payment_amount.' TK</th>
                                            <th></th>

                                          </tr>';
                                }


                            @endphp
                            </tbody>
                        </table>
                        <h3>Total Branch Payment Receive Amount: <?php echo $total_receive_amount; ?> TK</h3>
                        <h3>Total Branch Payment Pending Amount: <?php echo $total_pending_amount; ?> TK</h3>

                        {{--<table id="deliveryPaymentStatement" class="table table-bordered table-striped">--}}
                            {{--<thead>--}}
                                {{--<tr>--}}
                                    {{--<th width="5%" class="text-center"> SL </th>--}}
                                    {{--<th width="10%" class="text-center"> Date</th>--}}
                                    {{--<th width="10%" class="text-center"> Consignment </th>--}}
                                    {{--<th width="10%" class="text-center"> Parcel Invoice </th>--}}
                                    {{--<th width="10%" class="text-center"> Amount</th>--}}
                                {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<td colspan="5" style="font-size: 18px; font-weight: 600;">Branch Payment Amount</td>--}}
                                {{--</tr>--}}
                                {{--@php--}}
                                    {{--$total_payment_amount = 0;--}}
                                    {{--if(count($parcel_payment_data) > 0) {--}}
                                        {{--$i = 0;--}}
                                        {{--foreach ($parcel_payment_data as $parcel_delivery) {--}}
                                            {{--$i++;--}}
                                            {{--$payment_id = $parcel_delivery->parcel_delivery_payment_id;--}}


                                            {{--$total_payment_amount += $parcel_delivery->amount;--}}

                                            {{--echo '<tr>--}}
                                                    {{--<td>'.$i.'</td>--}}
                                                    {{--<td>'.date("Y-m-d", strtotime($parcel_delivery->created_at)).'</td>--}}
                                                    {{--<td>'.$parcel_delivery->parcel_delivery_payment->payment_invoice.'</td>--}}
                                                    {{--<td>'.$parcel_delivery->parcel->parcel_invoice.'</td>--}}
                                                    {{--<td>'.$parcel_delivery->amount.'</td>--}}
                                                  {{--</tr>';--}}
                                        {{--}--}}

                                        {{--echo '<tr>--}}
                                                {{--<th colspan="4" style="text-align: right">Total Branch Payment Amount: </th>--}}
                                                {{--<th>'.$total_payment_amount.' TK</th>--}}
                                              {{--</tr>';--}}
                                    {{--}--}}


                                {{--@endphp--}}
                                {{--<tr>--}}
                                    {{--<td colspan="5" style="font-size: 18px; font-weight: 600;">Account Receive Amount</td>--}}
                                {{--</tr>--}}
                                {{--@php--}}
                                    {{--$total_receive_amount = 0;--}}
                                    {{--if(count($parcel_payment_data) > 0) {--}}
                                        {{--$i = 0;--}}
                                        {{--foreach ($parcel_payment_data as $parcel_delivery) {--}}
                                            {{--$i++;--}}
                                            {{--if($parcel_delivery->status == 2) {--}}
                                                {{--$payment_id = $parcel_delivery->parcel_delivery_payment_id;--}}


                                                {{--$total_receive_amount += $parcel_delivery->amount;--}}

                                                {{--echo '<tr>--}}
                                                        {{--<td>'.$i.'</td>--}}
                                                        {{--<td>'.date("Y-m-d", strtotime($parcel_delivery->created_at)).'</td>--}}
                                                        {{--<td>'.$parcel_delivery->parcel_delivery_payment->payment_invoice.'</td>--}}
                                                        {{--<td>'.$parcel_delivery->parcel->parcel_invoice.'</td>--}}
                                                        {{--<td>'.$parcel_delivery->amount.'</td>--}}
                                                      {{--</tr>';--}}
                                            {{--}--}}
                                        {{--}--}}

                                        {{--echo '<tr>--}}
                                                {{--<th colspan="4" style="text-align: right">Total Account Receive Amount: </th>--}}
                                                {{--<th>'.$total_receive_amount.' TK</th>--}}
                                              {{--</tr>';--}}
                                    {{--}--}}


                                {{--@endphp--}}

                                {{--<tr>--}}
                                    {{--<th colspan="3" style="font-size: 18px; text-align: center;">Total Branch Balance Amount</th>--}}
                                    {{--<td colspan="2" style="font-size: 18px; text-align: center;">{{ $total_payment_amount - $total_receive_amount . ' TK' }}</td>--}}
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
    window.onload = function(){

        $("#filter").on("click", function () {

            var branch_id    = $("#branch_id").val();
            var from_date    = $("#from_date").val();
            var to_date      = $("#to_date").val();

            if(branch_id != "" || from_date != "" || to_date != "") {
                $.ajax({
                    cache: false,
                    url: "{{ route('admin.account.getBranchDeliveryPaymentStatement') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        branch_id:branch_id,
                        from_date:from_date,
                        to_date:to_date
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#branchDeliveryPaymentStatement").html(response);
                    }
                })
            }else{
                toastr.error("Please filled any one field");
            }
        });

        $("#refresh").on("click", function(){
            $("#branch_id").val("");
            $("#from_date").val("");
            $("#to_date").val("");

            window.location.reload();
        });
    }
  </script>
@endpush

