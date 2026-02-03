@if(!empty($cart) )

    @foreach ($cart as $item)
        <div class="badge badge-info" style="font-size: 20px;">
            {{ $item->attributes->parcel_invoice }}
            <span style="cursor: pointer;" onclick="return delete_parcel({{ $item->id }})">
                <i class="fa fa-trash text-danger" style="color:black"></i>
            </span>
        </div>
    @endforeach

    <input type="hidden"  id="cart_total_item" value="{{ $totalItem }}">
@endif

@if(isset($error) && !empty($error))
<script>
    toastr.error("{{ $error }}");
</script>

@endif
