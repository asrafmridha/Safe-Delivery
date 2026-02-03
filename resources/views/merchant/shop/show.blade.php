  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $shop->status == 1 ? "success":"danger" }}">{{ $shop->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Shop Name </th>
          <td width="70%">
            {{ $shop->shop_name }}
          </td>
        </tr>
        <tr>
          <th>Address</th>
          <td>
            {{ $shop->shop_address }}
          </td>
        </tr>

    </table>
  </div>