  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $parcelStep->status == 1 ? "success":"danger" }}">{{ $parcelStep->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Title </th>
            <td width="70%">
              {{ $parcelStep->title }}
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $parcelStep->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $parcelStep->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($parcelStep->image))
                  <img src="{{ asset('uploads/parcelStep/'.$parcelStep->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="parcelStep Image">
              @endif
            </td>
        </tr>
    </table>
  </div>
