<?php

use Illuminate\Support\Facades\Route;

Route::get('/branch', [App\Http\Controllers\Branch\AuthController::class, 'login'])->name('login');
Route::post('/branch', [App\Http\Controllers\Branch\AuthController::class, 'login_check'])->name('login');

Route::get('/branch/forgotPassword', [App\Http\Controllers\Branch\AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/branch/forgotPassword', [App\Http\Controllers\Branch\AuthController::class, 'confirmForgotPassword'])->name('forgotPassword');
Route::get('/branch/resetPassword/{token}', [App\Http\Controllers\Branch\AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/branch/resetPassword', [App\Http\Controllers\Branch\AuthController::class, 'confirmResetPassword'])->name('resetPassword');

Route::group(['middleware' => 'branch', 'prefix' => 'branch/'], function () {
    
    
    

// ================================================== new codes For Pickup request starts ================================================
   
     Route::get('parcel/parcelPickupRequestList', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'parcelPickupRequestList'] )->name('parcel.parcelPickupRequestList');//this
    Route::get('parcel/parcelReturnRequestList', [App\Http\Controllers\Branch\ParcelReturnRequestController::class, 'parcelReturnRequestList'] )->name('parcel.parcelReturnRequestList');//this
    Route::get('parcel/getParcelPickupRequestList', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'getParcelPickupRequestList'] )->name('parcel.getParcelPickupRequestList');
    Route::get('parcel/viewParcelPickupRequest/{parcelPickupRequest}', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'viewParcelPickupRequest'] )->name('parcel.viewParcelPickupRequest');
    Route::post('parcel/acceptPickupRequestParcel', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'acceptPickupRequestParcel'] )->name('parcel.acceptPickupRequestParcel');
    Route::post('parcel/rejectPickupRequestParcel', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'rejectPickupRequestParcel'] )->name('parcel.rejectPickupRequestParcel');
    Route::get('parcel/{parcelPickupRequest}/assignRiderPickupRequest', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'assignRiderPickupRequest'] )->name('parcel.assignRiderPickupRequest');
    Route::get('parcel/{parcelPickupRequest}/completePickupRequest', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'completePickupRequest'] )->name('parcel.completePickupRequest');
    Route::get('parcel/getParcelReturnRequestList', [App\Http\Controllers\Branch\ParcelReturnRequestController::class, 'getParcelReturnRequestList'] )->name('parcel.getParcelReturnRequestList');
    Route::get('parcel/viewParcelReturnRequest/{parcelReturnRequest}', [App\Http\Controllers\Branch\ParcelReturnRequestController::class, 'viewParcelReturnRequest'] )->name('parcel.viewParcelReturnRequest');
    Route::post('parcel/acceptReturnRequestParcel', [App\Http\Controllers\Branch\ParcelReturnRequestController::class, 'acceptReturnRequestParcel'] )->name('parcel.acceptReturnRequestParcel');
    Route::post('parcel/rejectReturnRequestParcel', [App\Http\Controllers\Branch\ParcelReturnRequestController::class, 'rejectReturnRequestParcel'] )->name('parcel.rejectReturnRequestParcel');
    Route::post('parcel/confirmPickupRequestAssignRider', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'confirmPickupRequestAssignRider'] )->name('parcel.confirmPickupRequestAssignRider');
    Route::post('parcel/confirmCompletePickupRequest', [App\Http\Controllers\Branch\ParcelPickupRequestController::class, 'confirmCompletePickupRequest'] )->name('parcel.confirmCompletePickupRequest');
    

   
// ====================== =========================== new codes  For Pickup request ends ====================================================




    /** Parcel Filter Route */
    Route::post('parcel/parcel-filter-list', [App\Http\Controllers\Branch\ParcelFilterController::class, 'filterParcelList'])->name('parcel.filterList');

    /** End Parcel Filter Route */

    Route::match(['get', 'post'], '/logout', [App\Http\Controllers\Branch\AuthController::class, 'logout'])->name('logout');
    Route::match(['get', 'post'], '/home', [App\Http\Controllers\Branch\HomeController::class, 'home'])->name('home');
    Route::get('profile', [App\Http\Controllers\Branch\HomeController::class, 'profile'])->name('profile');

    //================ Common Route  ================================
    Route::get('parcel/{parcel}/viewParcel', [App\Http\Controllers\Branch\ParcelController::class, 'viewParcel'])->name('parcel.viewParcel');
    //================ Common Route  ================================

    // Rider & Merchent List By Branch

    Route::get('list/merchantListByBranch', [App\Http\Controllers\Branch\HomeController::class, 'merchantListByBranch'])->name('merchantListByBranch');
    Route::get('list/merchantListByBranch/print', [App\Http\Controllers\Branch\HomeController::class, 'printMerchantListByBranch'])->name('printMerchantListByBranch');
    Route::get('list/riderListByBranch', [App\Http\Controllers\Branch\HomeController::class, 'riderListByBranch'])->name('riderListByBranch');
    Route::get('list/riderListByBranch/print', [App\Http\Controllers\Branch\HomeController::class, 'printRiderListByBranch'])->name('printRiderListByBranch');

    //================ Parcel Add Route ============================

    //For getting customer Info
    Route::get('customer/info', [App\Http\Controllers\Branch\ParcelController::class, 'customerInfo'])->name('customer.info');
    //For getting customer Info

    Route::get('parcel/add', [App\Http\Controllers\Branch\ParcelController::class, 'add'])->name('parcel.add');
    Route::post('parcel/store', [App\Http\Controllers\Branch\ParcelController::class, 'store'])->name('parcel.store');

    Route::post('parcel/getMercentInfo', [App\Http\Controllers\Branch\ParcelController::class, 'getMerchantInfo'])->name('getMerchantInfo');
    //================ Parcel Add Route ============================

    //================ Parcel List Route ============================
    Route::get('parcel/allParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'allParcelList'])->name('parcel.allParcelList');
    Route::get('parcel/getAllParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'getAllParcelList'])->name('parcel.getAllParcelList');
    Route::get('parcel/printAllParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'printAllParcelList'])->name('parcel.printAllParcelList');
    Route::post('parcel/excelAllParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'excelAllParcelList'])->name('parcel.excelAllParcelList');
    Route::get('parcel/{parcel}/editParcel', [App\Http\Controllers\Branch\ParcelController::class, 'editParcel'])->name('parcel.editParcel');
    Route::patch('parcel/{parcel}/confirmEditParcel', [App\Http\Controllers\Branch\ParcelController::class, 'confirmEditParcel'])->name('parcel.confirmEditParcel');

    Route::get('parcel/allRiderParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'allRiderParcelList'])->name('parcel.allRiderParcelList');
    Route::get('parcel/getAllRiderParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'getAllRiderParcelList'])->name('parcel.getAllRiderParcelList');
    Route::get('parcel/printAllRiderParcelList', [App\Http\Controllers\Branch\ParcelController::class, 'printAllRiderParcelList'])->name('parcel.printAllRiderParcelList');
    //================ Parcel List Route ============================

    //================ Pickup Parcel  Route  ================================
    Route::get('parcel/pickupParcelList', [App\Http\Controllers\Branch\PickupParcelController::class, 'pickupParcelList'])->name('parcel.pickupParcelList');
    Route::get('parcel/getPickupParcelList', [App\Http\Controllers\Branch\PickupParcelController::class, 'getPickupParcelList'])->name('parcel.getPickupParcelList');
    Route::get('parcel/printPickupParcelList', [App\Http\Controllers\Branch\PickupParcelController::class, 'printPickupParcelList'])->name('parcel.printPickupParcelList');
    Route::get('parcel/{parcel}/editPickupParcel', [App\Http\Controllers\Branch\PickupParcelController::class, 'editPickupParcel'])->name('parcel.editPickupParcel');
    Route::patch('parcel/{parcel}/confirmEditPickupParcel', [App\Http\Controllers\Branch\PickupParcelController::class, 'confirmEditPickupParcel'])->name('parcel.confirmEditPickupParcel');
    Route::delete('parcel/delete', [App\Http\Controllers\Branch\PickupParcelController::class, 'delete'])->name('parcel.delete');

    //================ Pickup Rider Run Route ================================
    Route::get('parcel/pickupRiderRunList', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunList'])->name('parcel.pickupRiderRunList');
    Route::get('parcel/getPickupRiderRunList', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'getPickupRiderRunList'])->name('parcel.getPickupRiderRunList');
    Route::get('parcel/printPickupRiderRunList', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'printPickupRiderRunList'])->name('parcel.printPickupRiderRunList');




   //  Branch Bulk Parcel Import Old System

  //  Route::get('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImport'])->name('parcel.merchantBulkParcelImport');
   // Route::post('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportStore'])->name('parcel.merchantBulkParcelImport');



     //Apu ----!! Branch Bulk Parcel Import New System !!----
    Route::get('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImport'])->name('parcel.merchantBulkParcelImport');
    Route::post('parcel/merchantBulkParcelImport', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportStore']);
    Route::post('parcel/merchantBulkParcelImportEntry', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportEntry'])->name('parcel.merchantBulkParcelImportEntry');
    Route::get('parcel/merchantBulkParcelImport/check', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportCheck'])->name('parcel.merchantBulkParcelImport.check');
    Route::get('parcel/merchantBulkParcelImport/reset', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'merchantBulkParcelImportReset'])->name('parcel.merchantBulkParcelImport.reset');

    //Apu ----!! Branch Bulk Parcel Import New System End !!----

    Route::get('parcel/{riderRun}/printPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'printPickupRiderRun'])->name('parcel.printPickupRiderRun');
    Route::get('parcel/{riderRun}/printAllPickupRiderRunParcel', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'printAllPickupRiderRunParcel'])->name('parcel.printAllPickupRiderRunParcel');

    Route::get('parcel/pickupRiderRunGenerate', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunGenerate'])->name('parcel.pickupRiderRunGenerate');
    Route::post('parcel/returnPickupRiderRunParcel', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'returnPickupRiderRunParcel'])->name('parcel.returnPickupRiderRunParcel');
    Route::post('parcel/pickupRiderRunParcelAddCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunParcelAddCart'])->name('parcel.pickupRiderRunParcelAddCart');
    Route::post('parcel/pickupRiderRunEditParcelAddCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunEditParcelAddCart'])->name('parcel.pickupRiderRunEditParcelAddCart');

    Route::post('parcel/pickupRiderRunParcelDeleteCart', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunParcelDeleteCart'])->name('parcel.pickupRiderRunParcelDeleteCart');
    Route::post('parcel/confirmPickupRiderRunGenerate', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunGenerate'])->name('parcel.confirmPickupRiderRunGenerate');

    Route::get('parcel/{riderRun}/editPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'editPickupRiderRun'])->name('parcel.editPickupRiderRun');
    Route::patch('parcel/{riderRun}/confirmPickupRiderRunGenerateEdit', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunGenerateEdit'])->name('parcel.confirmPickupRiderRunGenerateEdit');

    Route::get('parcel/{riderRun}/viewPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'viewPickupRiderRun'])->name('parcel.viewPickupRiderRun');
    Route::post('parcel/startPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'startPickupRiderRun'])->name('parcel.startPickupRiderRun');
    Route::post('parcel/cancelPickupRiderRun', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'cancelPickupRiderRun'])->name('parcel.cancelPickupRiderRun');

    Route::get('parcel/{riderRun}/pickupRiderRunReconciliation', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'pickupRiderRunReconciliation'])->name('parcel.pickupRiderRunReconciliation');
    Route::post('parcel/{riderRun}/confirmPickupRiderRunReconciliation', [App\Http\Controllers\Branch\PickupRiderRunParcelController::class, 'confirmPickupRiderRunReconciliation'])->name('parcel.confirmPickupRiderRunReconciliation');
    //================ Pickup Rider Run Route ================================

    //================ Delivery Branch Transfer ================================
    Route::get('parcel/deliveryBranchTransferList', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferList'])->name('parcel.deliveryBranchTransferList');
    Route::get('parcel/getDeliveryBranchTransferList', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'getDeliveryBranchTransferList'])->name('parcel.getDeliveryBranchTransferList');
    Route::get('parcel/printDeliveryBranchTransferList', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'printDeliveryBranchTransferList'])->name('parcel.printDeliveryBranchTransferList');
    Route::get('parcel/{deliveryBranchTransfer}/viewDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'viewDeliveryBranchTransfer'])->name('parcel.viewDeliveryBranchTransfer');
    Route::get('parcel/{deliveryBranchTransfer}/printDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'printDeliveryBranchTransfer'])->name('parcel.printDeliveryBranchTransfer');
    Route::post('parcel/cancelDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'cancelDeliveryBranchTransfer'])->name('parcel.cancelDeliveryBranchTransfer');
    Route::get('parcel/deliveryBranchTransferGenerate', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferGenerate'])->name('parcel.deliveryBranchTransferGenerate');
    Route::post('parcel/returnDeliveryBranchTransferParcel', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'returnDeliveryBranchTransferParcel'])->name('parcel.returnDeliveryBranchTransferParcel');
    Route::post('parcel/deliveryBranchTransferParcelAddCart', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferParcelAddCart'])->name('parcel.deliveryBranchTransferParcelAddCart');
    Route::post('parcel/deliveryBranchTransferParcelDeleteCart', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'deliveryBranchTransferParcelDeleteCart'])->name('parcel.deliveryBranchTransferParcelDeleteCart');
    Route::post('parcel/confirmDeliveryBranchTransferGenerate', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'confirmDeliveryBranchTransferGenerate'])->name('parcel.confirmDeliveryBranchTransferGenerate');

    Route::get('parcel/{deliveryBranchTransfer}/editDeliveryBranchTransfer', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'editDeliveryBranchTransfer'])->name('parcel.editDeliveryBranchTransfer');
    Route::patch('parcel/{deliveryBranchTransfer}/confirmDeliveryBranchTransferGenerateEdit', [App\Http\Controllers\Branch\DeliveryBranchTransferParcelController::class, 'confirmDeliveryBranchTransferGenerateEdit'])->name('parcel.confirmDeliveryBranchTransferGenerateEdit');
    //================ Delivery Branch Transfer ================================

    //================ Pickup Parcel  Route  ================================

    //================ Delivery  Parcel  Route  ================================
    Route::get('parcel/deliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'deliveryParcelList'])->name('parcel.deliveryParcelList');
    Route::get('parcel/getDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'getDeliveryParcelList'])->name('parcel.getDeliveryParcelList');
    Route::get('parcel/printDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'printDeliveryParcelList'])->name('parcel.printDeliveryParcelList');
    Route::get('parcel/completeDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'completeDeliveryParcelList'])->name('parcel.completeDeliveryParcelList');
    Route::get('parcel/getCompleteDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'getCompleteDeliveryParcelList'])->name('parcel.getCompleteDeliveryParcelList');
    Route::get('parcel/printCompleteDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'printCompleteDeliveryParcelList'])->name('parcel.printCompleteDeliveryParcelList');
    Route::get('parcel/rescheduleDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'rescheduleDeliveryParcelList'])->name('parcel.rescheduleDeliveryParcelList');
    Route::get('parcel/getRescheduleDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'getRescheduleDeliveryParcelList'])->name('parcel.getRescheduleDeliveryParcelList');
    Route::get('parcel/printRescheduleDeliveryParcelList', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'printRescheduleDeliveryParcelList'])->name('parcel.printRescheduleDeliveryParcelList');

    Route::get('parcel/{parcel}/editDeliveryParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'editDeliveryParcel'])->name('parcel.editDeliveryParcel');
    Route::patch('parcel/{parcel}/confirmEditDeliveryParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'confirmEditDeliveryParcel'])->name('parcel.confirmEditDeliveryParcel');

    Route::get('parcel/{parcel}/editDeliveryCompleteParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'editDeliveryCompleteParcel'])->name('parcel.editDeliveryCompleteParcel');
    Route::patch('parcel/{parcel}/confirmEditDeliveryCompleteParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'confirmEditDeliveryCompleteParcel'])->name('parcel.confirmEditDeliveryCompleteParcel');

    Route::get('parcel/{parcel}/editRescheduleParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'editRescheduleParcel'])->name('parcel.editRescheduleParcel');
    Route::patch('parcel/{parcel}/confirmEditRescheduleParcel', [App\Http\Controllers\Branch\DeliveryParcelController::class, 'confirmEditRescheduleParcel'])->name('parcel.confirmEditRescheduleParcel');

    //================ Delivery Rider Run ================================
    Route::get('parcel/deliveryRiderRunGenerate', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunGenerate'])->name('parcel.deliveryRiderRunGenerate');
    Route::post('parcel/returnDeliveryRiderRunParcel', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'returnDeliveryRiderRunParcel'])->name('parcel.returnDeliveryRiderRunParcel');
    Route::post('parcel/deliveryRiderRunParcelAddCart', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunParcelAddCart'])->name('parcel.deliveryRiderRunParcelAddCart');
    Route::post('parcel/deliveryRiderEditRunParcelAddCart', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderEditRunParcelAddCart'])->name('parcel.deliveryRiderEditRunParcelAddCart');
    Route::post('parcel/deliveryRiderRunParcelDeleteCart', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunParcelDeleteCart'])->name('parcel.deliveryRiderRunParcelDeleteCart');
    Route::post('parcel/confirmDeliveryRiderRunGenerate', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'confirmDeliveryRiderRunGenerate'])->name('parcel.confirmDeliveryRiderRunGenerate');

    Route::get('parcel/{riderRun}/printDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'printDeliveryRiderRun'])->name('parcel.printDeliveryRiderRun');

    Route::get('parcel/{riderRun}/deliveryRiderRunGenerateEdit', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunGenerateEdit'])->name('parcel.deliveryRiderRunGenerateEdit');
    Route::patch('parcel/{riderRun}/confirmDeliveryRiderRunGenerateEdit', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'confirmDeliveryRiderRunGenerateEdit'])->name('parcel.confirmDeliveryRiderRunGenerateEdit');

    Route::get('parcel/deliveryRiderRunList', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunList'])->name('parcel.deliveryRiderRunList');
    Route::get('parcel/getDeliveryRiderRunList', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'getDeliveryRiderRunList'])->name('parcel.getDeliveryRiderRunList');
    Route::get('parcel/printDeliveryRiderRunList', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'printDeliveryRiderRunList'])->name('parcel.printDeliveryRiderRunList');
    Route::get('parcel/{riderRun}/viewDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'viewDeliveryRiderRun'])->name('parcel.viewDeliveryRiderRun');
    Route::post('parcel/startDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'startDeliveryRiderRun'])->name('parcel.startDeliveryRiderRun');
    Route::post('parcel/cancelDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'cancelDeliveryRiderRun'])->name('parcel.cancelDeliveryRiderRun');

    Route::get('parcel/{riderRun}/deliveryRiderRunReconciliation', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'deliveryRiderRunReconciliation'])->name('parcel.deliveryRiderRunReconciliation');
    Route::post('parcel/confirmDeliveryRiderRunReconciliation', [App\Http\Controllers\Branch\DeliveryRiderRunParcelController::class, 'confirmDeliveryRiderRunReconciliation'])->name('parcel.confirmDeliveryRiderRunReconciliation');
    //================ Delivery Rider Run ================================


    Route::get('parcel/pathaoOrderGenerate', [App\Http\Controllers\Branch\PathaoController::class, 'pathaoOrderGenerate'])->name('parcel.pathaoOrderGenerate');
    Route::post('parcel/confirmPathaoOrderGenerate', [App\Http\Controllers\Branch\PathaoController::class, 'confirmPathaoOrderGenerate'])->name('parcel.confirmPathaoOrderGenerate');


    //================ Received Branch Transfer ================================
    Route::get('parcel/receivedBranchTransferList', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'receivedBranchTransferList'])->name('parcel.receivedBranchTransferList');
    Route::get('parcel/getReceivedBranchTransferList', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'getReceivedBranchTransferList'])->name('parcel.getReceivedBranchTransferList');
    Route::get('parcel/printReceivedBranchTransferList', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'printReceivedBranchTransferList'])->name('parcel.printReceivedBranchTransferList');
    Route::get('parcel/{deliveryBranchTransfer}/viewReceivedBranchTransfer', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'viewReceivedBranchTransfer'])->name('parcel.viewReceivedBranchTransfer');
    Route::get('parcel/{deliveryBranchTransfer}/printReceivedBranchTransfer', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'printReceivedBranchTransfer'])->name('parcel.printReceivedBranchTransfer');
    Route::get('parcel/{deliveryBranchTransfer}/receivedBranchTransferReceived', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'receivedBranchTransferReceived'])->name('parcel.receivedBranchTransferReceived');
    Route::patch('parcel/{deliveryBranchTransfer}/confirmReceivedBranchTransferReceived', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'confirmReceivedBranchTransferReceived'])->name('parcel.confirmReceivedBranchTransferReceived');
    Route::get('parcel/{deliveryBranchTransfer}/receivedBranchTransferReject', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'receivedBranchTransferReject'])->name('parcel.receivedBranchTransferReject');
    Route::patch('parcel/{deliveryBranchTransfer}/confirmReceivedBranchTransferReject', [App\Http\Controllers\Branch\ReceivedBranchTransferParcelController::class, 'confirmReceivedBranchTransferReject'])->name('parcel.confirmReceivedBranchTransferReject');

    //================ Received Branch Transfer ================================

    //================ Delivery Payment ================================
    Route::get('parcel/deliveryPaymentGenerate', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryPaymentGenerate'])->name('parcel.deliveryPaymentGenerate');
    Route::post('parcel/returnDeliveryPaymentParcel', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'returnDeliveryPaymentParcel'])->name('parcel.returnDeliveryPaymentParcel');
    Route::post('parcel/deliveryPaymentParcelAddCart', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryPaymentParcelAddCart'])->name('parcel.deliveryPaymentParcelAddCart');
    Route::post('parcel/deliveryPaymentParcelDeleteCart', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryPaymentParcelDeleteCart'])->name('parcel.deliveryPaymentParcelDeleteCart');
    Route::post('parcel/confirmDeliveryPaymentGenerate', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'confirmDeliveryPaymentGenerate'])->name('parcel.confirmDeliveryPaymentGenerate');
    Route::get('parcel/{parcelDeliveryPayment}/deliveryPaymentGenerateEdit', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryPaymentGenerateEdit'])->name('parcel.deliveryPaymentGenerateEdit');
    Route::patch('parcel/{parcelDeliveryPayment}/confirmDeliveryPaymentGenerateEdit', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'confirmDeliveryPaymentGenerateEdit'])->name('parcel.confirmDeliveryPaymentGenerateEdit');
    Route::get('parcel/{parcelDeliveryPayment}/viewDeliveryPayment', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'viewDeliveryPayment'])->name('parcel.viewDeliveryPayment');
    Route::get('parcel/{parcelDeliveryPayment}/printDeliveryPayment', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'printDeliveryPayment'])->name('parcel.printDeliveryPayment');

    Route::get('parcel/deliveryPaymentList', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryPaymentList'])->name('parcel.deliveryPaymentList');
    Route::get('parcel/getDeliveryPaymentList', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'getDeliveryPaymentList'])->name('parcel.getDeliveryPaymentList');
    Route::get('parcel/printDeliveryPaymentList', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'printDeliveryPaymentList'])->name('parcel.printDeliveryPaymentList');
    // Route::get('parcel/{riderRun}/viewDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'viewDeliveryRiderRun'] )->name('parcel.viewDeliveryRiderRun');
    // Route::post('parcel/startDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'startDeliveryRiderRun'] )->name('parcel.startDeliveryRiderRun');
    // Route::post('parcel/cancelDeliveryRiderRun', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'cancelDeliveryRiderRun'] )->name('parcel.cancelDeliveryRiderRun');

    // Route::get('parcel/{riderRun}/deliveryRiderRunReconciliation', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'deliveryRiderRunReconciliation'] )->name('parcel.deliveryRiderRunReconciliation');
    // Route::post('parcel/confirmDeliveryRiderRunReconciliation', [App\Http\Controllers\Branch\DeliveryPaymentParcelController::class, 'confirmDeliveryRiderRunReconciliation'] )->name('parcel.confirmDeliveryRiderRunReconciliation');
    //================ Delivery Payment ================================
    //================ Delivery  Parcel  Route  ================================

    //================ Return  Parcel  Route  ================================
    Route::get('parcel/returnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'returnParcelList'])->name('parcel.returnParcelList');
    Route::get('parcel/getReturnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'getReturnParcelList'])->name('parcel.getReturnParcelList');
    Route::get('parcel/printReturnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'printReturnParcelList'])->name('parcel.printReturnParcelList');

    Route::get('parcel/{parcel}/editReturnParcel', [App\Http\Controllers\Branch\ReturnParcelController::class, 'editReturnParcel'])->name('parcel.editReturnParcel');
    Route::patch('parcel/{parcel}/confirmEditReturnParcel', [App\Http\Controllers\Branch\ReturnParcelController::class, 'confirmEditReturnParcel'])->name('parcel.confirmEditReturnParcel');

    Route::get('parcel/completeReturnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'completeReturnParcelList'])->name('parcel.completeReturnParcelList');
    Route::get('parcel/getCompleteReturnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'getCompleteReturnParcelList'])->name('parcel.getCompleteReturnParcelList');
    Route::get('parcel/printCompleteReturnParcelList', [App\Http\Controllers\Branch\ReturnParcelController::class, 'printCompleteReturnParcelList'])->name('parcel.printCompleteReturnParcelList');

    Route::get('parcel/{parcel}/editReturnCompleteParcel', [App\Http\Controllers\Branch\ReturnParcelController::class, 'editReturnCompleteParcel'])->name('parcel.editReturnCompleteParcel');
    Route::patch('parcel/{parcel}/confirmEditReturnCompleteParcel', [App\Http\Controllers\Branch\ReturnParcelController::class, 'confirmEditReturnCompleteParcel'])->name('parcel.confirmEditReturnCompleteParcel');

    //================ Return Rider Run ================================
    Route::get('parcel/returnRiderRunGenerate', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderRunGenerate'])->name('parcel.returnRiderRunGenerate');
    Route::post('parcel/returnReturnRiderRunParcel', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnReturnRiderRunParcel'])->name('parcel.returnReturnRiderRunParcel');
    Route::post('parcel/returnRiderRunParcelAddCart', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderRunParcelAddCart'])->name('parcel.returnRiderRunParcelAddCart');
    Route::post('parcel/returnRiderEditRunParcelAddCart', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderEditRunParcelAddCart'])->name('parcel.returnRiderEditRunParcelAddCart');
    Route::post('parcel/returnRiderRunParcelDeleteCart', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderRunParcelDeleteCart'])->name('parcel.returnRiderRunParcelDeleteCart');
    Route::post('parcel/confirmReturnRiderRunGenerate', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'confirmReturnRiderRunGenerate'])->name('parcel.confirmReturnRiderRunGenerate');
    Route::get('parcel/{riderRun}/printReturnRiderRun', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'printReturnRiderRun'])->name('parcel.printReturnRiderRun');
    Route::get('parcel/{riderRun}/viewReturnRiderRun', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'viewReturnRiderRun'])->name('parcel.viewReturnRiderRun');

    Route::get('parcel/{riderRun}/editReturnRiderRun', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'editReturnRiderRun'])->name('parcel.editReturnRiderRun');
    Route::patch('parcel/{riderRun}/confirmReturnRiderRunGenerateEdit', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'confirmReturnRiderRunGenerateEdit'])->name('parcel.confirmReturnRiderRunGenerateEdit');

    Route::get('parcel/returnRiderRunList', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderRunList'])->name('parcel.returnRiderRunList');
    Route::get('parcel/getReturnRiderRunList', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'getReturnRiderRunList'])->name('parcel.getReturnRiderRunList');
    Route::get('parcel/printReturnRiderRunList', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'printReturnRiderRunList'])->name('parcel.printReturnRiderRunList');
    Route::post('parcel/startReturnRiderRun', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'startReturnRiderRun'])->name('parcel.startReturnRiderRun');
    Route::post('parcel/cancelReturnRiderRun', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'cancelReturnRiderRun'])->name('parcel.cancelReturnRiderRun');

    Route::get('parcel/{riderRun}/returnRiderRunReconciliation', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'returnRiderRunReconciliation'])->name('parcel.returnRiderRunReconciliation');
    Route::patch('parcel/{riderRun}/confirmReturnRiderRunReconciliation', [App\Http\Controllers\Branch\ReturnRiderRunParcelController::class, 'confirmReturnRiderRunReconciliation'])->name('parcel.confirmReturnRiderRunReconciliation');
    //================ Return Rider Run ================================

    //================ Return Branch Transfer ================================
    Route::get('parcel/returnBranchTransferList', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferList'])->name('parcel.returnBranchTransferList');
    Route::get('parcel/getReturnBranchTransferList', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'getReturnBranchTransferList'])->name('parcel.getReturnBranchTransferList');
    Route::get('parcel/printReturnBranchTransferList', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'printReturnBranchTransferList'])->name('parcel.printReturnBranchTransferList');
    Route::get('parcel/{returnBranchTransfer}/viewReturnBranchTransfer', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'viewReturnBranchTransfer'])->name('parcel.viewReturnBranchTransfer');
    Route::get('parcel/{returnBranchTransfer}/printReturnBranchTransfer', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'printReturnBranchTransfer'])->name('parcel.printReturnBranchTransfer');
    Route::post('parcel/cancelReturnBranchTransfer', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'cancelReturnBranchTransfer'])->name('parcel.cancelReturnBranchTransfer');

    Route::get('parcel/returnBranchTransferGenerate', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferGenerate'])->name('parcel.returnBranchTransferGenerate');
    Route::post('parcel/returnReturnBranchTransferParcel', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnReturnBranchTransferParcel'])->name('parcel.returnReturnBranchTransferParcel');
    Route::post('parcel/returnBranchTransferParcelClearCart', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferParcelClearCart'])->name('parcel.returnBranchTransferParcelClearCart');
    Route::post('parcel/returnBranchTransferParcelAddCart', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferParcelAddCart'])->name('parcel.returnBranchTransferParcelAddCart');
    Route::post('parcel/returnBranchTransferParcelDeleteCart', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferParcelDeleteCart'])->name('parcel.returnBranchTransferParcelDeleteCart');
    Route::post('parcel/confirmReturnBranchTransferGenerate', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'confirmReturnBranchTransferGenerate'])->name('parcel.confirmReturnBranchTransferGenerate');
    Route::get('parcel/{returnBranchTransfer}/returnBranchTransferGenerateEdit', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'returnBranchTransferGenerateEdit'])->name('parcel.returnBranchTransferGenerateEdit');
    Route::patch('parcel/{returnBranchTransfer}/confirmReturnBranchTransferGenerateEdit', [App\Http\Controllers\Branch\ReturnBranchTransferParcelController::class, 'confirmReturnBranchTransferGenerateEdit'])->name('parcel.confirmReturnBranchTransferGenerateEdit');
    //================ Return Branch Transfer ================================

    //================ Received Return Branch Transfer ================================
    Route::get('parcel/receivedReturnBranchTransferList', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'receivedReturnBranchTransferList'])->name('parcel.receivedReturnBranchTransferList');
    Route::get('parcel/getReceivedReturnBranchTransferList', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'getReceivedReturnBranchTransferList'])->name('parcel.getReceivedReturnBranchTransferList');
    Route::get('parcel/printReceivedReturnBranchTransferList', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'printReceivedReturnBranchTransferList'])->name('parcel.printReceivedReturnBranchTransferList');
    Route::get('parcel/{returnBranchTransfer}/viewReceivedReturnBranchTransfer', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'viewReceivedReturnBranchTransfer'])->name('parcel.viewReceivedReturnBranchTransfer');

    Route::get('parcel/{returnBranchTransfer}/receivedReturnBranchTransferReceived', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'receivedReturnBranchTransferReceived'])->name('parcel.receivedReturnBranchTransferReceived');
    Route::PATCH('parcel/{returnBranchTransfer}/confirmReceivedReturnBranchTransferReceived', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'confirmReceivedReturnBranchTransferReceived'])->name('parcel.confirmReceivedReturnBranchTransferReceived');
    Route::get('parcel/{returnBranchTransfer}/receivedReturnBranchTransferReject', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'receivedReturnBranchTransferReject'])->name('parcel.receivedReturnBranchTransferReject');
    Route::PATCH('parcel/{returnBranchTransfer}/confirmReceivedReturnBranchTransferReject', [App\Http\Controllers\Branch\ReceivedReturnBranchTransferParcelController::class, 'confirmReceivedReturnBranchTransferReject'])->name('parcel.confirmReceivedReturnBranchTransferReject');
    //================ Received Return Branch Transfer ================================
    //================ Return  Parcel  Route  ================================

    Route::get('orderTracking', [App\Http\Controllers\Branch\HomeController::class, 'orderTracking'])->name('orderTracking');
    Route::post('returnOrderTrackingResult', [App\Http\Controllers\Branch\HomeController::class, 'returnOrderTrackingResult'])->name('returnOrderTrackingResult');
    Route::get('coverageArea', [App\Http\Controllers\Branch\HomeController::class, 'coverageArea'])->name('coverageArea');
    Route::get('getCoverageAreas', [App\Http\Controllers\Branch\HomeController::class, 'getCoverageAreas'])->name('getCoverageAreas');
    Route::get('serviceCharge', [App\Http\Controllers\Branch\HomeController::class, 'serviceCharge'])->name('serviceCharge');
    Route::get('getServiceCharges', [App\Http\Controllers\Branch\HomeController::class, 'getServiceCharges'])->name('getServiceCharges');

    /** Vehicle */
    Route::get('vehicle/getVehicles', [App\Http\Controllers\Branch\VehicleController::class, 'getVehicles'])->name('vehicle.getVehicles');
    Route::delete('vehicle/delete', [App\Http\Controllers\Branch\VehicleController::class, 'delete'])->name('vehicle.delete');
    Route::post('vehicle/updateStatus', [App\Http\Controllers\Branch\VehicleController::class, 'updateStatus'])->name('vehicle.updateStatus');
    Route::resource('vehicle', App\Http\Controllers\Branch\VehicleController::class);

    /** Warehouse */
    Route::get('warehouse/getWarehouses', [App\Http\Controllers\Branch\WarehouseController::class, 'getWarehouses'])->name('warehouse.getWarehouses');
    Route::post('warehouse/updateStatus', [App\Http\Controllers\Branch\WarehouseController::class, 'updateStatus'])->name('warehouse.updateStatus');
    Route::delete('warehouse/delete', [App\Http\Controllers\Branch\WarehouseController::class, 'delete'])->name('warehouse.delete');
    Route::resource('warehouse', \App\Http\Controllers\Branch\WarehouseController::class);

    Route::get('itemCategory/getItemCategories', [App\Http\Controllers\Branch\ItemCategoryController::class, 'getItemCategories'])->name('itemCategory.getItemCategories');
    Route::delete('itemCategory/delete', [App\Http\Controllers\Branch\ItemCategoryController::class, 'delete'])->name('itemCategory.delete');
    Route::post('itemCategory/updateStatus', [App\Http\Controllers\Branch\ItemCategoryController::class, 'updateStatus'])->name('itemCategory.updateStatus');
    Route::resource('itemCategory', App\Http\Controllers\Branch\ItemCategoryController::class);

    /** Item Route list */
    Route::get('item/getItem', [App\Http\Controllers\Branch\ItemController::class, 'getItem'])->name('item.getItem');
    Route::post('item/updateStatus', [App\Http\Controllers\Branch\ItemController::class, 'updateStatus'])->name('item.updateStatus');
    Route::delete('item/delete', [App\Http\Controllers\Branch\ItemController::class, 'delete'])->name('item.delete');
    Route::resource('item', App\Http\Controllers\Branch\ItemController::class);

    /** Traditional Booking Parcel Route List */

    /** Parcel Booking Assign Vehicle */
    Route::get('bookingParcel/assignVehicle', [\App\Http\Controllers\Branch\BookingVehicleController::class, 'assignVehicle'])->name('bookingParcel.assignVehicle');
    Route::post('bookingParcel/assignFilterData', [\App\Http\Controllers\Branch\BookingParcelController::class, 'filterAssignBookingParcel'])->name('bookingParcel.filterAssignData');
    Route::post('bookingParcel/assignParcelAddCart', [\App\Http\Controllers\Branch\BookingParcelController::class, 'assignParcelAddCart'])->name('bookingParcel.assignParcelAddCart');
    Route::post('bookingParcel/assignParcelDeleteCart', [\App\Http\Controllers\Branch\BookingParcelController::class, 'assignParcelDeleteCart'])->name('bookingParcel.assignParcelDeleteCart');
    Route::post('bookingParcel/confirmAssignVehicleBookingParcel', [\App\Http\Controllers\Branch\BookingVehicleController::class, 'confirmAssignVehicleBookingParcel'])->name('bookingParcel.confirmAssignVehicleBookingParcel');
    /** Parcel Booking */
    Route::post('bookingPercel/addItem', [App\Http\Controllers\Branch\BookingParcelController::class, 'addCartItem'])->name('item.addCartItem');
    Route::post('bookingPercel/removeItem', [App\Http\Controllers\Branch\BookingParcelController::class, 'removeCartItem'])->name('item.removeCartItem');
    Route::post('bookingPercel/updateStatus', [App\Http\Controllers\Branch\BookingParcelController::class, 'updateStatus'])->name('bookingPercel.updateStatus');
    Route::get('bookingParcel/getBookingList', [App\Http\Controllers\Branch\BookingParcelController::class, 'getBookingParcelList'])->name('bookingParcel.getBookingList');
    Route::get('bookingParcel/{booking_parcel}/viewBookingParcel', [App\Http\Controllers\Branch\BookingParcelController::class, 'viewBookingParcel'])->name('bookingParcel.viewBookingParcel');
    Route::get('bookingParcel/printBookingParcel/{booking_parcel}', [App\Http\Controllers\Branch\BookingParcelController::class, 'printBookingParcel'])->name('bookingParcel.printBookingParcel');
    Route::post('bookingParcel/printBookingParcelList', [App\Http\Controllers\Branch\BookingParcelController::class, 'bookingParcelPrintList'])->name('bookingParcel.bookingParcelPrintList');

    /** Booking Item Barcode Print */
    Route::get('bookingParcel/{booking_parcel}/bookingParcelItems', [App\Http\Controllers\Branch\BookingParcelController::class, 'bookingParcelItems'])->name('bookingParcel.bookingParcelItems');
    Route::post('bookingPercel/printBookingItemBarcode', [App\Http\Controllers\Branch\BookingParcelController::class, 'printBookingItemBarcode'])->name('bookingPercel.printBookingItemBarcode');

    /* Destination Branch Functionality */
    Route::get('bookingParcel/bookingParcelReceiveList', [\App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'bookingParcelReceiveList'])->name('bookingParcel.bookingParcelReceiveList');
    Route::get('bookingParcel/getBookingParcelReceiveList', [App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'getBookingParcelReceiveList'])->name('bookingParcel.getBookingParcelReceiveList');
    Route::get('bookingParcel/printBookingParcelReceiveList', [App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'printBookingParcelReceiveList'])->name('bookingParcel.printBookingParcelReceiveList');
    Route::get('bookingParcel/receiveBookingParcel', [\App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'receiveBookingParcel'])->name('bookingParcel.receiveBookingParcel');
    Route::post('bookingParcel/getParcelListForDestinationBranchReceive', [\App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'getParcelListForDestinationBranchReceive'])->name('bookingParcel.getParcelListForDestinationBranchReceive');
    Route::post('bookingParcel/confirmDestinationReceivedParcel', [\App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'confirmDestinationReceivedParcel'])->name('bookingParcel.confirmDestinationReceivedParcel');
    Route::post('bookingParcel/rejectParcelFromDestination', [\App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'rejectParcelFromDestination'])->name('bookingParcel.rejectParcelFromDestination');

    Route::get('bookingParcel/{booking_parcel}/deliveryBookingParcel', [App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'deliveryBookingParcel'])->name('bookingParcel.deliveryBookingParcel');
    Route::patch('bookingParcel/{booking_parcel}/confirmDeliveryBookingParcel', [App\Http\Controllers\Branch\parcelDestinationBranchController::class, 'confirmDeliveryBookingParcel'])->name('bookingParcel.confirmDeliveryBookingParcel');

    Route::resource('bookingParcel', App\Http\Controllers\Branch\BookingParcelController::class);

    /** Booking Parcel List */

    /** Booking Parcel Payment Report */
    Route::get('bookingParcelPayment/lists', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'getBookingParcelPaymentList'])->name('bookingParcelPayment.getBookingParcelPaymentList');
    Route::get('bookingParcelPayment/print', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'printBookingParcelPaymentList'])->name('bookingParcelPayment.printBookingParcelPaymentList');
    Route::get('bookingParcelPayment/{parcelPayment}/view', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'viewBookingParcelPayment'])->name('bookingParcelPayment.viewBookingParcelPayment');
    Route::get('bookingParcelPayment/forward', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'paymentForwardToAccounts'])->name('bookingParcelPayment.paymentForwardToAccounts');

    Route::post('bookingParcelPayment/paymentParcelAddCart', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'paymentParcelAddCart'])->name('bookingParcelPayment.paymentParcelAddCart');
    Route::post('bookingParcelPayment/paymentParcelDeleteCart', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'paymentParcelDeleteCart'])->name('bookingParcelPayment.paymentParcelDeleteCart');
    Route::post('bookingParcelPayment/forward-confirm', [App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'confirmPaymentForwardToAccounts'])->name('bookingParcelPayment.confirmPaymentForwardToAccounts');

    Route::get('bookingParcelPayment/report', [\App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'bookingParcelPaymentReport'])->name('bookingParcelPayment.bookingParcelPaymentReport');
    Route::get('bookingParcelPayment/report/print', [\App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'printBookingParcelPaymentReport'])->name('bookingParcelPayment.printBookingParcelPaymentReport');
    Route::post('bookingParcelPayment/report-list', [\App\Http\Controllers\Branch\BookingParcelPaymentController::class, 'bookingParcelPaymentReportAjax'])->name('bookingParcelPayment.bookingParcelPaymentReportAjax');
    Route::resource('bookingParcelPayment', App\Http\Controllers\Branch\BookingParcelPaymentController::class);

});
