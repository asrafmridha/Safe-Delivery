  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $frequentlyAskQuestion->status == 1 ? "success":"danger" }}">{{ $frequentlyAskQuestion->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Question </th>
            <td width="70%">
              {{ $frequentlyAskQuestion->question }}
            </td>
        </tr>
        <tr>
            <th width="30%">Designation </th>
            <td width="70%">
              {{ $frequentlyAskQuestion->answer }}
            </td>
        </tr>
    </table>
  </div>
