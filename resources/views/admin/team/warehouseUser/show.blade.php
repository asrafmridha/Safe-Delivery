<div class="row">
    <table class="table table-sm">
        @if(!empty($warehouseUser->image))
        <tr>
            <td colspan="2" class="text-center">
                <img src="{{ asset('uploads/warehouseUser/'.$warehouseUser->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="branch User">
            </td>
        </tr>
        @endif

        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $warehouseUser->status == 1 ? "success":"danger" }}">{{ $warehouseUser->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Name</th>
          <td width="70%">
            {{ $warehouseUser->name }}
          </td>
        </tr>
        <tr>
          <th>Warehouse</th>
          <td>
            {{ $warehouseUser->warehouse->name }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $warehouseUser->address }}
          </td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td>
            {{ $warehouseUser->contact_number }}
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $warehouseUser->email }}
          </td>
        </tr>
        <tr>
          <th>Password</th>
          <td>
            {{ $warehouseUser->store_password }}
          </td>
        </tr>

    </table>
  </div>
