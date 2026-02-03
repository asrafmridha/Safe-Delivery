  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $pageContent->status == 1 ? "success":"danger" }}">{{ $pageContent->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Page Type </th>
            <td width="70%">
                @switch($pageContent->page_type)
                    @case(1)
                        About Page
                        @break
                    @case(2)
                        Service Page
                        @break
                    @case(3)
                        Merchant Registration Page
                        @break
                    @default
                        Other Page
                        @break
                @endswitch
            </td>
        </tr>
        <tr>
            <th>Short Details </th>
            <td>
              {{ $pageContent->short_details }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
              {!! $pageContent->long_details !!}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($pageContent->image))
                  <img src="{{ asset('uploads/pageContent/'.$pageContent->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="pageContent Image">
              @endif
            </td>
        </tr>
    </table>
  </div>
