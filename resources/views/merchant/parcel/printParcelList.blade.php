<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parcel list</title>
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
            width: 40%;
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
<div class="">
    <div class="left">
        <h2>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4>Parcel list</h4>
        <p><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <div class="right">
        @if(key_exists('parcel_status',$filter))
            <p class="text-right"><strong>Parcel Status: </strong>
                @if($filter['parcel_status'] == 1)
                    Delivery Complete
                @elseif($filter['parcel_status'] == 2)
                    Delivery Pending
                @elseif($filter['parcel_status'] == 3)
                    Delivery Cancel
                @elseif($filter['parcel_status'] == 4)
                    Payment Done
                @elseif($filter['parcel_status'] == 5)
                    Payment Pending
                @elseif($filter['parcel_status'] == 6)
                    Return Complete
                @elseif($filter['parcel_status'] == 7)
                    Pickup Request
                @elseif($filter['parcel_status'] == 8)
                    Branch Transfer Complete
                @elseif($filter['parcel_status'] == 9)
                    Pickup Complete
                @elseif($filter['parcel_status'] == 10)
                    Branch Transfer
                @endif
            </p>
        @endif
        @if(key_exists('merchant_id',$filter))
            <p class="text-right"><strong>Merchant: </strong>
                @php
                    $merchant = \App\Models\Merchant::find($filter['merchant_id']);
                @endphp
                @if($merchant)
                    {{$merchant->company_name}}
                @endif
            </p>
        @endif
        @if(key_exists('parcel_invoice',$filter))
            <p class="text-right"><strong>Parcel Invoice: </strong>
                {{$filter['parcel_invoice']}}
            </p>
        @endif
        @if(key_exists('merchant_order_id',$filter))
            <p class="text-right"><strong>Merchant Order ID: </strong>
                {{$filter['merchant_order_id']}}
            </p>
        @endif
        @if(key_exists('customer_contact_number',$filter))
            <p class="text-right"><strong>Customer Contact Number: </strong>
                {{$filter['customer_contact_number']}}
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
            <th> SL</th>
            <th> Invoice</th>
            <th> Order Tracking</th>
            <th> Date/Time</th>
            <th> Parcel Status</th>
            <th> Parcel OTP</th>
            <th> Company Name</th>

            <th> Customer Name</th>
            <th> Customer Number</th>
            <th> Customer Address</th>
            <th> Customer District</th>
            <th> Customer Area</th>
            <th> Service Type</th>
            <th> Item Type</th>

            <th> Product Price</th>
            <th> Collected Amount</th>
            <th> COD Charge</th>
            <th> Total Charge</th>
            <th> Remarks</th>
            <th> Notes</th>
            <th> Payment Status</th>
            <th> Return Status</th>
        </tr>
        @php
            $total_collect_amount = 0;
            $customer_collect_amount =0;
            $total_cod_charge =0;
            $total_charge =0;
        @endphp
        @foreach($parcels as $key => $parcel)
            @php
                $parcelStatus = returnParcelStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $status_name = $parcelStatus['status_name'];
                $district = "";
                if ($parcel->district) {
                    $district = $parcel->district->name;
                }
                $area = "";
                if ($parcel->area) {
                    $area = $parcel->area->name;
                }
                $logs_note = "";
                if ($parcel->parcel_logs) {
                    foreach ($parcel->parcel_logs as $parcel_log) {
                        if ("" != $logs_note) {
                            $logs_note .= ",<br>";
                        }
                        $logs_note .= $parcel_log->note;
                    }
                }
                $parcelPaymentStatus = returnPaymentStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $payment_status_name = $parcelPaymentStatus['status_name'];
                $parcelReturnStatus = returnReturnStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $return_status_name = $parcelReturnStatus['status_name'];
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$parcel->parcel_invoice}}</td>
                <td>{{$parcel->merchant_order_id}}</td>
                <td>{{$parcel->date}}</td>
                <td>{{$status_name}}</td>
                <td>{{$parcel->parcel_code}}</td>
                <td>{{$parcel->merchant->company_name}}</td>
                <td>{{$parcel->customer_name}}</td>
                <td>{{$parcel->customer_contact_number}}</td>
                <td>{{$parcel->customer_address}}</td>
                <td>{{$district}}</td>
                <td>{{$area}}</td>
                <td>{{optional($parcel->service_type)->title}}</td>
                <td>{{optional($parcel->item_type)->title}}</td>
                <td>{{$parcel->total_collect_amount}}</td>
                <td>{{$parcel->customer_collect_amount}}</td>
                <td>{{$parcel->cod_charge}}</td>
                <td>{{$parcel->total_charge}}</td>
                <td>{{$parcel->parcel_note}}</td>
                <td>{{$logs_note}}</td>
                <td>{{$payment_status_name}}</td>
                <td>{{$return_status_name}}</td>
            </tr>
            @php
                $total_collect_amount += $parcel->total_collect_amount;
                $customer_collect_amount +=$parcel->customer_collect_amount;
                $total_cod_charge +=$parcel->cod_charge;
                $total_charge +=$parcel->total_charge;
            @endphp
        @endforeach
        <tr>
            <th class="text-right" colspan="14">Totals: </th>
            <th>{{$total_collect_amount}}</th>
            <th>{{$customer_collect_amount}}</th>
            <th>{{$total_cod_charge}}</th>
            <th>{{$total_charge}}</th>
            <th colspan="4"></th>
        </tr>
    </table>
</div>
</body>
</html>

