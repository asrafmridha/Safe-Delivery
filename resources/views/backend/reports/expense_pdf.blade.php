<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
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
<div class="row mx-2">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <fieldset class="scheduler-border">
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
<!-- end -->
</body>
</html>
