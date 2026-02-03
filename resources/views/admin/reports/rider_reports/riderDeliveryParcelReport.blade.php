@extends('layouts.admin_layout.admin_layout')

@push('style_css')
    <style>

        .table-responsive > .table-bordered {
            border: 1px solid #dee2e6;
        }

    </style>
@endpush
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rider Delivery Parcel Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Rider Delivery Parcel Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="printArea">
                        <div class="card-header">

                            <div class="row input-daterange" style="margin-top: 10px">
                                <div class="col-md-3">
                                    <label for="rider_id">Rider </label>
                                    <select name="rider_id" id="rider_id" class="form-control select2" style="width: 100%" >
                                        <option value="0" >Select Rider  </option>
                                        @foreach ($riders as $rider)
                                            <option value="{{ $rider->id }}" >{{ $rider->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="col-md-3">--}}
                                {{--<label for="status">Parcel Payment Type </label>--}}
                                {{--<select name="status" id="status" class="form-control select2" style="width: 100%" >--}}
                                {{--<option value="0" >Select Delivery Payment Type </option>--}}
                                {{--<option value="1" >Send Request </option>--}}
                                {{--<option value="2" >Request Accept </option>--}}
                                {{--<option value="3" >Request Cancel </option>--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                <div class="col-md-3">
                                    <label for="from_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"  value="{{$start_date}}"/>
                                </div>
                                <div class="col-md-3">
                                    <label for="from_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"  value="{{$end_date}}"/>
                                </div>

                                {{--<div class="col-md-3">--}}
                                    {{--<label for="to_date">To Date</label>--}}
                                    {{--<input type="date" name="to_date" id="to_date" class="form-control" value=""/>--}}
                                {{--</div>--}}
                                <div class="col-md-3" style="margin-top: 20px">
                                    <button type="button" name="filter" id="filter" class="btn btn-success">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" name="print" id="printBtn" class="btn btn-primary">Print</button>
                                </div>
                            </div>

                        </div>
                        <div class="card-body table-responsive" id="riderDeliveryParcelReport">

                            <div class="report-header" style="margin-top: 10px;">
                                <h3 class="text-center">Rider Delivery Parcel Report </h3>
                                <h5 class="text-center">Date: <b>{{ $start_date }}</b> to <b>{{ $end_date }}</b></h5>
                            </div>
                            <table id="riderWiseReport" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL</th>
                                    <th width="5%" class="text-center"> Name</th>
                                    <th width="15%" class="text-center"> Area </th>
                                    <th width="10%" class="text-center"> Total Parcel </th>
                                    <th width="5%" class="text-center"> Done </th>
                                    <th width="5%" class="text-center"> Pending </th>
                                    <th width="5%" class="text-center"> Cancel</th>
                                    <th width="10%" class="text-center"> Collection Amount</th>
                                    <th width="20%" class="text-center"> Invoice No</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @if(count($report_data) > 0)
                                        @php
                                            $i = 0;
                                            $total_parcel = 0;
                                            $total_done_parcel = 0;
                                            $total_pending_parcel = 0;
                                            $total_cancel_parcel = 0;
                                            $total_collection_amount = 0;
                                        @endphp
                                        @foreach($report_data as $report)
                                            @php
                                                $i++;

                                                $total_parcel += $report->total_parcel;
                                                $total_done_parcel += $report->done_parcel;
                                                $total_pending_parcel += $report->pending_parcel;
                                                $total_cancel_parcel += $report->cancel_parcel;
                                                $total_collection_amount += $report->collection_amount;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td class="text-center">{{ $report->name }}</td>
                                                <td class="text-center">{{ $report->branch_name }}</td>
                                                <td class="text-center">{{ $report->total_parcel }}</td>
                                                <td class="text-center">{{ $report->done_parcel }}</td>
                                                <td class="text-center">{{ $report->pending_parcel }}</td>
                                                <td class="text-center">{{ $report->cancel_parcel }}</td>
                                                <td class="text-center">{{ $report->collection_amount }}</td>
                                                <td class="text-center">{{ $report->parcel_invoices }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-center"> <h5><b>Total</b></h5></td>
                                            <td class="text-center text-bold"> <h5><b>{{ $total_parcel }}</b></h5></td>
                                            <td class="text-center text-bold"> <h5><b>{{ $total_done_parcel }}</b></h5></td>
                                            <td class="text-center text-bold"> <h5><b>{{ $total_pending_parcel }}</b></h5></td>
                                            <td class="text-center text-bold"> <h5><b>{{ $total_cancel_parcel }}</b></h5></td>
                                            <td class="text-center text-bold"> <h5><b>{{ $total_collection_amount }}</b></h5></td>
                                            <td class="text-center text-bold"></td>
                                        </tr>
                                    @endif
                                </tbody>

                            </table>


                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="showResult">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- For Frint -->
    <script src="{{asset("print/print_this.js")}}"></script>
    <script>

        $("#printBtn").on("click", function () {

            $('#printArea').printThis({
                importCSS: false,
                loadCSS: "{{ asset("print/rider_parcel_report_print.css") }}",
                afterPrint: function () {
                    window.close();
                }
            });

        });

    </script>
    <script>
        window.onload = function(){

            $("#filter").on("click", function () {

                var rider_id    = $("#rider_id").val();
                var start_date    = $("#start_date").val();
                var end_date    = $("#end_date").val();

                if(rider_id != "" || (action_date != "")) {
                    $.ajax({
                        cache: false,
                        url: "{{ route('admin.rider.getDeliveryParcelReport') }}",
                        type: "post",
                        data: {
                            _token: "{{ csrf_token() }}",
                            rider_id:rider_id,
                            start_date:start_date,
                            end_date:end_date,
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        success: function (response) {
                            $("#riderDeliveryParcelReport").html(response);
                        }
                    })
                }else{
                    toastr.error("Please filled rider or date field");
                }
            });

            $("#refresh").on("click", function(){
                $("#rider_id").val("");
                $("#action_date").val("");

                window.location.reload();
            });
        }
    </script>
@endpush

