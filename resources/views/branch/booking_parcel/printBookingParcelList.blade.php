<script type="text/javascript">
//    function printPage(divName) {
//        var printContents = document.getElementById(divName).innerHTML;
//        var originalContents = document.body.innerHTML;
//        document.body.innerHTML = printContents;
//        // document.body.style.marginTop="-45px";
//
//        var button = document.getElementById('print');
//       // button.style.display = 'none'; //or
//        //button.style.visibility = 'hidden';
//        window.print();
//        document.body.innerHTML = originalContents;
//        $(".close").trigger('click');
//    }

    function printPage()
    {
        var divToPrint=document.getElementById('printArea');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}"><link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}"><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();
//        newWin.addEventListener('afterprint', (event) => {
//                newWin.close();
//        });
        newWin.addEventListener('afterprint', function () {
            newWin.close();
        });
    }



    function cancelPage() {
        //alert($(".close").trigger('click'));

    }
</script>

<div class="modal-header bg-default">
    <h4 class="modal-title">Booking Parcel List View </h4>
    <button type="button" style="display: none;" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <span id="print" class="float-right">
        <button class="btn btn-sm btn-info" onclick="printPage()">Print</button>
        <button class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
    </span>
</div>



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
                    <div class="col-md-2">Booking Parcel List</div>
                    <div class="col-md-12" style="border-bottom:2px red solid;"></div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style table-bordered" style="margin-top: 10px; text-align:center;">
                            <tr>
                                <th>SL. No.</th>
                                <th>Date </th>
                                <th>C/N No </th>
                                <th>Booking Type </th>
                                <th>Sender Contact </th>
                                <th>Receiver Contact </th>
                                <th>Receiver Branch </th>
                                <th>Delivery Type</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                            @if (count($booking_parcels) > 0)
                                @foreach ($booking_parcels as $parcel)

                                    <?php
                                        switch ($parcel->booking_parcel_type) {
                                            case 'cash':$booking_type  = "Cash"; $class  = "success";break;
                                            case 'to_pay':$booking_type = "To Pay"; $class = "info";break;
                                            case 'condition':$booking_type  = "Condition"; $class  = "primary";break;
                                            case 'credit':$booking_type = "Credit"; $class = "warning";break;
                                            default:$booking_type    = "None"; $class    = "danger";break;
                                        }
                                        $booking_parcel_type = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $booking_type . '</a>';

                                        switch ($parcel->delivery_type) {
                                            case 'hd':$delivery_type  = "HD"; $class  = "success";break;
                                            case 'thd':$delivery_type = "THD"; $class = "info";break;
                                            case 'od':$delivery_type  = "OD"; $class  = "primary";break;
                                            case 'tod':$delivery_type = "TOD"; $class = "warning";break;
                                            default:$delivery_type    = "None"; $class    = "danger";break;
                                        }
                                        $delivery_type_text = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';

                                        $receiver_warehouse_name = ($parcel->receiver_warehouses) ? $parcel->receiver_warehouses->name : 'Warehouse';
                                        switch ($parcel->status) {
                                            case 0:$status_name = "Parcel Reject from operation"; $class = "danger";break;
                                            case 1:$status_name = "Confirmed Booking"; $class = "success";break;
                                            case 2:$status_name = "Vehicle Assigned"; $class = "success";break;
                                            case 3:$status_name = "Assign $receiver_warehouse_name"; $class = "success";break;
                                            case 4:$status_name = "Warehouse Received Parcel"; $class = "success";break;
                                            case 5:$status_name = "Assign $receiver_warehouse_name"; $class = "success";break;
                                            case 6:$status_name = "Wait for destination branch receive"; $class = "success";break;
                                            case 7:$status_name = "Destination branch received Parcel"; $class = "success";break;
                                            case 8:$status_name = "Parcel Complete Delivery"; $class = "success";break;
                                            case 9:$status_name = "Parcel Return Delivery"; $class = "success";break;
                                            default:$status_name = "None"; $class = "success";break;
                                        }
                                        $status = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
                                    ?>
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $parcel->booking_date }}</td>
                                        <td>{{ $parcel->parcel_code }}</td>
                                        <td><?php echo $booking_parcel_type; ?></td>
                                        <td>{{ $parcel->sender_phone }}</td>
                                        <td>{{ $parcel->receiver_phone }}</td>
                                        <td>{{ $parcel->receiver_branch->name }}</td>
                                        <td><?php echo $delivery_type_text; ?></td>
                                        <td><?php echo $status; ?></td>
                                        <td>{{ number_format((float) $parcel->net_amount, 2, '.', '') }}</td>
                                    </tr>
                                @endforeach

                            @else
                                <tr>
                                    <td colspan="10" class="text-center"> No Data Available Here!</td>
                                </tr>
                            @endif


                        </table>
                    </div>
                </div>

            </div>

        </div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>
