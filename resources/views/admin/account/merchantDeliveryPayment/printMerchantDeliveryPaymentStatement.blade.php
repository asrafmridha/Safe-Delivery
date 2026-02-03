<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merchant Delivery Payment Statement</title>
    <style>
        table,
        td,
        th {
            border: 1px solid #a39c9c;
            text-align: left;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 0 3px;
        }

        .center {
            margin: auto;
            width: 40%;
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

        h2,
        h3,
        h4,
        p {
            line-height: 0;
        }
    </style>
</head>

<body>
<div class="">
    <div class="left">
        <h2>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4>Merchant Delivery Payment Statement</h4>
        <p><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <div class="right">
        @if(key_exists('merchant_id',$filter))
            <p class="text-right"><strong>Merchant: </strong>
                @php
                    $merchant = \App\Models\Merchant::find($filter['merchant_id']);
                @endphp
                @if($merchant)
                    {{$merchant->company_name}}
                @endif
            </p>
        @endif
        @if(key_exists('from_date',$filter))
            <p class="text-right"><strong>From Date: </strong>
                {{$filter['from_date']}}
            </p>
        @endif
        @if(key_exists('to_date',$filter))
            <p class="text-right"><strong>To Date: </strong>
                {{$filter['to_date']}}
            </p>
        @endif
    </div>

    <table>
        <tr>
            <th width="10%" class="text-center"> Date</th>
            <th width="10%" class="text-center"> Transaction ID</th>
            <th width="10%" class="text-center"> Parcel Invoice</th>
            <th width="10%" class="text-center"> Company Name</th>
            <th width="10%" class="text-center"> Merchant Name</th>
            <th width="10%" class="text-center"> Payment Amount</th>
            <th width="10%" class="text-center"> Delivery Charge</th>
            <th width="10%" class="text-center"> Payable Amount</th>
            <th width="10%" class="text-center"> Status</th>
        </tr>
        @php

            @endphp

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
        @php

            @endphp
    </table>
</div>
</body>

</html>
