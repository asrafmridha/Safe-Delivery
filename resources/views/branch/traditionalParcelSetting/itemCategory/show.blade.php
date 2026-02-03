  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $ItemCategory->status == 1 ? "success":"danger" }}">{{ $ItemCategory->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $ItemCategory->name }}
            </td>
        </tr>

        <tr>
            <th>Details </th>
            <td>
              {{ $ItemCategory->details }}
            </td>
        </tr>
    </table>
  </div>
