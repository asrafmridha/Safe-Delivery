  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $teamMember->status == 1 ? "success":"danger" }}">{{ $teamMember->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $teamMember->name }}
            </td>
        </tr>
        <tr>
            <th width="30%">Designation </th>
            <td width="70%">
              {{ $teamMember->designation->name }}
            </td>
        </tr>
        <tr>
            <th width="30%">Message </th>
            <td width="70%">
              {!! $teamMember->message !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($teamMember->image))
                  <img src="{{ asset('uploads/teamMember/'.$teamMember->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="teamMember User">
              @endif
            </td>
        </tr>

    </table>
  </div>
