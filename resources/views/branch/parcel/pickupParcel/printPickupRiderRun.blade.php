<!DOCTYPE html>
<html>
    <head>
        <title>Pickup Rider Run | {{ session()->get('company_name') ?? config('app.name', 'Flier Express') }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            body{
                font-size: 10px !important;
            }
            .col-md-1 {width:8%;  float:left;}
            .col-md-2 {width:16%; float:left;}
            .col-md-3 {width:25%; float:left;}
            .col-md-4 {width:33%; float:left;}
            .col-md-5 {width:42%; float:left;}
            .col-md-6 {width:50%; float:left;}
            .col-md-7 {width:58%; float:left;}
            .col-md-8 {width:66%; float:left;}
            .col-md-9 {width:75%; float:left;}
            .col-md-10{width:83%; float:left;}
            .col-md-11{width:92%; float:left;}
            .col-md-12{width:100%; float:left;}

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 2px;
                line-height: 1;
            }
            .table {
                margin-bottom: .0rem;
            }
            .table td, .table th {
                padding: .0rem;
            }
        </style>
    </head>
	<script type="text/javascript">
        window.print();
        window.onafterprint = function(event){
			window.close();
        };
	</script>

    <body>
        <div class="col-md-12" style="margin-top: 60px;">
            <div class="col-md-4">
                <table width="100%" style="margin-top: 3rm">
                    <thead>
                        <tr>
                            <td class="text-center text-bold">
                                <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="width: 65%; height: 60px">
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-4">
                <table class="table table-bordered" width="100%" style="margin-top: 3rm">
                    <tr>
                        <th class="text-left text-bold" width="50%">
                            Consignment:
                        </th>
                        <td class="text-left text-bold">
                            {{ $riderRun->run_invoice }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left text-bold" width="50%">
                            Rider Name:
                        </th>
                        <td class="text-left text-bold">
                            {{ $riderRun->rider->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left text-bold" width="50%">
                            Rider ID:
                        </th>
                        <td class="text-left text-bold">
                            {{ $riderRun->rider->r_id }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left text-bold" width="50%">
                            Reporting Branch:
                        </th>
                        <td class="text-left text-bold">
                            {{ $riderRun->branch->name }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left text-bold" width="50%">
                            Date of Dispatched:
                        </th>
                        <td class="text-left text-bold">
                            {{ \Carbon\Carbon::parse($riderRun->create_date_time)->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12" style="margin-top: 20px;">
            <table class="table table-bordered" width="100%" style="margin-top: 3rm">
                <thead>
                    <tr>
                        <th class="text-left text-bold" width="3%">
                            SL
                        </th>
                        <th class="text-left text-bold" width="10%">
                            Order ID
                        </th>
                        <th class="text-left text-bold" width="12%">
                            Merchant Express Order ID
                        </th>
                        <th class="text-left text-bold" width="15%">
                            Merchant
                        </th>
                        <th class="text-left text-bold" width="15%">
                            Merchant Address
                        </th>
                        <th class="text-left text-bold" width="15%">
                            Merchant Delivery Area
                        </th>
                        <th class="text-left text-bold" width="10%">
                            Customer Name
                        </th>
                        <th class="text-left text-bold" width="10%">
                            Customer Phone
                        </th>
                        <th class="text-left text-bold" width="10%">
                            Signature
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riderRun->rider_run_details as $rider_run_detail)
                        <tr>
                            <td class="text-left">
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-left">
                                {{ $rider_run_detail->parcel->parcel_invoice }}
                            </td>
                            <td class="text-left">
                                {{ $rider_run_detail->parcel->merchant_order_id }}
                            </td>
                            <td class="text-left">
                                @php
                                    $contact_number = "";
                                    if($rider_run_detail->parcel->merchant->contact_number) {
                                        if(strpos($rider_run_detail->parcel->merchant->contact_number,"/")){
                                            $multiple_number = explode('/', $rider_run_detail->parcel->merchant->contact_number);
                                            $contact_number = " ({$multiple_number[0]}, {$multiple_number[1]}";
                                        }else{
                                           $contact_number  = " ({$rider_run_detail->parcel->merchant->contact_number})";
                                        }
                                    }
                                @endphp
                                {{ $rider_run_detail->parcel->merchant->company_name.$contact_number }}
                            </td>
                            <td class="text-left">
                                @php
                                    if($rider_run_detail->parcel->merchant_shops) {
                                        echo '(<b>'.$rider_run_detail->parcel->merchant_shops->shop_name.'</b>)<br>';
                                    }
                                    if($rider_run_detail->parcel->pickup_address != "") {
                                        echo $rider_run_detail->parcel->pickup_address;
                                    }else{
                                        $rider_run_detail->parcel->merchant->address;
                                    }
                                @endphp
                                {{--{{ $rider_run_detail->parcel->merchant->address }}--}}
                            </td>
                            <td class="text-left">
                                {{ $rider_run_detail->parcel->merchant->area->name }}
                            </td>
                            <td class="text-left">
                                {{ $rider_run_detail->parcel->customer_name }}
                            </td>
                            <td class="text-left">
                                {{ $rider_run_detail->parcel->customer_contact_number }}
                            </td>
                            <td class="text-left">

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </body>
</html>
