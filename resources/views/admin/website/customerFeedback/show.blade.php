  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $customerFeedback->status == 1 ? "success":"danger" }}">{{ $customerFeedback->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Customer Name  </th>
            <td width="70%">
                {{ $customerFeedback->name }}
            </td>
        </tr>
        <tr>
            <th>Company</th>
            <td>
              {{ $customerFeedback->company }}
            </td>
        </tr>
        <tr>
            <th>Long Details </th>
            <td>
                {{ $customerFeedback->feedback }}
            </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($customerFeedback->image))
                  <img src="{{ asset('uploads/customerFeedback/'.$customerFeedback->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="customerFeedback Image">
              @endif
            </td>
        </tr>
    </table>
  </div>
