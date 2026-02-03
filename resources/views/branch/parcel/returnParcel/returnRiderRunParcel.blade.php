@if((!empty($parcels) && $parcels->count() > 0))
    @foreach($parcels as $parcel)
        @php
            $logs_note = "";
            if($parcel->parcel_logs){
                foreach ($parcel->parcel_logs as $parcel_log) {
                    if("" != $logs_note) {
                        $logs_note .= ",<br>";
                    }
                    $logs_note .= $parcel_log->note;
                }
            }
        @endphp
        <tr style="background-color: #f4f4f4;">
            <td class="text-center" >
                <input type="checkbox" id="checkItem"  class="parcelId"  value="{{ $parcel->id }}" >
            </td>
            <td class="text-center" >
                {{ $parcel->parcel_invoice }}
            </td>
            <td class="text-center" >
                {{ $parcel->merchant_order_id }}
            </td>
            <td class="text-center" >
                {{ $parcel->merchant->company_name }}
            </td>
            <td class="text-center" >
                {{ $parcel->merchant->contact_number }}
            </td>
            <td class="text-center" >
                {{ $logs_note }}
            </td>
            <td class="text-center" >
                {{ $parcel->customer_name }}
            </td>
             <td class="text-center" >
                {{ $parcel->customer_collect_amount }}
            </td>
            <td class="text-center" >
                {{ $parcel->customer_contact_number }}
            </td>
        </tr>
    @endforeach
@else
    <script>
        toastr.error("Parcel not Found");
    </script>
@endif


