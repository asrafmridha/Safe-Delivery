<template>
    <div class="content">
        <div class="container-fluid">
            <div class="row admin_client_info">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3> {{ counter_data.total_parcel }} </h3>
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
                            <h3> {{ counter_data.total_cancel_parcel }} </h3>
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
                            <h3> {{ counter_data.total_waiting_pickup_parcel }} </h3>
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
                            <h3> {{ counter_data.total_waiting_delivery_parcel }} </h3>
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
                            <h3> {{ counter_data.total_delivery_complete_parcel }} </h3>
                            <p>Total Delivery Complete Parcel </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <!--<div class="col-lg-3 col-6">-->
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
                <!--</div>-->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> {{ counter_data.total_return_complete_parcel }} </h3>
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
                            <h3> {{ counter_data.total_pending_collect_amount }} </h3>
                            <p>Total Pending Collect Amount </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> {{ counter_data.total_collect_amount }} </h3>
                            <p>Total Collect Amount </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
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
        mounted() {
            // console.log(this.counters);
            // Echo.private("merchantDashboardCounter."+this.userid)
            //     .listen('MerchantDashboardRealTimeCounter', (data) => {

            //         console.log(data.counter_data);
            //         this.counter_data = data.counter_data;
            // });
        }
    }
</script>
