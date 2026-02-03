<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Received Return Branch Transfer List</title>
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
        <h4 class="text-center">Received Return Branch Transfer List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <table>
        <tr>
            <th class="text-center"> SL</th>
            <th class="text-center"> Consignment</th>
            <th class="text-center"> Branch Name</th>
            <th class="text-center"> Branch Address</th>
            <th class="text-center"> Branch Contact Number</th>
            <th class="text-center"> Create Date</th>
            <th class="text-center"> Received Date</th>
            <th class="text-center"> Transfer Parcel</th>
            <th class="text-center"> Received Parcel</th>
            <th class="text-center"> Status</th>
        </tr>
        @foreach($returnBranchTransfers as $key => $returnBranchTransfer)
            @php
                switch ($returnBranchTransfer->status) {
                                    case 1 : $status_name  = "Return Request";break;
                                    case 2 : $status_name  = "Return Request Cancel";break;
                                    case 3 : $status_name  = "Return Request Accept";break;
                                    case 4 : $status_name  = "Return Request Reject";break;
                                    default:$status_name = "None";break;
                                }
                @endphp
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td class="text-center">{{$returnBranchTransfer->return_transfer_invoice}}</td>
                <td class="text-center">{{$returnBranchTransfer->from_branch->name}}</td>
                <td class="text-center">{{$returnBranchTransfer->from_branch->address}}</td>
                <td class="text-center">{{$returnBranchTransfer->from_branch->contact_number}}</td>
                <td class="text-center">{{date('d-m-Y H:i:s', strtotime($returnBranchTransfer->create_date_time))}}</td>
                <td class="text-center">{{($returnBranchTransfer->received_date_time) ? date('d-m-Y H:i:s', strtotime($returnBranchTransfer->received_date_time)) : ""}}</td>
                <td class="text-center">{{$returnBranchTransfer->total_transfer_parcel}}</td>
                <td class="text-center">{{$returnBranchTransfer->total_transfer_received_parcel}}</td>
                <td class="text-center">{{$status_name}}</td>


            </tr>
        @endforeach
    </table>
</div>
</body>
</html>

