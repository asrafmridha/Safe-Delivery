<?php

use Illuminate\Support\Facades\Route;

Route::get('/warehouse', [App\Http\Controllers\Warehouse\AuthController::class, 'login'])->name('login');
Route::post('/warehouse', [App\Http\Controllers\Warehouse\AuthController::class, 'login_check'])->name('login');

Route::get('/warehouse/forgotPassword', [App\Http\Controllers\Warehouse\AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/warehouse/forgotPassword', [App\Http\Controllers\Warehouse\AuthController::class, 'confirmForgotPassword'])->name('forgotPassword');
Route::get('/warehouse/resetPassword/{token}', [App\Http\Controllers\Warehouse\AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/warehouse/resetPassword', [App\Http\Controllers\Warehouse\AuthController::class, 'confirmResetPassword'])->name('resetPassword');



Route::group(['middleware' => 'warehouse', 'prefix'=>'warehouse/'], function () {

    Route::match(['get', 'post'], '/logout', [App\Http\Controllers\Warehouse\AuthController::class, 'logout'] )->name('logout');
    Route::match(['get', 'post'], '/home', [App\Http\Controllers\Warehouse\HomeController::class, 'home'] )->name('home');


    Route::get('profile', [App\Http\Controllers\Warehouse\HomeController::class, 'profile'] )->name('profile');

    //================ Common Route  ================================
        Route::get('parcel/{parcel}/viewParcel', [App\Http\Controllers\Warehouse\ParcelController::class, 'viewParcel'] )->name('parcel.viewParcel');
    //================ Common Route  ================================

    // Rider & Merchent List By Branch

    Route::get('list/merchantListByBranch', [App\Http\Controllers\Branch\HomeController::class, 'merchantListByBranch'] )->name('merchantListByBranch');
    Route::get('list/riderListByBranch', [App\Http\Controllers\Branch\HomeController::class, 'riderListByBranch'] )->name('riderListByBranch');


    //================ Pickup Parcel  Route  ================================
        Route::get('parcel/pickupParcelList', [App\Http\Controllers\Branch\PickupParcelController::class, 'pickupParcelList'] )->name('parcel.pickupParcelList');
        Route::get('parcel/getPickupParcelList', [App\Http\Controllers\Branch\PickupParcelController::class, 'getPickupParcelList'] )->name('parcel.getPickupParcelList');
        Route::get('parcel/{parcel}/editPickupParcel', [App\Http\Controllers\Branch\PickupParcelController::class, 'editPickupParcel'] )->name('parcel.editPickupParcel');
        Route::patch('parcel/{parcel}/confirmEditPickupParcel', [App\Http\Controllers\Branch\PickupParcelController::class, 'confirmEditPickupParcel'] )->name('parcel.confirmEditPickupParcel');

        //================ Pickup Rider Run Route ================================
            Route::get('parcel/pickupRiderRunList', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunList'] )->name('parcel.pickupRiderRunList');
            Route::get('parcel/getPickupRiderRunList', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'getPickupRiderRunList'] )->name('parcel.getPickupRiderRunList');
            Route::get('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImport'] )->name('parcel.merchantBulkParcelImport');
            Route::post('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportStore'] )->name('parcel.merchantBulkParcelImport');

            Route::get('parcel/{riderRun}/printPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'printPickupRiderRun'] )->name('parcel.printPickupRiderRun');
            Route::get('parcel/{riderRun}/printAllPickupRiderRunParcel', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'printAllPickupRiderRunParcel'] )->name('parcel.printAllPickupRiderRunParcel');

            Route::get('parcel/pickupRiderRunGenerate', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunGenerate'] )->name('parcel.pickupRiderRunGenerate');
            Route::post('parcel/returnPickupRiderRunParcel', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'returnPickupRiderRunParcel'] )->name('parcel.returnPickupRiderRunParcel');
            Route::post('parcel/pickupRiderRunParcelAddCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunParcelAddCart'] )->name('parcel.pickupRiderRunParcelAddCart');
            Route::post('parcel/pickupRiderRunEditParcelAddCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunEditParcelAddCart'] )->name('parcel.pickupRiderRunEditParcelAddCart');

            Route::post('parcel/pickupRiderRunParcelDeleteCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunParcelDeleteCart'] )->name('parcel.pickupRiderRunParcelDeleteCart');
            Route::post('parcel/confirmPickupRiderRunGenerate', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunGenerate'] )->name('parcel.confirmPickupRiderRunGenerate');

            Route::get('parcel/{riderRun}/editPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'editPickupRiderRun'] )->name('parcel.editPickupRiderRun');
            Route::patch('parcel/{riderRun}/confirmPickupRiderRunGenerateEdit', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunGenerateEdit'] )->name('parcel.confirmPickupRiderRunGenerateEdit');

            Route::get('parcel/{riderRun}/viewPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'viewPickupRiderRun'] )->name('parcel.viewPickupRiderRun');
            Route::post('parcel/startPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'startPickupRiderRun'] )->name('parcel.startPickupRiderRun');
            Route::post('parcel/cancelPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'cancelPickupRiderRun'] )->name('parcel.cancelPickupRiderRun');

            Route::get('parcel/{riderRun}/pickupRiderRunReconciliation', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunReconciliation'] )->name('parcel.pickupRiderRunReconciliation');
            Route::post('parcel/{riderRun}/confirmPickupRiderRunReconciliation', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunReconciliation'] )->name('parcel.confirmPickupRiderRunReconciliation');
        //================ Pickup Rider Run Route ================================

        //================ Delivery Branch Transfer ================================
            Route::get('parcel/deliveryBranchTransferList', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferList'] )->name('parcel.deliveryBranchTransferList');
            Route::get('parcel/getDeliveryBranchTransferList', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'getDeliveryBranchTransferList'] )->name('parcel.getDeliveryBranchTransferList');
            Route::get('parcel/{deliveryBranchTransfer}/viewDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'viewDeliveryBranchTransfer'] )->name('parcel.viewDeliveryBranchTransfer');
            Route::post('parcel/cancelDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'cancelDeliveryBranchTransfer'] )->name('parcel.cancelDeliveryBranchTransfer');
            Route::get('parcel/deliveryBranchTransferGenerate', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferGenerate'] )->name('parcel.deliveryBranchTransferGenerate');
            Route::post('parcel/returnDeliveryBranchTransferParcel', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'returnDeliveryBranchTransferParcel'] )->name('parcel.returnDeliveryBranchTransferParcel');
            Route::post('parcel/deliveryBranchTransferParcelAddCart', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferParcelAddCart'] )->name('parcel.deliveryBranchTransferParcelAddCart');
            Route::post('parcel/deliveryBranchTransferParcelDeleteCart', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferParcelDeleteCart'] )->name('parcel.deliveryBranchTransferParcelDeleteCart');
            Route::post('parcel/confirmDeliveryBranchTransferGenerate', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'confirmDeliveryBranchTransferGenerate'] )->name('parcel.confirmDeliveryBranchTransferGenerate');

            Route::get('parcel/{deliveryBranchTransfer}/editDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'editDeliveryBranchTransfer'] )->name('parcel.editDeliveryBranchTransfer');
            Route::patch('parcel/{deliveryBranchTransfer}/confirmDeliveryBranchTransferGenerateEdit', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'confirmDeliveryBranchTransferGenerateEdit'] )->name('parcel.confirmDeliveryBranchTransferGenerateEdit');
        //================ Delivery Branch Transfer ================================

    //================ Pickup Parcel  Route  ================================

    //================ Traditional Parcel Booking  Route  ================================
        Route::get('bookingParcel/getBookingList', [App\Http\Controllers\Warehouse\BookingParcelController::class, 'getBookingParcelList'] )->name('bookingParcel.getBookingList');
        Route::get('bookingParcel/{booking_parcel}/viewBookingParcel', [App\Http\Controllers\Warehouse\BookingParcelController::class, 'viewBookingParcel'] )->name('bookingParcel.viewBookingParcel');
        Route::resource('bookingParcel', App\Http\Controllers\Warehouse\BookingParcelController::class);


        /** Operation Assign Vehicle Controller Route */
        Route::get('operationBookingParcel/assignVehicleList', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'vehicleAssignList'])->name('operationBookingParcel.vehicleAssignList');
        Route::post('operationBookingParcel/getParcelListForVehicleToWareHouseAssign', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'getParcelListForVehicleToWareHouseAssign'])->name('operationBookingParcel.getParcelListForVehicleToWareHouseAssign');
        Route::post('operationBookingParcel/confirmWarehouseAssign', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'confirmWarehouseAssign'])->name('operationBookingParcel.confirmWarehouseAssign');
        Route::post('operationBookingParcel/rejectParcelFromVehicle', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'rejectParcelFromVehicle'])->name('operationBookingParcel.rejectParcelFromVehicle');

        /** After First Warehouse Assign */
        Route::post('operationBookingParcel/getParcelListForVehicleToWarehouseReceive', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'getParcelListForVehicleToWarehouseReceive'])->name('operationBookingParcel.getParcelListForVehicleToWarehouseReceive');
        Route::post('operationBookingParcel/getParcelListForWarehouseToVehicleWarehouseAssign', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'getParcelListForWarehouseToVehicleWarehouseAssign'])->name('operationBookingParcel.getParcelListForWarehouseToVehicleWarehouseAssign');
        Route::post('operationBookingParcel/confirmAssignVehicleOrWarehouse', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'confirmAssignVehicleOrWarehouse'])->name('operationBookingParcel.confirmAssignVehicleOrWarehouse');




        Route::get('operationBookingParcel/bookingParcelOperation', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'bookingParcelOperation'])->name('operationBookingParcel.bookingParcelOperation');
        Route::post('operationBookingParcel/rejectParcelFromWarehouse', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'rejectParcelFromWarehouse'])->name('operationBookingParcel.rejectParcelFromWarehouse');
        Route::post('operationBookingParcel/confirmWarehouseReceived', [App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class, 'confirmWarehouseReceived'])->name('operationBookingParcel.confirmWarehouseReceived');

        Route::resource('operationBookingParcel', \App\Http\Controllers\Warehouse\BookingParcelAssignRejectController::class);
    //================ Traditional Parcel Booking  Route  ================================




});
