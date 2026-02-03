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
    $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
    $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
@endphp
<div class="row mx-2">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Profit Report</legend>
            <div class="row mx-2">
                <div class="col-md-12 text-center">
                    <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                    {{--@if($filter_supplier)
                        <p><strong>Supplier: </strong>{{$filter_supplier->name}}</p>
                    @endif
                    @if($filter_client)
                        <p><strong>Client: </strong>{{$filter_client->name}}</p>
                    @endif
                    @if($filter_currency)
                        <p><strong>Currency: </strong>{{$filter_currency->name." - ".$filter_currency->code}}</p>
                    @endif
                    @if($status_name)
                        <p><strong>Status: </strong>{{$status_name}}</p>
                    @endif--}}
                    @if($from_date)
                        <p><strong>From Date: </strong>{{$from_date}}</p>
                    @endif
                    @if($to_date)
                        <p><strong>To Date: </strong>{{$to_date}}</p>
                    @endif
                 {{--   <table class="table">
                        <tbody>
                        <tr style="background-color: #bacae3;">
                            <th>Total Transaction: </th>
                            <td>{{$total_transaction}}</td>
                        </tr>
                        <tr style="background-color: #bbf4cd;">
                            <th>Total Buying BDT Amount: </th>
                            <td>{{$total_b_bdt_amount}}</td>
                        </tr>
                        <tr style="background-color: #e2a8ea;">
                            <th>Total Selling BDT Amount: </th>
                            <td>{{$total_s_bdt_amount}}</td>
                        </tr>
                        <tr style="background-color: #f6bfbf;">
                            <th>Total Profit: </th>
                            <td>{{$total_profit}}</td>
                        </tr>
                        </tbody>
                    </table>--}}

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Currency</th>
                            <th>Transaction</th>
                            <th>Amount</th>
                            <th>Buying Amount</th>
                            <th>Selling Amount</th>
                            <th>Profit</th>
                        </tr>

                        </thead>
                        <tbody>
                        @php
                            $total_transaction = 0;
                            $total_amount = 0;
                            $total_b_bdt_amount = 0;
                            $total_s_bdt_amount = 0;
                            $total_profit = 0;
                        @endphp

                        @foreach($currencies as $currency)
                            <tr>
                                <td>{{$currency->currency->code}}</td>
                                <td>{{$currency->total}}</td>
                                <td>{{$currency->total_amount}} {{$currency->currency->code}}</td>
                                <td>{{$currency->total_b_bdt_amount}} BDT</td>
                                <td>{{$currency->total_s_bdt_amount}} BDT</td>
                                <td>{{$currency->total_s_bdt_amount - $currency->total_b_bdt_amount}} BDT</td>
                            </tr>

                            @php
                                $total_transaction += $currency->total;
                                $total_amount+=$currency->total_amount;
                                $total_b_bdt_amount+=$currency->total_b_bdt_amount;
                                $total_s_bdt_amount+=$currency->total_s_bdt_amount;
                                $total_profit += ($currency->total_s_bdt_amount - $currency->total_b_bdt_amount);
                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Totals</th>
                            <th>{{$total_transaction}}</th>
                            <th>{{$total_amount}}</th>
                            <th>{{$total_b_bdt_amount}} BDT</th>
                            <th>{{$total_s_bdt_amount}} BDT</th>
                            <th>{{$total_profit}} BDT</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- end -->
        </fieldset>
    </div>
</div>
<!-- end -->
</body>
</html>
