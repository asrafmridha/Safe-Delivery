  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $weightPackage->status == 1 ? "success":"danger" }}">{{ $weightPackage->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th >ID </th>
            <td >
              {{ $weightPackage->wp_id }}
            </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $weightPackage->name }}
            </td>
        </tr>
        <tr>
            <th >Title </th>
            <td >
              {{ $weightPackage->title }}
            </td>
        </tr>
        <tr>
            <th>Weight Type </th>
            <td>
              @php
                if($weightPackage->weight_type  == 1){
                    $weight_type =  "KG";
                }
                else{
                    $weight_type =  "CFT";
                }
                echo $weight_type;
              @endphp
            </td>
        </tr>
        <tr>
            <th>Details </th>
            <td>
              {{ $weightPackage->details }}
            </td>
        </tr>
        <tr>
            <th>Rate </th>
            <td>
              {{ number_format($weightPackage->rate,2) }}
            </td>
        </tr>
    </table>
  </div>
