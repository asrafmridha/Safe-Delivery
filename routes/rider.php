<?php

use Illuminate\Support\Facades\Route;

Route::get('/rider', [App\Http\Controllers\Rider\HomeController::class, 'login'])->name('login');
Route::post('/rider', [App\Http\Controllers\Rider\HomeController::class, 'login_check'])->name('login');


Route::get('/rider/forgotPassword', [App\Http\Controllers\Rider\HomeController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/rider/forgotPassword', [App\Http\Controllers\Rider\HomeController::class, 'confirmForgotPassword'])->name('forgotPassword');
Route::get('/rider/resetPassword/{token}', [App\Http\Controllers\Rider\HomeController::class, 'resetPassword'])->name('resetPassword');
Route::post('/rider/resetPassword', [App\Http\Controllers\Rider\HomeController::class, 'confirmResetPassword'])->name('resetPassword');



Route::group(['middleware' => 'rider', 'prefix'=>'rider/'], function () {
    Route::match(['get', 'post'], '/home', [App\Http\Controllers\Rider\HomeController::class, 'home'] )->name('home');
    Route::match(['get', 'post'], '/logout', [App\Http\Controllers\Rider\HomeController::class, 'logout'] )->name('logout');

    Route::get('profile', [App\Http\Controllers\Rider\HomeController::class, 'profile'] )->name('profile');

    Route::get('parcel/{parcel}/viewParcel', [App\Http\Controllers\Rider\ParcelController::class, 'viewParcel'] )->name('parcel.viewParcel');


    //================ Pickup Parcel ================================
        Route::get('parcel/pickupParcelList', [App\Http\Controllers\Rider\PickupParcelController::class, 'pickupParcelList'] )->name('parcel.pickupParcelList');
        Route::get('parcel/getPickupParcelList', [App\Http\Controllers\Rider\PickupParcelController::class, 'getPickupParcelList'] )->name('parcel.getPickupParcelList');
        Route::post('parcel/parcelPickupRequestAccept', [App\Http\Controllers\Rider\PickupParcelController::class, 'parcelPickupRequestAccept'] )->name('parcel.parcelPickupRequestAccept');
        Route::post('parcel/parcelPickupRequestReject', [App\Http\Controllers\Rider\PickupParcelController::class, 'parcelPickupRequestReject'] )->name('parcel.parcelPickupRequestReject');
        Route::get('parcel/{parcel}/parcelPickupComplete', [App\Http\Controllers\Rider\PickupParcelController::class, 'parcelPickupComplete'] )->name('parcel.parcelPickupComplete');
        Route::post('parcel/confirmParcelPickupComplete', [App\Http\Controllers\Rider\PickupParcelController::class, 'confirmParcelPickupComplete'] )->name('parcel.confirmParcelPickupComplete');
        Route::get('parcel/{parcel}/parcelPickupReschedule', [App\Http\Controllers\Rider\PickupParcelController::class, 'parcelPickupReschedule'] )->name('parcel.parcelPickupReschedule');
        Route::post('parcel/confirmParcelPickupReschedule', [App\Http\Controllers\Rider\PickupParcelController::class, 'confirmParcelPickupReschedule'] )->name('parcel.confirmParcelPickupReschedule');
    //================ Pickup Parcel ================================

    //================ Delivery Parcel ================================
        Route::get('parcel/deliveryParcelList', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'deliveryParcelList'] )->name('parcel.deliveryParcelList');
        Route::get('parcel/getDeliveryParcelList', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'getDeliveryParcelList'] )->name('parcel.getDeliveryParcelList');
        Route::get('parcel/deliveryCompleteParcelList', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'deliveryCompleteParcelList'] )->name('parcel.deliveryCompleteParcelList');
        Route::get('parcel/getDeliveryCompleteParcelList', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'getDeliveryCompleteParcelList'] )->name('parcel.getDeliveryCompleteParcelList');
        Route::post('parcel/parcelDeliveryRequestAccept', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'parcelDeliveryRequestAccept'] )->name('parcel.parcelDeliveryRequestAccept');
        Route::post('parcel/parcelDeliveryRequestReject', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'parcelDeliveryRequestReject'] )->name('parcel.parcelDeliveryRequestReject');
        Route::get('parcel/{parcel}/parcelDeliveryComplete', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'parcelDeliveryComplete'] )->name('parcel.parcelDeliveryComplete');
        Route::post('parcel/returnConfirmParcelCode', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'returnConfirmParcelCode'] )->name('parcel.returnConfirmParcelCode');
        Route::post('parcel/confirmParcelDeliveryComplete', [App\Http\Controllers\Rider\DeliveryParcelController::class, 'confirmParcelDeliveryComplete'] )->name('parcel.confirmParcelDeliveryComplete');
    //================ Delivery Parcel ================================


    //================ Payment Parcel List ================================
        Route::get('payment/collectionParcelList', [App\Http\Controllers\Rider\PaymentParcelController::class, 'collectionParcelList'] )->name('payment.collectionParcelList');
        Route::get('payment/getCollectionParcelList', [App\Http\Controllers\Rider\PaymentParcelController::class, 'getCollectionParcelList'] )->name('payment.getCollectionParcelList');
        Route::get('payment/paidAmountParcelList', [App\Http\Controllers\Rider\PaymentParcelController::class, 'paidAmountParcelList'] )->name('payment.paidAmountParcelList');
        Route::get('payment/getPaidAmountParcelList', [App\Http\Controllers\Rider\PaymentParcelController::class, 'getPaidAmountParcelList'] )->name('payment.getPaidAmountParcelList');
    //================ Payment Parcel List ================================


    //================ Return Parcel ================================
        Route::get('parcel/returnParcelList', [App\Http\Controllers\Rider\ReturnParcelController::class, 'returnParcelList'] )->name('parcel.returnParcelList');
        Route::get('parcel/getReturnParcelList', [App\Http\Controllers\Rider\ReturnParcelController::class, 'getReturnParcelList'] )->name('parcel.getReturnParcelList');
        Route::post('parcel/parcelReturnRequestAccept', [App\Http\Controllers\Rider\ReturnParcelController::class, 'parcelReturnRequestAccept'] )->name('parcel.parcelReturnRequestAccept');
        Route::post('parcel/parcelReturnRequestReject', [App\Http\Controllers\Rider\ReturnParcelController::class, 'parcelReturnRequestReject'] )->name('parcel.parcelReturnRequestReject');
        Route::get('parcel/{parcel}/parcelReturnComplete', [App\Http\Controllers\Rider\ReturnParcelController::class, 'parcelReturnComplete'] )->name('parcel.parcelReturnComplete');
        Route::patch('parcel/{parcel}/confirmParcelReturnComplete', [App\Http\Controllers\Rider\ReturnParcelController::class, 'confirmParcelReturnComplete'] )->name('parcel.confirmParcelReturnComplete');
    //================ Return Parcel ================================



    Route::get('parcel/list', [App\Http\Controllers\Rider\ParcelController::class, 'list'] )->name('parcel.list');
    Route::get('parcel/getParcelList', [App\Http\Controllers\Rider\ParcelController::class, 'getParcelList'] )->name('parcel.getParcelList');




    Route::post('parcel/parcelPickupConfirm', [App\Http\Controllers\Rider\ParcelController::class, 'parcelPickupConfirm'] )->name('parcel.parcelPickupConfirm');

    Route::get('parcel/{parcel}/processParcel', [App\Http\Controllers\Rider\ParcelController::class, 'processParcel'] )->name('parcel.processParcel');

});
