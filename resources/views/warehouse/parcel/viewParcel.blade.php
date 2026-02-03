<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Information</legend>
                    <table class="table table-style">
                        <tr>
                            <th style="width: 40%">Invoice </th>
                            <td style="width: 10%"> : </td>
                            <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>
                        </tr>
                        <tr>
                            <th style="width: 40%">Date </th>
                            <td style="width: 10%"> : </td>
                            <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                        </tr>
                    </table>

                    <fieldset>
                        <legend>Parcel Charge </legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%">Weight Package </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Delivery Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                            </tr>

                            @if($parcel->cod_charge != 0 && $parcel->total_collect_amount)
                            <tr>
                                <th style="width: 40%">COD Percent </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->cod_percent }} % </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">COD Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->cod_charge }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%">Collection Amount </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                            </tr>
                            @endif

                            <tr>
                                <th style="width: 40%">Total Charge </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->total_charge }} </td>
                            </tr>
                        </table>
                    </fieldset>

                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Merchant Information</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Customer Information</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12 row">
                @if(!empty($parcel->pickup_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_branch_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif

                @if(!empty($parcel->pickup_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif

                @if(!empty($parcel->delivery_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_branch_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif

                @if(!empty($parcel->delivery_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif
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
