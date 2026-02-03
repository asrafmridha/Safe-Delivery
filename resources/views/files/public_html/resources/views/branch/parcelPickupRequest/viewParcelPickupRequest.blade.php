<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Pickup Request Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <fieldset>
                    <legend>Parcel Pickup Request Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Invoice </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->pickup_request_invoice }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Status </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    @switch($parcelPickupRequest->status)
                                        @case(1)
                                        <span class='text-bold text-warning' style='font-size:16px;'>Requested</span>
                                        @break
                                        @case(2)
                                        <span class='text-bold text-success' style='font-size:16px;'>Accepted</span>
                                        @break
                                        @case(3)
                                        <span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>
                                        @break
                                        @case(4)
                                        <span class='text-bold text-primary' style='font-size:16px;'>Rider Assigned</span>
                                        @break
                                        @case(5)
                                        <span class='text-bold text-success' style='font-size:16px;'>Request Completed</span>
                                        @break
                                        @default

                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Request Type </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    @switch($parcelPickupRequest->request_type)
                                        @case(1)
                                        <!--<span class='text-bold text-info' style='font-size:16px;'>Regular Delivery [Delivery Charge 100 Tk, Delivery Time (6 Hours), Only Inside Dhaka]</span>-->
                                         <span class='text-bold text-info' style='font-size:16px;'>Regular Delivery </span>
                                        @break
                                        @case(2)
                                        <!--<span class='text-bold text-info' style='font-size:16px;'>Express Delivery [Delivery Charge 65 Tk, Delivery Time (24 Hours)]</span>-->
                                        <span class='text-bold text-info' style='font-size:16px;'>Express Delivery</span>
                                        @break
                                        @default

                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->name }} </td>
                            </tr>
                            <!--<tr>
                                <th style="width: 40%">Branch Contact Number</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Branch Address</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->branch->address }} </td>
                            </tr>-->
                            <tr>
                                <th style="width: 40%">Merchant Company </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->company_name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Name </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Merchant Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->merchant->business_address }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcelPickupRequest->date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Total Parcel  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->total_parcel }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Note  </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcelPickupRequest->note }} </td>
                            </tr>
                        </table>
                </fieldset>
            </div>
        </div>
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
