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
<!--                        <a :href="counter_data.riderUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
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
<!--                        <a :href="counter_data.merchantUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
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
<!--                        <a :href="counter_data.merchantUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
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
                    <h3>Parcel Information Cheek</h3>
                </div>

                <!-- 2nd Row -->
                <!-- Today Pickup Parcel -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
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
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.totalPickupRequest }}</h3>
                                    <p>Total Pickup Request</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('totalPickupRequest').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="totalPickupRequest" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="4">
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPickupComplete }}</h3>
                                    <p>Today Pickup Done</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.filterUrl" class="small-box-footer" onclick="event.preventDefault();
                                                     document.getElementById('todayPickupDone').submit();">More info <i class="fas fa-arrow-circle-right"></i></a>
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <form :action="counter_data.filterUrl" method="post" id="todayPickupDone" style="display: none;">
                                    <input type="hidden" name="_token" :value="counter_data.token">
                                    <input type="text" name="filter_type" value="2">
                                </form>
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalPickupComplete }}</h3>
                                    <p>Total Pickup Done</p>
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

<!--                    </div>-->
<!--                </div>-->

                <!-- 3rd Row -->
                <!-- Parcel Info -->
<!--                <div class="col-lg-12 col-md-12">-->
<!--                    <div class="row">-->
<!--                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.todayNewParcels }}</h3>
                                    <p>Today New Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                &lt;!&ndash;<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;
                                &lt;!&ndash;<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>&ndash;&gt;
                            </div>
                        </div>-->

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.previousPendingParcels }}</h3>
                                    <p>Previous Pending Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ counter_data.todayParcelForDelivery }}</h3>
                                    <p>Today Parcel for Delivery</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.pickupParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>


                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
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

<!--                    </div>-->
<!--                </div>-->

                <!-- 4th Row -->
                <!-- Today Delivery Parcel -->
<!--                <div class="col-lg-12 col-md-12">-->
<!--                    <div class="row">-->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etodayDeliveryComplete }}</h3>
                                    <p>Today Delivery Complete</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
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

                        <div class="col-lg-3 col-6">
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

<!--                    </div>-->
<!--                </div>-->

                <!-- Total Delivery Parcel -->
<!--                <div class="col-lg-12 col-md-12">-->
<!--                    <div class="row">-->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalDeliveryComplete }}</h3>
                                    <p>Total Delivery Complete</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
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

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalReturnParcel }} </h3>
                                    <p>Total Return Parcel</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.etotalPendingReturnParcels }} </h3>
                                    <p>Total Waiting for Return</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!--<a href="{{ route('branch.parcel.deliveryParcelList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
<!--                    </div>-->
<!--                </div>-->

                <!-- Total Parcel Amount -->
<!--                <div class="col-lg-12 col-md-12">-->
<!--                    <div class="row amount_area">-->
                        <div class="col-lg-3 col-6">
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

                        <div class="col-lg-3 col-6">
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

                        <div class="col-lg-3 col-6">
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

<!--                    </div>-->
<!--                </div>-->

                <!-- Parcel Amount for merchant and account-->
<!--                <div class="col-lg-12 col-md-12">-->
<!--                    <div class="row amount_area">-->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayPaidToMerchant }} TK</h3>
                                    <p>Today Paid To Merchant</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
<!--                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalPaidToMerchant }} TK</h3>
                                    <p>Total Paid To Merchant</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a :href="counter_data.merchant_statement_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.totalPendingAmount }} TK</h3>
                                    <p>Total Pending Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a :href="counter_data.merchant_statement_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Parcel Amount for merchant and account-->
                <div class="col-lg-12 col-md-12">
                    <div class="row amount_area">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayTotalIncome }} TK</h3>
                                    <p>Today Total Income</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
<!--                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ counter_data.thisMonthIncome }} TK</h3>
                                    <p>This Month Total Income</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
<!--                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <!--<div class="col-lg-4 col-6">-->
                        <!--<div class="small-box bg-info">-->
                        <!--<div class="inner">-->
                        <!--<h3>{{ 0 }}</h3>-->
                        <!--<p>Total Paid To Merchant</p>-->
                        <!--</div>-->
                        <!--<div class="icon">-->
                        <!--<i class="ion ion-person-add"></i>-->
                        <!--</div>-->
                        <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--</div>-->
                        <!--</div>-->

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.totalIncome }} TK</h3>
                                    <p>Total Income Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
<!--                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
                    </div>
                </div>

            </div>

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
            },

            searchBTN(e){
                e.preventDefault();
                axios.get('/vue_js_test')
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
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
