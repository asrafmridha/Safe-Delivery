<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pending/Reschedule Delivery Parcel List</title>
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
        <h4>Pending/Reschedule Delivery Parcel List</h4>
        <p><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <div class="right">
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
            <th class="text-center"> Date</th>
            <th class="text-center"> Invoice</th>
            <th class="text-center"> Parcel Code</th>
            <th class="text-center"> Company Name</th>
            <th class="text-center"> Customer Name</th>
            <th class="text-center"> Customer Address</th>
            <th class="text-center"> District</th>
            <th class="text-center"> Area</th>
            <th class="text-center"> Collection Amount</th>
            <th class="text-center"> COD Charge</th>
            <th class="text-center"> Delivery Charge</th>
            <th class="text-center"> Weight Package Charge</th>
            <th class="text-center"> Total Charge</th>
            <th class="text-center"> Status</th>
        </tr>
        @foreach($parcels as $key => $parcel)
            @php
                $parcelStatus = returnParcelStatusNameForBranch($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $status_name = $parcelStatus['status_name'];
                $district = "";
                if ($parcel->district) {
                    $district = $parcel->district->name;
                }
                $area = "";
                if ($parcel->area) {
                    $area = $parcel->area->name;
                }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$parcel->reschedule_parcel_date}}</td>
                <td>{{$parcel->parcel_invoice}}</td>
                <td>{{$parcel->parcel_code}}</td>
                <td>{{$parcel->merchant->company_name}}</td>
                <td>{{$parcel->customer_name}}</td>
                <td>{{$parcel->customer_address}}</td>
                <td>{{$district}}</td>
                <td>{{$area}}</td>
{{--                <td>{{optional($parcel->service_type)->title}}</td>--}}
{{--                <td>{{optional($parcel->item_type)->title}}</td>--}}
                <td>{{$parcel->total_collect_amount}}</td>
                <td>{{$parcel->cod_charge}}</td>
                <td>{{$parcel->delivery_charge}}</td>
                <td>{{$parcel->weight_package_charge}}</td>
                <td>{{$parcel->total_charge}}</td>
                <td>{{$status_name}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

