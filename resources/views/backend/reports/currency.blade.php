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
        $currency_id =key_exists('currency_id',$filter)? $filter['currency_id']:"";
        $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
        $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");
        $filter_currency="";
        if ($currency_id){
            $filter_currency=\App\Models\Currency::where('id',$currency_id)->first();
        }
    @endphp
    <form action="{{route('report.currency')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
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
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Currency Report</legend>
                <div class="row mx-2">
                    <div class="col-md-12 text-center">
                        <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                        @if($filter_currency)
                            <p><strong>Currency: </strong>{{$filter_currency->name." - ".$filter_currency->code}}</p>
                        @else
                            <p><strong>Currency: </strong> All</p>
                        @endif

                        @if($from_date)
                            <p><strong>From Date: </strong>{{$from_date}}</p>
                        @endif
                        @if($to_date)
                            <p><strong>To Date: </strong>{{$to_date}}</p>
                        @endif
                        @if($total_approved_transaction>0)
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Approved/Completed</legend>
                                <table class="table">
                                    <tbody>
                                    <tr style="background-color: #bacae3;">
                                        <th>Total Transaction:</th>
                                        <td>{{$total_approved_transaction}}</td>
                                    </tr>
                                    <tr style="background-color: #6dbefb;">
                                        <th>Total Amount:</th>
                                        <td>{{$total_approved_amount}} {{$filter_currency->code}}</td>
                                    </tr>
                                    <tr style="background-color: #bbf4cd;">
                                        <th>Total Buying BDT Amount:</th>
                                        <td>{{$total_approved_b_bdt_amount}} BDT</td>
                                    </tr>
                                    <tr style="background-color: #e2a8ea;">
                                        <th>Total Selling BDT Amount:</th>
                                        <td>{{$total_approved_s_bdt_amount}} BDT</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        @endif
                        @if($total_pending_transaction>0)

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Pending</legend>
                                <table class="table">
                                    <tbody>
                                    <tr style="background-color: #bacae3;">
                                        <th>Total Transaction:</th>
                                        <td>{{$total_pending_transaction}}</td>
                                    </tr>
                                    <tr style="background-color: #6dbefb;">
                                        <th>Total Amount:</th>
                                        <td>{{$total_pending_amount}} {{$filter_currency->code}}</td>
                                    </tr>
                                    <tr style="background-color: #bbf4cd;">
                                        <th>Total Buying BDT Amount:</th>
                                        <td>{{$total_pending_b_bdt_amount}} BDT</td>
                                    </tr>
                                    <tr style="background-color: #e2a8ea;">
                                        <th>Total Selling BDT Amount:</th>
                                        <td>{{$total_pending_s_bdt_amount}} BDT</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        @endif
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
                var currency_id = $('#currency_id option:selected').val();
                var transaction_for = $('#transaction_for option:selected').val();
                var transaction_type = $('#transaction_type option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.currencyPrint') !!}',
                    data: {
                        currency_id: currency_id,
                        from_date: from_date,
                        to_date: to_date,
                        transaction_for: transaction_for,
                        transaction_type: transaction_type
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
                var currency_id = $('#currency_id option:selected').val();
                var c_name = $('#currency_id option:selected').attr('c_name');
                var transaction_for = $('#transaction_for option:selected').val();
                var transaction_type = $('#transaction_type option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.currencyPdf') !!}',
                    data: {
                        currency_id: currency_id,
                        from_date: from_date,
                        to_date: to_date,
                        transaction_for: transaction_for,
                        transaction_type: transaction_type
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
