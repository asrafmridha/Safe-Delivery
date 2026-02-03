<div class="row">
    <table class="table table-sm">
        @if(!empty($branch->image))
        <tr>
            <td colspan="2" class="text-center">
                <img src="{{ asset('uploads/branch/'.$branch->image) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="branch User">
            </td>
        </tr>
        @endif
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $branch->status == 1 ? "success":"danger" }}">{{ $branch->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">Name</th>
          <td width="70%">
            {{ $branch->name }}
          </td>
        </tr>
        <tr>
          <th>Full Address</th>
          <td>
            {{ $branch->address }}
          </td>
        </tr>
        <tr>
          <th>District</th>
          <td>
            {{ $branch->district->name }}
          </td>
        </tr>
        {{-- <tr>
          <th>Thana/Upazila</th>
          <td>
            {{ $branch->upazila->name }}
          </td>
        </tr> --}}
        <tr>
          <th>Area</th>
          <td>
            {{ $branch->area->name }}
          </td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td>
            {{ $branch->contact_number }}
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>
            {{ $branch->email }}
          </td>
        </tr>


        @if(!empty($branch->branch_users->count() > 0))
        <tr>
            <th>Branch User List</th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="30%">Name</th>
                      <th width="30%">Address</td>
                      <th width="30%">Contact Number</td>
                    </tr>
                    @foreach ($branch->branch_users as $branch_user)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $branch_user->name }} </th>
                      <td >{{ $branch_user->address }} </td>
                      <td >{{ $branch_user->contact_number }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif
        @if(!empty($branch->merchants->count() > 0))
        <tr>
            <th>Merchant List</th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="30%">Name</th>
                      <th width="30%">Address</td>
                      <th width="30%">Contact Number</td>
                    </tr>
                    @foreach ($branch->merchants as $merchant)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $merchant->name }} </th>
                      <td >{{ $merchant->address }} </td>
                      <td >{{ $merchant->contact_number }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif
        @if(!empty($branch->riders->count() > 0))
        <tr>
            <th>Rider List</th>
            <td>
                <table class="table table-sm">
                    <tr>
                      <th width="10%">#</th>
                      <th width="30%">Name</th>
                      <th width="30%">Address</td>
                      <th width="30%">Contact Number</td>
                    </tr>
                    @foreach ($branch->riders as $rider)
                    <tr>
                      <td >{{ $loop->iteration }} </th>
                      <td >{{ $rider->name }} </th>
                      <td >{{ $rider->address }} </td>
                      <td >{{ $rider->contact_number }} </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif
    </table>
  </div>
