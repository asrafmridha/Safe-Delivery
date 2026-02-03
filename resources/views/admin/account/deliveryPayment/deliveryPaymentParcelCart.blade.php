@if(!empty($cart) )
        <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
            <thead>
                <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                    <th width="10%" class="text-center">
                        <i class="fa fa-trash " style="color:black"></i>
                    </th>
                    <th width="25%" class="text-center">Invoice </th>
                    <th width="20%" class="text-center">Merchant Name</th>
                    <th width="20%" class="text-center">Customer Name</th>
                    <th width="25%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                    <tr style="background-color: #f4f4f4;">
                        <td class="text-center" >
                            <span style="cursor: pointer;" onclick="return delete_parcel({{ $item->id }})">
                                <i class="fa fa-trash text-danger" style="color:black"></i>
                            </span>
                        </td>
                        <td class="text-center" >
                            {{ $item->attributes->parcel_invoice }}
                        </td>
                        <td class="text-center" >
                            {{ $item->attributes->merchant_name }}
                        </td>
                        <td class="text-center" >
                            {{ $item->attributes->customer_name }}
                        </td>
                        <td class="text-right" >
                            {{ number_format($item->price,2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    <input type="hidden"  id="cart_total_item" value="{{ $totalItem }}">
    <input type="hidden"  id="cart_total_amount" value="{{ $getTotal }}">
@endif
@if(isset($error) && !empty($error))
<script>
    toastr.error("{{ $error }}");
</script>
@endif
