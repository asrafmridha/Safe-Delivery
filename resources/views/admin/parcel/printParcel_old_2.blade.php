<!DOCTYPE html>
<html>
<head>
    <title>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <style>
        body {
            position: relative;
        }
        .top_info td img {
            margin-top: 5px !important;
            margin-bottom: 0 !important;
        }
        .merchant_info table tr td:last-child {
            width: 50% !important;
        }
        .borderless > thead > tr > th {
            border: 1px solid #fff !important;
        }
        .table {
            margin-bottom: .0rem;
        }
        .table td, .table th {
            padding: .0rem;
        }
    </style>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{asset("print/print_this.js")}}"></script>
    <script type="text/javascript">
    </script>
    <script>
        $(document).ready(function () {
            $('.labelPrint').printThis({
                importCSS: false,
                loadCSS: "{{ asset("print/barcode-printer/label-css.css") }}",
                afterPrint: function () {
                    window.close();
                }
            });
        });
    </script>
</head>

<body class="labelPrint">
<style>
    body {
        position: relative;
    }
    .top_info td img {
        margin-top: 5px !important;
        margin-bottom: 0 !important;
    }
    .merchant_info table tr td:last-child {
        width: 50% !important;
    }
    .borderless > thead > tr > th {
        border: 1px solid #fff !important;
    }
    .table {
        margin-bottom: .0rem;
    }
    .table td, .table th {
        padding: .0rem;
    }
</style>

<table width="100%" class=""
       style="margin-top: 5px; padding-left: 5px;">
    <thead>
    <tr style="margin: 0">
        <th class="" style="text-align: right;vertical-align: top;width: 15%;border: 1px solid #000000">
            Merchant:
        </th>
        <td style="border: 1px solid #000000" colspan="2">
            <table width="100%" class="table borderless">
                <tr style="border: none">
                    <th style="border: none;text-align: left"> {{ $parcel->merchant->company_name }} </th>
                </tr>
                <tr style="border: none">
                    <td style="border: none"> {{ $parcel->merchant->business_address }} </td>
                </tr>
                <tr style="border: none">
                    <td style="border: none">{{ $parcel->merchant->contact_number }} </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th class="" style="text-align: right;vertical-align: top;width: 15%;border: 1px solid #000000">
            Customer:
        </th>
        <td style="width: 70%; border: 1px solid #000000">
            <table width="100%" class="table borderless">
                <tr style="border: none">
                    <th style="border: none;text-align: left"> {{$parcel->customer_name }} </th>
                </tr>
                <tr style="border: none">
                    <td style="border: none"> {{  $parcel->customer_address  }} </td>
                </tr>
                <tr style="border: none">
                    <td style="border: none">{{ $parcel->customer_contact_number }} </td>
                </tr>
            </table>
        </td>
        <th style="text-align: center;vertical-align: top;width: 33%;border: 1px solid #000000">
            TK:{{$parcel->total_collect_amount}}
        </th>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%">
                <tr>
                    <th class="" style="text-align: center;vertical-align: top;width: 33%;border: 1px solid #000000">
                        Invoice: {{ $parcel->merchant_order_id }}
                    </th>
                    <th style="text-align: center; width: 33%; border: 1px solid #000000">
                        Area: {{$parcel->district->service_area->name}}
                    </th>
                    <th style="text-align: center;vertical-align: top;width: 34%;border: 1px solid #000000">
                        HUB: {{ optional($parcel->pickup_branch)->name }}
                    </th>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%">
                <tr>
                    <td rowspan="2" class=""
                        style="text-align: center;vertical-align: center;width: 15%;border: 1px solid #000000">
                        <img class="img-qr_code"
                             src="data:image/png; base64,{{ \DNS2D::getBarcodePNG($parcel->parcel_invoice, 'QRCODE') }}"
                             alt="QR code"
                             style="height:55px; width:55px;"/>
                    </td>
                    <th colspan="2" style="text-align: center; width: 33%; border: 1px solid #000000">
                        <img class="img-bar_code"
                             src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($parcel->parcel_invoice, 'C128', 2, 30) }}"
                             alt="barcode"
                             style="height:50px; width:70%; margin-top: 20px; margin-bottom: 5px"/>
                        <p class="align-center"> {{ $parcel->parcel_invoice }}</p>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center; width: 33%; border: 1px solid #000000">
                        Parcel ID: {{ $parcel->parcel_invoice }}</th>
                    <th style="text-align: center; width: 33%; border: 1px solid #000000">
                        Created: {{ $parcel->created_at->format("Y-m-d h:i") }}</th>
                </tr>
            </table>
        </td>
    </tr>
    </thead>
</table>

</body>
</html>
