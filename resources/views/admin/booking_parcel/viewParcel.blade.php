<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <fieldset>
                        <legend>Parcel Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Invoice </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->parcel_code }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Booking Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($booking_parcel->booking_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Delivery Type </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    @php
                                        if($booking_parcel->delivery_type == 'hd'){
                                                echo 'Home Delivery';
                                        }elseif($booking_parcel->delivery_type == 'thd'){
                                            echo 'Transit Home Delivery';
                                        }elseif($booking_parcel->delivery_type == 'od'){
                                            echo 'Office Delivery';
                                        }elseif($booking_parcel->delivery_type == 'tod'){
                                            echo 'Transit Office Delivery';
                                        }
                                    @endphp
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Sender Branch </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->sender_branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Receiver Branch </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->receiver_branch->name }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <div class="col-md-6">
                    <fieldset>
                        <legend>Payment Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Total Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->total_amount }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Vat Amount({{ $booking_parcel->vat_percent }} %) </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->vat_amount }} </td>
                            </tr>
                            
                              <tr>
                                <th style="width: 40%">Discount Total </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->discount }} </td>
                            </tr>
                            
                            <tr>
                                <th style="width: 40%">Grand Total </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->grand_amount }} </td>
                            </tr>
                          
                            <tr>
                                <th style="width: 40%">Net Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->net_amount }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Paid Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->paid_amount }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Due Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $booking_parcel->due_amount }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>

                <div class="col-md-6">
                        <fieldset>
                            <legend>Sender Information </legend>
                            <table class="table table-style">

                                <tr>
                                    <th style="width: 40%">Sender Name </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Phone </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_phone }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender NID </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_nid }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Address </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_address }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Divisiuon </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_division->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender District </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_district->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Upazila/Thana </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_upazila->name }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Sender Area </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $booking_parcel->sender_area->name }} </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>

                        <div class="col-md-6">
                            <fieldset>
                                <legend>Receiver Information </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Receiver Name </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver Phone </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_phone }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_address }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver Divisiuon </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_division->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver District </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_district->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver Upazila/Thana </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_upazila->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Receiver Area </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $booking_parcel->receiver_area->name }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>



                        <div class="col-md-10 col-md-offset-1">
                            <fieldset>
                                <legend>Item Information </legend>
                                <table class="table table-style table-bordered">
                                    <tr>
                                        <th>SL. No.</th>
                                        <th>Item Category </th>
                                        <th>Item Name </th>
                                        <th>Unit </th>
                                        <th>Unit Rate </th>
                                        <th>Quantity </th>
                                        <th>Total Rate </th>
                                    </tr>
                                    @foreach ($booking_parcel->booking_items as $booking_item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_categories->name:'Others' }}</td>
                                            <td>{{ ($booking_item->item_id != 0)?$booking_item->item->item_name:$booking_item->item_name }}</td>
                                            <td>{{ ($booking_item->item_id != 0)?$booking_item->item->units->name:$booking_item->unit_name }}</td>
                                            <td>{{ $booking_item->unit_price }}</td>
                                            <td>{{ $booking_item->quantity }}</td>
                                            <td>{{ $booking_item->total_item_price }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </fieldset>
                        </div>

               {{--  @if(!empty($booking_parcelLogs->count() > 0))
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Parcel Log</legend>
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 5%"> # </th>
                                    <th style="width: 10%"> Date </th>
                                    <th style="width: 10%"> Time </th>
                                    <th style="width: 25%"> Status </th>
                                    <th style="width: 25%"> To (Action) </th>
                                    <th style="width: 25%"> From </th>
                                </tr>
                                @foreach ($booking_parcelLogs as $booking_parcelLog)
                                    @php
                                        $to_user    = "";
                                        $from_user  = "";
                                        $status     = "";

                                        switch($booking_parcelLog->status){
                                            case 1 :
                                                $status     = "Parcel Send Pick Request";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Merchant : ".$booking_parcelLog->merchant->name;
                                                    if(!empty($booking_parcelLog->pickup_branch)){
                                                        $from_user  = "Pickup Branch : ".$booking_parcelLog->pickup_branch->name;
                                                    }
                                                }
                                                break;
                                            case 2 :
                                                $status     = "Parcel Hold";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Merchant : ".$booking_parcelLog->merchant->name;
                                                }
                                                break;
                                            case 3 :
                                                $status     = "Branch Assign Pickup Rider";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Pickup Branch : ".$booking_parcelLog->pickup_branch->name;
                                                    $from_user  = "Pickup Rider : ".$booking_parcelLog->pickup_rider->name;
                                                }
                                                break;
                                            case 4 :
                                                $status     = "Pickup Rider Request Accept";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Pickup Rider : ".$booking_parcelLog->pickup_rider->name;
                                                }
                                                break;
                                            case 5 :
                                                $status     = "Pickup Rider Pickup Parcel";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Pickup Rider : ".$booking_parcelLog->pickup_rider->name;
                                                }
                                                break;
                                            case 6 :
                                                $status     = "Pickup Branch Received Parcel";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Pickup Branch : ".$booking_parcelLog->pickup_branch->name;
                                                }
                                                break;
                                            case 7 :
                                                $status     = "Pickup Branch Assign Delivery Branch";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Pickup Branch : ".$booking_parcelLog->pickup_branch->name;
                                                    $from_user    = "Delivery Branch : ".$booking_parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 8 :
                                                $status     = "Delivery Branch Received Parcel";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Branch : ".$booking_parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 9 :
                                                $status     = "Delivery Branch Reject Parcel";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Branch : ".$booking_parcelLog->delivery_branch->name;
                                                }
                                                break;
                                            case 10 :
                                                $status     = "Delivery Branch Assign Delivery Rider";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Branch : ".$booking_parcelLog->delivery_branch->name;
                                                    $from_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 11 :
                                                $status     = "Delivery Rider Request Accept ";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 12 :
                                                $status     = "Delivery Rider Return Delivery Branch ";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 13 :
                                                $status     = "Delivery Complete";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 14 :
                                                $status     = "Partial Delivery";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                            case 15 :
                                                $status     = "Reschedule ";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name ." (Reschedule Date : " .\Carbon\Carbon::parse($booking_parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
                                                }
                                                break;
                                            case 16 :
                                                $status     = "Reject ";
                                                if(!empty($booking_parcelLog->admin)){
                                                    $to_user    = "Admin : ".$booking_parcelLog->admin->name;
                                                }
                                                else{
                                                    $to_user    = "Delivery Rider : ".$booking_parcelLog->delivery_rider->name;
                                                }
                                                break;
                                        }

                                    @endphp
                                    <tr>
                                        <td > {{ $loop->iteration }} </td>
                                        <td >
                                            {{ \Carbon\Carbon::parse($booking_parcelLog->date)->format('d/m/Y') }}
                                        </td>
                                        <td >
                                            {{ \Carbon\Carbon::parse($booking_parcelLog->time)->format('H:i:s') }}
                                        </td>
                                        <td > {{ $status }} </td>
                                        <td > {{ $to_user }} </td>
                                        <td > {{ $from_user }} </td>
                                    </tr>
                                @endforeach

                            </table>
                        </fieldset>
                    </div>
                @endif --}}

        </div>
</div>

<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>
