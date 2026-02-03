@php
    $application    = \App\Models\Application::first();
@endphp

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
    <h4 class="modal-title">Merchant Delivery Payment List </h4>
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
                        <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="height:60px; width: 100px;">
                    </div>
                    <div class="col-md-8" >
                        <strong style="color: red; font-size: 18px;letter-spacing:1px;">{{ session()->get('company_name') ?? config('app.name', 'Courier') }}</strong><br/>
                        <strong>Address : </strong> &nbsp; {{ $application->address }} <br/>
                        <strong style="color:red;letter-spacing:1px;">Phone: {{ $application->contact_number }}, www.paddlecourier.com</strong>
                    </div>
                    <div class="col-md-2">Merchant Delivery Payment List</div>
                    <div class="col-md-12" style="border-bottom:2px red solid;"></div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style table-bordered" style="margin-top: 10px; text-align:center;">
                            <tr>
                                <th>SL. No.</th>
                                <th>Consignment </th>
                                <th>Merchant Company </th>
                                <th>Payment Parcel </th>
                                <th>Received Payment Parcel </th>
                                <th>Payment Amount </th>
                                <th>Received Payment Amount </th>
                                <th>Status</th>
                                <th width="10%">Signature</th>
                            </tr>
                            @if (count($merchantDeliveryPaymentList) > 0)
                                @foreach ($merchantDeliveryPaymentList as $mPaymentList)

                                    <?php                                    switch ($mPaymentList->status) {
                                        case 1:$status_name  = "Payment Request"; $class  = "success";break;
                                        case 2:$status_name  = "Payment Accept"; $class  = "success";break;
                                        case 3:$status_name  = "Payment Reject"; $class  = "danger";break;
                                        default:$status_name = "None"; $class = "success";break;
                                    }
//                                    return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
                                        $status = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px; text-decoration: none;"> ' . $status_name . '</a>';
                                    ?>
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mPaymentList->merchant_payment_invoice }}</td>
                                        <td>{{ $mPaymentList->merchant->company_name }}</td>
                                        <td>{{ $mPaymentList->total_payment_parcel }}</td>
                                        <td>{{ $mPaymentList->total_payment_received_parcel }}</td>
                                        <td>{{ number_format((float) $mPaymentList->total_payment_amount, 2, '.', '') }}</td>
                                        <td>{{ number_format((float) $mPaymentList->total_payment_received_amount, 2, '.', '') }}</td>
                                        <td><?php echo $status; ?></td>
                                        <td></td>
                                    </tr>
                                @endforeach

                            @else
                                <tr>
                                    <td colspan="9" class="text-center"> No Data Available Here!</td>
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
