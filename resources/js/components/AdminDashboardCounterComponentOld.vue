<template>
    <div class="content">
        <div class="container-fluid">

            <div class="row admin_client_info">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ counter_data.branches }}</h3>
                            <p>Branches or Agents</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a :href="counter_data.branch_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        <!--<a href="{{ route('admin.branch.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ counter_data.warehouses }}</h3>
                            <p>Warehouses</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a :href="counter_data.warehouse_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        <!--<a href="{{ route('admin.warehouse.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ counter_data.merchants }}</h3>
                            <p>Merchants</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <!--<a href="{{ route('admin.merchant.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <a :href="counter_data.merchant_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ counter_data.riders }}</h3>
                            <p>Riders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <!--<a href="{{ route('admin.rider.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <a :href="counter_data.rider_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupRequest }}</h3>
                                    <p>Today Pickup Request</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer url_link" onclick="event.preventDefault();
                                document.getElementById('todayPickupRequest').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <form :action="counter_data.parcel_url" method="get" id="todayPickupRequest" style="display: none;">
                                    <!--<input type="hidden" name="_token" :value="counter_data.token">-->
                                    <input type="text" name="filter_type" value="1">
                                    <input type="text" name="from_date" :value="currentDate()">
                                    <input type="text" name="to_date" :value="currentDate()">
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupComplete }}</h3>
                                    <p>Today Pickup Done</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupPending }}</h3>
                                    <p>Today Pickup Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                            <!--</div>-->
                        <!--</div>-->

                    </div>
                </div>

                <!-- Today Delivery Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryParcels }}</h3>
                                    <p>Today Delivery Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryComplete }}</h3>
                                    <p>Today Delivery Done</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryPending }}</h3>
                                    <p>Today Delivery Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryCancel }}</h3>
                                    <p>Today delivery Cancel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Delivery Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryParcels }}</h3>
                                    <p>Total Delivery Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryComplete }}</h3>
                                    <p>Total Delivery Done</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryPending }}</h3>
                                    <p>Total Delivery Pending</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryCancel }}</h3>
                                    <p>Total Delivery Cancel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.parcel_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Parcel Amount -->
                <div class="col-lg-12 col-md-12">
                    <div class="row amount_area">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.ecourierTotalCollectAmount }}</h3>
                                    <p>Total Collection Amount</p>
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
                                    <h3>{{ counter_data.ecourierPaidToAccount }}</h3>
                                    <p>Paid To Account</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.ecourierBalanceCollectAmount }}</h3>
                                    <p>Balance Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
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
                            <!--<h3>{{ counter_data.todayDeliveryParcels }}</h3>-->
                            <!--<p>Today Delivery Parcel</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.todayBookingParcels }}</h3>-->
                            <!--<p>Today Booking Parcels</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-danger">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.todayRejectParcels }}</h3>-->
                            <!--<p>Today Reject Parcels </p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-success">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalDeliveryParcels }}</h3>-->
                            <!--<p>Total Delivery Parcel</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalBookingParcels }}</h3>-->
                            <!--<p>Total Booking Parcels</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-lg-4 col-6">-->
                    <!--<div class="small-box bg-danger">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalRejectParcels }}</h3>-->
                            <!--<p>Total Reject Parcels</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--<a href="" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--&lt;!&ndash;<a href="{{ route('admin.bookingParcel.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;-->
                    <!--</div>-->
                <!--</div>-->

            <!--</div>-->

            <!--<div class="row amount_area">-->
                <!--<div class="col-md-3 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalDeliveryCollectionAmount }}</h3>-->
                            <!--<p>Delivery Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-bag"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-md-3 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalBookingParcelsCollectAmount }}</h3>-->
                            <!--<p>Booking Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-md-3 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.totalCollectAmount }}</h3>-->
                            <!--<p>Total Collection Amount</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                            <!--<i class="ion ion-pie-graph"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--</div>-->
                <!--</div>-->

                <!--<div class="col-md-3 col-6">-->
                    <!--<div class="small-box bg-warning">-->
                        <!--<div class="inner">-->
                            <!--<h3>{{ counter_data.accountsTotalBalance }}</h3>-->
                            <!--<p>Total Accounts Amount</p>-->
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
        methods: {
            currentDate() {
//                var currentDate = new Date();
//                alert(currentDate);
                var currentDateWithFormat = new Date().toJSON().slice(0,10);
//                var currentDateWithFormat = new Date().toJSON().slice(0,10).replace(/-/g,'/');
                return currentDateWithFormat;
            }
        },
        mounted() {
            // console.log(this.counters);
            // Echo.private("adminDashboardCounter")
            //     .listen('AdminDashboardRealTimeCounter', (data) => {

            //         console.log(data.counter_data);
            //         this.counter_data = data.counter_data;
            // });
        }
    }
</script>
