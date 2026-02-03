@if(!empty($cart) )
        <table class="table table-bordered table-stripede"  style="background-color:white;width: 100%">
            <thead>
                <tr  style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                    <th width="10%" class="text-center">
                        <i class="fa fa-trash " style="color:black"></i>
                    </th>
                    <th width="25%" class="text-center">C/N No </th>
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
                            {{ $item->name }}
                        </td>
                        <td class="text-right" >
                            {{ number_format((float) $item->price, 2, '.', '') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    <input type="hidden"  id="cart_total_item" value="{{ $totalItem }}">
    <input type="hidden"  id="cart_total_amount" value="{{ number_format((float) $getTotal, 2, '.', '') }}">
@endif
@if(isset($error) && !empty($error))
<script>
    toastr.error("{{ $error }}");
</script>
@endif
