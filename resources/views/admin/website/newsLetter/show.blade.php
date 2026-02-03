  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $visitorMessage->status == 1 ? "danger":"success" }}">{{ $visitorMessage->status == 1 ? "Unread":"Read" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $visitorMessage->name }}
            </td>
        </tr>
        <tr>
            <th >Email </th>
            <td >
              {{ $visitorMessage->email }}
            </td>
        </tr>
        <tr>
            <th >Subject </th>
            <td >
              {{ $visitorMessage->subject }}
            </td>
        </tr>
        <tr>
            <th >Message </th>
            <td >
              {{ $visitorMessage->message }}
            </td>
        </tr>


    </table>
  </div>
