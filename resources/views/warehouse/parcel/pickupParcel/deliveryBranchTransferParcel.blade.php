@if((!empty($parcels) && $parcels->count() > 0))
    @foreach($parcels as $parcel)
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
                {{ $parcel->merchant->name }}
            </td>
            <td class="text-center" >
                {{ $parcel->customer_name }}
            </td>
            <td class="text-center" >
                {{ $parcel->customer_contact_number }}
            </td>
            <td class="text-center" >
                {{ $parcel->district->name }}
            </td>
            <td class="text-center" >
                {{ $parcel->area->name }}
            </td>
        </tr>
    @endforeach
@else
    <script>
        toastr.error("Parcel not Found");
    </script>
@endif


