<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <style>
        table, td, th {
            border: 1px solid #a39c9c;
            text-align: left;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 0 3px;
        }

        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }

        .left {
            width: 50%;
            float: left;
        }

        .right {
            width: 40%;
            float: right;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        body {
            margin: 5px 30px;
        }

        h2, h3, h4, p {
            line-height: 0;
        }

        .table-style td, .table-style th {
            padding: .1rem !important;
        }
    </style>
</head>
<body>
@php
    $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";
    $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
    $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
    $transaction_type =key_exists('transaction_type',$filter)? $filter['transaction_type']:"";
    $type = key_exists('transaction_for',$filter)? $filter['transaction_for']:"";
    $filter_currency="";
    if ($currency_id){
        $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
    }
    $totalCredit=0;
    $totalAmount=0;
    $totalDebit=0;
    $final_balance=0;
@endphp
<div class="row mx-2">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Currency Report</legend>
            <div class="row mx-2">
                <div class="col-md-12 text-center">
                    <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                    @if($filter_currency)
                        <p><strong>Currency: </strong>{{$filter_currency->name." - ".$filter_currency->code}}</p>
                    @else
                        <p><strong>Currency: </strong> All</p>
                    @endif

                    @if($from_date)
                        <p><strong>From Date: </strong>{{$from_date}}</p>
                    @endif
                    @if($to_date)
                        <p><strong>To Date: </strong>{{$to_date}}</p>
                    @endif

                    @if($total_approved_transaction>0)
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Approved/Completed</legend>
                            <table class="table">
                                <tbody>
                                <tr style="background-color: #bacae3;">
                                    <th>Total Transaction:</th>
                                    <td>{{$total_approved_transaction}}</td>
                                </tr>
                                <tr style="background-color: #bbf4cd;">
                                    <th>Total Pending Buying BDT Amount:</th>
                                    <td>{{$total_approved_b_bdt_amount}}</td>
                                </tr>
                                <tr style="background-color: #e2a8ea;">
                                    <th>Total Selling BDT Amount:</th>
                                    <td>{{$total_approved_s_bdt_amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    @endif
                    @if($total_pending_transaction>0)
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Pending</legend>
                            <table class="table">
                                <tbody>
                                <tr style="background-color: #bacae3;">
                                    <th>Total Transaction:</th>
                                    <td>{{$total_pending_transaction}}</td>
                                </tr>
                                <tr style="background-color: #bbf4cd;">
                                    <th>Total Pending Buying BDT Amount:</th>
                                    <td>{{$total_pending_b_bdt_amount}}</td>
                                </tr>
                                <tr style="background-color: #e2a8ea;">
                                    <th>Total Selling BDT Amount:</th>
                                    <td>{{$total_pending_s_bdt_amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    @endif
                </div>
            </div>
            <!-- end -->
        </fieldset>
    </div>
</div>
<!-- end -->
</body>
</html>
