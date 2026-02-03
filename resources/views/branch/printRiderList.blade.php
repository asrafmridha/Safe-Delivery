<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rider List</title>
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
<div>
    <div class="center">
        <h2 class="text-center">{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4 class="text-center">Rider List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    @if($branchUser->branch->riders->count() > 0)
        <table class="table table-style table-bordered">
            <tr>
                <th> #</th>
                <th> Name</th>
                <th> Address</th>
                <th> Contact Number</th>
            </tr>
            @foreach($branchUser->branch->riders as $rider)
                <tr>
                    <td>
                    {{ $loop->iteration }} </th>
                    <td>
                    {{ $rider->name }} </th>
                    <td>{{ $rider->address }} </td>
                    <td>{{ $rider->contact_number }} </td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
</body>
</html>
