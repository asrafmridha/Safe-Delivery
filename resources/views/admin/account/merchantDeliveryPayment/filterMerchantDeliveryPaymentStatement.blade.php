<table id="merchantPaymentStatement" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="10%" class="text-center"> Date</th>
        <th width="10%" class="text-center"> Transaction ID </th>
        <th width="10%" class="text-center"> Parcel Invoice </th>
        <th width="10%" class="text-center"> Company Name </th>
        <th width="10%" class="text-center"> Merchant Name </th>
        <th width="10%" class="text-center"> Payment Amount</th>
        <th width="10%" class="text-center"> Delivery Charge</th>
        <th width="10%" class="text-center"> Payable Amount</th>
        <th width="10%" class="text-center"> Status</th>
    </tr>
    </thead>

    <tbody>
    @php
        $total_payment_amount = 0;
        $total_charge_amount  = 0;
        $total_paid_amount  = 0;
        $total_pending_amount  = 0;
        $total_cancel_amount  = 0;
        $total_receive_amount  = 0;

        $old_payment_date = "";
        $old_transaction_id = "";
        if(count($merchant_payment_data) > 0) {
            $i = 0;
            foreach ($merchant_payment_data as $merchant_payment) {
                $i++;
                $payment_id = $merchant_payment->parcel_merchant_delivery_payment_id;

                $payment_date = date("Y-m-d", strtotime($merchant_payment->created_at));
                $payment_date_column = "";
                if($payment_date != $old_payment_date) {
                    $dateRowCount   = count($date_array[$payment_date]);
                    $payment_date_column = '<td rowspan="'.$dateRowCount.'" class="text-center align-middle">'.$payment_date.'</td>';
                }

                $transaction_id = $merchant_payment->parcel_merchant_delivery_payment->merchant_payment_invoice;
                $transaction_id_column  = "";
                if($transaction_id != $old_transaction_id) {
                    $tranRowCount   = count($transaction_ids[$transaction_id]);
                    $transaction_id_column  = '<td rowspan="'.$tranRowCount.'" class="text-center align-middle">'.$transaction_id.'</td>';
                }

                if($merchant_payment->status == 1) {
                    $status = "Request Send <br> (".$merchant_payment->parcel_merchant_delivery_payment->created_at.")";
                    $total_pending_amount += $merchant_payment->paid_amount;
                }elseif($merchant_payment->status == 2) {
                    $status = "Received <br> (".$merchant_payment->parcel_merchant_delivery_payment->date_time.")";
                    $total_receive_amount += $merchant_payment->paid_amount;
                }else {
                    $status = "Canceled <br> (".$merchant_payment->parcel_merchant_delivery_payment->date_time.")";
                    $total_cancel_amount += $merchant_payment->paid_amount;
                }

                $total_payment_amount += $merchant_payment->collected_amount;
                $total_charge_amount  += ($merchant_payment->collected_amount - $merchant_payment->paid_amount);
                //$total_charge_amount  += ($merchant_payment->cod_charge + $merchant_payment->delivery_charge + $merchant_payment->weight_package_charge + $merchant_payment->return_charge);
                $total_paid_amount    += $merchant_payment->paid_amount;
                echo '<tr>
                        '.$payment_date_column.'
                        '.$transaction_id_column.'
                        <td class="text-center">'.$merchant_payment->parcel->parcel_invoice.'</td>
                        <td class="text-center">'.$merchant_payment->parcel->merchant->company_name.'</td>
                        <td class="text-center">'.$merchant_payment->parcel->merchant->name.'</td>
                        <td class="text-center">'.$merchant_payment->collected_amount.'</td>
                        <td class="text-center">'.($merchant_payment->collected_amount - $merchant_payment->paid_amount).'</td>
                        <td class="text-center">'.$merchant_payment->paid_amount.'</td>
                        <td class="text-center">'.$status.'</td>
                      </tr>';


                $old_payment_date = $payment_date;
                $old_transaction_id = $transaction_id;
            }

            echo '<tr>
                    <th colspan="5" style="text-align: right">Total Amount: </th>
                    <th class="text-center">'.$total_payment_amount.' TK</th>
                    <th class="text-center">'.$total_charge_amount.' TK</th>
                    <th class="text-center">'.$total_paid_amount.' TK</th>
                    <th class="text-center"></th>
                  </tr>';
        }else {
            echo '<tr>
                    <td colspan="9" class="text-center">No data available here!<td>
                </tr>
                <script>
                    toastr.error("Accounts Merchant Payment Data not Found");
                </script>';
        }


    @endphp
    </tbody>

</table>
<h3>Merchant Receive Amount: <?php echo $total_receive_amount; ?> TK</h3>
<h3>Merchant Pending Amount: <?php echo $total_pending_amount; ?> TK</h3>
<h3>Merchant Cancel Amount: <?php echo $total_cancel_amount; ?> TK</h3>