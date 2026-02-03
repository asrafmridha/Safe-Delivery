<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Return Branch Transfer</title>
    <style>
        table, td, th {
            border: 1px solid #a39c9c;
            text-align: left;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 0 3px;
        }

        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }

        .left {
            width: 50%;
            float: left;
        }

        .right {
            width: 40%;
            float: right;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        body {
            margin: 5px 30px;
        }

        h2, h3, h4, p {
            line-height: 0;
        }

        .table-style td, .table-style th {
            padding: .1rem !important;
        }
    </style>
</head>
<body>
<div>

    <div class="center">
        <h2 class="text-center">{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</h2>
        <h4 class="text-center">Return Branch Transfer</h4>
        <p class="text-center"><strong>Consignment: </strong>{{ $returnBranchTransfer->return_transfer_invoice }}
        </p>
        <p class="text-center"><strong>Date: </strong>{{date('d M, Y')}}</p>
    </div>
    <fieldset>
        <legend>Return Branch Transfer Information</legend>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-style">
                    <tr>
                        <th style="width: 40%"> Consignment</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%"> {{ $returnBranchTransfer->return_transfer_invoice }} </td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Create Date</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->create_date_time)->format('d/m/Y H:i:s') }} </td>
                    </tr>

                    @if($returnBranchTransfer->reject_date_time)
                        <tr>
                            <th style="width: 40%">Reject Date</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->reject_date_time)->format('d/m/Y H:i:s') }} </td>
                        </tr>
                    @endif

                    @if($returnBranchTransfer->received_date_time)
                        <tr>
                            <th style="width: 40%">Cancel Date</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->received_date_time)->format('d/m/Y H:i:s') }} </td>
                        </tr>
                    @endif
                    <tr>
                        <th style="width: 40%">Total Transfer</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%"> {{ $returnBranchTransfer->total_transfer_parcel }} </td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Total Transfer Received</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%"> {{ $returnBranchTransfer->total_run_complete_parcel }} </td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Status</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%">
                            @switch($returnBranchTransfer->status)
                                @case(1)
                                <div class="badge badge-success">Transfer Create</div>
                                @break
                                @case(2)
                                <div class="badge badge-danger">Transfer Cancel</div>
                                @break
                                @case(3)
                                <div class="badge badge-success ">Transfer Received</div>
                                @break
                                @case(3)
                                <div class="badge badge-danger ">Transfer Reject</div>
                                @break
                                @default
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 40%">Note</th>
                        <td style="width: 10%"> :</td>
                        <td style="width: 50%"> {{ $returnBranchTransfer->note }} </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <fieldset>
                    <legend>To Branch Information</legend>
                    <table class="table table-style">
                        <tr>
                            <th style="width: 40%"> Name</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->to_branch->name }} </td>
                        </tr>
                        <tr>
                            <th style="width: 40%"> Contact Number</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->to_branch->contact_number }} </td>
                        </tr>
                        <tr>
                            <th style="width: 40%"> Address</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->to_branch->address }} </td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend>From Branch Information</legend>
                    <table class="table table-style">
                        <tr>
                            <th style="width: 40%"> Name</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->name }} </td>
                        </tr>
                        <tr>
                            <th style="width: 40%"> Contact Number</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->contact_number }} </td>
                        </tr>
                        <tr>
                            <th style="width: 40%"> Address</th>
                            <td style="width: 10%"> :</td>
                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->address }} </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </div>
        @if($returnBranchTransfer->return_branch_transfer_details->count() > 0)
            <fieldset>
                <legend>Delivery Transfer Parcel</legend>
                <table class="table table-style table-striped">
                    <thead>
                    <tr>
                        <th width="5%" class="text-center"> SL</th>
                        <th width="15%" class="text-center">Order ID</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Merchant Name</th>
                        <th width="15%" class="text-center">Merchant Number</th>
                        <th width="15%" class="text-center">Customer Name</th>
                        <th width="15%" class="text-center">Complete Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($returnBranchTransfer->return_branch_transfer_details as $return_branch_transfer_detail)
                        <tr>
                            <td class="text-center"> {{ $loop->iteration }} </td>
                            <td class="text-center"> {{ $return_branch_transfer_detail->parcel->parcel_invoice }} </td>
                            <td class="text-center">
                                @switch($return_branch_transfer_detail->status)
                                    @case(1) Transfer Create  @break
                                    @case(2) Transfer Cancel @break
                                    @case(3) Transfer Received @break
                                    @case(4) Transfer Reject @break
                                    @default  @break
                                @endswitch
                            </td>
                            <td class="text-center"> {{ $return_branch_transfer_detail->parcel->merchant->name }} </td>
                            <td class="text-center"> {{ $return_branch_transfer_detail->parcel->merchant->contact_number }} </td>
                            <td class="text-center"> {{ $return_branch_transfer_detail->parcel->customer_name }} </td>
                            <td class="text-center"> {{ $return_branch_transfer_detail->note }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </fieldset>
        @endif
    </fieldset>
</div>
</body>
</html>
