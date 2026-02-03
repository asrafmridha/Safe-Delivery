<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Warehouse User list</title>
    <style>
        table, td, th {
            border: 1px solid #a39c9c;
            text-align: left;
            /*font-size: 12px;*/
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
        <h4 class="text-center">Warehouse User list</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Name </th>
            <th class="text-center"> Email </th>
            <th class="text-center"> Warehouse </th>
            <th class="text-center"> Contact Number </th>
            <th class="text-center"> Status </th>
        </tr>
        @foreach($warehouseUsers as $key => $warehouseUser)
            @php

            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$warehouseUser->name}}</td>
                <td class="text-center">{{$warehouseUser->email}}</td>
                <td class="text-center">{{$warehouseUser->warehouse->name}}</td>
                <td class="text-center">{{$warehouseUser->contact_number}}</td>
                <td class="text-center">{{$warehouseUser->status == 1 ? "Active" : "Inactive"}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

