  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $partner->status == 1 ? "success":"danger" }}">{{ $partner->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $partner->name }}
            </td>
        </tr>
        <tr>
            <th width="30%">url </th>
            <td width="70%">
              {{ $partner->url }}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($partner->image))
                  <img src="{{ asset('uploads/partner/'.$partner->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Partner User">
              @endif
            </td>
        </tr>

    </table>
  </div>
