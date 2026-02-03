<template>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-expanded" @click="markNotificationAsRead" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ totalUnreadNotification }}</span>
      </a>
      <div v-if="readUnreadNotification.length > 0" class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header">{{ totalNotification }} Notifications</span>

        <div class="dropdown-body">
          <NotificationItem v-for="punread in readUnreadNotification" v-bind:key="punread.data.parcel_info.id+'_'+punread.data.parcel_info.action_date" :unread="punread"></NotificationItem>
        </div>
        <!--<div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>-->

        <!--<div class="dropdown-divider"></div>-->
        <!--<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>-->
      <!--</div>-->

      <!--<div v-else class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">-->
        <!--<span class="dropdown-item dropdown-header">0 Notifications</span>-->

        <!--<div>-->
            <!--<div class="dropdown-divider"></div>-->
            <!--<a href="#" class="dropdown-item">-->
              <!--Default-->
            <!--</a>-->
        <!--</div>-->

        <!--<div class="dropdown-divider"></div>-->
        <!--<a href="#" class="dropdown-item">-->
          <!--<i class="fas fa-users mr-2"></i> 8 friend requests-->
          <!--<span class="float-right text-muted text-sm">12 hours</span>-->
        <!--</a>-->
        <!--<div class="dropdown-divider"></div>-->
        <!--<a href="#" class="dropdown-item">-->
          <!--<i class="fas fa-file mr-2"></i> 3 new reports-->
          <!--<span class="float-right text-muted text-sm">2 days</span>-->
        <!--</a>-->

        <div class="dropdown-divider"></div>
        <a :href="notifyurl" target="_blank" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>

    </li>
</template>

<script>
    import NotificationItem from './MerchantParcelNotificationItem.vue';
    export default {
        props: ['userid', 'unreads', 'readunreads', 'notifylisturl'],
        components: {NotificationItem},
        data() {
            return {
                unreadNotification: this.unreads,
                readUnreadNotification: this.readunreads,
                totalUnreadNotification: this.unreads.length,
                totalNotification: this.readunreads.length,
                notifyurl: this.notifylisturl
            }
        },
        methods: {
            markNotificationAsRead() {
              if(this.totalUnreadNotification) {
                  axios.get('/merchant/parcel/notification-read');
                  this.totalUnreadNotification = 0;
                  console.log("Succesfully Read");
              }
            },
            showMerchantNotification(notify) {
                var notification = new Notification(notify.parcel_info.parcel_invoice +" is "+ notify.parcel_info.status_name);
            }

        },
        mounted() {
//            console.log(this.readUnreadNotification);
            // Notification.requestPermission().then(function (result) {
            //     console.log(result);
            // });
            // Echo.private("App.Models.Merchant."+this.userid)
            //     .notification((notify) => {
            //         console.log("Notify OK");
            //         let newUnreadNotification = {data: {merchant: notify.merchant, parcel_info:notify.parcel_info}};

            //         this.readUnreadNotification.unshift(newUnreadNotification);
            //         this.totalUnreadNotification++;
            //         this.totalNotification++;

            //         if(!("Notification" in window)) {
            //             alert("This browser does not support desktop notification");
            //         }
            //         else if(Notification.permission === "granted") {
            //             this.showMerchantNotification(notify);
            //         }
            //         else if(Notification.permission !== "denied") {
            //             Notification.requestPermission().then(function (permission) {
            //                 if(permission === "granted") {
            //                     this.showMerchantNotification(notify)
            //                 }
            //             });
            //         }

            // })
        }
    }
</script>

<style scope>
  .dropdown-body {
    height: 300px;
    overflow: auto;
  }
</style>
