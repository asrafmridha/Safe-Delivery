  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $aboutPoint->status == 1 ? "success":"danger" }}">{{ $aboutPoint->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Title </th>
            <td width="70%">
              {{ $aboutPoint->title }}
            </td>
        </tr>
        <tr>
            <th>Details </th>
            <td>
              {{ $aboutPoint->details }}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($aboutPoint->image))
                  <img src="{{ asset('uploads/aboutPoint/'.$aboutPoint->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="About Point Image">
              @endif
            </td>
        </tr>
    </table>
  </div>
