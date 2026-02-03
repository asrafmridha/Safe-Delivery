@extends('layouts.branch_layout.branch_layout')

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
          <h1 class="m-0 text-dark">Parcel Filter List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Parcel Filter List</li>
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
                    {{--<div class="card-header">--}}

                        {{--<div class="row input-daterange" style="margin-top: 10px">--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label for="merchant_id">Merchant </label>--}}
                                {{--<select name="merchant_id" id="merchant_id" class="form-control select2" style="width: 100%" >--}}
                                    {{--<option value="0" >Select Merchant  </option>--}}
                                    {{--@foreach ($merchants as $merchant)--}}
                                        {{--<option value="{{ $merchant->id }}" >{{ $merchant->company_name }} </option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label for="status">Parcel Payment Type </label>--}}
                                {{--<select name="status" id="status" class="form-control select2" style="width: 100%" >--}}
                                    {{--<option value="0" >Select Delivery Payment Type </option>--}}
                                    {{--<option value="1" >Send Request </option>--}}
                                    {{--<option value="2" >Request Accept </option>--}}
                                    {{--<option value="3" >Request Cancel </option>--}}
                                {{--</select>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label for="from_date">From Date</label>--}}
                                {{--<input type="date" name="from_date" id="from_date" class="form-control"  value=""/>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3">--}}
                                {{--<label for="to_date">To Date</label>--}}
                                {{--<input type="date" name="to_date" id="to_date" class="form-control" value=""/>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-3" style="margin-top: 20px">--}}
                                {{--<button type="button" name="filter" id="filter" class="btn btn-success">--}}
                                    {{--<i class="fas fa-search-plus"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" name="refresh" id="refresh" class="btn btn-info">--}}
                                    {{--<i class="fas fa-sync-alt"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" name="print" id="printBtn" class="btn btn-primary">Print</button>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    <div class="card-body table-responsive" id="merchantParcelReport">

                        <div class="report-header" style="margin-top: 10px;">
                            <h3 class="text-center">Parcel Filter List </h3>
                            @php
                                if($filter_type == 1) {
                                    echo '<h5 class="text-center">Today Pickup Request</h5>';
                                }elseif($filter_type == 2) {
                                    echo '<h5 class="text-center">Total Pickup Request</h5>';
                                }elseif($filter_type == 3) {
                                    echo '<h5 class="text-center">Total Pickup Done</h5>';
                                }elseif($filter_type == 4) {
                                    echo '<h5 class="text-center">Today Pickup Pending</h5>';
                                }else{
                                    echo '';
                                }
                            @endphp
                            {{--<h5 class="text-center">From <b>{{ $from_date }}</b> to <b>{{ $to_date }}</b></h5>--}}
                        </div>
                        <table id="merchantWiseReport" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5%" class="text-center"> SL</th>
                                <th width="5%" class="text-center"> Merchant ID</th>
                                <th width="15%" class="text-center"> Merchant Company </th>
                                <th width="15%" class="text-center"> Address </th>
                                <th width="15%" class="text-center"> Phone </th>
                                <th width="5%" class="text-center"> Parcel Quantity</th>
                            </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $total_parcel = 0;
                                    if(count($parcels_data) > 0) {
                                        $i = 0;
                                        foreach ($parcels_data as $parcel) {
                                            $i++;

                                            $total_parcel   += $parcel->count_parcel;
                                ?>
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td class="text-center">{{ $parcel->m_id }}</td>
                                                <td class="text-left">{{ $parcel->company_name }}</td>
                                                <td class="text-center">{{ $parcel->address }}</td>
                                                <td class="text-center">{{ $parcel->contact_number }}</td>
                                                <td class="text-center">{{ $parcel->count_parcel }}</td>
                                            </tr>
                                <?php
                                        }

                                        echo '<tr>
                                                <td colspan="5" class="text-center"><h5><b>Total Parcel</b></h5></td>
                                                <td class="text-center"><h5><b>'.$total_parcel.'</b></h5></td>
                                              </tr>';
                                    }else{
                                    echo '<tr>
                                            <td colspan="6" class="text-center">No data available here!</td>
                                          </tr>';
                                    }

                                ?>
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
{{--<script src="{{asset("print/print_this.js")}}"></script>--}}
{{--<script>--}}

    {{--$("#printBtn").on("click", function () {--}}

        {{--$('#printArea').printThis({--}}
            {{--importCSS: false,--}}
            {{--loadCSS: "{{ asset("print/merchant_parcel_report_print.css") }}",--}}
            {{--afterPrint: function () {--}}
                {{--window.close();--}}
            {{--}--}}
        {{--});--}}

    {{--});--}}
{{--</script>--}}

<script>
    window.onload = function(){

        $("#filter").on("click", function () {

            var merchant_id    = $("#merchant_id").val();
            var from_date    = $("#from_date").val();
            var to_date      = $("#to_date").val();

            if(merchant_id != "" || (from_date != "" && to_date != "")) {
                $.ajax({
                    cache: false,
                    url: "{{ route('admin.merchant.getParcelReport') }}",
                    type: "post",
                    data: {
                        _token: "{{ csrf_token() }}",
                        merchant_id:merchant_id,
                        from_date:from_date,
                        to_date:to_date
                    },
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function (response) {
                        $("#merchantParcelReport").html(response);
                    }
                })
            }else{
                toastr.error("Please filled merchant or both date field");
            }
        });

        $("#refresh").on("click", function(){
            $("#merchant_id").val("");
            $("#from_date").val("");
            $("#to_date").val("");

            window.location.reload();
        });
    }
  </script>
@endpush

