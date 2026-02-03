<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Received Branch Transfer List</title>
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
    <div class="center">
        <h2 class="text-center">{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4 class="text-center">Received Branch Transfer List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Consignment</th>
            <th class="text-center"> Branch Name</th>
            <th class="text-center"> Branch Address</th>
            <th class="text-center"> Branch Contact Number</th>
            <th class="text-center"> Create Date</th>
            <th class="text-center"> Received Date</th>
            <th class="text-center"> Transfer Parcel</th>
            <th class="text-center"> Received Parcel</th>
        </tr>
        @foreach($deliveryBranchTransfers as $key => $deliveryBranchTransfer)
            @php

                @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->delivery_transfer_invoice}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->to_branch->name}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->to_branch->address}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->to_branch->contact_number}}</td>
                <td class="text-center">{{date('d-m-Y H:i:s', strtotime($deliveryBranchTransfer->create_date_time))}}</td>
                <td class="text-center">{{($deliveryBranchTransfer->received_date_time) ? date('d-m-Y H:i:s', strtotime($deliveryBranchTransfer->received_date_time)) : ""}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->total_transfer_parcel}}</td>
                <td class="text-center">{{$deliveryBranchTransfer->total_transfer_received_parcel}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

