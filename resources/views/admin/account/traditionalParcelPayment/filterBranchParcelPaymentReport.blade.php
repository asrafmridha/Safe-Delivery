<?php
    if(count($parcel_payment_reports) > 0) {
        for($i=0; $i<count($parcel_payment_reports); $i++) {
            echo html_entity_decode($parcel_payment_reports[$i]);
        }
    }else{
        echo '<tr>
                <td colspan="8" class="text-center">No data available here!<td>
            </tr>';
?>
        <script>
            toastr.error("Branch Payment not Found");
        </script>
<?php

}
?>
<tr>
    <td colspan="6" class="text-center text-bold">Total</td>
    <td class="text-center text-bold">{{ number_format((float) $payment_total_amount, 2, '.', '') }}</td>
    <td class="text-center text-bold">{{ number_format((float) $payment_total_pending_amount, 2, '.', '') }}</td>
    <td class="text-center text-bold">{{ number_format((float) $payment_total_receive_amount, 2, '.', '') }}</td>
</tr>