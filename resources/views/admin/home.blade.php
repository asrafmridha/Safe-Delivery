@extends('layouts.admin_layout.admin_layout')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    @php
        $admin_user = auth()->guard('admin')->user();
        $admin_type = $admin_user->type;

        if($admin_type == 3) {
    @endphp
        <account-dashboard-counter :userid="{{ auth()->guard('admin')->user()->id }}" :counters="{{ $counter_data }}"></account-dashboard-counter>
    @php
        }else{
    @endphp
        <admin-dashboard-counter :userid="{{ auth()->guard('admin')->user()->id }}" :counters="{{ $counter_data }}"></admin-dashboard-counter>
    @php
        }
    @endphp


    {{--<div class="content">--}}
        {{--<div class="container-fluid">--}}

            {{--<div class="row admin_client_info">--}}
                {{--<div class="col-lg-3 col-6">--}}
                    {{--<div class="small-box bg-info">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $branches->count() }}</h3>--}}
                            {{--<p>Branches or Agents</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-bag"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.branch.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-3 col-6">--}}
                    {{--<div class="small-box bg-success">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $warehouses->count() }}</h3>--}}
                            {{--<p>Warehouses</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-stats-bars"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.warehouse.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-3 col-6">--}}
                    {{--<div class="small-box bg-warning">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $merchants->count() }}</h3>--}}
                            {{--<p>Merchants</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-person-add"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.merchant.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-3 col-6">--}}
                    {{--<div class="small-box bg-danger">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $riders->count() }}</h3>--}}
                            {{--<p>Riders</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-pie-graph"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.rider.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="row admin_ecourier_parcel_info">--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<h3>E-Courier Parcel Information </h3>--}}
                {{--</div>--}}
                {{--<!-- Today Pickup Parcel -->--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-success">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $todayPickupRequest->count() }}</h3>--}}
                                    {{--<p>Today Pickup Request</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-bag"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-info">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $todayPickupComplete->count() }}</h3>--}}
                                    {{--<p>Today Pickup Done</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-person-add"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-warning">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $todayPickupPending->count() }}</h3>--}}
                                    {{--<p>Today Pickup Pending</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-danger">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $todayPickupCancel->count() }}</h3>--}}
                                    {{--<p>Today Pickup Cancel</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!-- Today Delivery Parcel -->--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-success">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etodayDeliveryParcels->count() }}</h3>--}}
                                    {{--<p>Today Delivery Parcel</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-bag"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-info">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etodayDeliveryComplete->count() }}</h3>--}}
                                    {{--<p>Today Delivery Done</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-person-add"></i>--}}
                                {{--</div>--}}
                                {{--<a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-warning">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etodayDeliveryPending->count() }}</h3>--}}
                                    {{--<p>Today Delivery Pending</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-danger">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etodayDeliveryCancel->count() }}</h3>--}}
                                    {{--<p>Today delivery Cancel</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!-- Total Delivery Parcel -->--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-success">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etotalDeliveryParcels->count() }}</h3>--}}
                                    {{--<p>Total Delivery Parcel</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-bag"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-info">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etotalDeliveryComplete->count() }}</h3>--}}
                                    {{--<p>Total Delivery Done</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-person-add"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-warning">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etotalDeliveryPending->count() }}</h3>--}}
                                    {{--<p>Total Delivery Pending</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-3 col-6">--}}
                            {{--<div class="small-box bg-danger">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $etotalDeliveryCancel->count() }}</h3>--}}
                                    {{--<p>Total Delivery Cancel</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!-- Total Parcel Amount -->--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<div class="row amount_area">--}}
                        {{--<div class="col-lg-4 col-6">--}}
                            {{--<div class="small-box bg-success">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $ecourierTotalCollectAmount.' TK' }}</h3>--}}
                                    {{--<p>Total Collection Amount</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-bag"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-4 col-6">--}}
                            {{--<div class="small-box bg-info">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $ecourierPaidToAccount.' TK' }}</h3>--}}
                                    {{--<p>Paid To Account</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-person-add"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-lg-4 col-6">--}}
                            {{--<div class="small-box bg-warning">--}}
                                {{--<div class="inner">--}}
                                    {{--<h3>{{ $ecourierBalanceCollectAmount . ' TK' }}</h3>--}}
                                    {{--<p>Balance Amount</p>--}}
                                {{--</div>--}}
                                {{--<div class="icon">--}}
                                    {{--<i class="ion ion-pie-graph"></i>--}}
                                {{--</div>--}}
                                {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}


            {{--</div>--}}

            {{--<!-- For Traditional Parcel Info -->--}}
            {{--<div class="row admin_traditional_parcel_info">--}}
                {{--<div class="col-lg-12 col-md-12">--}}
                    {{--<h3>Traditional Parcel Information </h3>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-success">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $todayDeliveryParcels->count() }}</h3>--}}
                            {{--<p>Today Delivery Parcel</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-bag"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-info">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $todayBookingParcels->count() }}</h3>--}}
                            {{--<p>Today Booking Parcels</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-person-add"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-danger">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $todayRejectParcels->count() }}</h3>--}}
                            {{--<p>Today Reject Parcels </p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-pie-graph"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-success">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalDeliveryParcels->count() }}</h3>--}}
                            {{--<p>Total Delivery Parcel</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-bag"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-info">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalBookingParcels->count() }}</h3>--}}
                            {{--<p>Total Booking Parcels</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-person-add"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-lg-4 col-6">--}}
                    {{--<div class="small-box bg-danger">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalRejectParcels->count() }}</h3>--}}
                            {{--<p>Total Reject Parcels</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-pie-graph"></i>--}}
                        {{--</div>--}}
                        {{--<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="row amount_area">--}}
                {{--<div class="col-md-3 col-6">--}}
                    {{--<div class="small-box bg-warning">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalDeliveryCollectionAmount . ' TK'}}</h3>--}}
                            {{--<p>Delivery Collection Amount</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-bag"></i>--}}
                        {{--</div>--}}
                        {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-3 col-6">--}}
                    {{--<div class="small-box bg-warning">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalBookingParcelsCollectAmount . ' TK' }}</h3>--}}
                            {{--<p>Booking Collection Amount</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-person-add"></i>--}}
                        {{--</div>--}}
                        {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-3 col-6">--}}
                    {{--<div class="small-box bg-warning">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $totalCollectAmount . ' TK' }}</h3>--}}
                            {{--<p>Total Collection Amount</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-pie-graph"></i>--}}
                        {{--</div>--}}
                        {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-3 col-6">--}}
                    {{--<div class="small-box bg-warning">--}}
                        {{--<div class="inner">--}}
                            {{--<h3>{{ $accountsTotalBalance . ' TK' }}</h3>--}}
                            {{--<p>Total Accounts Amount</p>--}}
                        {{--</div>--}}
                        {{--<div class="icon">--}}
                            {{--<i class="ion ion-pie-graph"></i>--}}
                        {{--</div>--}}
                        {{--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}


        {{--</div>--}}
    {{--</div>--}}
@endsection

@push("script_js")
    <script>
//        Echo.private("events")
//            .listen('RealTimeMessage', (data) => {
//                console.log(data);
//        });

//        Echo.private('App.Models.Admin.1')
//            .notification((notification) => {
//            console.log(notification.message);
//        });

//        $(document).on("click", ".url_link", function () {
//
//            alert("click this url");
//        })
    </script>
@endpush
