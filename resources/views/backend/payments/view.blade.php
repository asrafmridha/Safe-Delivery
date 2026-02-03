<div class="modal-header bg-default">
    <h4 class="modal-title">Payment Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row select2-dropdownParent">
            <div class="col-md-12">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Payment Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Overview</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 30%;text-align: right">Date</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 66%;text-align: left"> {{date("d M, Y",strtotime($payment->date)) }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Transaction No</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $payment->payment_no }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Amount</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ number_format($payment->amount,2).' BDT'}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">From Representative</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $payment->client_representative}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">To Representative</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $payment->supplier_representative}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Remarks</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left"> {{ $payment->remarks}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%;text-align: right">Status</th>
                                        <td style="width: 10%"> :</td>
                                        <td style="width: 60%;text-align: left">
                                            @if ($payment->status == 1)
                                                <p class="badge badge-info">Approved</p>
                                            @elseif ($payment->status == 0)
                                                <p class="badge badge-warning">Pending</p>
                                            @elseif ($payment->status == 2)
                                                <p class="badge badge-danger">Rejected</p>
                                            @elseif ($payment->status == 3)
                                                <p class="badge badge-primary">Order</p>
                                            @elseif ($payment->status == 4)
                                                <p class="badge badge-success">Completed</p>
                                            @endif
                                        </td>
                                    </tr>
                                    @if (file_exists("uploads/attachments/".$payment->attachment))
                                        <tr>
                                            <th style="width: 30%;text-align: right">Attachment</th>
                                            <td style="width: 10%"> :</td>
                                            <td style="width: 60%;text-align: left">
                                                <a class="badge badge-primary mt-2"
                                                   href="{{asset("uploads/attachments/".$payment->attachment )}}"
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

                                @if($payment->client)
                                    <div class="col-md-12">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">From</legend>
                                            <table class="table table-style">
                                                <tr>
                                                    <th style="width: 40%">Client Name</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"> {{ $payment->client->name }} </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Country</th>
                                                    <td style="width: 10%"> :</td>
                                                    <td style="width: 50%"
                                                        class="text-capitalize"> {{ $payment->client->country->name }} </td>
                                                </tr>

                                            </table>
                                        </fieldset>
                                    </div>
                                @endif
                                    @if($payment->supplier)
                                        <div class="col-md-12">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">To</legend>
                                                <table class="table table-style">
                                                    <tr>
                                                        <th style="width: 40%">Supplier Name</th>
                                                        <td style="width: 10%"> :</td>
                                                        <td style="width: 50%"> {{ $payment->supplier->name }} </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%">Country</th>
                                                        <td style="width: 10%"> :</td>
                                                        <td style="width: 50%"
                                                            class="text-capitalize"> {{ $payment->supplier->country->name }} </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Update Status</legend>
                                <form action="{{route('payment.change.status',$payment->id)}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status">Select Status</label>
                                                <select name="status" id="status"
                                                        class="form-control @error('status') is-invalid @enderror"
                                                        onchange="getStatus()">
                                                    <option
                                                        value="0" {{$payment->status == 0 ? 'selected': ''}}>
                                                        Pending
                                                    </option>
                                                    <option
                                                        value="3" {{$payment->status == 3 ? 'selected': ''}}>
                                                        Order
                                                    </option>
                                                    <option
                                                        value="1" {{$payment->status == 1 ? 'selected': ''}}>
                                                        Approved
                                                    </option>
                                                    <option
                                                        value="4" {{$payment->status == 4 ? 'selected': ''}}>
                                                        Completed
                                                    </option>
                                                    <option
                                                        value="2" {{$payment->status == 2 ? 'selected': ''}}>
                                                        Rejected
                                                    </option>
                                                </select>
                                                @error('status')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="float-right col-md-6">
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" name="remarks" id="remarks" class="form-control"
                                                       value="{{$payment->remarks}}">
                                            </div>
                                            @error('pin')
                                            <div class="text-danger font-italic">
                                                <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="float-right col-md-2">
                                            <label for="status">Your Pin</label>
                                            <input type="text" class="form-control @error('pin') is-invalid @enderror"
                                                   id="pin" name="pin" placeholder="Your Pin">
                                            @error('pin')
                                            <div class="text-danger font-italic">
                                                <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="input-group-append" style="margin-top: 20px">
                                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>

                        {{--<div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Transaction Log</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 5%"> #</th>
                                        <th style="width: 10%">User</th>
                                        <th style="width: 20%"> Date-Time</th>
                                        <th style="width: 25%"> Subject</th>
                                        <th style="width: 45%"> Description</th>
                                    </tr>
                                    @foreach($payment->log as $key=>$log)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$log->user->name}}</td>
                                            <td>{{$log->created_at->format("d M, Y g:i a")}}</td>
                                            <td>{{$log->subject}}</td>
                                            <td>{{$log->description}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </fieldset>
                        </div>--}}
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>

</script>
