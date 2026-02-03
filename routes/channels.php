<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('events', function () {
    return true;
},['guards' => ['web', 'admin', 'auth']]);

Broadcast::channel('adminDashboardCounter', function ($admin) {
    return true;
},['guards' => ['web', 'admin', 'auth']]);

Broadcast::channel('branchDashboardCounter.{id}', function ($branch, $id) {
    return (int) $branch->id === (int) $id;
},['guards' => ['web', 'admin', 'branch', 'auth']]);

Broadcast::channel('merchantDashboardCounter.{id}', function ($merchant, $id) {
    return (int) $merchant->id === (int) $id;
},['guards' => ['web', 'admin', 'merchant', 'auth']]);

Broadcast::channel('App.Models.Admin.{id}', function ($admin, $id) {
    return (int) $admin->id === (int) $id;
},['guards' => ['web', 'admin', 'auth']]);

Broadcast::channel('App.Models.Merchant.{id}', function ($merchant, $id) {
    return (int) $merchant->id === (int) $id;
//    return true;
},['guards' => ['web', 'admin', 'merchant', 'auth']]);
