<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parcel Payment List</title>
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
    </style>
</head>
<body>
<div>
    <div class="left">
        <h2>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4>Parcel Payment List</h4>
        <p><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <div class="right">
        @if(key_exists('payment_receive_type',$filter))
            @php
                if ($filter['payment_receive_type'] == 'booking'){
        $status = "Booking";
    }elseif ($filter['payment_receive_type'] == 'delivery'){
        $status = "Delivery";
    }

            @endphp
            <p class="text-right"><strong>Payment Type: </strong>
                {{$status}}
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
        <thead>
        <tr>
            <th width="5%" class="text-center"> SL</th>
            <th width="10%" class="text-center"> Date</th>
            <th width="10%" class="text-center"> C/N No</th>
            <th width="10%" class="text-center"> Payment Receive</th>
            <th width="10%" class="text-center"> Parcel Amount</th>
            <th width="10%" class="text-center"> Branch Amount</th>
            <th width="10%" class="text-center"> Forward Amount</th>
            <th width="10%" class="text-center"> Account Receive Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            if(count($payment_details) > 0) {
                for($i=0; $i<count($payment_details); $i++) {
                    echo html_entity_decode($payment_details[$i]);
                }
            }else{
                echo '<tr>
                        <td colspan="8" style="text-align:center"> No data available here!</td>
                    </tr>';
            }
        @endphp

        <tr>
            <td colspan="4" class="text-center text-bold">Total</td>
            <td class="text-center text-bold">{{ number_format((float) $total_parcel_amount, 2, '.', '') }}</td>
            <td class="text-center text-bold">{{ number_format((float) $total_branch_amount, 2, '.', '') }}</td>
            <td class="text-center text-bold">{{ number_format((float) $total_forward_amount, 2, '.', '') }}</td>
            <td class="text-center text-bold">{{ number_format((float) $total_receive_amount, 2, '.', '') }}</td>
        </tr>

        </tbody>
    </table>
</div>
</body>
</html>

