@extends('layouts.admin_layout.admin_layout')

@push('style_css')
    <style>

        .table-responsive > .table-bordered {
            border: 1px solid #dee2e6;
        }

    </style>
@endpush
@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Merchant Pickup Parcel Report</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Merchant Pickup Parcel Report</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="printArea">
                    <div class="card-header">

                        <div class="row input-daterange" style="margin-top: 10px">
                            <div class="col-md-3">
                                <label for="merchant_id">Merchant </label>
                                <select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" >
                                    <option value="0" >Select Merchant  </option>
                                    @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" >{{ $merchant->company_name }} </option>
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
                                <button type="button" name="print" id="printBtn" class="btn btn-primary">Print</button>
                                <a href="{{ route('admin.merchantPickupParcelReportExport') }}" class="btn btn-danger" id="excelDownload">Excel</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive" id="merchantParcelReport">

                        <div class="report-header" style="margin-top: 10px;">
                            <h3 class="text-center">Merchant Pickup Parcel Report </h3>
                            <h5 class="text-center">From <b>{{ $from_date }}</b> to <b>{{ $to_date }}</b></h5>
                        </div>
                        <table id="merchantWiseReport" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center"> SL</th>
                                <th width="5%" class="text-center"> Merchant ID</th>
                                <th width="15%" class="text-center"> Merchant Company </th>
                                @php
                                    if(count($date_array) > 0) {

                                        foreach ($date_array as $k=>$v) {
                                            echo '<th class="text-center">'.$v.'</th>';
                                        }
                                    }
                                @endphp
                                <th width="5%" class="text-center"> Total</th>
                            </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $total_parcel = 0;
                                    if(count($merchants) > 0) {
                                        $i = 0;
                                        foreach ($merchants as $merchant) {
                                            $i++;
                                ?>
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td class="text-center">{{ $merchant->m_id }}</td>
                                                <td class="text-left">{{ $merchant->company_name }}</td>
                                                <?php
                                                    $total_parcel_count = 0;
                                                    if(count($date_array) > 0) {

                                                        foreach ($date_array as $k=>$v) {
                                                            $parcel_count = 0;
                                                            if(array_key_exists($merchant->id.'_'.$k, $final_array)) {
                                                                $parcel_count = $final_array[$merchant->id.'_'.$k];
                                                            }
                                                            $total_parcel_count += $parcel_count;
                                                            echo '<td class="text-center">'.$parcel_count.'</td>';

                                                            $full_date_array[$k] += $parcel_count;
                                                        }

                                                    }

                                                    $total_parcel += $total_parcel_count;
                                                ?>
                                                <td class="text-center text-bold">{{ $total_parcel_count }}</td>
                                            </tr>
                                <?php
                                        }

                                        echo '<tr>
                                                <!--<td colspan="'.(count($date_array) + 3).'" class="text-center"><h5><b>Total Parcel</b></h5></td>-->
                                                <td colspan="3" class="text-center"><h5><b>Total Parcel</b></h5></td>';
                                        foreach ($date_array as $k=>$v) {
                                            $value = $full_date_array[$k];
                                            echo  '<td class="text-center"><h6><b>'.$value.'</b></h6></td>';

                                        }
                                        echo    '<td class="text-center"><h5><b>'.$total_parcel.'</b></h5></td>
                                              </tr>';
                                    }

                                ?>
                            </tbody>

                        </table>

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
                                    {{--<th colspan="2" style="font-size: 18px; text-align: center;">{{ $total_paid_amount - $total_receive_amount . ' TK' }}</th>--}}
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

<!-- For Frint -->
<script src="{{asset("print/print_this.js")}}"></script>
<script>

    $("#printBtn").on("click", function () {

        $('#printArea').printThis({
            importCSS: false,
            loadCSS: "{{ asset("print/merchant_parcel_report_print.css") }}",
            afterPrint: function () {
                window.close();
            }
        });

    });

    //            window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }


    //        function printBarcode(areaID) {
    //            var printContent = document.getElementById(areaID);
    //            var WinPrint = window.open('', '', '');
    //            WinPrint.document.write(printContent.innerHTML);
    //            WinPrint.document.close();
    //            WinPrint.focus();
    //            WinPrint.print();
    //            WinPrint.close();
    //        }
</script>
<script>
    var export_url = "{{ route('admin.merchantPickupParcelReportExport') }}";

    window.onload = function(){

        $("#filter").on("click", function () {

            var merchant_id    = $("#merchant_id").val();
            var from_date    = $("#from_date").val();
            var to_date      = $("#to_date").val();


            if(merchant_id != "" && merchant_id != 0 && (from_date != "" && to_date != "")) {
                var url = export_url+'?merchant_id='+merchant_id+'&from_date='+from_date+'&to_date='+to_date;
            }
            else if(merchant_id != "" && merchant_id != 0) {
                url = export_url+'?merchant_id='+merchant_id;
            }
            else if((from_date != "" && to_date != "")) {
                url = export_url+'?from_date='+from_date+'&to_date='+to_date;
            }
            else{
                url = export_url;
            }

            $("#excelDownload").attr("href", url);

            if((merchant_id != "" && merchant_id != 0)|| (from_date != "" && to_date != "")) {
                $.ajax({
                    cache: false,
                    url: "{{ route('admin.merchant.getParcelReport') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        merchant_id:merchant_id,
                        from_date:from_date,
                        to_date:to_date
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#merchantParcelReport").html(response);
                    }
                })
            }else{
                toastr.error("Please filled merchant or both date field");
            }
        });

        $("#refresh").on("click", function(){
            $("#merchant_id").val("");
            $("#from_date").val("");
            $("#to_date").val("");

            window.location.reload();
        });
    }

//    function addUrl(event) {
//        var merchant_id    = $("#merchant_id").val();
//        var from_date    = $("#from_date").val();
//        var to_date      = $("#to_date").val();
//
//        if(merchant_id != "" && merchant_id != 0) {
//            $(event).attr('href', function() {
//                console.log(this.href + '&merchant_id='+merchant_id);
//            });
//        }
//
//        return false;
//    }
  </script>
@endpush

