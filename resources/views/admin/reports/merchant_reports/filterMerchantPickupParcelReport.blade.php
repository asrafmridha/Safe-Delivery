@extends('layouts.admin_layout.admin_layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<?php



?>
<div class="content">
    <div class="container-fluid">
        <div class="card" id="printArea">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('admin.merchant.pickup.getParcelReport')}}" method="post">
                        @csrf
                        <div class="row">

                            <div class="col-md-10">
                                <div class="row mb-2">

                                    <div class="col-sm-12 col-md-3">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" />
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <button type="submit" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>

                                <button type="button" name="print" id="printBtn" class="btn btn-primary">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <fieldset>
                        <legend>Report</legend>
                        <table class="table table-style table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 30%; text-align: center;">Merchant </th>
                                    <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                    <th class="text-center" style="width: 30%; text-align: center;">Parcel Quantity</th>
                                    <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                    <th class="text-center" style="width: 30%; text-align: center;">Parcel Collection
                                        Amounts</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                $total_amount = 0;
                                $total_qua = 0;
                                @endphp


                                @foreach ( $merchants as $merchant)

                                @php
                                $merchant_id = $merchant->id;

                                $parcels = \App\Models\Parcel::where('merchant_id', $merchant_id
                                )->whereBetween('pickup_branch_date', [$from_date, $to_date])->get();


                                $total_quantity = \App\Models\Parcel::where('merchant_id', $merchant_id
                                )->whereBetween('pickup_branch_date', [$from_date, $to_date])->get()->count();





                                $total_collect_amo = 0;



                                foreach ( $parcels as $parcel){

                                $total_collect_amo += $parcel->total_collect_amount;


                                }
                                @endphp

                                @php
                                $total_amount += $total_collect_amo;
                                $total_qua += $total_quantity;


                                @endphp



                                @if($total_collect_amo != 0)
                                <tr>
                                    <td class="text-center" style="width: 30%; text-align: center;">
                                        {{ $merchant->company_name }}
                                    </td>
                                    <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                    <td class="text-center" style="width: 30%; text-align: center;">{{$total_quantity}}
                                    </td>
                                    <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                    <td class="text-center" style="width: 30%; text-align: center;">
                                        {{$total_collect_amo}}
                                    </td>
                                </tr>
                                @endif

                                @endforeach






                            </tbody>

                            <tfoot>
                                <tr>
                                    <td class="text-center" style="width: 30%; text-align: center;"> <b>Total </b></td>
                                    <td class="text-center" style="width: 5%; text-align: center;"><b> : </b></td>
                                    <td class="text-center" style="width: 30%; text-align: center;"> <b>
                                            {{ $total_qua }} </b>
                                    </td>
                                    <td class="text-center" style="width: 5%; text-align: center;"> <b> :</td> </b>
                                    <td class="text-center" style="width: 30%; text-align: center;"> <b> Tk
                                            :{{$total_amount}} /= </b>

                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </fieldset>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection

@push('style_css')
<style>
    th,
    td p {
        margin-bottom: 0;
        white-space: nowrap;
    }

    th,
    td .parcel_status {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        margin: 0 auto;
    }

    /*
        div.container {
            width: 80%;
        }
        */
</style>
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">

@endpush
@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


<!-- For Frint -->
<script src="{{asset("print/print_this.js")}}"></script>
<script>
    $("#printBtn").on("click", function() {

        $('#printArea').printThis({
            importCSS: false,
            loadCSS: "{{ asset('print/rider_parcel_report_print.css') }}",
            afterPrint: function() {
                window.close();
            }
        });

    });
</script>
@endpush