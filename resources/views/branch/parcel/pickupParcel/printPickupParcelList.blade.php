<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pickup Parcel List</title>
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
        <h4 class="text-center">Pickup Parcel List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center">Date</th>
            <th class="text-center"> Invoice</th>
            <!--<th class="text-center"> Status</th>-->
            <th class="text-center"> Company Name</th>
            <th class="text-center"> Merchant Contact Number</th>
            <th class="text-center"> Customer Name</th>
            <th class="text-center"> Customer Number</th>
            <th class="text-center"> Pickup Address</th>
            <th class="text-center"> District</th>
            <!--<th class="text-center"> Area</th>-->
            <th class="text-center"> Collectable Amount</th>
        </tr>
        @foreach($pickupParcels as $key => $pickupParcel)
            @php
                $parcelStatus = returnParcelStatusNameForBranch($pickupParcel->status, $pickupParcel->delivery_type, $pickupParcel->payment_type);
                $status_name = $parcelStatus['status_name'];
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$pickupParcel->pickup_branch_date ?? $pickupParcel->date}}</td>
                <td class="text-center">{{$pickupParcel->parcel_invoice}}</td>
                <!--<td class="text-center">{{$status_name}}</td>-->
                <td class="text-center">{{$pickupParcel->merchant->company_name}}</td>
                <td class="text-center">{{$pickupParcel->merchant->contact_number}}</td>
                <td class="text-center">{{$pickupParcel->customer_name}}</td>
                <td class="text-center">{{$pickupParcel->customer_contact_number}}</td>
                <td class="text-center">{{$pickupParcel->pickup_address}}</td>
                <td class="text-center">{{$pickupParcel->district->name}}</td>
                
                <!--<td class="text-center">{{$pickupParcel->upazila->name}}</td>-->
                <td class="text-center">{{$pickupParcel->total_collect_amount}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

