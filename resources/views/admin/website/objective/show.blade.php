  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $objective->status == 1 ? "success":"danger" }}">{{ $objective->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $objective->name }}
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $objective->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $objective->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($objective->image))
                  <img src="{{ asset('uploads/objective/'.$objective->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Service Image">
              @endif
            </td>
        </tr>
        <tr>
            <th>Icon</th>
            <td>
              @if(!empty($objective->icon))
                  <img src="{{ asset('uploads/objective/'.$objective->icon) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Service Icon">
              @endif
            </td>
        </tr>

    </table>
  </div>
