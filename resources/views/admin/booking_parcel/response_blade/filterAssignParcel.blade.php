@if((!empty($parcels) && $parcels->count() > 0))
    @foreach($parcels as $parcel)
        <?php
            switch ($parcel->delivery_type) {
//                case 'hd':$delivery_type  = "Home Delivery"; $class="success"; break;
//                case 'thd':$delivery_type  = "Transit Home Delivery"; $class="info"; break;
//                case 'od':$delivery_type  = "Office Delivery"; $class="primary"; break;
//                case 'tod':$delivery_type  = "Transit Office Delivery"; $class="warning"; break;
                case 'hd':$delivery_type  = "HD"; $class="success"; break;
                case 'thd':$delivery_type  = "THD"; $class="info"; break;
                case 'od':$delivery_type  = "OD"; $class="primary"; break;
                case 'tod':$delivery_type  = "TOD"; $class="warning"; break;
                default:$delivery_type = "None"; $class = "danger";break;
            }
        ?>
        <tr style="background-color: #f4f4f4;">
            <td class="text-center" >
                <input type="checkbox" id="checkItem"  class="bookingId"  value="{{ $parcel->id }}" >
            </td>
            <td class="text-center" >
                {{ $parcel->parcel_code }}
            </td>
            <td class="text-center" >
                {{ $parcel->sender_phone }}
            </td>
            <td class="text-center" >
                {{ $parcel->receiver_branch->name }}
            </td>
            <td class="text-center" >
                {{ $parcel->net_amount }}
            </td>
            <td class="text-center" >
                {{ $delivery_type }}
            </td>
        </tr>
    @endforeach
@else
    <script>
        toastr.error("Parcel not Found");
    </script>
@endif


