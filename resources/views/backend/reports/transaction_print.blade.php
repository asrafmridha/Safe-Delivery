<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report-transaction</title>
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
        $supplier_id =key_exists('supplier_id',$filter)? $filter['supplier_id']:"";
                $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";

$status =key_exists('status',$filter)? $filter['status']:"";
$from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
$to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
$status_name = null;
if ($status==0){
    $status_name="Pending";
} elseif ($status==1){
    $status_name="Approved";
}elseif ($status==2){
    $status_name="Rejected";
}elseif ($status==3){
    $status_name="Ordered";
}elseif ($status==4){
    $status_name="Completed";
}else{
    $status_name="All";
}
  $filter_client="";
if ($client_id){
    $filter_client=\App\Models\Client::where('id',$client_id)->first();
}
  $filter_supplier="";
if ($supplier_id){
    $filter_supplier=\App\Models\Client::where('id',$supplier_id)->first();
}
$filter_currency="";
        if ($currency_id){
            $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
        }

$totalBdtAmount=0;
$totalBuyBdtAmount=0;
$totalProfit=0;
@endphp
<div {{--style="font-size: 9px;"--}} >
    <div class="center"><p class="text-center">Transaction - Report</p></div>
    <div class="left">
        <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
        @if($filter_supplier)
            <p><strong>Supplier: </strong>{{$filter_supplier->name}}</p>
        @endif
        @if($filter_client)
            <p><strong>Client: </strong>{{$filter_client->name}}</p>
        @endif
        @if($filter_currency)
            <p><strong>Currency: </strong>{{$filter_currency->name." - ".$filter_currency->code}}</p>
        @endif
    </div>
    <div class="right text-right">
        @if($status_name)
            <p><strong>Status: </strong>{{$status_name}}</p>
        @endif
        @if($from_date)
            <p><strong>From Date: </strong>{{$from_date}}</p>
        @endif
        @if($to_date)
            <p><strong>To Date: </strong>{{$to_date}}</p>
        @endif
    </div>


    <!-- data table start -->
    <div class="data_table my-4" style="margin-top: 60px">
        <div class="content_section">
            <table class="table table-bordered" style="width: 100%">
                <thead>
                <tr>
                    <th width="3%" class="text-center">#</th>
                    <th>Date</th>
                    <th>Transaction No</th>
                    <th>Client Name</th>
                    <th>Supplier Name</th>
                    <th>Status</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Buying Rate</th>
                    <th>Selling Rate</th>
                    <th>Buying BDT Amount</th>
                    <th>Selling BDT Amount</th>
                    <th>Profit</th>
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
                        <td>{{$item->supplier?$item->supplier->name:"---"}}</td>
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
                        <td class="text-center">{{$item->s_rate}}</td>
                        <td class="text-center">{{$item->b_bdt_amount}}</td>
                        <td class="text-center">{{$item->s_bdt_amount}}</td>
                        <td class="text-center">{{$item->profit}}</td>
                        <td class="text-center">{{$item->remarks}}</td>
                        <td class="text-center">{{$item->sl}}</td>
                        <td class="text-center">{{$item->beneficiary}}</td>
                        <td class="text-center">{{$item->created_user->name}}</td>
                    </tr>
                    @php
                        $totalBuyBdtAmount+=$item->b_bdt_amount;
                        $totalBdtAmount+=$item->s_bdt_amount;
                        $totalProfit+=$item->profit;
                    @endphp
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="10" style="text-align: right">Totals:</th>
                    <th class="text-center">{{number_format($totalBuyBdtAmount)}}</th>
                    <th class="text-center">{{number_format($totalBdtAmount)}}</th>
                    <th class="text-center">{{number_format($totalProfit)}}</th>
                    <th colspan="4"></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<!-- end -->
</body>
</html>
