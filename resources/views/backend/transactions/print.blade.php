<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$transaction->transaction_no}}</title>
</head>
<body>
<div class="modal-header bg-default">
{{--    <h4 class="modal-title">Transaction Information View </h4>--}}
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Transaction Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Overview</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 30%;text-align: right">Date</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 66%;text-align: left"> {{date("d M, Y",strtotime($transaction->date)) }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Transaction No</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->transaction_no }} </td>
                                    </tr>

                                    <tr>
                                        <th style="width: 30%;text-align: right">Currency</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->currency->name.' - '.$transaction->currency->code }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Amount</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ number_format($transaction->amount,2).' '.$transaction->currency->code}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Buying Rate</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->b_rate}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Selling Rate</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->s_rate}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Profit</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->profit}} BDT</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Remarks</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->remarks}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Beneficiary</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $transaction->beneficiary}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Status</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left">
                                            @if ($transaction->status == 1)
                                                <p class="badge badge-info">Approved</p>
                                            @elseif ($transaction->status == 0)
                                                <p class="badge badge-warning">Pending</p>
                                            @elseif ($transaction->status == 2)
                                                <p class="badge badge-danger">Rejected</p>
                                            @elseif ($transaction->status == 3)
                                                <p class="badge badge-primary">Order</p>
                                            @elseif ($transaction->status == 4)
                                                <p class="badge badge-success">Completed</p>
                                            @endif
                                        </td>
                                    </tr>
                                    @if (file_exists("uploads/attachments/".$transaction->attachment))
                                        <tr>
                                            <th style="width: 30%;text-align: right">Attachment</th>
                                            <td style="width: 10%"> :</td>
                                            <td style="width: 60%;text-align: left">
                                                <a class="badge badge-primary mt-2"
                                                   href="{{asset("uploads/attachments/".$transaction->attachment )}}"
                                                   download
                                                   title="Download attachment">Download attachment</a>
                                            </td>

                                        </tr>
                                    @endif
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @if($transaction->supplier)
                                    <div class="col-md-12">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">From</legend>
                                            <table class="table table-style">
                                                <tr>
                                                    <th style="width: 40%">Supplier Name</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $transaction->supplier->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Country</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"
                                                        class="text-capitalize"> {{ $transaction->supplier->country->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Representative</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"
                                                        class="text-capitalize"> {{ $transaction->supplier_representative }} </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                @endif
                                @if($transaction->client)
                                    <div class="col-md-12">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">To</legend>
                                            <table class="table table-style">
                                                <tr>
                                                    <th style="width: 40%">Client Name</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $transaction->client->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Country</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"
                                                        class="text-capitalize"> {{ $transaction->client->country->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Representative</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"
                                                        class="text-capitalize"> {{ $transaction->client_representative }} </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>

</body>
</html>
