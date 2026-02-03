@extends('layouts.merchant_layout.merchant_layout')
@push('style_css')
   <style>
    #newsbar {
        height: 40px;
        overflow: hidden;
        position: relative;
        background: #ccc;
        margin: 20px 0;
    }

    .news-item {
        line-height: 38px;
        display: inline-block;
    }

    .clickableDiv {
        display: block;
        text-decoration: none;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        overflow: hidden;

    }
    
    .small-box {
    border-radius: 1.5rem;
    }
</style>
@endpush
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div id="newsbar">
                        <marquee onMouseOver="stop()" onMouseOut="start()">
                            @if($news)
                                <h3 class="news-item"><a href="#" class="view-news-modal" data-toggle="modal" data-target="#viewNewsModal" details="{{ $news->short_details }}">{{ $news->title }}</a></h3>
                            @else
                                <h3 class="news-item">Don't have any news</h3>
                            @endif
                        </marquee>
                    </div>
                </div>
            </div>

{{--            <div class="row mb-4">--}}
{{--                <div class="col-lg-3 col-6">--}}
{{--                    <a class="btn btn-warning btn-block" href="{{ route('merchant.parcel.parcelPickupRequest') }}">--}}
{{--                        Pickup Request--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 col-6" @if($total_pending_payment <= 0) style="pointer-events:  none;" @endif>--}}
{{--                    <a class="btn btn-success btn-block" href="{{ route('merchant.parcel.parcelPaymentRequest') }}">--}}
{{--                        Payment Request--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 col-6">--}}
{{--                    <a class="btn btn-info btn-block" href="{{ route('merchant.parcel.parcelReturnRequest') }}">--}}
{{--                        Return Request--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 col-6">--}}
{{--                    <button type="button" class="btn btn-primary btn-block" href="#">--}}
{{--                        Balance ( {{ number_format($total_pending_payment,2, '.', '') }})--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}


              <div class="row admin_client_info justify-content-center">
            <div class="col-lg-3 col-6 text-right">
                <button type="button" class="btn btn-primary btn-block" href="#">
                    Balance ( {{ number_format($total_pending_payment,2, '.', '') }})
                </button>
            </div>
            </div>
             <div class="content" style="margin-top: 20px;"></div>
        

            <div class="row admin_client_info" style="justify-content: center;" >

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3> {{ $counter_data['today_total_parcel'] }} </h3>
                            <p>Today Total Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> {{ $counter_data['today_total_cancel_parcel'] }} </h3>
                            <p>Today Cancel Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> {{ $counter_data['today_total_waiting_pickup_parcel'] }} </h3>
                            <p>Today Waiting Pickup Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> {{ $counter_data['today_total_waiting_delivery_parcel'] }} </h3>
                            <p>Today Waiting Delivery Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ $counter_data['today_total_delivery_complete_parcel'] }} </h3>
                            <p>Today Delivery Complete Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3> {{ $counter_data['total_parcel'] }} </h3>
                            <p>Total Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> {{ $counter_data['total_cancel_parcel'] }} </h3>
                            <p>Total Cancel Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> {{ $counter_data['total_waiting_pickup_parcel'] }} </h3>
                            <p>Total Waiting Pickup Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> {{ $counter_data['total_waiting_delivery_parcel'] }} </h3>
                            <p>Total Waiting Delivery Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ $counter_data['total_delivery_complete_parcel'] }} </h3>
                            <p>Total Delivery Complete Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                {{-- <!--<div class="col-lg-3 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3> {{ counter_data.total_partial_delivery_complete }} </h3>-->
                            <!--<p>Total Partial Delivery Complete </p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-3 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3> {{ counter_data.total_pending_delivery }} </h3>-->
                            <!--<p>Total Pending Delivery </p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-3 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3> {{ counter_data.total_delivery_parcel }} </h3>-->
                            <!--<p>Total Delivery Parcel </p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-3 col-6">-->
                    <!--<div class="small-box bg-danger">-->
                        <!--<div class="inner">-->
                            <!--<h3> {{ counter_data.total_return_parcel }} </h3>-->
                            <!--<p>Total Return Parcel </p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>--> --}}


                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> {{ $counter_data['total_return_complete_parcel'] }} </h3>
                            <p>Total Return Complete Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
{{--                            <h3> {{ number_format($counter_data['total_pending_collect_amount'],2) }} </h3>--}}
                            <h3> {{ number_format($total_pending_payment,2, '.', '') }} </h3>
                            <p>Payment in Process</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <!--@if($total_pending_payment > 0)-->
                        <!--    <a href="{{ route('merchant.parcel.parcelPaymentRequest') }}" class="small-box-footer">Payment Request<i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--@endif-->
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> {{ number_format($counter_data['total_collect_amount'],2) }} </h3>
                            <p>Total Collect Amount </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ number_format($counter_data['total_collect_amount'],2) }} </h3>
                            <p>Total Paid Amount </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>


        <div class="modal fade" id="viewNewsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 id="news_title" class="modal-title">View Notice Or News Details</h4>
                        <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="showResult">

                    </div>
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_js')
    <script>
        $(document).ready(function () {

            $(".view-news-modal").on("click", function () {

                var title = $(this).text();
                var details = $(this).attr('details');

                $("#news_title").html(title);
                $("#showResult").html(details);
            });
        })
//        Echo.private("App.Models.Merchant.5")
//            .notification((notify) => {
//
//            console.log(notify);
//
//        });
    </script>
@endpush
