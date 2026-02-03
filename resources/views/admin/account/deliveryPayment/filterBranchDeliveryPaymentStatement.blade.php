<table id="deliveryPaymentStatement" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="10%" class="text-center"> Date</th>
        <th width="10%" class="text-center"> Branch</th>
        <th width="10%" class="text-center"> Transaction ID </th>
        <th width="10%" class="text-center"> Parcel Invoice </th>
        <th width="10%" class="text-center"> Company Name </th>
        <th width="10%" class="text-center"> Parcel Price</th>
        <th width="10%" class="text-center"> Delivery Charge</th>
        <th width="10%" class="text-center"> Total Amount</th>
        <th width="10%" class="text-center"> Status</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_receive_amount = 0;
        $total_pending_amount = 0;

        $total_parcel_amount = 0;
        $total_delivery_charge = 0;
        $total_payment_amount = 0;


        $old_delivery_date = '';
        $old_payment_invoice = '';

        if(count($parcel_payment_data) > 0) {
            $i = 0;
            foreach ($parcel_payment_data as $parcel_delivery) {
                $i++;

                $delivery_date = date("Y-m-d", strtotime($parcel_delivery->created_at));
                $rowCount = count($date_array[$delivery_date]);
                $delivery_date_column ="";
                if($delivery_date != $old_delivery_date) {
                    $delivery_date_column = '<td rowspan="'.$rowCount.'" class="text-center align-middle">'.$delivery_date.'</td>';
                }

                $payment_invoice = $parcel_delivery->parcel_delivery_payment->payment_invoice;
                $pInvRowCount   = count($pinvoice_array[$payment_invoice]);
                $payment_invoice_column ="";
                if($payment_invoice != $old_payment_invoice) {
                    $payment_invoice_column = '<td rowspan="'.$pInvRowCount.'" class="text-center align-middle">'.$payment_invoice.'</td>';
                }

                $payment_id = $parcel_delivery->parcel_delivery_payment_id;

                if($parcel_delivery->status == 1) {
                    $status = "Pending";
                    $total_pending_amount += $parcel_delivery->amount;
                }elseif($parcel_delivery->status == 2) {
                    $status = "Received <br> (".$parcel_delivery->parcel_delivery_payment->date_time.")";
                    $total_receive_amount += $parcel_delivery->amount;
                }else {
                    $status = "Rejected";
                    $total_pending_amount += $parcel_delivery->amount;
                }

                $total_parcel_amount += $parcel_delivery->parcel->total_collect_amount;
                $total_delivery_charge += $parcel_delivery->parcel->total_charge;
                $total_payment_amount += $parcel_delivery->amount;


                echo '<tr>
                        '.$delivery_date_column.'
                        <td class="text-center">'.$parcel_delivery->parcel_delivery_payment->branch->name.'</td>
                        '.$payment_invoice_column.'
                        <td class="text-center">'.$parcel_delivery->parcel->parcel_invoice.'</td>
                        <td class="text-center">'.$parcel_delivery->parcel->merchant->company_name.'</td>
                        <td class="text-center">'.number_format($parcel_delivery->parcel->total_collect_amount,2).'</td>
                        <td class="text-center">'.number_format($parcel_delivery->parcel->total_charge,2).'</td>
                        <td class="text-center">'.number_format($parcel_delivery->amount,2).'</td>
                        <td class="text-center">'.$status.'</td>
                      </tr>';


                $old_delivery_date = $delivery_date;
                $old_payment_invoice = $payment_invoice;
            }

            echo '<tr>
                    <th colspan="5" style="text-align: right">Total Amount: </th>
                    <th class="text-center">'.$total_parcel_amount.' TK</th>
                    <th class="text-center">'.$total_delivery_charge.' TK</th>
                    <th class="text-center">'.$total_payment_amount.' TK</th>
                    <th></th>

                  </tr>';
        }


    @endphp
    </tbody>
</table>
<h3>Total Branch Payment Receive Amount: <?php echo $total_receive_amount; ?> TK</h3>
<h3>Total Branch Payment Pending Amount: <?php echo $total_pending_amount; ?> TK</h3>
