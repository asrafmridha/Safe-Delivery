<!DOCTYPE html>
<html>
    <head>
        <title>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>

            .bold_text{
                /* font-size: 20px !important; */
            }

            .borderless>thead>tr>th{
                border: 1px solid #fff !important;
            }

            @media  print{
                .borderless>thead>tr>th{
                    border: 1px solid #fff !important;
                }
                .borderless>tbody>tr>td,
                .borderless>tbody>tr>th,
                .borderless>tfoot>tr>td,
                .borderless>tfoot>tr>th,
                .borderless>thead>tr>td,
                .borderless>thead>tr>th{
                    border: 1px solid #fff !important;
                }

                .table {
                    margin-bottom: .0rem !important;
                }
                .table td, .table th {
                    padding: .0rem !important;
                }
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

        @foreach ($riderRun->rider_run_details as $rider_run_detail)
            <table width="100%" style="margin-top: 3rm">
                <thead>
                    <tr>
                        <td class="text-center text-bold">
                            <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="width: 65%; height: 60px">
                        </td>
                    </tr>
                    <tr>
                        <td  class="text-center text-bold">
                            <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($rider_run_detail->parcel->parcel_invoice, 'C39', 1.5, 33) }}"
                            alt="barcode"
                            style="height:40px; width:80%; margin-top: 20px; margin-bottom: 5px"/>
                            <p> {{ $rider_run_detail->parcel->parcel_invoice }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td  class="text-left text-bold">
                            <p> Date : {{ \Carbon\Carbon::parse($rider_run_detail->parcel->date)->format('d/m/Y') }} </p>
                        </td>
                    </tr>
                </thead>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top: 10px; padding-left: 5px;">
                <thead>
                    <tr>
                        <td  class="text-left text-bold">
                        <b>Merchant </b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" class="table borderless">
                                <tr>
                                    <th style="width: 30%" class="text-left" > ID </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->merchant->m_id }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Name </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->merchant->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Number </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <th style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->merchant->contact_number }} </th>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Address </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->merchant->address }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Product Brief </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left"> {{ $rider_run_detail->parcel->product_details }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </thead>
            </table>
            <table width="100%" class="table table-bordered" style="margin-top: 10px; padding-left: 5px;">
                <thead>
                    <tr>
                        <td  class="text-left text-bold">
                        <b>Customer </b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" class="table borderless">
                                <tr>
                                    <th style="width: 30%" class="text-left"> Name </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->customer_name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Number </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <th style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->customer_contact_number }} </th>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Address </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->customer_contact_address }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 30%" class="text-left"> Collection Amount </th>
                                    <td style="width: 5%" class="text-center"> : </td>
                                    <td style="width: 65%" class="text-left">{{ $rider_run_detail->parcel->total_collect_amount }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </thead>
            </table>
            <table width="100%" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <td class="text-left text-bold">
                            <img src="data:image/png; base64,{{ \DNS2D::getBarcodePNG($rider_run_detail->parcel->parcel_invoice, 'QRCODE') }}" alt="QR code"
                            style="height:80px; width:80px;"/>
                        </td>
                    </tr>
                </thead>
            </table>
            <table width="100%" style="margin-top: 60px;">
                <thead>
                    <tr>
                        <td style="width: 50%" class="text-left text-bold">
                            <p style="text-decoration: overline;"> Rider Signature</p>
                        </td>
                        <td style="width: 50%" class="text-left text-bold">
                            <p style="text-decoration: overline;" >Customer Signature</p>
                        </td>
                    </tr>
                </thead>
            </table>
        @endforeach



    </body>
</html>
