<div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $staff->status == 1 ? "success":"danger" }}">{{ $staff->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Name</th>
          <td width="70%">
            {{ $staff->name }}
          </td>
        </tr>
        <tr>
            <th>Contact Number</th>
            <td>
                {{ $staff->phone }}
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                {{ $staff->email }}
            </td>
        </tr>
        <tr>
          <th>Designation</th>
          <td>
            {{ $staff->designation }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $staff->address }}
          </td>
        </tr>
        <tr>
          <th>Branch</th>
          <td>
            {{ $staff->branch->name }}
          </td>
        </tr>
        <tr>
          <th>Salary</th>
          <td>
            {{ $staff->salary }}
          </td>
        </tr>
        <tr>
            <th>Image</th>
            <td>
              @if(!empty($staff->image))
                  <img src="{{ asset('uploads/staff/'.$staff->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="staff image">
              @endif
            </td>
        </tr>
    </table>
  </div>
