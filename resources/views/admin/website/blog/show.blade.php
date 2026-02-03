  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $blog->status == 1 ? "success":"danger" }}">{{ $blog->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Title </th>
            <td width="70%">
              {{ $blog->title }}
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $blog->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $blog->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($blog->image))
                  <img src="{{ asset('uploads/blog/'.$blog->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="Blog Image">
              @endif
            </td>
        </tr>
        <tr>
            <th>Date</th>
            <td>
                {{ \Carbon\Carbon::parse($blog->date)->format('d-m-Y') }}
            </td>
        </tr>
    </table>
  </div>
