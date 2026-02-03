@if($parcels->count() > 0)
    @foreach($parcels as $parcel)
        @php
            $returnCharge = 0;
            if($parcel->delivery_type == 4 || $parcel->delivery_type == 2){
                $returnCharge = $parcel->merchant_service_area_return_charge;
            }
            $change         = $parcel->customer_collect_amount - $parcel->weight_package_charge - $parcel->delivery_charge ;
            $payable_amount = $change - $parcel->cod_charge - $returnCharge;
        @endphp
        <tr style="background-color: #f4f4f4;">
            <td class="text-center" >
                <input type="checkbox" id="checkItem"  class="parcelId"
                value="{{ $parcel->id }}" >
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
                {{ $parcel->customer_name }} <br>
                {{ $parcel->customer_contact_number }}
            </td>
            <td class="text-right" >
                {{ number_format($parcel->customer_collect_amount,2) }}
            </td>
            <td class="text-right" >
                {{ number_format($parcel->weight_package_charge,2) }}  <br>
                ({{ $parcel->weight_package->name }})
            </td>
            <td class="text-right" >
                {{ number_format($parcel->delivery_charge,2) }}  <br>
            </td>
            <td class="text-right" >
                {{-- {{ number_format($parcel->cod_charge,2) }} --}}

                <input type="number" id="cod_charge{{ $parcel->id }}"
                value="{{ floatval($parcel->cod_charge) }}"
                parcel_id="{{ $parcel->id }}"
                class="form-control text-center cod_charge" step="any" />

            </td>
            <td class="text-right" >
                <input type="number" id="return_charge{{ $parcel->id }}"
                value="{{ $returnCharge }}"
                parcel_id="{{ $parcel->id }}"
                class="form-control text-center return_charge" step="any" />

                <input type="hidden" id="total_charge_amount{{ $parcel->id }}"
                value="{{ $change }}" />

            </td>
            <td class="text-right " id="view_total_charge_amount{{ $parcel->id }}">
                {{ number_format($payable_amount,2) }}
            </td>
        </tr>
    @endforeach
@else
    <script>
        toastr.error("Parcel not Found");
    </script>
@endif
