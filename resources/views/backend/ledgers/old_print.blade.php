<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Ledger</title>
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
    $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
    $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
    $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
    $order_by =key_exists('order_by',$filter)? $filter['order_by']:"";
    $count =key_exists('count',$filter)? $filter['count']:10;


    $filter_client="";
    if ($client_id){
        $filter_client=\App\Models\Client::where('id',$client_id)->first();
    }
    $totalBdtAmount=0;
    $totalPaymentAmount=0;
    $balance = $previous_balance;
@endphp
<fieldset class="scheduler-border">
    <legend class="scheduler-border">Client Ledger</legend>
    <p class="text-center"><strong>Date: </strong>{{date("d M, Y")}}</p>
    <div class="text-center">
        @if($filter_client)
            <p><strong>Client: </strong>{{$filter_client->name}}</p>
        @endif
        @if($from_date)
            <p><strong>From Date: </strong>{{$from_date}}</p>
        @endif
        @if($to_date)
            <p><strong>To Date: </strong>{{$to_date}}</p>
        @endif
        @if($balance)
            <p><strong>Previous Balance: </strong>{{$balance}}</p>
        @endif
    </div>

    <!-- data table start -->
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Transactions</legend>
        <div class="data_table my-4">
            <div class="content_section table-responsive">

                <table class="table table-bordered"
                       id="client_ledger" {{--style="font-size: 13px;"--}}>
                    <thead>
                    <tr>
                        <th width="3%" class="text-center">#</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Supplier Name</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>BDT Amount</th>
                        <th>Balance</th>
                        <th>Remarks</th>
                        <th>S/L</th>
                        <th>Beneficiary</th>
                        <th>Created By</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($items as $key=>$item)
                        @if(key_exists('payment_no',$item))
                            @php
                                if ($item['client_id']==$client_id){
                                    $balance += $item['amount'];
                                }else{
                                    $balance -= $item['amount'];
                                }
                            @endphp
                            <tr class="bg-success">
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                <td class="text-center">{{$item['payment_no']}}</td>
                                <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                <td class="text-center">
                                    @if ($item['status'] == 1)
                                        <span class="badge badge-info">Approved</span>
                                    @elseif ($item['status'] == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($item['status'] == 2)
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif ($item['status'] == 3)
                                        <span class="badge badge-primary">Order</span>
                                    @elseif ($item['status'] == 4)
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                                <td class="text-center">BDT</td>
                                <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                <td class="text-center">---</td>
                                <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                <td class="text-center">{{number_format($balance,2)}}</td>
                                <td class="text-center">---</td>
                                <td class="text-center">---</td>
                                <td class="text-center">---</td>
                                <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                            </tr>
                        @elseif(key_exists('transaction_no',$item))
                            @php
                                if ($item['client_id']==$client_id){
                                    $balance -= $item['s_bdt_amount'];
                                }else{
                                    $balance += $item['b_bdt_amount'];
                                }
                            @endphp
                            <tr class="bg-danger">
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                <td class="text-center">{{$item['transaction_no']}}</td>
                                <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                <td class="text-center">
                                    @if ($item['status'] == 1)
                                        <span class="badge badge-info">Approved</span>
                                    @elseif ($item['status'] == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($item['status'] == 2)
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif ($item['status'] == 3)
                                        <span class="badge badge-primary">Order</span>
                                    @elseif ($item['status'] == 4)
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                                <td class="text-center">{{key_exists('currency',$item)?$item['currency']['code']:"---"}}</td>
                                <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                @if ($item['client_id']==$client_id)
                                    <td class="text-center">{{$item['s_rate']}}</td>
                                    <td class="text-center">{{$item['s_bdt_amount']}}</td>
                                @else
                                    <td class="text-center">{{$item['b_rate']}}</td>
                                    <td class="text-center">{{$item['b_bdt_amount']}}</td>
                                @endif
                                <td class="text-center">{{$balance}}</td>
                                <td class="text-center">{{$item['remarks']}}</td>
                                <td class="text-center">{{$item['sl']}}</td>
                                <td class="text-center">{{$item['beneficiary']}}</td>
                                <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    {{-- <tfoot>
                     <tr>
                         <th colspan="8" style="text-align: right">Totals:</th>
                         <th class="text-center">{{number_format($totalBdtAmount)}}</th>
                     </tr>
                     </tfoot>--}}
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
</fieldset>
</body>
</html>




