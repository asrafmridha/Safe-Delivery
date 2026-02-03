<div class="report-header" style="margin-top: 10px;">
    <h3 class="text-center">Merchant Parcel Report </h3>
    <h5 class="text-center">From <b>{{ $from_date }}</b> to <b>{{ $to_date }}</b></h5>
</div>
<table id="merchantWiseReport" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="5%" class="text-center"> SL</th>
        <th width="5%" class="text-center"> Merchant ID</th>
        <th width="15%" class="text-center"> Merchant Company </th>
        @php
            if(count($date_array) > 0) {

                foreach ($date_array as $k=>$v) {
                    echo '<th class="text-center">'.$v.'</th>';
                }
            }
        @endphp
        <th width="5%" class="text-center"> Total</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $total_parcel = 0;
    if(count($merchants) > 0) {
    $i = 0;
    foreach ($merchants as $merchant) {
    $i++;
    ?>
    <tr>
        <td class="text-center">{{ $i }}</td>
        <td class="text-center">{{ $merchant->m_id }}</td>
        <td class="text-left">{{ $merchant->company_name }}</td>
        <?php
        $total_parcel_count = 0;
        if(count($date_array) > 0) {

            foreach ($date_array as $k=>$v) {
                $parcel_count = 0;
                if(array_key_exists($merchant->id.'_'.$k, $final_array)) {
                    $parcel_count = $final_array[$merchant->id.'_'.$k];
                }
                $total_parcel_count += $parcel_count;
                echo '<td class="text-center">'.$parcel_count.'</td>';

                $full_date_array[$k] += $parcel_count;
            }

        }

        $total_parcel += $total_parcel_count;
        ?>
        <td class="text-center text-bold">{{ $total_parcel_count }}</td>
    </tr>
    <?php
    }

    echo '<tr>
            <!--<td colspan="'.(count($date_array) + 3).'" class="text-center"><h5><b>Total Parcel</b></h5></td>-->
            <td colspan="3" class="text-center"><h5><b>Total Parcel</b></h5></td>';
    foreach ($date_array as $k=>$v) {
        $value = $full_date_array[$k];
        echo  '<td class="text-center"><h6><b>'.$value.'</b></h6></td>';

    }
    echo    '<td class="text-center"><h5><b>'.$total_parcel.'</b></h5></td>
                                              </tr>';
    }

    ?>
    </tbody>

</table>

