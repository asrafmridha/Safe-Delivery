<div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $rider->status == 1 ? "success":"danger" }}">{{ $rider->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Name</th>
          <td width="70%">
            {{ $rider->name }}
          </td>
        </tr>
        <tr>
          <th>Rider ID</th>
          <td>
            {{ $rider->r_id }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $rider->address }}
          </td>
        </tr>
        <tr>
          <th>District</th>
          <td>
            {{ $rider->district->name }}
          </td>
        </tr>
        {{-- <tr>
          <th>Thana/Upazila</th>
          <td>
            {{ $rider->upazila->name }}
          </td>
        </tr> --}}
        <tr>
          <th>Area</th>
          <td>
            {{ $rider->area->name }}
          </td>
        </tr>
        <tr>
          <th>Branch</th>
          <td>
            {{ $rider->branch->name }}
          </td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td>
            {{ $rider->contact_number }}
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $rider->email }}
          </td>
        </tr>
        <!--<tr>-->
        <!--  <th>Password</th>-->
        <!--  <td>-->
        <!--    {{ $rider->store_password }}-->
        <!--  </td>-->
        <!--</tr>-->
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($rider->image))
                  <img src="{{ asset('uploads/rider/'.$rider->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="rider User">
              @endif
            </td>
        </tr>
    </table>
  </div>
