<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merchant list</title>
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
        <h4 class="text-center">Merchant list</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> ID </th>
            <th class="text-center"> Company Name </th>
            <th class="text-center"> Name </th>
            <th class="text-center"> Email </th>
            <th class="text-center"> Contact Number </th>
            <th class="text-center"> Branch </th>
            <th class="text-center"> COD </th>
            <th class="text-center"> Area </th>
            <th class="text-center"> District </th>
            <th class="text-center"> Status </th>
        </tr>
        @foreach($merchants as $key => $merchant)
            @php
                $cod_charge = "0 %";
                if (!empty($merchant->cod_charge)) {
                    $cod_charge = $merchant->cod_charge . ' %';
                } elseif (is_null($merchant->cod_charge)) {
                    $cod_charge = "";
                }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$merchant->m_id}}</td>
                <td>{{$merchant->company_name}}</td>
                <td>{{$merchant->name}}</td>
                <td>{{$merchant->email}}</td>
                <td>{{$merchant->contact_number}}</td>
                <td>{{$merchant->branch->name}}</td>
                <td>{{$cod_charge}}</td>
                <td>{{$merchant->area->name}}</td>
                <td>{{$merchant->district->name}}</td>
                <td>{{$merchant->status == 1 ? "Active" : "Inactive"}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

