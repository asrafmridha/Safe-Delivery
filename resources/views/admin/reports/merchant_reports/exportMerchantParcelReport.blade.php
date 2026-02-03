{{--<div class="report-header" style="margin-top: 10px;">--}}
    {{--<h3 class="text-center">Merchant Parcel Report </h3>--}}
    {{--<h5 class="text-center">From <b>{{ $from_date }}</b> to <b>{{ $to_date }}</b></h5>--}}
{{--</div>--}}
<table id="merchantWiseReport" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th style="text-align:center; font-weight: bold;"> SL</th>
        <th style="text-align:center; font-weight: bold;"> Merchant ID</th>
        <th style="text-align:center; font-weight: bold;"> Merchant Company </th>
        @php
            if(count($date_array) > 0) {

                foreach ($date_array as $k=>$v) {
                    echo '<th style="text-align:center; font-weight: bold;">'.$v.'</th>';
                }
            }
        @endphp
        <th style="text-align:center; font-weight: bold;"> Total</th>
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
        <td style="text-align:center;">{{ $i }}</td>
        <td style="text-align:center;">{{ $merchant->m_id }}</td>
        <td style="text-align:left;">{{ $merchant->company_name }}</td>
        <?php
        $total_parcel_count = 0;
        if(count($date_array) > 0) {

            foreach ($date_array as $k=>$v) {
                $parcel_count = 0;
                if(array_key_exists($merchant->id.'_'.$k, $final_array)) {
                    $parcel_count = $final_array[$merchant->id.'_'.$k];
                }
                $total_parcel_count += $parcel_count;
                echo '<td style="text-align:center;">'.$parcel_count.'</td>';

                $full_date_array[$k] += $parcel_count;
            }

        }

        $total_parcel += $total_parcel_count;
        ?>
        <td style="text-align:center; font-weight: bold;">{{ $total_parcel_count }}</td>
    </tr>
    <?php
    }

    echo '<tr>
            <!--<td colspan="'.(count($date_array) + 3).'" class="text-center"><h5><b>Total Parcel</b></h5></td>-->
            <td colspan="3" style="text-align:center;"><h5><b>Total Parcel</b></h5></td>';
    foreach ($date_array as $k=>$v) {
        $value = $full_date_array[$k];
        echo  '<td style="text-align:center;"><h6><b>'.$value.'</b></h6></td>';

    }
    echo    '<td style="text-align:center;"><h5><b>'.$total_parcel.'</b></h5></td>
                                              </tr>';
    }

    ?>
    </tbody>

</table>

