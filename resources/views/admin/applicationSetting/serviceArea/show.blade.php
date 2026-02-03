  <div class="row">
    <table class="table table-sm">
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $serviceArea->status == 1 ? "success":"danger" }}">{{ $serviceArea->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
            <th width="30%">Name </th>
            <td width="70%">
              {{ $serviceArea->name }}
            </td>
        </tr>
        <tr>
            <th>COD % </th>
            <td>
              {{ number_format($serviceArea->cod_charge,2) }}
            </td>
        </tr>
        <tr>
            <th>Default Charge  </th>
            <td>
              {{ number_format($serviceArea->default_charge,2) }}
            </td>
        </tr>
        <tr>
            <th>Delivery Time  </th>
            <td>
              {{ $serviceArea->delivery_time.' hrs delivery' }}
            </td>
        </tr>
        <tr>
            <th>Weight Type </th>
            <td>
                @php
                    if($serviceArea->weight_type == 1){
                        echo "KG";
                    }
                    else{
                        echo "CFT";
                    }
                @endphp
            </td>
        </tr>
        <tr>
            <th>Details </th>
            <td>
              {{ $serviceArea->details }}
            </td>
        </tr>
    </table>
  </div>
