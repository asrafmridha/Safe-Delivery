@php
    if(count($staff_payments) > 0) {

        $total_salary_amount = 0;
        $tota_paid_amount = 0;
        $old_month = "";

        $counter = 1;
        $column = "";
        $k = 0;
        foreach ($staff_payments as $spayment) {

            $total_salary_amount += $spayment->salary_amount;
            $tota_paid_amount   += $spayment->paid_amount;

            $new_month = $spayment->payment_month;

            if($new_month != $old_month) {

                $counter = count($final_array[$new_month]);
                $column = '<td rowspan="'.$counter.'" style="vertical-align: middle;">'.date("M Y", strtotime($spayment->payment_month)).'</td>';
            }else{
                $column = '';
                //$column = '<td>'.date("M Y", strtotime($spayment->payment_month)).'</td>';
            }

            echo '<tr>
                    '.$column.'
                    <td>'.$spayment->name.'</td>
                    <td>'.$spayment->designation.'</td>
                    <td>'.$spayment->phone.'</td>
                    <td>'.$spayment->branch_name.'</td>
                    <td class="text-right">'.$spayment->salary_amount.'</td>
                    <td class="text-right">'.$spayment->paid_amount.'</td>
                </tr>';

            $old_month = $new_month;
        }

        echo '<tr>
                    <th colspan="5" class="text-right"> Total Amount: </th>
                    <th class="text-right">'.number_format($total_salary_amount, 2, '.', '').'</th>
                    <th class="text-right">'.number_format($tota_paid_amount, 2, '.', '').'</th>
                </tr>';
    }else{
        echo '<tr>
                <td colspan="7" class="text-center"> No Data Available Here!</td>
            </tr>';
    }
@endphp

