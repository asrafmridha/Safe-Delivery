@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Branch Parcel Payment Report</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Branch Parcel Payments Report</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Branch Parcel Payment Report </h3>

                        <div class="row input-daterange" style="margin-top: 40px">
                            <div class="col-md-3">
                                <label for="branch_id">Branch </label>
                                <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%" >
                                    <option value="" >Select Branch  </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" >{{ $branch->name }} </option>
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
                                <label for="from_date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control"  value=""/>
                            </div>
                            <div class="col-md-3">
                                <label for="to_date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value=""/>
                            </div>
                            <div class="col-md-3" style="margin-top: 20px">
                                <button type="button" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="parcelPaymentReport" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center"> SL </th>
                                    <th width="10%" class="text-center"> Date</th>
                                    <th width="10%" class="text-center"> Consignment </th>
                                    <th width="10%" class="text-center"> Branch </th>
                                    <th width="10%" class="text-center"> Payment Parcel </th>
                                    <th width="10%" class="text-center"> Received Payment Parcel</th>
                                    <th width="10%" class="text-center"> Payment Amount </th>
                                    <th width="10%" class="text-center"> Pending Amount </th>
                                    <th width="10%" class="text-center"> Received Payment Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if(count($parcel_payment_reports) > 0) {
                                        for($i=0; $i<count($parcel_payment_reports); $i++) {
                                            echo html_entity_decode($parcel_payment_reports[$i]);
                                        }
                                    }else{
                                        echo '<tr>
                                                <td colspan="8" class="text-center">No data available here!<td>
                                            </tr>';
                                    }
                                @endphp
                                <tr>
                                    <td colspan="6" class="text-center text-bold">Total</td>
                                    <td class="text-center text-bold">{{ number_format((float) $payment_total_amount, 2, '.', '') }}</td>
                                    <td class="text-center text-bold">{{ number_format((float) $payment_total_pending_amount, 2, '.', '') }}</td>
                                    <td class="text-center text-bold">{{ number_format((float) $payment_total_receive_amount, 2, '.', '') }}</td>
                                </tr>
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
<script>
    window.onload = function(){

        $("#filter").on("click", function () {

            var branch_id    = $("#branch_id").val();
            var from_date    = $("#from_date").val();
            var to_date      = $("#to_date").val();

            if(branch_id != "" || from_date != "" || to_date != "") {
                $.ajax({
                    cache: false,
                    url: "{{ route('admin.account.traditional.branchBookingParcelPaymentReportAjax') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        branch_id:branch_id,
                        from_date:from_date,
                        to_date:to_date
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#parcelPaymentReport tbody").html(response);
                    }
                })
            }else{
                toastr.error("Please filled any one field");
            }
        });

        $("#refresh").on("click", function(){
            $("#branch_id").val("");
            $("#from_date").val("");
            $("#to_date").val("");

            window.location.reload();
        });
    }
  </script>
@endpush

