<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Received Booking Parcel List</title>
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
        <h4 class="text-center">Received Booking Parcel List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Date </th>
            <th class="text-center"> C/N No</th>
            <th class="text-center"> Booking Type </th>
            <th class="text-center"> Sender Contact </th>
            <th class="text-center"> Sender Branch </th>
            <th class="text-center"> Receiver Contact </th>
            <th class="text-center"> Net Amount </th>
            <th class="text-center"> Delivery Type </th>
            <th class="text-center"> Status </th>
        </tr>
        @foreach($bookingParcels as $key => $bookingParcel)
            @php
                switch ($bookingParcel->booking_parcel_type) {
                                    case 'cash':$booking_type  = "Cash";break;
                                    case 'to_pay':$booking_type = "To Pay";break;
                                    case 'condition':$booking_type  = "Condition";break;
                                    case 'credit':$booking_type = "Credit";break;
                                    default:$booking_type    = "None";break;
                                }
                                $total_amount = $bookingParcel->net_amount + $bookingParcel->pickup_charge;
  switch ($bookingParcel->delivery_type) {
                    case 'hd':$delivery_type  = "HD"; break;
                    case 'thd':$delivery_type  = "THD";break;
                    case 'od':$delivery_type  = "OD"; break;
                    case 'tod':$delivery_type  = "TOD";break;
                    default:$delivery_type = "None"; break;
                }

              $receiver_warehouse_name = ($bookingParcel->receiver_warehouses) ? $bookingParcel->receiver_warehouses->wh_name : 'Warehouse';

                switch ($bookingParcel->status) {
                    case 0:$status_name    = "Parcel Reject from operation"; break;
                    case 1:$status_name    = "Confirmed Booking";break;
                    case 2:$status_name    = "Vehicle Assigned"; break;
                    case 3:$status_name    = "Assign $receiver_warehouse_name";break;
                    case 4:$status_name    = "Warehouse Received Parcel";break;
                    case 5:$status_name    = "Assign $receiver_warehouse_name";break;
                    case 6:$status_name    = "On the way to receive";break;
                    case 7:$status_name    = "Received Parcel";break;
                    case 8:$status_name    = "Parcel Delivery Complete";break;
                    case 9:$status_name    = "Parcel Delivery Return";break;
                    //    case 8:$status_name  = "Delivery Branch Received"; $class  = "success";break;
                    //    case 9:$status_name  = "Delivery Branch Reject"; $class  = "success";break;
                    //    case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                    //    case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                    default:$status_name = "None";break;
                }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$bookingParcel->booking_date}}</td>
                <td class="text-center">{{$bookingParcel->parcel_code}}</td>
                <td class="text-center">{{$booking_type}}</td>
                <td class="text-center">{{$bookingParcel->sender_phone}}</td>
                <td class="text-center">{{$bookingParcel->sender_branch->name}}</td>
                <td class="text-center">{{$bookingParcel->receiver_phone}}</td>
                <td class="text-center">{{sprintf("%.2f", $total_amount)}}</td>
                <td class="text-center">{{$delivery_type}}</td>
                <td class="text-center">{{$status_name}}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

