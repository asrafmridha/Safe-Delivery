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
                            <li class="breadcrumb-item active" aria-current="page">Currency Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @php
        $expense_head_id =key_exists('expense_head_id',$filter)? $filter['expense_head_id']:"";
        $status =key_exists('status',$filter)? $filter['status']:"";
        $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
        $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
        $filter_expense_head="";
        if ($expense_head_id){
            $filter_expense_head=\App\Models\ExpenseHead::where('id',$expense_head_id)->first();
        }
        $total_amount = 0;
        if ($status==0){
            $status_name="Pending";
        } elseif ($status==1){
            $status_name="Approved";
        }elseif ($status==2){
            $status_name="Rejected";
        }else{
            $status_name="All";
        }
    @endphp
    <form action="{{route('report.expense')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expense_head_id">Select Expense Head</label>
                            <select name="expense_head_id" id="expense_head_id"
                                    class="form-control select2 @error('expense_head_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="" c_name="all">Select Expense Head</option>
                                @foreach($expense_heads as $expense_head)
                                    <option value="{{$expense_head->id}}"
                                            {{$expense_head_id == $expense_head->id?'selected':''}} c_name="{{$expense_head->title}}">
                                        {{$expense_head->title}}
                                    </option>
                                @endforeach
                            </select>
                            @error('expense_head_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="0" {{$status==0?"selected":""}}>Pending</option>
                                <option value="1" {{$status==1?"selected":""}}>Approved</option>
                                <option value="2" {{$status==2?"selected":""}}>Rejected</option>
                            </select>
                            @error('details')
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
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Expense Report</legend>
                <div class="row mx-2">
                    <div class="col-md-12 text-center">
                        <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                        @if($filter_expense_head)
                            <p><strong>Expense Head: </strong>{{$filter_expense_head->title}}</p>
                        @else
                            <p><strong>Expense Head: </strong> All</p>
                        @endif
                        @if($status_name)
                            <p><strong>Status: </strong>{{$status_name}}</p>
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
                    <div class="content_section table-responsive" style="white-space: nowrap">
                        <table class="table table-bordered table-striped"
                               id="client_ledger" {{--style="font-size: 13px;"--}}>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Expense Head</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                                <th>Status</th>
                                {{--                                <th width="100px">Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $key=>$item)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                                    <td class="text-center">{{$item->expense_head->title}}</td>
                                    <td class="text-center">{{$item->amount}}</td>
                                    <td class="text-center">{{$item->remarks}}</td>
                                    <td class="text-center">{{$item->created_user->name}}</td>
                                    <td class="text-center">
                                        @if ($item->status == 1)
                                            <span class="badge badge-info">Approved</span>
                                        @elseif ($item->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($item->status == 2)
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $total_amount+=$item->amount;
                                @endphp
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right">Totals:</th>
                                <th class="text-center">{{number_format($total_amount)}}</th>
                                <th colspan="3"></th>
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
                var expense_head_id = $('#expense_head_id option:selected').val();
                var status = $('#status option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.expensePrint') !!}',
                    data: {
                        expense_head_id: expense_head_id,
                        from_date: from_date,
                        to_date: to_date,
                        status: status,
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
                var expense_head_id = $('#expense_head_id option:selected').val();
                var status = $('#status option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.expensePdf') !!}',
                    data: {
                        expense_head_id: expense_head_id,
                        from_date: from_date,
                        to_date: to_date,
                        status: status,
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (html) {
                        // console.log(html)
                        var blob = new Blob([html]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "report_expense_" + from_date + "_to_" + to_date + "_" + ".pdf";
                        link.click();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
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
        });
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{--    <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}
@endsection
