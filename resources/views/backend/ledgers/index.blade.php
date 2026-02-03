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
                            <li class="breadcrumb-item active" aria-current="page">Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @php
        $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
        $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
        $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
        $order_by =key_exists('order_by',$filter)? $filter['order_by']:"";
        $count =key_exists('count',$filter)? $filter['count']:10;


        $filter_client="";
        if ($client_id){
            $filter_client=\App\Models\Client::where('id',$client_id)->first();
        }
        $totalBdtAmount=0;
        $totalPaymentAmount=0;
        $balance = $previous_balance;
    @endphp
    <form action="{{route('ledger.client')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="order_by">Order BY</label>
                            <select name="order_by" id="order_by"
                                    class="form-control @error('order_by') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="asc" {{$order_by=='asc'?'selected':''}}>Ascending</option>
                                <option value="desc" {{$order_by=='desc'?'selected':''}}>Descending</option>
                            </select>
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
            </div>
        </div>
    </form>
    <form action="{{route('ledger.clientPdf')}}" id="pdfForm">
        @csrf
        <input type="hidden" id="pdf_client_id" name="client_id" value="">
        <input type="hidden" id="pdf_from_date" name="from_date" value="">
        <input type="hidden" id="pdf_to_date" name="to_date" value="">
        <input type="hidden" id="pdf_order_by" name="order_by" value="">
        <button type="button" class="btn btn-danger btn-sm float-right" id="pdfBtn">
            <i class="fa fa-file"></i> Download PDF
        </button>
    </form>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Client Ledger</legend>
        <p class="text-center"><strong>Date: </strong>{{date("d M, Y")}}</p>
        <div class="text-center">
            @if($filter_client)
                <p><strong>Client: </strong>{{$filter_client->name}}</p>
            @endif
            @if($order_by)
                <p><strong>Order By: </strong>{{$order_by=="desc"?"Descending":"Ascending"}}</p>
            @endif
            @if($from_date)
                <p><strong>From Date: </strong>{{$from_date}}</p>
            @endif
            @if($to_date)
                <p><strong>To Date: </strong>{{$to_date}}</p>
            @endif
            @if($balance)
                <p><strong>Previous Balance: </strong>{{$balance}}</p>
            @endif
        </div>

        <!-- data table start -->
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Transactions</legend>
            <div class="data_table my-4">
                <div class="content_section table-responsive">

                    <table class="table table-bordered"
                           id="client_ledger" {{--style="font-size: 13px;"--}}>
                        <thead>
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th>Date</th>
                            <th>Transaction No</th>
                            <th>Supplier Name</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Rate</th>
                            <th>BDT Amount</th>
                            <th>Payable</th>
                            <th>Receivable</th>
                            <th>Balance</th>
                            <th>Remarks</th>
                            <th>S/L</th>
                            <th>Beneficiary</th>
                            <th>Created By</th>
                            <th width="12%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $total_payable = 0;
                            $total_receivable = 0;
                            $desc_balance=$desc_final_balance;
                        @endphp

                        @if($order_by == 'desc')
                            @foreach($items as $key=>$item)
                                @if(key_exists('payment_no',$item))
                                    <tr class="bg-success">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                        <td class="text-center">{{$item['payment_no']}}</td>
                                        <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                        <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                        <td class="text-center">
                                            @if ($item['status'] == 1)
                                                <span class="badge badge-info">Approved</span>
                                            @elseif ($item['status'] == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($item['status'] == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($item['status'] == 3)
                                                <span class="badge badge-primary">Order</span>
                                            @elseif ($item['status'] == 4)
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                Receive
                                            @else
                                                Payment
                                            @endif
                                        </td>
                                        <td class="text-center">BDT</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                {{$item['amount']}}
                                                @php
                                                    $total_payable+=$item['amount'];
                                                @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']!=$client_id)
                                                {{$item['amount']}}
                                                @php
                                                    $total_receivable+=$item['amount'];
                                                @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">{{number_format($desc_balance,2)}}</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm view-payment-modal"
                                                    data-toggle="modal"
                                                    data-target="#viewModal" payment_id="{{$item['id']}}"
                                                    title="View Payment"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    @php
                                        if ($item['client_id']==$client_id){
                                            $desc_balance -= $item['amount'];
                                        }else{
                                            $desc_balance += $item['amount'];
                                        }
                                    @endphp
                                @elseif(key_exists('transaction_no',$item))

                                    <tr class="bg-danger">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                        <td class="text-center">{{$item['transaction_no']}}</td>
                                        <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                        <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                        <td class="text-center">
                                            @if ($item['status'] == 1)
                                                <span class="badge badge-info">Approved</span>
                                            @elseif ($item['status'] == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($item['status'] == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($item['status'] == 3)
                                                <span class="badge badge-primary">Order</span>
                                            @elseif ($item['status'] == 4)
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                Sales
                                            @else
                                                Purchase
                                            @endif
                                        </td>
                                        <td class="text-center">{{key_exists('currency',$item)?$item['currency']['code']:"---"}}</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        @if ($item['client_id']==$client_id)
                                            <td class="text-center">{{$item['s_rate']}}</td>
                                            <td class="text-center">{{$item['s_bdt_amount']}}</td>
                                        @else
                                            <td class="text-center">{{$item['b_rate']}}</td>
                                            <td class="text-center">{{$item['b_bdt_amount']}}</td>
                                        @endif
                                        <td class="text-center">
                                            @if ($item['client_id']!=$client_id)
                                                @if ($item['client_id']==$client_id)
                                                    {{$item['s_bdt_amount']}}
                                                    @php
                                                        $total_payable+=$item['s_bdt_amount'];
                                                    @endphp
                                                @else
                                                    {{$item['b_bdt_amount']}}
                                                    @php
                                                        $total_payable+=$item['b_bdt_amount'];
                                                    @endphp
                                                @endif
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                @if ($item['client_id']==$client_id)
                                                    {{$item['s_bdt_amount']}}
                                                    @php
                                                        $total_receivable+=$item['s_bdt_amount'];
                                                    @endphp
                                                @else
                                                    {{$item['b_bdt_amount']}}
                                                    @php
                                                        $total_receivable+=$item['b_bdt_amount'];
                                                    @endphp
                                                @endif
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">{{$desc_balance}}</td>
                                        <td class="text-center">{{$item['remarks']}}</td>
                                        <td class="text-center">{{$item['sl']}}</td>
                                        <td class="text-center">{{$item['beneficiary']}}</td>
                                        <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm view-modal" data-toggle="modal"
                                                    data-target="#viewModal" transaction_id="{{$item['id']}}"
                                                    title="View transaction"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                    @php
                                        if ($item['client_id']==$client_id){
                                            $desc_balance += $item['s_bdt_amount'];
                                        }else{
                                            $desc_balance -= $item['b_bdt_amount'];
                                        }
                                    @endphp
                                @endif
                            @endforeach
                        @else
                            @foreach($items as $key=>$item)
                                @if(key_exists('payment_no',$item))
                                    @php
                                        if ($item['client_id']==$client_id){
                                            $balance += $item['amount'];
                                        }else{
                                            $balance -= $item['amount'];
                                        }
                                    @endphp
                                    <tr class="bg-success">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                        <td class="text-center">{{$item['payment_no']}}</td>
                                        <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                        <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                        <td class="text-center">
                                            @if ($item['status'] == 1)
                                                <span class="badge badge-info">Approved</span>
                                            @elseif ($item['status'] == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($item['status'] == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($item['status'] == 3)
                                                <span class="badge badge-primary">Order</span>
                                            @elseif ($item['status'] == 4)
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                Receive
                                            @else
                                                Payment
                                            @endif
                                        </td>
                                        <td class="text-center">BDT</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                {{$item['amount']}}
                                                @php
                                                    $total_payable+=$item['amount'];
                                                @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']!=$client_id)
                                                {{$item['amount']}}
                                                @php
                                                    $total_receivable+=$item['amount'];
                                                @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">{{number_format($balance,2)}}</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">---</td>
                                        <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm view-payment-modal"
                                                    data-toggle="modal"
                                                    data-target="#viewModal" payment_id="{{$item['id']}}"
                                                    title="View Payment"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                @elseif(key_exists('transaction_no',$item))
                                    @php
                                        if ($item['client_id']==$client_id){
                                            $balance -= $item['s_bdt_amount'];
                                        }else{
                                            $balance += $item['b_bdt_amount'];
                                        }
                                    @endphp
                                    <tr class="bg-danger">
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{date('d M, Y',strtotime($item['date']))}}</td>
                                        <td class="text-center">{{$item['transaction_no']}}</td>
                                        <td>{{key_exists('supplier',$item)?$item['supplier']['name']:"---"}}</td>
                                        <td>{{key_exists('client',$item)?$item['client']['name']:"---"}}</td>
                                        <td class="text-center">
                                            @if ($item['status'] == 1)
                                                <span class="badge badge-info">Approved</span>
                                            @elseif ($item['status'] == 0)
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif ($item['status'] == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($item['status'] == 3)
                                                <span class="badge badge-primary">Order</span>
                                            @elseif ($item['status'] == 4)
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                Sales
                                            @else
                                                Purchase
                                            @endif
                                        </td>
                                        <td class="text-center">{{key_exists('currency',$item)?$item['currency']['code']:"---"}}</td>
                                        <td class="text-center">{{number_format($item['amount'],2)}}</td>
                                        @if ($item['client_id']==$client_id)
                                            <td class="text-center">{{$item['s_rate']}}</td>
                                            <td class="text-center">{{$item['s_bdt_amount']}}</td>
                                        @else
                                            <td class="text-center">{{$item['b_rate']}}</td>
                                            <td class="text-center">{{$item['b_bdt_amount']}}</td>
                                        @endif
                                        <td class="text-center">
                                            @if ($item['client_id']!=$client_id)
                                                @if ($item['client_id']==$client_id)
                                                    {{$item['s_bdt_amount']}}
                                                    @php
                                                        $total_payable+=$item['s_bdt_amount'];
                                                    @endphp
                                                @else
                                                    {{$item['b_bdt_amount']}}
                                                    @php
                                                        $total_payable+=$item['b_bdt_amount'];
                                                    @endphp
                                                @endif
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item['client_id']==$client_id)
                                                @if ($item['client_id']==$client_id)
                                                    {{$item['s_bdt_amount']}}
                                                    @php
                                                        $total_receivable+=$item['s_bdt_amount'];
                                                    @endphp
                                                @else
                                                    {{$item['b_bdt_amount']}}
                                                    @php
                                                        $total_receivable+=$item['b_bdt_amount'];
                                                    @endphp
                                                @endif
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-center">{{$balance}}</td>
                                        <td class="text-center">{{$item['remarks']}}</td>
                                        <td class="text-center">{{$item['sl']}}</td>
                                        <td class="text-center">{{$item['beneficiary']}}</td>
                                        <td class="text-center">{{key_exists('created_user',$item)?$item['created_user']['name']:"---"}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-danger btn-sm view-modal" data-toggle="modal"
                                                    data-target="#viewModal" transaction_id="{{$item['id']}}"
                                                    title="View transaction"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="11" style="text-align: right">Totals:</th>
                            <th class="text-center">{{$total_payable}}</th>
                            <th class="text-center">{{$total_receivable}}</th>
                            <th colspan="5"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- end -->
        </fieldset>
    </fieldset>
    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="showResult">
            </div>
        </div>
    </div>

@endsection
@section('script')
    {{--    <script type="text/javascript" src="{{asset('assets/backend/js/jquery.min.js')}}"></script>--}}

    <script type="text/javascript">
        window.onload = function () {
            $(document).on('click', '#printBtn', function () {
                var client_id = $('#client_id option:selected').val();
                var order_by = $('#order_by option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('ledger.clientPrint') !!}',
                    data: {
                        client_id: client_id,
                        from_date: from_date,
                        to_date: to_date,
                        order_by: order_by
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

            $('#client_ledger').on('click', '.view-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.view', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            console.log(response);
                            $('#showResult').html(response);
                        },
                    })
                }
            });
            $('#client_ledger_payment').on('click', '.view-payment-modal', function () {
                var payment_id = $(this).attr('payment_id');
                var url = "{{ route('payment.view', ":payment_id") }}";
                url = url.replace(':payment_id', payment_id);
                $('#showResult').html('');
                if (payment_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            $('#showResult').html(response);
                        },
                    })
                }
            });


            $(document).on('click', '#pdfBtn', function () {
                var client_id = $('#client_id option:selected').val();
                var c_name = $('#client_id option:selected').attr('c_name');
                var order_by = $('#order_by option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                document.getElementById("pdf_client_id").value = client_id;
                document.getElementById("pdf_order_by").value = order_by;
                document.getElementById("pdf_from_date").value = from_date;
                document.getElementById("pdf_to_date").value = to_date;

                document.getElementById("pdfForm").submit();
                {{--$.ajax({--}}
                {{--    type: 'GET',--}}
                {{--    url: '{!! route('ledger.clientPdf') !!}',--}}
                {{--    data: {--}}
                {{--        client_id: client_id,--}}
                {{--        from_date: from_date,--}}
                {{--        to_date: to_date,--}}
                {{--        order_by: order_by--}}
                {{--    },--}}
                {{--    xhrFields: {--}}
                {{--        responseType: "blob"--}}
                {{--    },--}}
                {{--   /* xhr:function(){--}}
                {{--        var xhr = new XMLHttpRequest();--}}
                {{--        xhr.responseType= 'blob'--}}
                {{--        return xhr;--}}
                {{--    },*/--}}
                {{--    success: function (html) {--}}
                {{--        // console.log(html)--}}
                {{--        var blob = new Blob([html]);--}}
                {{--        var link = document.createElement('a');--}}
                {{--        link.href = window.URL.createObjectURL(blob);--}}
                {{--        link.download = "Ledger_" + c_name + "_" + from_date + "_to_" + to_date + "_" + order_by + ".pdf";--}}
                {{--        link.click();--}}
                {{--    },--}}
                {{--    error: function (data) {--}}
                {{--        console.log('Error:', data);--}}
                {{--    }--}}
                {{--});--}}
            });


        }
    </script>
    <!-- data table -->
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#client_ledger').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 150, -1],
                    [10, 25, 50, 100, 150, 'All'],
                ]
            });
            $('#client_ledger_payment').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 150, -1],
                    [10, 25, 50, 100, 150, 'All'],
                ]
            });
        });
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{--    <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}
@endsection
