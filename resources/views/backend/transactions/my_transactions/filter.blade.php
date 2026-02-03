@extends('layouts.backend')

@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('transaction.myList')}}" class="breadcrumb-link">Transaction</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Filter Transaction</li>
                            @if(check_permission('transaction my-list'))
                                <a href="{{route('transaction.myList')}}" class="btn btn-primary ml-auto">
                                    Back
                                </a>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @php
        $status =key_exists('status',$filter)? $filter['status']:"";
        $transaction_type =key_exists('transaction_type',$filter)? $filter['transaction_type']:"";
        $created_by =key_exists('created_by',$filter)? $filter['created_by']:"";
        $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";
        $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
        $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
        $to_date =key_exists('to_date',$filter)? $filter['to_date']:"";
        $status_name = null;
        if ($status==0){
            $status_name="Pending";
        } elseif ($status==1){
            $status_name="Approved";
        }elseif ($status==2){
            $status_name="Rejected";
        }
         $transaction_type_name = null;
        if ($transaction_type=="debit"){
            $transaction_type_name="Debit";
        } elseif ($transaction_type=="credit"){
            $transaction_type_name="Credit";
        }

        $create_user="";
        if ($created_by){
            $create_user=\App\Models\User::where('id',$created_by)->first();
        }
        $filter_currency="";
        if ($currency_id){
            $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
        }
        $filter_client="";
        if ($client_id){
            $filter_client=\App\Models\Client::where('id',$client_id)->first();
        }
        $totalBdtAmount=0;
        $totalDebit=0;
        $totalCredit=0;

    @endphp
    <form action="{{route('transaction.myList.filter')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select name="status" id="status"
                                    class="form-control select2 @error('status') is-invalid @enderror">
                                <option value="">Select status</option>
                                <option value="0" {{$status==0?"selected":''}}>Pending</option>
                                <option value="1" {{$status==1?"selected":''}}>Approved</option>
                                <option value="2" {{$status==2?"selected":''}}>Rejected</option>
                            </select>
                            @error('status')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency_id">Select Currency</label>
                            <select name="currency_id" id="currency_id"
                                    class="form-control select2 @error('currency_id') is-invalid @enderror">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option
                                        value="{{$currency->id}}" {{$currency_id==$currency->id?'selected':''}}>{{$currency->code.' - '.$currency->name}}</option>
                                @endforeach
                            </select>
                            @error('currency_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="client_id">Select Client</label>
                            <select name="client_id" id="client_id"
                                    class="form-control select2 @error('client_id') is-invalid @enderror">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}" {{$client_id==$client->id?'selected':''}}>
                                        {{$client->name.' - ('.$client->country->name.")"}}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="transaction_type">Transaction Type</label>
                        <select id="transaction_type" name="transaction_type"
                                class="form-control @error('transaction_type') is-invalid @enderror">
                            <option value="">All Type</option>
                            <option value="debit" {{$transaction_type=="debit"?"selected":''}}>Debit</option>
                            <option value="credit" {{$transaction_type=="credit"?"selected":''}}>Credit</option>
                        </select>
                        @error('transaction_type')
                        <div class="text-danger font-italic">
                            <p><i class="fas fa-exclamation-circle"></i> {{$message}}</p>
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" value="{{$from_date}}" name="from_date" id="from_date"
                                   class="form-control @error('from_date') is-invalid @enderror">
                            @error('from_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{$to_date}}"
                                   class="form-control @error('to_date') is-invalid @enderror">
                            @error('to_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 mt-5">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <input type="button" class="btn btn-success btn-sm my-3" onclick="printDiv('printableArea')"
                       value="Print"/>
            </div>
        </div>
    </form>
    <div id="printableArea">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Transaction Filter</legend>
            <p class="text-center"><strong>Date: </strong>{{date("d M, Y")}}</p>
            <div class="row mx-5">
                <div class="col-md-6">
                    @if($status_name)
                        <p><strong>Status: </strong>{{$status_name}}</p>
                    @endif
                    @if($create_user)
                        <p><strong>Created By: </strong>{{$create_user->name}}</p>
                    @endif
                    @if($filter_currency)
                        <p><strong>Currency: </strong>{{$filter_currency->code.' - '.$filter_currency->name}}</p>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    @if($transaction_type_name)
                        <p><strong>Transaction Type: </strong>{{$transaction_type_name}}</p>
                    @endif
                    @if($filter_client)
                        <p><strong>Client: </strong>{{$filter_client->name}}</p>
                    @endif
                    @if($from_date)
                        <p><strong>From Date: </strong>{{$from_date}}</p>
                    @endif
                    @if($to_date)
                        <p><strong>To Date: </strong>{{$to_date}}</p>
                    @endif
                </div>
            </div>

            <!-- data table start -->
            <div class="data_table my-4">
                <div class="content_section">
                    <table class="table table-bordered table-striped " {{--style="font-size: 13px;"--}}>
                        <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="9%">Date</th>
                            <th width="12%">Transaction No</th>
                            <th >Client Name</th>
                            <th >Currency</th>
                            <th >Transaction Type</th>
                            <th >Payment Type</th>
                            <th >Remarks</th>
                            <th width="10%">Status</th>
                            <th >Created By</th>
                            <th >Amount</th>
                            <th >Rate</th>
                            <th width="12%">BDT Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{date('d M, Y',strtotime($item->date))}}</td>
                                <td>{{$item->transaction_no}}</td>
                                <td>{{optional($item->client)->name}}</td>
                                <td>{{$item->currency->code}}</td>

                                <td>{{$item->transaction_type}}</td>
                                <td>{{$item->payment_type}}</td>
                                <td>{{$item->remarks}}</td>
                                <td>
                                    @if ($item->status == 1)
                                        Approved
                                    @elseif ($item->status == 0)
                                        Pending
                                    @elseif ($item->status == 2)
                                        Rejected
                                    @endif
                                </td>
                                <td>{{$item->created_user->name}}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{$item->rate}}</td>
                                <td>{{$item->bdt_amount}}</td>
                            </tr>
                            @php
                                $totalBdtAmount+=$item->bdt_amount;
                                if ($item->transaction_type=="debit"){
                                    $totalDebit+=$item->bdt_amount;
                                }
                                if ($item->transaction_type=="credit"){
                                    $totalCredit+=$item->bdt_amount;
                                }
                            @endphp
                        @endforeach
                        <tr>
                            <th rowspan="5" colspan="8" class="text-center">
                                <h2 class="mt-5">Totals</h2>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total Transaction BDT Amount:</th>
                            <th>{{$totalBdtAmount}}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total Credit:</th>
                            <th>{{$totalCredit}}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total Debit:</th>
                            <th>{{$totalDebit}}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total Balance:</th>
                            <th>{{$totalCredit-$totalDebit}}</th>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end -->
        </fieldset>
    </div>


@endsection
@section('script')
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
@section('style')
    <style>
        table {
            width: 100%;
            table-layout: fixed;
            overflow-wrap: break-word;
        }
    </style>
@endsection
