/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import RegisterComponent from './components/MerchantRegisterNotificationComponent.vue';

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// Vue.component('merchant-registration', require('./components/MerchantRegisterNotificationComponent.vue').default);
Vue.component('merchant-registration', RegisterComponent);
Vue.component('merchant-parcel-notification', require('./components/MerchantParcelNotificationComponent.vue').default);

/** For Admin Dashboard */
Vue.component('admin-dashboard-counter', require('./components/AdminDashboardCounterComponent.vue').default);
Vue.component('account-dashboard-counter', require('./components/AccountDashboardCounterComponent.vue').default);

/** For Branch Dashboard */
Vue.component('branch-dashboard-counter', require('./components/BranchDashboardCounterComponent.vue').default);

/** For Merchant Dashboard */
Vue.component('merchant-dashboard-counter', require('./components/MerchantDashboardCounterComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// import NotificationItem from './components/MerchantRegisterNotificationComponent.vue';

// window.onload = function () {
//     var app = new Vue({
//         el: '#app',
//         // components: { RegisterComponent }
//     });
// }

const app =new Vue({
    el: '#app',
    //components: { RegisterComponent }
});
