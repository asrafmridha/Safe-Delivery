<div class="row">
    <table class="table table-sm">
        @if(!empty($branchUser->image))
        <tr>
            <td colspan="2" class="text-center">
                <img src="{{ asset('uploads/branchUser/'.$branchUser->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="branch User">
            </td>
        </tr>
        @endif

        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $branchUser->status == 1 ? "success":"danger" }}">{{ $branchUser->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Name</th>
          <td width="70%">
            {{ $branchUser->name }}
          </td>
        </tr>
        <tr>
          <th>Branch</th>
          <td>
            {{ $branchUser->branch->name }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $branchUser->address }}
          </td>
        </tr>
        <tr>
          <th>District</th>
          <td>
            {{ $branchUser->branch->district->name }}
          </td>
        </tr>
        <tr>
          <th>Thana/Upazila</th>
          <td>
            {{ $branchUser->branch->upazila->name }}
          </td>
        </tr>
        <tr>
          <th>Area</th>
          <td>
            {{ $branchUser->branch->area->name }}
          </td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td>
            {{ $branchUser->contact_number }}
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $branchUser->email }}
          </td>
        </tr>
<!--        <tr>
          <th>Password</th>
          <td>
            {{ $branchUser->store_password }}
          </td>
        </tr>
-->
    </table>
  </div>
