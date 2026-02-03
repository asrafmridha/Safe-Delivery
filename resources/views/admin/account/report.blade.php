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

    //    $filter_type = (array_key_exists('filter_type', $_GET)) ? $_GET['filter_type'] : 0;
    //    $from_date = (array_key_exists('from_date', $_GET)) ? $_GET['from_date'] : '';
    //    $to_date = (array_key_exists('to_date', $_GET)) ? $_GET['to_date'] : '';

    ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('admin.report')}}" method="post">
                        @csrf
                        <div class="row">

                            <div class="col-md-10">
                                <div class="row mb-2">
                                    <div class="col-sm-12 col-md-3">
                                        <label for="branch_id">Branch</label>
                                        <select name="branch_id" id="branch_id"
                                                class="form-control select2" style="width: 100%" required>
                                            <option value="0">All Branch</option>
                                            @foreach ($branches as $branch)
                                                <option
                                                    value="{{ $branch->id }}" {{$branch_id==$branch->id?'selected':''}}>{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="merchant_id"> Merchant</label>
                                        <select name="merchant_id" id="merchant_id" class="form-control select2"
                                                style="width: 100%" required>
                                            <option value="0">All Merchant</option>
                                            @foreach ($merchants as $merchant)
                                                <option
                                                    value="{{ $merchant->id }}" {{$merchant_id==$merchant->id?'selected':''}}>{{ $merchant->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="from_date">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                               value="{{ $from_date }}"/>
                                    </div>
                                    <div class="col-sm-12 col-md-3">
                                        <label for="to_date">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                               value="{{ $to_date }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <button type="submit" name="filter" id="filter" class="btn btn-success">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <!--                                <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                                                    <i class="fas fa-sync-alt"></i>
                                                                </button>
                                                                <button type="button" name="print" id="print" class="btn btn-primary">
                                                                    <i class="fas fa-print"></i>
                                                                </button>-->
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
                                <th class="text-center" style="width: 30%; text-align: center;">Title</th>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <th class="text-center" style="width: 30%; text-align: center;">Parcel Quantity</th>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <th class="text-center" style="width: 30%; text-align: center;">Parcel Collection Amounts</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--                            <tr>--}}
                            {{--                                <td class="text-right" style="width: 40%">Total Parcel</td>--}}
                            {{--                                <td class="text-center" style="width: 10%"> :</td>--}}
                            {{--                                <td class="text-left" style="width: 50%">{{$total_parcel}}</td>--}}
                            {{--                            </tr>--}}
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Pickup</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_pickup}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_pickup_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Assigned For Delivery</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_delivery_assigned_parcel}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_delivery_assigned_parcel_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Delivered</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_delivered}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_delivered_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Full Delivered</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_full_delivered}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_full_delivered_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Partial Delivered</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_partial_delivered}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_partial_delivered_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Waiting For Delivery</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_waiting_for_delivery}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_waiting_for_delivery_amounts}}</td>
                            </tr>
{{--                            <tr>--}}
{{--                                <td class="text-center" style="width: 40%">Total Delivery Rescheduled</td>--}}
{{--                                <td class="text-center" style="width: 5%"> :</td>--}}
{{--                                <td class="text-left" style="width: 50%">{{$total_delivery_rescheduled}}</td>--}}
{{--                            </tr>--}}
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Delivery Returned</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_delivery_returned}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_delivery_returned_amounts}}</td>
                            </tr>

                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Returned</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_return}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_return_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Merchant Cancel</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_merchant_cancel}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_merchant_cancel_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Merchant Pickup Request</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_merchant_pickup_request}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_merchant_pickup_request_amounts}}</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 30%; text-align: center;">Total Parcel Waiting For Pickup</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_waiting_for_pickup}}</td>
                                <td class="text-center" style="width: 5%; text-align: center;"> :</td>
                                <td class="text-center" style="width: 30%; text-align: center;">{{$total_parcel_waiting_for_pickup_amounts}}</td>
                            </tr>


                            </tbody>

                        </table>
                    </fieldset>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>
        th, td p {
            margin-bottom: 0;
            white-space: nowrap;
        }

        th, td .parcel_status {
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
@endpush
