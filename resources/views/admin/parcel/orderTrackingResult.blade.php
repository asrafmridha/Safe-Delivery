<div class="content" style="margin-top: 20px;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Information</legend>
                    <div class="row">
                        <div class="col-md-6">
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
                                <tr>
                                    <th style="width: 40%"> Merchant Order ID </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->merchant_order_id }} </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-6">
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
                                        <td style="width: 50%"> {{ $parcel->cod_percent }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">COD Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->cod_charge }} % </td>
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
                        </div>
                    </div>

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
                                    <tr>
                                        <th style="width: 40%"> District </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->district->name }} </td>
                                    </tr>
                                    {{-- <tr>
                                        <th style="width: 40%"> Thana/Upazila </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->upazila->name }} </td>
                                    </tr> --}}
                                    <tr>
                                        <th style="width: 40%"> Area </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->area->name }} </td>
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
                                <td style="width: 50%"> {{ $parcel->delivery_rider ? $parcel->delivery_rider->name : "" }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider ? $parcel->delivery_rider->contact_number : "" }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider ? $parcel->delivery_rider->address : "" }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif
            </div>

            @if(!empty($parcelLogs->count() > 0))
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
                            @foreach ($parcelLogs as $parcelLog)
                                @php
                                    $parcelLogStatus = returnParcelLogStatusNameForAdmin($parcelLog, $parcel->delivery_type);
                                    $to_user    = $parcelLogStatus['to_user'];
                                    $from_user  = $parcelLogStatus['from_user'];
                                    $status     = $parcelLogStatus['status_name'];
                                @endphp
                                <tr>
                                    <td > {{ $loop->iteration }} </td>
                                    <td >
                                        {{ \Carbon\Carbon::parse($parcelLog->date)->format('d/m/Y') }}
                                    </td>
                                    <td >
                                        {{ \Carbon\Carbon::parse($parcelLog->time)->format('h:i:s A') }}
                                    </td>
                                    <td > {{ $status }} </td>
                                    <td > {{ $to_user }} </td>
                                    <td > {{ $from_user }} </td>
                                </tr>
                            @endforeach
                        </table>
                    </fieldset>
                </div>
            @endif
        </div>
    </div>
</div>
