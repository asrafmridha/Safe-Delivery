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
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ counter_data.active_merchants }} </h3>
                            <p>Active Merchant</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a :href="counter_data.merchant_url" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

<!--                        <a :href="counter_data.merchantUrl" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                        <!--<a href="{{ route('branch.merchantListByBranch') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
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
                    <h3>Parcel Information </h3>
                </div>
                <!-- Today Product and Collection Amount -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.todayProductValue }} TK</h3>
                                    <p>Today Product Value</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a :href="counter_data.parcel_url" class="small-box-footer url_link">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.todayCollectionAmount }} TK</h3>
                                    <p>Today Collection Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalProductValue }} TK</h3>
                                    <p>Total Product Value</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!--<a :href="counter_data.parcel_url" class="small-box-footer url_link">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Product and Collection Amount -->
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                       

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ counter_data.totalCollectionAmount }} TK</h3>
                                    <p>Total Collection Amount</p>
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
                                    <h3>{{ counter_data.todayPaidToMerchant }} TK</h3>
                                    <p>Today Paid To Merchant</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
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

                    </div>
                </div>

                <!-- Parcel Amount for merchant and account-->
                <div class="col-lg-12 col-md-12">
                    <div class="row amount_area">
                        

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
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

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.todayTotalIncome }} TK</h3>
                                    <p>Today Total Income</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.totalIncome }} TK</h3>
                                    <p>Total Income Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ counter_data.total_expense }} TK</h3>
                                    <p>Total Expense Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ counter_data.total_expense_month }} TK</h3>
                                    <p>Total Expense Amount(Current Month)</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                         <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ counter_data.total_expense_date }} TK</h3>
                                    <p>Total Expense Amount(Current Date)</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Parcel Amount for merchant and account-->
                <div class="col-lg-12 col-md-12">
                    <div class="row amount_area">
                        
                        

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
