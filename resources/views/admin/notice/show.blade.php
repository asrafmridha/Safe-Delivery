<div class="row">
    <table class="table table-sm">
        
        <tr>
          <th >Status</th>
          <td >
            <span class="bg-{{ $notice->status == 1 ? "success":"danger" }}">{{ $notice->status == 1 ? "Active":"Inactive" }}</span>
          </td>
        </tr>
        <tr>
          <th width="30%">title</th>
          <td width="70%">
            {{ $notice->title }}
          </td>
        </tr>
        <tr>
          <th>Type</th>
          <td>
            <?php
                switch ($notice->type){
                    case 1:
                        $type = "Notice";
                        break;
                    case 2:
                        $type = "News";
                        break;
                    default:
                        $type = "N/A";
                        break;
                }
                echo $type;
            ?>
          </td>
        </tr>
        <tr>
          <th>Publish For</th>
          <td>
            <?php
                switch ($notice->publish_for){
                    case 1:
                        $type = "Branch";
                        break;
                    case 2:
                        $type = "Merchant";
                        break;
                    default:
                        $type = "All";
                        break;
                }
                echo $type;
            ?>
          </td>
        </tr>
        <tr>
          <th>Details</th>
          <td>
            {{ $notice->short_details }}
          </td>
        </tr>
        <tr>
          <th>Date</th>
          <td>
            {{ $notice->date_time }}
          </td>
        </tr>

    </table>
  </div>
