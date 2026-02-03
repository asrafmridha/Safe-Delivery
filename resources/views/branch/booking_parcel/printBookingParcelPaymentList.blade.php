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
        @if(key_exists('status',$filter))
            @php
                switch ($filter['status']) {
                                  case 1 : $status  = "Send Request";break;
                                  case 2 : $status  = "Request Accept";break;
                                  case 3 : $status  = "Request Reject";break;
                                  default: $status = "None";break;
                              }
            @endphp
            <p class="text-right"><strong>Parcel Payment Type: </strong>
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
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Consignment </th>
            <th class="text-center"> Payment Parcel </th>
            <th class="text-center"> Received Payment Parcel</th>
            <th class="text-center"> Payment Amount </th>
            <th class="text-center"> Received Payment Amount</th>
            <th class="text-center"> Date </th>
            <th class="text-center"> Status </th>
        </tr>
        @foreach($bookingParcelPayments as $key => $bookingParcelPayment)
            @php
                switch ($bookingParcelPayment->payment_status) {
                     case '0':$status  = "Request Cancel";break;
                     case '1':$status = "Send Request"; break;
                     case '2':$status = "Request Accept";break;
                     default:$status    = "None";break;
                 }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$bookingParcelPayment->bill_no}}</td>
                <td>{{$bookingParcelPayment->payment_parcel}}</td>
                <td>{{$bookingParcelPayment->receive_parcel}}</td>
                <td>{{sprintf("%.2f", $bookingParcelPayment->total_amount)}}</td>
                <td>{{sprintf("%.2f", $bookingParcelPayment->receive_amount)}}</td>
                <td>{{$bookingParcelPayment->payment_date}}</td>
                <td>{{$status}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

