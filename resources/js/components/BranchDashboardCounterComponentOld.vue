<template>
    <div class="content">
        <div class="container-fluid">
            <div class="row admin_client_info">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ counter_data.riders }}</h3>
                            <p>Riders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a :href="counter_data.riderUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        <!--<a href="{{ route('branch.riderListByBranch') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3> {{ counter_data.merchants }} </h3>
                            <p>Merchant</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a :href="counter_data.merchantUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        <!--<a href="{{ route('branch.merchantListByBranch') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ counter_data.active_merchants }} </h3>
                            <p>Active Merchant</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a :href="counter_data.merchantUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        <!--<a href="{{ route('branch.merchantListByBranch') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.brancUsers }}</h3>-->
                            <!--<p>Users</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ counter_data.branches }}</h3>
                            <p>Total branch or agents</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>
            </div>

            <div class="row admin_ecourier_parcel_info">
                <div class="col-lg-12 col-md-12">
                    <h3>E-Courier Parcel Information </h3>
                </div>

                <!-- Today Pickup Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupRequest }}</h3>
                                    <p>Today Pickup Request</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('todayPickupRequest').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="todayPickupRequest" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="1">
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalPickupRequest }}</h3>
                                    <p>today  Pickup Request</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('totalPickupRequest').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="totalPickupRequest" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="2">
                                </form>
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupComplete }}</h3>
                                    <p>Today Pickup Done</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('todayPickupComplete').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="todayPickupComplete" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="3">
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupPending }}</h3>
                                    <p>Today Pickup Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('todayPickupPending').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="todayPickupPending" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="4">
                                </form>
                            </div>
                        </div>

                        <!--<div class="col-lg-3 col-6">-->
                            <!--<div class="small-box bg-danger">-->
                                <!--<div class="inner">-->
                                    <!--<h3>{{ counter_data.todayPickupCancel }}</h3>-->
                                    <!--<p>Today Pickup Cancel</p>-->
                                <!--</div>-->
                                <!--<div class="icon">-->
                                    <!--<i class="ion ion-pie-graph"></i>-->
                                <!--</div>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--&lt;!&ndash;<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </div>

                <!-- Parcel Info -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.pendingParcels }}</h3>
                                    <p>Pending Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.newParcels }}</h3>
                                    <p>New Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>


                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalParcelForDelivery }}</h3>
                                    <p>Total Parcel for Delivery</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Todays and total rider run list -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-6 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayRiderRunListParcels }}</h3>
                                    <p>Today's Rider Run List </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-6 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalRiderRunListParcels }}</h3>
                                    <p>Total Rider Run List </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Today Delivery Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryComplete }}</h3>
                                    <p>Today Delivery Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryPending }}</h3>
                                    <p>Today Delivery Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryCancel }}</h3>
                                    <p>Today's Cancel Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Total Delivery Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryComplete }}</h3>
                                    <p>Total Delivery Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryPending }}</h3>
                                    <p>Total Pending Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryCancel }} </h3>
                                    <p>Total Cancel Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Parcel Amount -->
                <div class="col-lg-12 col-md-12">
                    <div class="row amount_area">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ counter_data.ecourierTodayProductValue }} TK</h3>
                                    <p>Today's Product Value</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.ecourierTodayCollectAmount }} TK</h3>
                                    <p>Today's Collection Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.ecourierTotalCollectAmount }} TK</h3>
                                    <p>Total Collection Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Return Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayReturnComplete }}</h3>
                                    <p>Today's Return Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalReturnComplete }}</h3>
                                    <p>Total Return Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalPendingReturn }} </h3>
                                    <p>Total Pending Return Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- For Traditional Parcel Info -->
            <!--<div class="row admin_traditional_parcel_info">-->
                <!--<div class="col-lg-12 col-md-12">-->
                    <!--<h3>Traditional Parcel Information </h3>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalDeliveryParcels }} </h3>-->
                            <!--<p>Total Delivery Parcel</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('branch.bookingParcel.bookingParcelReceiveList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalBookingParcels }} </h3>-->
                            <!--<p>Total Booking Parcels</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('branch.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalCollectAmount }} TK</h3>-->
                            <!--<p>Total Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.todayDeliveryParcels }} </h3>-->
                            <!--<p>Today Delivery Parcel</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('branch.bookingParcel.bookingParcelReceiveList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.todayBookingParcels }} </h3>-->
                            <!--<p>Today Booking Parcels</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('branch.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.paidToAccount }} TK</h3>-->
                            <!--<p>Paid to Account</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('branch.parcel.deliveryBranchTransferGenerate') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalDeliveryCollectionAmount }} TK</h3>-->
                            <!--<p>Delivery Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalBookingParcelsCollectAmount }} TK</h3>-->
                            <!--<p>Booking Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.balanceCollectAmount }} TK</h3>-->
                            <!--<p>Balance Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

            <!--</div>-->



        </div>
    </div>
</template>

<script>
    export default {
        props: ['counters', 'userid'],
        data() {
          return {
              counter_data: this.counters
          }
        },
        mounted() {
            // console.log(this.counters);
            // Echo.private("branchDashboardCounter."+ this.userid)
            //     .listen('BranchDashboardRealTimeCounter', (data) => {

            //         console.log(data.counter_data);
            //         this.counter_data = data.counter_data;
            // });
        }
    }
</script>
