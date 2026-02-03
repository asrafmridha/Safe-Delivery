<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaction</title>
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
    $user_id =key_exists('user_id',$filter)? $filter['user_id']:"";
    $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
    $to_date =key_exists('to_date',$filter)? $filter['to_date']:"";
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
     $filter_user="";
        if ($user_id){
            $filter_user=\App\Models\User::where('id',$user_id)->first();
        }
    $totalBdtAmount=0;
    $totalDebit=0;
    $totalCredit=0;

@endphp
<fieldset class="scheduler-border">
    <legend class="scheduler-border">Internal Transaction Filter</legend>
    <p class="text-center"><strong>Date: </strong>{{date("d M, Y")}}</p>
    <div class="row mx-5 ">
        <div class="col-md-6 left">
            @if($status_name)
                <p><strong>Status: </strong>{{$status_name}}</p>
            @endif
            @if($create_user)
                <p><strong>Created By: </strong>{{$create_user->name}}</p>
            @endif
            @if($filter_currency)
                <p><strong>Currency: </strong>{{$filter_currency->code.' - '.$filter_currency->name}}</p>
            @endif
        </div>
        <div class="col-md-6 text-right right">
            @if($transaction_type_name)
                <p><strong>Transaction Type: </strong>{{$transaction_type_name}}</p>
            @endif
            @if($filter_user)
                <p><strong>User: </strong>{{$filter_user->name}}</p>
            @endif
            @if($from_date)
                <p><strong>From Date: </strong>{{$from_date}}</p>
            @endif
            @if($to_date)
                <p><strong>To Date: </strong>{{$to_date}}</p>
            @endif
        </div>
    </div>

    <!-- data table start -->
    <div class="data_table my-4">
        <div class="content_section">
            <table class="table table-bordered" {{--style="font-size: 13px;"--}}>
                <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="9%">Date</th>
                    <th width="12%">Transaction No</th>
                    <th>User Name</th>
                    <th>Currency</th>
                    <th>Payment Type</th>
                    <th>Remarks</th>
                    <th width="10%">Status</th>
                    <th>Created By</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th width="12%">Credit</th>
                    <th width="12%">Debit</th>
                    <th width="12%">Balance</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $debit=0;
                    $credit=0;
                @endphp
                @foreach($items as $key=>$item)
                    @php
                        $totalBdtAmount+=$item->bdt_amount;
                        if ($item->transaction_type=="debit"){
                            $totalDebit+=$item->bdt_amount;
                        }
                        if ($item->transaction_type=="credit"){
                            $totalCredit+=$item->bdt_amount;
                        }
                    @endphp
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{date('d M, Y',strtotime($item->date))}}</td>
                        <td>{{$item->transaction_no}}</td>
                        <td>{{$item->user?$item->user->name:"---"}}</td>
                        <td>{{$item->currency->code}}</td>
                        <td>{{$item->payment_type}}</td>
                        <td>{{$item->remarks}}</td>
                        <td>
                            @if ($item->status == 1)
                                Approved
                            @elseif ($item->status == 0)
                                Pending
                            @elseif ($item->status == 2)
                                Rejected
                            @endif
                        </td>
                        <td>{{$item->created_user->name}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->rate}}</td>
                        {{--                                <td>{{$item->bdt_amount}}</td>--}}
                        <td>{{$item->transaction_type=="credit"?$item->bdt_amount:0}}</td>
                        <td>{{$item->transaction_type=="debit"?$item->bdt_amount:0}}</td>
                        <td>{{$totalCredit - $totalDebit}}</td>
                    </tr>

                @endforeach
                <tr>
                    <th colspan="11" class="text-right">Totals:</th>
                    <th>{{$totalCredit}}</th>
                    <th>{{$totalDebit}}</th>
                    <th>{{$totalCredit - $totalDebit}}</th>
                </tr>
                <tr>
                    <th rowspan="5" colspan="9" class="text-center">
                        <h2 class="mt-5">Summary</h2>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Total Transaction BDT Amount:</th>
                    <th>{{$totalBdtAmount}}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Total Credit:</th>
                    <th>{{$totalCredit}}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Total Debit:</th>
                    <th>{{$totalDebit}}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Total Balance:</th>
                    <th>{{$totalCredit-$totalDebit}}</th>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <!-- end -->
</fieldset>


<script>

</script>

</body>
</html>
