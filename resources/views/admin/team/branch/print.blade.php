<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Branches</title>
    <style>
        table, td, th {
            border: 1px solid #a39c9c;
            text-align: left;
            font-size: 10px;
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
        <h4 class="text-center">Branch list</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th> Name</th>
            <th> Email</th>
            <th> Address</th>
            <th> Type</th>
            <th> Parent</th>
            <th> District</th>
            <th> Area</th>
            <th> Status</th>
        </tr>
        @foreach($branches as $key => $branch)
            @php
                if ($branch->type == 1) {
                    $type = "Parent";
                } else {
                    $type = "Sub-Branch";
                }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$branch->name}}</td>
                <td>{{$branch->email}}</td>
                <td>{{$branch->address}}</td>
                <td>{{$type}}</td>
                <td>{{($branch->parent_branch) ? $branch->parent_branch->name : "Default"}}</td>
                <td>{{$branch->district->name}}</td>
                <td>{{$branch->area->name}}</td>
                <td>{{$branch->status == 1 ? "Active" : "Inactive"}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

