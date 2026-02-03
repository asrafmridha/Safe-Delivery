  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $feature->status == 1 ? "success":"danger" }}">{{ $feature->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Title </th>
            <td width="70%">
              {{ $feature->title }}
            </td>
        </tr>
        <tr>
            <th>Heading </th>
            <td>
              {{ $feature->heading }}
            </td>
        </tr>
        <tr>
            <th>Details </th>
            <td>
              {{ $feature->details }}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($feature->image))
                  <img src="{{ asset('uploads/feature/'.$feature->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Feature Image">
              @endif
            </td>
        </tr>
    </table>
  </div>
