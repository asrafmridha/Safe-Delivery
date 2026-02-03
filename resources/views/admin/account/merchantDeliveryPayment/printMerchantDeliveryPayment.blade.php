<!DOCTYPE html>
<html>
<head>
    <title>Merchant Delivery Payment| {{ session()->get('company_name') ?? config('app.name', 'Flier Express') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>

        body {
            font-size: 10px !important;
        }

        .col-md-1 {
            width: 8%;
            float: left;
        }

        .col-md-2 {
            width: 16%;
            float: left;
        }

        .col-md-3 {
            width: 25%;
            float: left;
        }

        .col-md-4 {
            width: 33%;
            float: left;
        }

        .col-md-5 {
            width: 42%;
            float: left;
        }

        .col-md-6 {
            width: 50%;
            float: left;
        }

        .col-md-7 {
            width: 58%;
            float: left;
        }

        .col-md-8 {
            width: 66%;
            float: left;
        }

        .col-md-9 {
            width: 75%;
            float: left;
        }

        .col-md-10 {
            width: 83%;
            float: left;
        }

        .col-md-11 {
            width: 92%;
            float: left;
        }

        .col-md-12 {
            width: 100%;
            float: left;
        }

        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > thead > tr > th {
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
    window.onafterprint = function (event) {
        window.close();
    };
</script>

<body>
<div class="col-md-12" style="margin-top: 60px;">
    <div class="col-md-4">
        <table width="100%" style="margin-top: 3rem">
            <thead>
            <tr>
                <td class="text-center text-bold">
                    <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}"
                         style="width: 80%; height: 30px">
                </td>
            </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered" width="100%" style="margin-top: 3rem">
            <caption class="text-center text-bold">
                        <span style="font-size: 16px; font-weight: bold">
                            Payment
                        </span>
            </caption>
            <tr>
                <th style="width: 40%"> ID</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"
                    class="text-center"> {{ $parcelMerchantDeliveryPayment->merchant_payment_invoice }} </td>
            </tr>
            <tr>
                <th style="width: 40%"> Date</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"
                    class="text-center"> {{ \Carbon\Carbon::parse($parcelMerchantDeliveryPayment->date_time)->format('d/m/Y') }} </td>
            </tr>
            <tr>
                <th style="width: 40%">Total Parcel</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"
                    class="text-right"> {{ $parcelMerchantDeliveryPayment->total_payment_parcel }} </td>
            </tr>
            <tr>
                <th style="width: 40%">Total Amount</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"
                    class="text-right"> {{ number_format($parcelMerchantDeliveryPayment->total_payment_amount,2) }} </td>
            </tr>
            <tr>
                <th style="width: 40%"> Reference</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"
                    class="text-center"> {{ $parcelMerchantDeliveryPayment->transfer_reference }} </td>
            </tr>
            <tr>
                <th style="width: 40%"> Note</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->note }} </td>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-bordered" width="100%" style="margin-top: 3rem">
            <caption class="text-center text-bold">
                        <span style="font-size: 16px; font-weight: bold">
                            Merchant
                        </span>
            </caption>
            <tr>
                <th style="width: 40%"> Name</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->company_name }} </td>
            </tr>
            <tr>
                <th style="width: 40%"> Contact</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->contact_number }} </td>
            </tr>
            <tr>
                <th style="width: 40%"> Address</th>
                <td style="width: 10%"> :</td>
                <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->address }} </td>
            </tr>
        </table>
    </div>
</div>

<div class="col-md-12" style="margin-top: 20px;">
    @if($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details->count() > 0)
        <table class="table table-bordered" width="100%" style="margin-top: 3rem">
            <thead>
            <tr>
                <th width="5%" class="text-center"> SL</th>
                <th width="10%" class="text-center">Invoice</th>
                <th width="10%" class="text-center">Order ID</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-center">Customer Name</th>
                <th width="10%" class="text-center">Customer Number</th>
                <th width="8%" class="text-center">Amount to be Collect</th>
                <th width="10%" class="text-center">Collected</th>
                <th width="10%" class="text-center"> Weight Charge</th>
                <th width="10%" class="text-center"> COD Charge</th>
                <th width="10%" class="text-center">Delivery</th>
                <th width="10%" class="text-center">Return</th>
                <th width="10%" class="text-center">Total Charge</th>
                <th width="10%" class="text-center">Paid Amount</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalCollectionAmount=0;
                $total_weight_package_charge=0;
                $total_cod_charge=0;
                $total_delivery_charge=0;
                $total_return_charge=0;
                $total_paid_amount=0;
                $total_charge=0;
                $total_collect_amount=0;

            @endphp
            
            
            @foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $parcel_merchant_delivery_payment_detail)
            @php
                                    $parcelStatus = returnParcelStatusNameForMerchant($parcel_merchant_delivery_payment_detail->parcel->status, $parcel_merchant_delivery_payment_detail->parcel->delivery_type, $parcel_merchant_delivery_payment_detail->parcel->payment_type);
                                    
                                @endphp
                <tr>
                    <td class="text-center"> {{ $loop->iteration }} </td>
                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice }} </td>
                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->merchant_order_id }} </td>
                    <td class="text-center"> {{$parcelStatus['status_name']}} </td>
                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->customer_name }} </td>
                    <td class="text-center"> {{ $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number }} </td>
                    
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->parcel->total_collect_amount,2) }} </td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->collected_amount,2) }} </td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->weight_package_charge,2) }} </td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->cod_charge,2) }} </td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->delivery_charge,2) }} </td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->return_charge,2) }} </td>
<td class="text-center">
    {{
        number_format(
            $parcel_merchant_delivery_payment_detail->parcel->total_charge + $parcel_merchant_delivery_payment_detail->return_charge,
            2
        )
    }}
</td>
                    <td class="text-center"> {{ number_format($parcel_merchant_delivery_payment_detail->paid_amount,2) }} </td>
                </tr>
                @php
                
                    $totalCollectionAmount+=$parcel_merchant_delivery_payment_detail->collected_amount;
                    $total_weight_package_charge+=$parcel_merchant_delivery_payment_detail->weight_package_charge;
                    $total_cod_charge+=$parcel_merchant_delivery_payment_detail->cod_charge;
                    $total_delivery_charge+=$parcel_merchant_delivery_payment_detail->delivery_charge;
                    $total_return_charge+=$parcel_merchant_delivery_payment_detail->return_charge;
                    $total_paid_amount+=$parcel_merchant_delivery_payment_detail->paid_amount;
                    $total_charge+=($parcel_merchant_delivery_payment_detail->parcel->total_charge+$parcel_merchant_delivery_payment_detail->return_charge);
                    $total_collect_amount+=$parcel_merchant_delivery_payment_detail->parcel->total_collect_amount;
                @endphp
            @endforeach
            <tr>
                <th colspan="6" style="text-align: right">Totals:</th>
                
                <th class="text-center">{{number_format($total_collect_amount)}}</th>
                <th class="text-center">{{number_format($totalCollectionAmount)}}</th>
                <th class="text-center">{{number_format($total_weight_package_charge)}}</th>
                <th class="text-center">{{number_format($total_cod_charge)}}</th>
                <th class="text-center">{{number_format($total_delivery_charge)}}</th>
                <th class="text-center">{{number_format($total_return_charge)}}</th>
                <th class="text-center">{{number_format($total_charge)}}</th>
                <th class="text-center">{{number_format($total_paid_amount)}}</th>
            </tr>
            </tbody>
        </table>

        <table class="table" width="100%">
            <tbody>
            <tr>
                <th width="33%" class="text-center">
                    <br><br><br>
                    <span style="border-top:2px solid black; font-weight:bold ">
                                   &nbsp;&nbsp;&nbsp; Merchant Signature &nbsp;&nbsp;&nbsp;
                                </span>
                </th>
                <th width="33%" class="text-center"></th>
                <th width="33%" class="text-center">
                    <br><br><br>
                    <span style="border-top:2px solid black; font-weight:bold ">
                                    &nbsp;&nbsp;&nbsp; Authority &nbsp;&nbsp;&nbsp;
                                </span>
                </th>
            </tr>
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
