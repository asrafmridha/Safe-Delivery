<html>
    <head>
        <title>{{ isset($page_title) ?  $page_title."  " : ''}}</title>
        <link rel="icon" type="image/png" href="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}">
        @stack('style_css')
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <script type="text/javascript">
            function printPage(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                // document.body.style.marginTop="-45px";

                var button = document.getElementById('print');
               // button.style.display = 'none'; //or
                //button.style.visibility = 'hidden';
                window.print();
                document.body.innerHTML = originalContents;
                window.close();
            }

            function cancelPage() {
                window.close();
            }
        </script>

    </head>

    <body>

						<span id="print" class="float-right">
							<button class="btn btn-sm btn-info" onclick="printPage('printArea')">Print</button>
							<button class="btn btn-sm btn-danger" onclick="cancelPage()">Cancel</button>
						</span>

        <div id="printArea">
            <div id="customer-copy">
                <div class="row" style="text-align: center;">
                    <div class="col-md-2">
                        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="height:60px; width:100%;">
                    </div>
                    <div class="col-md-8" >
                        <strong style="color: red; font-size: 18px;letter-spacing:1px;">{{ session()->get('company_name') ?? config('app.name', 'Courier') }}</strong><br/>
                        <strong>Head Office : </strong> &nbsp; Al-Razi Complex Suite#C/304, !66-167 Shaheed Syed Nazrul Islam Sarani, Bijoy Nagar, Dhaka 1000.<br/>
                        <strong style="color:red;letter-spacing:1px;">Hot Line :01810007676, www.mettroexpress.com</strong>
                    </div>
                    <div class="col-md-2">Customer Copy</div>
                    <div class="col-md-12" style="border-bottom:2px red solid;"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Service Type :</strong>
                        @php
                        if($booking_parcel->delivery_type == 'hd'){
                                echo 'Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'thd'){
                            echo 'Transit Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'od'){
                            echo 'Office Delivery';
                        }elseif($booking_parcel->delivery_type == 'tod'){
                            echo 'Transit Office Delivery';
                        }
                        @endphp
                        <br/>
                        <strong>Parcel No :</strong> {{ $booking_parcel->parcel_code }}<br/>

                        <strong>From</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->sender_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->sender_phone }} <br/>
                    </div>

                    <div class="col-md-4" style="text-align: left;">
                        <br>
                        <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($booking_parcel->parcel_code, 'C39', 1.5, 33) }}" alt="barcode" style="height:40px; width:200px;"/>
                        <br>
                        {{ $booking_parcel->sender_branch->name }} To  {{ $booking_parcel->receiver_branch->name }}
                    </div>

                    <div class="col-md-4">
                        <strong>  Booking Date : </strong>@php echo date('d-M-Y', strtotime($booking_parcel->booking_date)); @endphp <br>
                        <strong>To</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->receiver_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->receiver_phone }} <br/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style table-bordered" style="margin-top: 10px; text-align:center;">
                            <tr>
                                <th>SL. No.</th>
                                <th>Category </th>
                                <th>Description </th>
                                <th>Unit </th>
                                <th>Unit Rate </th>
                                <th>Quantity </th>
                                <th>Total Rate </th>
                            </tr>
                            @foreach ($booking_parcel->booking_items as $booking_item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                    <td>{{ $booking_item->unit_price }}</td>
                                    <td>{{ $booking_item->quantity }}</td>
                                    <td>{{ $booking_item->total_item_price }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th colspan="6" style="text-align: right">Total Amount </th>
                                <th>{{ $booking_parcel->total_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Vat Amount ({{ $booking_parcel->vat_percent }} %)</th>
                                <th>{{ $booking_parcel->vat_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Discount Total </th>
                                <th>{{ $booking_parcel->discount_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Grand Total</th>
                                <th> {{ $booking_parcel->grand_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Net Amount </th>
                                <th>{{ $booking_parcel->net_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Paid Amount </th>
                                <th>{{ $booking_parcel->paid_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Due Amount </th>
                                <th>{{ $booking_parcel->due_amount }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong style="text-decoration: overline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Booking Officer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                    </div>

                    <div class="col-md-6">

                    </div>
                </div>
            </div>

            <div id="booking-copy" style="margin-top: 20px !important;">
                <div class="row" style="text-align: center;">
                    <div class="col-md-2">
                        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="height:60px; width:100%;">
                    </div>
                    <div class="col-md-8" >
                         <strong style="color: red; font-size: 18px;letter-spacing:1px;">{{ session()->get('company_name') ?? config('app.name', 'Courier') }}</strong><br/>
                        <strong>Head Office : </strong> &nbsp; Al-Razi Complex Suite#C/304, !66-167 Shaheed Syed Nazrul Islam Sarani, Bijoy Nagar, Dhaka 1000.<br/>
                        <strong style="color:red;letter-spacing:1px;">Hot Line :01810007676, www.mettroexpress.com</strong>
                    </div>
                    <div class="col-md-2">Booking Branch Copy</div>
                    <div class="col-md-12" style="border-bottom:2px red solid;"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Service Type :</strong>
                        @php
                        if($booking_parcel->delivery_type == 'hd'){
                                echo 'Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'thd'){
                            echo 'Transit Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'od'){
                            echo 'Office Delivery';
                        }elseif($booking_parcel->delivery_type == 'tod'){
                            echo 'Transit Office Delivery';
                        }
                        @endphp
                        <br/>
                        <strong>Parcel No :</strong> {{ $booking_parcel->parcel_code }}<br/>

                        <strong>From</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->sender_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->sender_phone }} <br/>
                    </div>

                    <div class="col-md-4">
                        <br>
                        <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($booking_parcel->parcel_code, 'C39', 1.5, 33) }}" alt="barcode" style="height:40px; width:200px;"/>
                        <br>
                        {{ $booking_parcel->sender_branch->name }} To  {{ $booking_parcel->receiver_branch->name }}
                    </div>

                    <div class="col-md-4">
                        <strong>  Booking Date : </strong>@php echo date('d-M-Y', strtotime($booking_parcel->booking_date)); @endphp <br>
                        <strong>To</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->receiver_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->receiver_phone }} <br/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style table-bordered" style="margin-top: 10px; text-align:center;">
                            <tr>
                                <th>SL. No.</th>
                                <th>Category </th>
                                <th>Description </th>
                                <th>Unit </th>
                                <th>Unit Rate </th>
                                <th>Quantity </th>
                                <th>Total Rate </th>
                            </tr>
                            @foreach ($booking_parcel->booking_items as $booking_item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                    <td>{{ $booking_item->unit_price }}</td>
                                    <td>{{ $booking_item->quantity }}</td>
                                    <td>{{ $booking_item->total_item_price }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th colspan="6" style="text-align: right">Total Amount </th>
                                <th>{{ $booking_parcel->total_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Vat Amount ({{ $booking_parcel->vat_percent }} %)</th>
                                <th>{{ $booking_parcel->vat_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Discount Total </th>
                                <th>{{ $booking_parcel->discount_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Grand Total</th>
                                <th> {{ $booking_parcel->grand_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Net Amount </th>
                                <th>{{ $booking_parcel->net_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Paid Amount </th>
                                <th>{{ $booking_parcel->paid_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Due Amount </th>
                                <th>{{ $booking_parcel->due_amount }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong style="text-decoration: overline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Booking Officer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                    </div>

                    <div class="col-md-6">

                    </div>
                </div>
            </div>


            <div id="delivery-copy" style="margin-top: 20px !important;">
                <div class="row" style="text-align: center;">
                    <div class="col-md-2">
                        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="height:60px; width:100%;">
                    </div>
                    <div class="col-md-8" >
                         <strong style="color: red; font-size: 18px;letter-spacing:1px;">{{ session()->get('company_name') ?? config('app.name', 'Courier') }}</strong><br/>
                        <strong>Head Office : </strong> &nbsp; Al-Razi Complex Suite#C/304, !66-167 Shaheed Syed Nazrul Islam Sarani, Bijoy Nagar, Dhaka 1000.<br/>
                        <strong style="color:red;letter-spacing:1px;">Hot Line :01810007676, www.mettroexpress.com</strong>
                    </div>
                    <div class="col-md-2">Delivery Branch Copy</div>
                    <div class="col-md-12" style="border-bottom:2px red solid;"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Service Type :</strong>
                        @php
                        if($booking_parcel->delivery_type == 'hd'){
                                echo 'Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'thd'){
                            echo 'Transit Home Delivery';
                        }elseif($booking_parcel->delivery_type == 'od'){
                            echo 'Office Delivery';
                        }elseif($booking_parcel->delivery_type == 'tod'){
                            echo 'Transit Office Delivery';
                        }
                        @endphp
                        <br/>
                        <strong>Parcel No :</strong> {{ $booking_parcel->parcel_code }}<br/>

                        <strong>From</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->sender_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->sender_phone }} <br/>
                    </div>

                    <div class="col-md-4">
                        <br>
                        <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($booking_parcel->parcel_code, 'C39', 1.5, 33) }}" alt="barcode" style="height:40px; width:200px;"/>
                        <br>
                        {{ $booking_parcel->sender_branch->name }} To  {{ $booking_parcel->receiver_branch->name }}
                    </div>

                    <div class="col-md-4">
                        <strong>  Booking Date : </strong>@php echo date('d-M-Y', strtotime($booking_parcel->booking_date)); @endphp <br>
                        <strong>To</strong> : <br/>
                        <strong>Address :</strong>  {{ $booking_parcel->receiver_address }} <br/>
                        <strong> Phone :</strong> {{ $booking_parcel->receiver_phone }} <br/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style table-bordered" style="margin-top: 10px; text-align:center;">
                            <tr>
                                <th>SL. No.</th>
                                <th>Category </th>
                                <th>Description </th>
                                <th>Unit </th>
                                <th>Unit Rate </th>
                                <th>Quantity </th>
                                <th>Total Rate </th>
                            </tr>
                            @foreach ($booking_parcel->booking_items as $booking_item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                    <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                    <td>{{ $booking_item->unit_price }}</td>
                                    <td>{{ $booking_item->quantity }}</td>
                                    <td>{{ $booking_item->total_item_price }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th colspan="6" style="text-align: right">Total Amount </th>
                                <th>{{ $booking_parcel->total_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Vat Amount ({{ $booking_parcel->vat_percent }} %)</th>
                                <th>{{ $booking_parcel->vat_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Discount Total </th>
                                <th>{{ $booking_parcel->discount_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Grand Total</th>
                                <th> {{ $booking_parcel->grand_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Net Amount </th>
                                <th>{{ $booking_parcel->net_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Paid Amount </th>
                                <th>{{ $booking_parcel->paid_amount }}</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: right">Due Amount </th>
                                <th>{{ $booking_parcel->due_amount }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <strong style="text-decoration: overline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Booking Officer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                    </div>

                    <div class="col-md-6">

                    </div>
                </div>
            </div>

        </div>
    </body>
</html>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>
