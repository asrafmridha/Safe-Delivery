  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $deliveryService->status == 1 ? "success":"danger" }}">{{ $deliveryService->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $deliveryService->name }}
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $deliveryService->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $deliveryService->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($deliveryService->image))
                  <img src="{{ asset('uploads/deliveryService/'.$deliveryService->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="deliveryService Image">
              @endif
            </td>
        </tr>
        <tr>
            <th>Icon</th>
            <td>
                @if(!empty($deliveryService->icon))
                    <img src="{{ asset('uploads/deliveryService/'.$deliveryService->icon) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Service Icon">
                @endif
            </td>
        </tr>
    </table>
  </div>
