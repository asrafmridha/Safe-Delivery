<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delivery Rider Run List</title>
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
        <h4>Delivery Rider Run List</h4>
        <p><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <div class="right">
        @if(key_exists('run_status',$filter))
            <p class="text-right"><strong>Parcel Status: </strong>
                @if($filter['run_status'] == 1)
                    Run Create
                @elseif($filter['run_status'] == 2)
                    Run Start
                @elseif($filter['run_status'] == 3)
                    Run Cancel
                @elseif($filter['run_status'] == 4)
                    Run Complete
                @endif
            </p>
        @endif
        @if(key_exists('rider_id',$filter))
            <p class="text-right"><strong>Merchant: </strong>
                @php
                    $rider = \App\Models\Rider::find($filter['rider_id']);
                @endphp
                @if($rider)
                    {{$rider->name}}
                @endif
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
           @php
          $final_amount=0;
          $total_parcel=0;
          $total_complete_parcel=0;
            @endphp
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Consignment</th>
            <th class="text-center"> Rider Name</th>
            <th class="text-center"> Rider Phone</th>
            <th class="text-center"> Create Date</th>
            <th class="text-center"> Complete Date</th>
            <th class="text-center"> Total Amount</th>
            <th class="text-center"> Run Parcel</th>
            <th class="text-center"> Complete Parcel</th>
            <th class="text-center"> Status</th>
        </tr>
        @foreach($riderRuns as $key => $riderRun)
            @php
            
            $parcels=$riderRun->rider_run_details;
                $total_amount = 0;
                foreach($parcels as $parcel){
                $total_amount += $parcel->parcel->total_collect_amount;
                }
            $final_amount+=$total_amount;
            
            $total_parcel+=$riderRun->total_run_parcel;
            $total_complete_parcel+=$riderRun->total_run_complete_parcel;
            
            
                switch ($riderRun->status) {
                    case 1 :
                        $status_name = "Run Create";
                        break;
                    case 2 :
                        $status_name = "Run Start";
                        break;
                    case 3 :
                        $status_name = "Run Cancel";
                        break;
                    case 4 :
                        $status_name = "Run Complete";
                        break;
                    default:
                        $status_name = "None";
                        break;
                }
            @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$riderRun->run_invoice}}</td>
                <td class="text-center">{{$riderRun->rider->name}}</td>
                <td class="text-center">{{$riderRun->rider->contact_number}}</td>
                <td class="text-center">{{$riderRun->create_date_time}}</td>
                <td class="text-center">{{$riderRun->complete_date_time}}</td>
                <td class="text-center">{{$total_amount}}</td>
                <td class="text-center">{{$riderRun->total_run_parcel}}</td>
                <td class="text-center">{{$riderRun->total_run_complete_parcel}}</td>
                <td class="text-center">{{$status_name}}</td>
            </tr>
        @endforeach
         <tr>
                <th class="text-center" colspan="6">Total:</th>
                <th class="text-center">{{$final_amount}}</th>
                <th class="text-center">{{$total_parcel}}</th>
                <th class="text-center">{{$total_complete_parcel}}</th>
            </tr>
    </table>
</div>
</body>
</html>

