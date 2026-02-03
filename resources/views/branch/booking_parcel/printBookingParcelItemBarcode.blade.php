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
                <div class="row">
                    <div class="col-md-4 offset-md-4" style="text-align: center;">
                        <br>
                        <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($booking_parcel->parcel_code, 'C39', 1.5, 33) }}" alt="barcode" style="height:40px; width:200px;"/>
                        <br>
                        {{ $booking_parcel->sender_branch->name }} To  {{ $booking_parcel->receiver_branch->name }}
                    </div>
                </div>

                <div class="row" style="margin: 20px 0px 20px 0px;text-align: center;">
                    @foreach ($booking_parcel->booking_items as $booking_item)
                    <div class="col-md-12 text-center" style="margin-bottom: 30px;">
                        <strong>Category : </strong> {{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}
                        <strong>Description : </strong> {{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            @php
                                $id = $booking_parcel->parcel_code.'-'.$booking_item->id;
                                $number = $number_of_barcode[$booking_item->id];
                                for($i=0; $i<$number; $i++){
                                    @endphp
                                    <div class="col-md-2" style="margin: 0px 0px 30px 0px;">
                                        <img src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($booking_item->id, 'C39', 1.5, 33) }}" alt="barcode" style="width:80%"/>
                                        <br>
                                        {{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}
                                     </div>
                                    @php
                                }
                            @endphp
                        </div>
                    </div>
                    @endforeach
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
