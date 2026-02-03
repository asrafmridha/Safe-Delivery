<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merchant List</title>
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
        <h4 class="text-center">Merchant List</h4>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    @if($branchUser->branch->merchants->count() > 0)
    <table class="table table-style table-bordered">
        <tr>
            <th rowspan="2" style="width: 10%"> #</th>
            <th rowspan="2" style="width: 15%"> Name</th>
            <th rowspan="2" style="width: 15%"> Company</th>
            <td rowspan="2" style="width: 20%"> Address</td>
            <td rowspan="2" style="width: 15%"> Contact Number</td>
            <td rowspan="2" class="text-center" style="width: 10%"> COD Charge</td>
            <td class="text-center" colspan="3" style="width: 30%;"> Service Charge</td>
        </tr>
        <tr>
            @foreach ($serviceAreas as $serviceArea)
                <td class="text-center" style="width: 10%"> {{ $serviceArea->name }} </td>
            @endforeach
        </tr>
        @foreach($branchUser->branch->merchants as $merchant)
            <tr>
                <th>{{ $loop->iteration }} </th>
                <th>{{ $merchant->name }} </th>
                <th>{{ $merchant->company_name }} </th>
                <td>{{ $merchant->address }} </td>
                <td>{{ $merchant->contact_number }} </td>
                <td class="text-center">{{ $merchant->cod_charge ?? 0  }} %</td>

                @foreach ($serviceAreas as $serviceArea)
                    @php
                        $merchantServiceAreaCharge = $serviceArea->default_charge;
                        foreach($merchant->service_area_charges as $service_area_charge){
                            if($service_area_charge->id == $serviceArea->id){
                                $merchantServiceAreaCharge                 = $service_area_charge->pivot->charge;
                            }
                        }
                    @endphp
                    <td class="text-center"> {{ number_format($merchantServiceAreaCharge,2) }} </td>
                @endforeach
            </tr>
        @endforeach
    </table>
    @endif
</div>
</body>
</html>

