<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Ledger</title>
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
    $status =key_exists('status',$filter)? $filter['status']:"";
    $transaction_type =key_exists('transaction_type',$filter)? $filter['transaction_type']:"";
    $created_by =key_exists('created_by',$filter)? $filter['created_by']:"";
    $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";
    $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
    $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
    $to_date =key_exists('to_date',$filter)? $filter['to_date']:"";
    $order_by =key_exists('order_by',$filter)? $filter['order_by']:"";
    $status_name = null;
    if ($status==0){
        $status_name="Pending";
    } elseif ($status==1){
        $status_name="Approved";
    }elseif ($status==2){
        $status_name="Rejected";
    }
     $transaction_type_name = null;
    if ($transaction_type=="debit"){
        $transaction_type_name="Debit";
    } elseif ($transaction_type=="credit"){
        $transaction_type_name="Credit";
    }

    $create_user="";
    if ($created_by){
        $create_user=\App\Models\User::where('id',$created_by)->first();
    }
    $filter_currency="";
    if ($currency_id){
        $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
    }
    $filter_client="";
    if ($client_id){
        $filter_client=\App\Models\Client::where('id',$client_id)->first();
    }

     $totalBdtAmount=0;
     $totalPaymentAmount=0;

@endphp
{{--<fieldset class="scheduler-border">--}}
{{--    <legend class="scheduler-border">Supplier Ledger</legend>--}}
    <div class="text-center center">
        <h3>Supplier Ledger</h3>
        <p><strong>Date: </strong>{{date("d M, Y")}}</p>
        @if($filter_client)
            <p><strong>Supplier: </strong>{{$filter_client->name}}</p>
        @endif
        @if($from_date)
            <p><strong>From Date: </strong>{{$from_date}}</p>
        @endif
        @if($to_date)
            <p><strong>To Date: </strong>{{$to_date}}</p>
        @endif
    </div>

    <!-- data table start -->
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Transactions</legend>
        <div class="data_table my-4">
            <div class="content_section table-responsive">
                <table class="table table-bordered table-striped"
                       id="client_ledger" {{--style="font-size: 13px;"--}}>
                    <thead>
                    <tr>
                        <th width="3%" class="text-center">#</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>BDT Amount</th>
                        <th>Remarks</th>
                        <th>S/L</th>
                        <th>Beneficiary</th>
                        <th>Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key=>$item)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                            <td class="text-center">{{$item->transaction_no}}</td>
                            <td>{{$item->client?$item->client->name:"---"}}</td>
                            <td class="text-center">
                                @if ($item->status == 1)
                                    <span class="badge badge-info">Approved</span>
                                @elseif ($item->status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($item->status == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif ($item->status == 3)
                                    <span class="badge badge-primary">Order</span>
                                @elseif ($item->status == 4)
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </td>
                            <td class="text-center">{{$item->currency->code}}</td>
                            <td class="text-center">{{number_format($item->amount,2)}}</td>
                            <td class="text-center">{{$item->b_rate}}</td>
                            <td class="text-center">{{$item->b_bdt_amount}}</td>
                            <td class="text-center">{{$item->remarks}}</td>
                            <td class="text-center">{{$item->sl}}</td>
                            <td class="text-center">{{$item->beneficiary}}</td>
                            <td class="text-center">{{$item->created_user->name}}</td>
                        </tr>
                        @php
                            $totalBdtAmount+=$item->b_bdt_amount;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="8" style="text-align: right">Totals:</th>
                        <th class="text-center">{{number_format($totalBdtAmount)}}</th>
                        <td colspan="4"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Payments</legend>
        <div class="data_table my-4">
            <div class="content_section table-responsive">
                <table class="table table-bordered table-striped"
                       id="client_ledger_payment" {{--style="font-size: 13px;"--}}>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Payment No</th>
                        <th>From</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Remarks</th>
                        <th>Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $key=>$item)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                            <td class="text-center">{{$item->payment_no}}</td>
                            <td>{{$item->client?$item->client->name:"---"}}</td>
                            <td class="text-center">
                                @if ($item->status == 1)
                                    <span class="badge badge-info">Approved</span>
                                @elseif ($item->status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($item->status == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif ($item->status == 3)
                                    <span class="badge badge-primary">Order</span>
                                @elseif ($item->status == 4)
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </td>
                            <td class="text-center">{{number_format($item->amount,2)}}</td>
                            <td class="text-center">{{$item->remarks}}</td>
                            <td class="text-center">{{$item->payment_method->name}}</td>
                            <td class="text-center">{{$item->created_user->name}}</td>
                        </tr>
                        @php
                            $totalPaymentAmount+=$item->amount;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right">Totals:</th>
                        <th class="text-center">{{number_format($totalPaymentAmount)}}</th>
                        <th colspan="3"></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Summery</legend>
        <div class="data_table my-4">
            <div class="content_section table-responsive">
                <table class="table table-bordered table-striped"
                       id="" {{--style="font-size: 13px;"--}}>
                    <tbody>
                    <tr>
                        <td class="text-center" rowspan="3" width="50%"><h1 class="mt-5">Summery</h1></td>
                        <td style="text-align: right">Total Transaction Amount:</td>
                        <td style="text-align: left">{{number_format($totalBdtAmount)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Total Payment Amount:</td>
                        <td style="text-align: left">{{number_format($totalPaymentAmount)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Due:</td>
                        <td style="text-align: left">{{number_format($totalBdtAmount-$totalPaymentAmount)}}</td>

                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
{{--</fieldset>--}}
<!-- end -->

</body>
</html>
