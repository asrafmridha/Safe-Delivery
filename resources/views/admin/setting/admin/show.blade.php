  <div class="row"> 
    <table class="table table-sm"> 
        <tr> 
          <th >Status</th>
          <td >
            <span class="bg-{{ $admin->status == 1 ? "success":"danger" }}">{{ $admin->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr> 
        <tr> 
          <th width="30%">Name</th>
          <td width="70%">
            {{ $admin->name }}
          </td>
        </tr> 
        <tr> 
          <th>Phone</th>
          <td>
            {{ $admin->contact_number }}
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $admin->email }}
          </td>
        </tr>
        <tr> 
          <th>Type</th>
          <td>
            {{ ($admin->type == 1)? 'Admin':'General User' }}
          </td>
        </tr> 
        <tr> 
          <th>Photo</th>
          <td>
            @if(!empty($admin->photo))
                <img src="{{ asset('uploads/admin/'.$admin->photo) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="User">
            @endif
          </td>
        </tr> 
    </table>
  </div>
                     