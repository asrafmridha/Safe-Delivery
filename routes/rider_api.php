<?php

use Illuminate\Support\Facades\Route;

Route::post('/rider/login', [App\Http\Controllers\API\Rider\AuthController::class, 'login'])->name('login');
// Route::post('/rider/registration', [App\Http\Controllers\API\Rider\AuthController::class, 'registration'])->name('registration');


Route::group(['middleware' => 'jwt', 'prefix'=>'rider/'], function () {
    Route::post('/', [App\Http\Controllers\API\Rider\AuthController::class, 'me']);
    Route::post('refresh', [App\Http\Controllers\API\Rider\AuthController::class, 'refresh']);
    Route::post('logout', [App\Http\Controllers\API\Rider\AuthController::class, 'logout']);
    Route::post('payload', [App\Http\Controllers\API\Rider\AuthController::class, 'payload']);
    Route::post('dashboard', [App\Http\Controllers\API\Rider\HomeController::class, 'dashboard']);

    //================ Rider Pickup Route  ================================
        Route::post('getPickupParcelList', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'getPickupParcelList']);
        Route::get('getAllPickupParcelList', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'getAllPickupParcelList']);
        Route::post('getPickupParcel', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'getPickupParcel']);
        Route::post('parcelPickupRequestAccept', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'parcelPickupRequestAccept']);
        Route::post('parcelPickupRequestReject', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'parcelPickupRequestReject']);
        Route::post('parcelPickupReschedule', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'parcelPickupReschedule']);
        Route::post('parcelPickupComplete', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'parcelPickupComplete']);
        
        
        
         Route::get('pickupRequestList', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'pickupRequestList']);
        Route::post('pickupRequestComplete', [App\Http\Controllers\API\Rider\PickupParcelController::class, 'pickupRequestComplete']);
    //================ Rider Pickup Route  ================================



    //================ Rider Delivery Route ================================
        Route::post('getDeliveryParcelList', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'getDeliveryParcelList']);
        Route::post('getDeliveryParcel', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'getDeliveryParcel']);
        Route::post('parcelDeliveryRequestAccept', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'parcelDeliveryRequestAccept']);
        Route::post('parcelDeliveryRequestReject', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'parcelDeliveryRequestReject']);
        Route::post('returnConfirmParcelCode', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'returnConfirmParcelCode']);
        Route::post('parcelDeliveryOtpSendCustomer', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'parcelDeliveryOtpSendCustomer']);
        Route::post('parcelDeliveryComplete', [App\Http\Controllers\API\Rider\DeliveryParcelController::class, 'parcelDeliveryComplete']);
    //================ Rider Delivery Route ================================


    //================ Rider Return Route  ================================
        Route::post('getReturnParcelList', [App\Http\Controllers\API\Rider\ReturnParcelController::class, 'getReturnParcelList']);
        Route::post('getReturnParcel', [App\Http\Controllers\API\Rider\ReturnParcelController::class, 'getReturnParcel']);
        Route::post('parcelReturnRequestAccept', [App\Http\Controllers\API\Rider\ReturnParcelController::class, 'parcelReturnRequestAccept']);
        Route::post('parcelReturnRequestReject', [App\Http\Controllers\API\Rider\ReturnParcelController::class, 'parcelReturnRequestReject']);
        Route::post('parcelReturnComplete', [App\Http\Controllers\API\Rider\ReturnParcelController::class, 'parcelReturnComplete']);
        //================ Rider Return Route  ================================

    Route::post('collectionParcelList', [App\Http\Controllers\API\Rider\PaymentParcelController::class, 'collectionParcelList']);
    Route::post('paidAmountParcelList', [App\Http\Controllers\API\Rider\PaymentParcelController::class, 'paidAmountParcelList']);


});

