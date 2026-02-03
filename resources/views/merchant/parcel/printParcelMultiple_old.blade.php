<!DOCTYPE html>
<html>
<head>
    <title>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}

    <style>
        .div-table {
            display: table;
            width: 100%;
            border-left: 1px solid #000000;
            border-top: 1px solid #000000;
            /*margin: 10px;*/
        }

        .div-table-row {
            display: table-row;
            width: auto;
            clear: both;
        }

        .div-table-col {
            float: left; /* fix for  buggy browsers */
            display: table-column;
            border-bottom: 1px solid #000000;
            border-right: 1px solid #000000;
            margin: 0px;
        }

        p {
            margin: 0 0 5px;
        }

        .button {
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }
    </style>

    <script type="text/javascript">
        function printPage() {
            var button = document.getElementById('print');
            button.style.display = 'none';
            window.print();
            window.close();
        }

        function cancelPage() {
            window.close();
        }
    </script>
</head>

<body class="labelPrint">
<span id="print" style="float: right; margin-bottom: 20px">
    <button class="button" style="background-color: #4CAF50;" onclick="printPage()">Print</button>
    <button class="button" style="background-color: #f44336;" onclick="cancelPage()">Cancel</button>
</span>
<style>
    .div-table {
        display: table;
        width: 100%;
        border-left: 1px solid #000000;
        border-top: 1px solid #000000;
        /*margin: 10px;*/
    }

    .div-table-row {
        display: table-row;
        width: auto;
        clear: both;
    }

    .div-table-col {
        float: left; /* fix for  buggy browsers */
        display: table-column;
        border-bottom: 1px solid #000000;
        border-right: 1px solid #000000;
        margin: 0;
    }

    p {
        margin: 0 0 0;
    }
</style>
<div id="printArea">
    @foreach($parcels as $parcel)
        <div class="div-table">
            <div class="div-table-row">
                <div class="div-table-col" style="width: 100%; text-align: center; font-size: 20px">
                    <strong>{{session()->get('company_name')}}</strong>
                </div>
            </div>
            <div class="div-table-row">
                <div class="div-table-col" style="width: 15%; height: 40px; text-align: right;border-right: 0;">
                    <strong style="margin-right: 5px">Merchant:</strong>
                </div>
                <div class="div-table-col" style="width: 85%;height: 40px;margin-right: -1px">
                    <strong style="margin-left: 5px">{{ $parcel->merchant->company_name }}</strong>
                    {{--            <p style="margin-left: 5px">{{ $parcel->merchant->business_address }}</p>--}}
                    <p style="margin-left: 5px">{{ $parcel->merchant->contact_number }}</p>
                </div>
            </div>
            <div class="div-table-row">
                <div class="div-table-col" style="width: 15%; height: 75px; text-align: right;border-right: 0">
                    <strong style="margin-right: 5px">Customer:</strong>
                </div>
                <div class="div-table-col" style="width: 70%;height: 75px;border-right: 0">
                    <strong style="margin-left: 5px">{{$parcel->customer_name }}</strong>
                    <p style="margin-left: 5px">{{  $parcel->customer_address  }}</p>
                    <p style="margin-left: 5px">{{ $parcel->customer_contact_number }}</p>
                </div>

                <div class="div-table-col" style="width: 15%; height: 75px; text-align: center;margin-right: -1px">
                    <p style="margin-top: 10px">TK: {{$parcel->total_collect_amount}}</p>
                </div>
            </div>

            <div class="div-table-row" style="text-align: center">
                <div class="div-table-col" style="width: 33%;margin-right: -1px">
                    <strong>Invoice: {{ $parcel->merchant_order_id }}</strong>
                </div>
                <div class="div-table-col" style="width: 33%;margin-right: -1px">
                    <strong>Area: {{$parcel->area->name}}</strong>
                </div>
                <div class="div-table-col" style="width: 34%;margin-right: -1px">
                    <strong>HUB: {{ optional($parcel->pickup_branch)->name }}</strong>
                </div>
            </div>
            <div class="div-table-row" style="text-align: center">
                <div class="div-table-col" style="width: 100%;">
                    {{ $parcel->product_details }}
                </div>
            </div>
            <div class="div-table-row" style="text-align: center;">
                <div class="div-table-col" style="width: 20%;height: 102px;margin-right: -1px;padding-bottom: 1px">
                    <img class="img-qr_code"
                         src="data:image/png; base64,{{ \DNS2D::getBarcodePNG($parcel->parcel_invoice, 'QRCODE') }}"
                         alt="QR code"
                         style="height:55px; width:55px;margin: 10px"/>
                </div>
                <div class="div-table-col" style="width: 80%;height: 80px;margin-right: -1px">
                    <img class="img-bar_code"
                         src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($parcel->parcel_invoice, 'C128', 2, 30) }}"
                         alt="barcode"
                         style="height:50px; width:70%; margin-top: 5px;"/>
                    <p class="align-center"> {{ $parcel->parcel_invoice }}</p>
                </div>
                <div class="div-table-col" style="width: 40%;height: 22px;margin-right: -1px">
                    <strong>Parcel ID: {{ $parcel->parcel_invoice }}</strong>
                </div>
                <div class="div-table-col" style="width: 40%;height: 22px;margin-right: -1px">
                    <strong>
                        Created: {{ $parcel->created_at->format("Y-m-d h:i") }}
                    </strong>
                </div>
            </div>

        </div>

        <p style='overflow:hidden;page-break-before:always;'></p>
    @endforeach
</div>
</body>
</html>
