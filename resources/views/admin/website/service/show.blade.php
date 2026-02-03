  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $service->status == 1 ? "success":"danger" }}">{{ $service->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $service->name }}
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $service->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $service->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($service->image))
                  <img src="{{ asset('uploads/service/'.$service->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Service Image">
              @endif
            </td>
        </tr>
        <tr>
            <th>Icon</th>
            <td>
              @if(!empty($service->icon))
                  <img src="{{ asset('uploads/service/'.$service->icon) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Service Icon">
              @endif
            </td>
        </tr>

    </table>
  </div>
