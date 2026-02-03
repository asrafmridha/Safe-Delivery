<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Item list</title>
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
        <h4 class="text-center">Item list</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th> Item Name</th>
            <th> Item Category</th>
            <th> Unit</th>
            <th> OD Rate</th>
            <th> HD Rate</th>
            <th> Transit OD</th>
            <th> Transit HD</th>
            <th class="text-center"> Status</th>
        </tr>
        @foreach($items as $key => $item)
            @php

            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$item->item_name}}</td>
                <td>{{$item->item_categories->name}}</td>
                <td>{{$item->units->name}}</td>
                <td>{{$item->od_rate}}</td>
                <td>{{$item->hd_rate}}</td>
                <td>{{$item->transit_od}}</td>
                <td>{{$item->transit_hd}}</td>
                <td class="text-center">{{$item->status == 1 ? "Active" : "Inactive"}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

