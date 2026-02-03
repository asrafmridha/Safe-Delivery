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
                            <li class="breadcrumb-item active" aria-current="page">Profit Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @php
        $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
        $supplier_id =key_exists('supplier_id',$filter)? $filter['supplier_id']:"";
                $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";

$status =key_exists('status',$filter)? $filter['status']:"";
$from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
$to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
$status_name = null;
if ($status==0){
    $status_name="Pending";
} elseif ($status==1){
    $status_name="Approved";
}elseif ($status==2){
    $status_name="Rejected";
}elseif ($status==3){
    $status_name="Ordered";
}elseif ($status==4){
    $status_name="Completed";
}else{
    $status_name="All";
}
  $filter_client="";
if ($client_id){
    $filter_client=\App\Models\Client::where('id',$client_id)->first();
}
  $filter_supplier="";
if ($supplier_id){
    $filter_supplier=\App\Models\Client::where('id',$supplier_id)->first();
}
$filter_currency="";
        if ($currency_id){
            $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
        }

$totalBdtAmount=0;
$totalBuyBdtAmount=0;
$totalProfit=0;
    @endphp
    <form action="{{route('report.profit')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-6">
                <div class="row">
                    {{-- <div class="col-md-3">
                         <div class="form-group">
                             <label for="currency_id">Select Currency</label>
                             <select name="currency_id" id="currency_id"
                                     class="form-control select2 @error('currency_id') is-invalid @enderror"
                                     onchange="this.form.submit()">
                                 <option value="" c_name="all">Select Currency</option>
                                 @foreach($currencies as $currency)
                                     <option value="{{$currency->id}}"
                                             {{$currency_id==$currency->id?'selected':''}} c_name="{{$currency->name}}">
                                         {{$currency->name.' - ('.$currency->code.")"}}
                                     </option>
                                 @endforeach
                             </select>
                             @error('client_id')
                             <p class="text-danger">{{$message}}</p>
                             @enderror
                         </div>
                     </div>--}}
                    {{--<div class="col-md-4">
                        <div class="form-group">
                            <label for="client_id">Select Client</label>
                            <select name="client_id" id="client_id"
                                    class="form-control select2 @error('client_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}"
                                            {{$client_id==$client->id?'selected':''}} c_name="{{$client->name}}">
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
                        <div class="form-group">
                            <label for="supplier_id">Select Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                    class="form-control select2 @error('supplier_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">Select Supplier</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}"
                                            {{$supplier_id==$client->id?'selected':''}} c_name="{{$client->name}}">
                                        {{$client->name.' - ('.$client->country->name.")"}}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>--}}
                    {{-- <div class="col-md-3">
                         <div class="form-group">
                             <label for="status">Status</label>
                             <select name="status" id="status"
                                     class="form-control select2 @error('status') is-invalid @enderror"
                                     onchange="this.form.submit()">
                                 <option value="">All</option>
                                 <option value="0" {{$status==0?"selected":''}}>Pending</option>
                                 <option value="3" {{$status==3?"selected":''}}>Order</option>
                                 <option value="1" {{$status==1?"selected":''}}>Approved</option>
                                 <option value="4" {{$status==4?"selected":''}}>Completed</option>
                                 <option value="2" {{$status==2?"selected":''}}>Rejected</option>
                             </select>
                             @error('status')
                             <p class="text-danger">{{$message}}</p>
                             @enderror
                         </div>
                     </div>--}}

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" value="{{$from_date}}" name="from_date" id="from_date"
                                   class="form-control @error('from_date') is-invalid @enderror"
                                   onchange="this.form.submit()">
                            @error('from_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{$to_date}}"
                                   class="form-control @error('to_date') is-invalid @enderror"
                                   onchange="this.form.submit()" placeholder="DD/MM/YYYY">
                            @error('to_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center" style="margin-top: 28px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                <button type="button" class="btn btn-success btn-sm" id="printBtn">
                    <i class="fa fa-print"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="pdfBtn">
                    <i class="fa fa-file"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="row mx-2">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Profit Report</legend>
                <div class="row mx-2">
                    <div class="col-md-12 text-center">
                        <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                        {{--@if($filter_supplier)
                            <p><strong>Supplier: </strong>{{$filter_supplier->name}}</p>
                        @endif
                        @if($filter_client)
                            <p><strong>Client: </strong>{{$filter_client->name}}</p>
                        @endif
                        @if($filter_currency)
                            <p><strong>Currency: </strong>{{$filter_currency->name." - ".$filter_currency->code}}</p>
                        @endif
                        @if($status_name)
                            <p><strong>Status: </strong>{{$status_name}}</p>
                        @endif--}}
                        @if($from_date)
                            <p><strong>From Date: </strong>{{$from_date}}</p>
                        @endif
                        @if($to_date)
                            <p><strong>To Date: </strong>{{$to_date}}</p>
                        @endif
                       {{-- <table class="table">
                            <tbody>
                            <tr style="background-color: #bacae3;">
                                <th>Total Transaction:</th>
                                <td>{{$total_transaction}}</td>
                            </tr>
                            <tr style="background-color: #bbf4cd;">
                                <th>Total Buying BDT Amount:</th>
                                <td>{{$total_b_bdt_amount}}</td>
                            </tr>
                            <tr style="background-color: #e2a8ea;">
                                <th>Total Selling BDT Amount:</th>
                                <td>{{$total_s_bdt_amount}}</td>
                            </tr>
                            <tr style="background-color: #f6bfbf;">
                                <th>Total Profit:</th>
                                <td>{{$total_profit}}</td>
                            </tr>
                            </tbody>
                        </table>--}}
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Transaction</th>
                                <th>Amount</th>
                                <th>Buying Amount</th>
                                <th>Selling Amount</th>
                                <th>Profit</th>
                            </tr>

                            </thead>
                            <tbody>
                            @php
                            $total_transaction = 0;
                            $total_amount = 0;
                            $total_b_bdt_amount = 0;
                            $total_s_bdt_amount = 0;
                            $total_profit = 0;
                            @endphp

                            @foreach($currencies as $currency)
                                <tr>
                                    <td>{{$currency->currency->code}}</td>
                                    <td>{{$currency->total}}</td>
                                    <td>{{$currency->total_amount}} {{$currency->currency->code}}</td>
                                    <td>{{$currency->total_b_bdt_amount}} BDT</td>
                                    <td>{{$currency->total_s_bdt_amount}} BDT</td>
                                    <td>{{$currency->total_s_bdt_amount - $currency->total_b_bdt_amount}} BDT</td>
                                </tr>

                                @php
                                    $total_transaction += $currency->total;
                                    $total_amount+=$currency->total_amount;
                                    $total_b_bdt_amount+=$currency->total_b_bdt_amount;
                                    $total_s_bdt_amount+=$currency->total_s_bdt_amount;
                                    $total_profit += ($currency->total_s_bdt_amount - $currency->total_b_bdt_amount);
                                @endphp
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Totals</th>
                                <th>{{$total_transaction}}</th>
                                <th>{{$total_amount}}</th>
                                <th>{{$total_b_bdt_amount}} BDT</th>
                                <th>{{$total_s_bdt_amount}} BDT</th>
                                <th>{{$total_profit}} BDT</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- end -->
            </fieldset>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        window.onload = function () {
            $(document).on('click', '#printBtn', function () {
                var client_id = $('#client_id option:selected').val();
                var status = $('#status option:selected').val();
                var supplier_id = $('#supplier_id option:selected').val();
                var currency_id = $('#currency_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.profitPrint') !!}',
                    data: {
                        client_id: client_id,
                        supplier_id: supplier_id,
                        currency_id: currency_id,
                        status: status,
                        from_date: from_date,
                        to_date: to_date,
                    },
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click', '#pdfBtn', function () {
                var client_id = $('#client_id option:selected').val();
                var status = $('#status option:selected').val();
                var supplier_id = $('#supplier_id option:selected').val();
                var currency_id = $('#currency_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var c_name = $('#payment_type option:selected').attr('c_name');
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.profitPdf') !!}',
                    data: {
                        client_id: client_id,
                        supplier_id: supplier_id,
                        currency_id: currency_id,
                        status: status,
                        from_date: from_date,
                        to_date: to_date,
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (html) {
                        // console.log(html)
                        var blob = new Blob([html]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "report_" + c_name + "_" + from_date + "_to_" + to_date + "_" + ".pdf";
                        link.click();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{--    <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}
@endsection
