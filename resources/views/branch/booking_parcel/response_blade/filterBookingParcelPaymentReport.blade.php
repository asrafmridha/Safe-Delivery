<?php
    if(count($payment_details) > 0) {
        for($i=0; $i<count($payment_details); $i++) {
            echo html_entity_decode($payment_details[$i]);
        }
    }else{
        echo '<tr>
                <td colspan="8" style="text-align:center"> No data available here!</td>
            </tr>';

?>
        <script>
            toastr.error("Parcel Payment not Found");
        </script>
<?php

}
?>

<tr>
    <td colspan="4" class="text-center text-bold">Total </td>
    <td class="text-center text-bold">{{ number_format((float) $total_parcel_amount, 2, '.', '') }}</td>
    <td class="text-center text-bold">{{ number_format((float) $total_branch_amount, 2, '.', '') }}</td>
    <td class="text-center text-bold">{{ number_format((float) $total_forward_amount, 2, '.', '') }}</td>
    <td class="text-center text-bold">{{ number_format((float) $total_receive_amount, 2, '.', '') }}</td>
</tr>


