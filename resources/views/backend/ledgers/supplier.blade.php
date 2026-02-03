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
                            <li class="breadcrumb-item active" aria-current="page">Supplier Ledger</li>
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
    @endphp
    <form action="{{route('ledger.supplier')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_id">Select Supplier</label>
                            <select name="client_id" id="client_id"
                                    class="form-control select2 @error('client_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">Select Supplier</option>
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
                            <label for="order_by">Order By</label>
                            <select name="order_by" id="order_by"
                                    class="form-control select2 @error('order_by') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="desc" {{$order_by=="desc"?"selected":''}}>Descending</option>
                                <option value="asc" {{$order_by=="asc"?"selected":''}}>Ascending</option>
                            </select>
                            @error('order_by')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    {{--<div class="col-md-1">
                        <div class="form-group">
                            <label for="count">Count</label>
                            <select name="count" id="count"
                                    class="form-control select2 @error('count') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="10" {{$count==10?"selected":''}}>10</option>
                                <option value="50" {{$count==50?"selected":''}}>50</option>
                                <option value="100" {{$count==100?"selected":''}}>100</option>
                                <option value="all" {{$count=="all"?"selected":''}}>All</option>
                            </select>
                            @error('order_by')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>--}}
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
    <form action="{{route('ledger.supplierPdf')}}" id="pdfForm">
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
        <legend class="scheduler-border">Supplier Ledger</legend>
        <p class="text-center"><strong>Date: </strong>{{date("d M, Y")}}</p>
        <div class="text-center">
            @if($filter_client)
                <p><strong>Supplier: </strong>{{$filter_client->name}}</p>
            @endif
            @if($from_date)
                <p><strong>From Date: </strong>{{$from_date}}</p>
            @endif
            @if($to_date)
                <p><strong>To Date: </strong>{{$to_date}}</p>
            @endif
        </div>

        <!-- data table start -->
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Transactions</legend>
            <div class="data_table my-4">
                <div class="content_section table-responsive">
                    <table class="table table-bordered table-striped"
                           id="client_ledger" {{--style="font-size: 13px;"--}}>
                        <thead>
                        <tr>
                            <th width="3%" class="text-center">#</th>
                            <th>Date</th>
                            <th>Transaction No</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Rate</th>
                            <th>BDT Amount</th>
                            <th>Remarks</th>
                            <th>S/L</th>
                            <th>Beneficiary</th>
                            <th>Created By</th>
                            <th width="12%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $key=>$item)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                                <td class="text-center">{{$item->transaction_no}}</td>
                                <td>{{$item->client?$item->client->name:"---"}}</td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                        <span class="badge badge-info">Approved</span>
                                    @elseif ($item->status == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge badge-primary">Order</span>
                                    @elseif ($item->status == 4)
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$item->currency->code}}</td>
                                <td class="text-center">{{number_format($item->amount,2)}}</td>
                                <td class="text-center">{{$item->b_rate}}</td>
                                <td class="text-center">{{$item->b_bdt_amount}}</td>
                                <td class="text-center">{{$item->remarks}}</td>
                                <td class="text-center">{{$item->sl}}</td>
                                <td class="text-center">{{$item->beneficiary}}</td>
                                <td class="text-center">{{$item->created_user->name}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-sm view-modal" data-toggle="modal"
                                            data-target="#viewModal" transaction_id="{{$item->id}}"
                                            title="View transaction"><i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                            @php
                                $totalBdtAmount+=$item->b_bdt_amount;
                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="8" style="text-align: right">Totals:</th>
                            <th class="text-center">{{number_format($totalBdtAmount)}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- end -->
        </fieldset>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Payments</legend>
            <div class="data_table my-4">
                <div class="content_section table-responsive">
                    <table class="table table-bordered table-striped"
                           id="client_ledger_payment" {{--style="font-size: 13px;"--}}>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Payment No</th>
                            <th>From</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Remarks</th>
                            <th>Created By</th>
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $key=>$item)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                                <td class="text-center">{{$item->payment_no}}</td>
                                <td>{{$item->client?$item->client->name:"---"}}</td>
                                <td class="text-center">
                                    @if ($item->status == 1)
                                        <span class="badge badge-info">Approved</span>
                                    @elseif ($item->status == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($item->status == 2)
                                        <span class="badge badge-danger">Rejected</span>
                                    @elseif ($item->status == 3)
                                        <span class="badge badge-primary">Order</span>
                                    @elseif ($item->status == 4)
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                                <td class="text-center">{{number_format($item->amount,2)}}</td>
                                <td class="text-center">{{$item->remarks}}</td>
                                <td class="text-center">{{$item->payment_method->name}}</td>
                                <td class="text-center">{{$item->created_user->name}}</td>
                                <td class="text-center">
                                    <button class="btn btn-success btn-sm view-payment-modal" data-toggle="modal"
                                            data-target="#viewModal" payment_id="{{$item->id}}"
                                            title="View transaction"><i class="fa fa-eye"></i></button>
                                </td>
                            </tr>
                            @php
                                $totalPaymentAmount+=$item->amount;
                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="5" style="text-align: right">Totals:</th>
                            <th class="text-center">{{number_format($totalPaymentAmount)}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- end -->
        </fieldset>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Summery</legend>
            <div class="data_table my-4">
                <div class="content_section table-responsive">
                    <table class="table table-bordered table-striped"
                           id="" {{--style="font-size: 13px;"--}}>
                        <tbody>
                            <tr>
                                <td class="text-center" rowspan="3" width="50%"><h1 class="mt-5">Summery</h1></td>
                                <td  style="text-align: right">Total Transaction Amount:</td>
                                <td  style="text-align: left">{{number_format($totalBdtAmount)}}</td>
                            </tr>
                            <tr>
                                <td  style="text-align: right">Total Payment Amount:</td>
                                <td  style="text-align: left">{{number_format($totalPaymentAmount)}}</td>
                            </tr>
                            <tr>
                                <td  style="text-align: right">Due:</td>
                                <td  style="text-align: left">{{number_format($totalBdtAmount-$totalPaymentAmount)}}</td>
                            </tr>

                        </tbody>
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
                    url: '{!! route('ledger.supplierPrint') !!}',
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
