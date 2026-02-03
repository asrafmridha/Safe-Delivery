  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $slider->status == 1 ? "success":"danger" }}">{{ $slider->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($slider->image))
                  <img src="{{ asset('uploads/slider/'.$slider->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Slider User">
              @endif
            </td>
        </tr>
        <tr>
          <th width="30%">Title </th>
          <td width="70%">
            {{ $slider->title }}
          </td>
        </tr>
        <tr>
          <th>Details</th>
          <td>
            {{ $slider->details }}
          </td>
        </tr>


    </table>
  </div>
