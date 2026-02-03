<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExpenseController;

Route::get('/admin', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login');
Route::post('/admin', [App\Http\Controllers\Admin\AuthController::class, 'login_check'])->name('login');

Route::get('/admin/forgotPassword', [App\Http\Controllers\Admin\AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/admin/forgotPassword', [App\Http\Controllers\Admin\AuthController::class, 'confirmForgotPassword'])->name('forgotPassword');
Route::get('/admin/resetPassword/{token}', [App\Http\Controllers\Admin\AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/admin/resetPassword', [App\Http\Controllers\Admin\AuthController::class, 'confirmResetPassword'])->name('resetPassword');


Route::group(['middleware' => 'admin', 'prefix' => 'admin/'], function () {
    Route::match(['get', 'post'], '/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::match(['get', 'post'], '/home', [App\Http\Controllers\Admin\HomeController::class, 'home'])->name('home');
    Route::resource('application', App\Http\Controllers\Admin\ApplicationController::class);

    Route::match(['get', 'post'], '/report', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report');


    Route::get('admin/getAdmins', [App\Http\Controllers\Admin\AdminController::class, 'getAdmins'])->name('admin.getAdmins');
    Route::post('admin/updateStatus', [App\Http\Controllers\Admin\AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::delete('admin/delete', [App\Http\Controllers\Admin\AdminController::class, 'delete'])->name('admin.delete');
    Route::resource('admin', App\Http\Controllers\Admin\AdminController::class);

    Route::get('slider/getSliders', [App\Http\Controllers\Admin\SliderController::class, 'getSliders'])->name('slider.getSliders');
    Route::post('slider/updateStatus', [App\Http\Controllers\Admin\SliderController::class, 'updateStatus'])->name('slider.updateStatus');
    Route::delete('slider/delete', [App\Http\Controllers\Admin\SliderController::class, 'delete'])->name('slider.delete');
    Route::resource('slider', App\Http\Controllers\Admin\SliderController::class);

    Route::get('designation/getDesignations', [App\Http\Controllers\Admin\DesignationController::class, 'getDesignations'])->name('designation.getDesignations');
    Route::post('designation/updateStatus', [App\Http\Controllers\Admin\DesignationController::class, 'updateStatus'])->name('designation.updateStatus');
    Route::delete('designation/delete', [App\Http\Controllers\Admin\DesignationController::class, 'delete'])->name('designation.delete');
    Route::resource('designation', App\Http\Controllers\Admin\DesignationController::class);

    Route::get('teamMember/getTeamMembers', [App\Http\Controllers\Admin\TeamMemberController::class, 'getTeamMembers'])->name('teamMember.getTeamMembers');
    Route::post('teamMember/updateStatus', [App\Http\Controllers\Admin\TeamMemberController::class, 'updateStatus'])->name('teamMember.updateStatus');
    Route::delete('teamMember/delete', [App\Http\Controllers\Admin\TeamMemberController::class, 'delete'])->name('teamMember.delete');
    Route::resource('teamMember', App\Http\Controllers\Admin\TeamMemberController::class);

    Route::get('partner/getPartners', [App\Http\Controllers\Admin\PartnerController::class, 'getPartners'])->name('partner.getPartners');
    Route::post('partner/updateStatus', [App\Http\Controllers\Admin\PartnerController::class, 'updateStatus'])->name('partner.updateStatus');
    Route::delete('partner/delete', [App\Http\Controllers\Admin\PartnerController::class, 'delete'])->name('partner.delete');
    Route::resource('partner', App\Http\Controllers\Admin\PartnerController::class);


    Route::get('frequentlyAskQuestion/getFrequentlyAskQuestions', [App\Http\Controllers\Admin\FrequentlyAskQuestionController::class, 'getFrequentlyAskQuestions'])->name('frequentlyAskQuestion.getFrequentlyAskQuestions');
    Route::post('frequentlyAskQuestion/updateStatus', [App\Http\Controllers\Admin\FrequentlyAskQuestionController::class, 'updateStatus'])->name('frequentlyAskQuestion.updateStatus');
    Route::delete('frequentlyAskQuestion/delete', [App\Http\Controllers\Admin\FrequentlyAskQuestionController::class, 'delete'])->name('frequentlyAskQuestion.delete');
    Route::resource('frequentlyAskQuestion', App\Http\Controllers\Admin\FrequentlyAskQuestionController::class);


    Route::get('objective/getServices', [App\Http\Controllers\Admin\ObjectiveController::class, 'getObjectives'])->name('objective.getObjectives');
    Route::post('objective/updateStatus', [App\Http\Controllers\Admin\ObjectiveController::class, 'updateStatus'])->name('objective.updateStatus');
    Route::delete('objective/delete', [App\Http\Controllers\Admin\ObjectiveController::class, 'delete'])->name('objective.delete');
    Route::resource('objective', App\Http\Controllers\Admin\ObjectiveController::class);


    Route::get('service/getServices', [App\Http\Controllers\Admin\ServiceController::class, 'getServices'])->name('service.getServices');
    Route::post('service/updateStatus', [App\Http\Controllers\Admin\ServiceController::class, 'updateStatus'])->name('service.updateStatus');
    Route::delete('service/delete', [App\Http\Controllers\Admin\ServiceController::class, 'delete'])->name('service.delete');
    Route::resource('service', App\Http\Controllers\Admin\ServiceController::class);

    Route::get('deliveryService/getDeliveryServices', [App\Http\Controllers\Admin\DeliveryServiceController::class, 'getDeliveryServices'])->name('deliveryService.getDeliveryServices');
    Route::post('deliveryService/updateStatus', [App\Http\Controllers\Admin\DeliveryServiceController::class, 'updateStatus'])->name('deliveryService.updateStatus');
    Route::delete('deliveryService/delete', [App\Http\Controllers\Admin\DeliveryServiceController::class, 'delete'])->name('deliveryService.delete');
    Route::resource('deliveryService', App\Http\Controllers\Admin\DeliveryServiceController::class);


    Route::get('blog/getBlogs', [App\Http\Controllers\Admin\BlogController::class, 'getBlogs'])->name('blog.getBlogs');
    Route::post('blog/updateStatus', [App\Http\Controllers\Admin\BlogController::class, 'updateStatus'])->name('blog.updateStatus');
    Route::delete('blog/delete', [App\Http\Controllers\Admin\BlogController::class, 'delete'])->name('blog.delete');
    Route::resource('blog', App\Http\Controllers\Admin\BlogController::class);


    Route::get('socialLink/getSocialLinks', [App\Http\Controllers\Admin\SocialLinkController::class, 'getSocialLinks'])->name('socialLink.getSocialLinks');
    Route::post('socialLink/updateStatus', [App\Http\Controllers\Admin\SocialLinkController::class, 'updateStatus'])->name('socialLink.updateStatus');
    Route::delete('socialLink/delete', [App\Http\Controllers\Admin\SocialLinkController::class, 'delete'])->name('socialLink.delete');
    Route::resource('socialLink', App\Http\Controllers\Admin\SocialLinkController::class);

    Route::get('parcelStep/getParcelSteps', [App\Http\Controllers\Admin\ParcelStepController::class, 'getParcelSteps'])->name('parcelStep.getParcelSteps');
    Route::post('parcelStep/updateStatus', [App\Http\Controllers\Admin\ParcelStepController::class, 'updateStatus'])->name('parcelStep.updateStatus');
    Route::delete('parcelStep/delete', [App\Http\Controllers\Admin\ParcelStepController::class, 'delete'])->name('parcelStep.delete');
    Route::resource('parcelStep', App\Http\Controllers\Admin\ParcelStepController::class);

    Route::get('aboutPoint/getParcelSteps', [App\Http\Controllers\Admin\AboutPointController::class, 'getAboutPoints'])->name('aboutPoint.getAboutPoints');
    Route::post('aboutPoint/updateStatus', [App\Http\Controllers\Admin\AboutPointController::class, 'updateStatus'])->name('aboutPoint.updateStatus');
    Route::delete('aboutPoint/delete', [App\Http\Controllers\Admin\AboutPointController::class, 'delete'])->name('aboutPoint.delete');
    Route::resource('aboutPoint', App\Http\Controllers\Admin\AboutPointController::class);

    Route::get('pageContent/getPageContents', [App\Http\Controllers\Admin\PageContentController::class, 'getPageContents'])->name('pageContent.getPageContents');
    Route::post('pageContent/updateStatus', [App\Http\Controllers\Admin\PageContentController::class, 'updateStatus'])->name('pageContent.updateStatus');
    Route::delete('pageContent/delete', [App\Http\Controllers\Admin\PageContentController::class, 'delete'])->name('pageContent.delete');
    Route::resource('pageContent', App\Http\Controllers\Admin\PageContentController::class);


    Route::get('visitorMessage/index', [App\Http\Controllers\Admin\VisitorMessageController::class, 'index'])->name('visitorMessage.index');
    Route::get('visitorMessage/{visitorMessage}/show', [App\Http\Controllers\Admin\VisitorMessageController::class, 'show'])->name('visitorMessage.show');
    Route::get('visitorMessage/getVisitorMessages', [App\Http\Controllers\Admin\VisitorMessageController::class, 'getVisitorMessages'])->name('visitorMessage.getVisitorMessages');
    Route::post('visitorMessage/updateStatus', [App\Http\Controllers\Admin\VisitorMessageController::class, 'updateStatus'])->name('visitorMessage.updateStatus');
    Route::delete('visitorMessage/delete', [App\Http\Controllers\Admin\VisitorMessageController::class, 'delete'])->name('visitorMessage.delete');

    Route::get('newsLetter/index', [App\Http\Controllers\Admin\NewsLetterController::class, 'index'])->name('newsLetter.index');
    Route::get('newsLetter/{newsLetter}/show', [App\Http\Controllers\Admin\NewsLetterController::class, 'show'])->name('newsLetter.show');
    Route::get('newsLetter/getNewsLetters', [App\Http\Controllers\Admin\NewsLetterController::class, 'getNewsLetters'])->name('newsLetter.getNewsLetters');
    Route::post('newsLetter/updateStatus', [App\Http\Controllers\Admin\NewsLetterController::class, 'updateStatus'])->name('newsLetter.updateStatus');
    Route::delete('newsLetter/delete', [App\Http\Controllers\Admin\NewsLetterController::class, 'delete'])->name('newsLetter.delete');


    Route::get('customerFeedback/getCustomerFeedbacks', [App\Http\Controllers\Admin\CustomerFeedbackController::class, 'getCustomerFeedbacks'])->name('customerFeedback.getCustomerFeedbacks');
    Route::post('customerFeedback/updateStatus', [App\Http\Controllers\Admin\CustomerFeedbackController::class, 'updateStatus'])->name('customerFeedback.updateStatus');
    Route::delete('customerFeedback/delete', [App\Http\Controllers\Admin\CustomerFeedbackController::class, 'delete'])->name('customerFeedback.delete');
    Route::resource('customerFeedback', App\Http\Controllers\Admin\CustomerFeedbackController::class);

    Route::get('pageContent/getPageContents', [App\Http\Controllers\Admin\PageContentController::class, 'getPageContents'])->name('pageContent.getPageContents');
    Route::post('pageContent/updateStatus', [App\Http\Controllers\Admin\PageContentController::class, 'updateStatus'])->name('pageContent.updateStatus');
    Route::delete('pageContent/delete', [App\Http\Controllers\Admin\PageContentController::class, 'delete'])->name('pageContent.delete');
    Route::resource('pageContent', App\Http\Controllers\Admin\PageContentController::class);

    Route::get('becomeMerchant', [App\Http\Controllers\Admin\ContentController::class, 'becomeMerchant'])->name('becomeMerchant');
    Route::get('becomeFranchisee', [App\Http\Controllers\Admin\ContentController::class, 'becomeFranchisee'])->name('becomeFranchisee');

    Route::post('content/store', [App\Http\Controllers\Admin\ContentController::class, 'storeAndUpdate'])->name('content.store');
    Route::patch('content/update/{id}', [App\Http\Controllers\Admin\ContentController::class, 'storeAndUpdate'])->name('content.update');

    Route::get('office/getOffices', [App\Http\Controllers\Admin\OfficeController::class, 'getOffices'])->name('office.getOffices');
    Route::post('office/updateStatus', [App\Http\Controllers\Admin\OfficeController::class, 'updateStatus'])->name('office.updateStatus');
    Route::delete('office/delete', [App\Http\Controllers\Admin\OfficeController::class, 'delete'])->name('office.delete');
    Route::resource('office', App\Http\Controllers\Admin\OfficeController::class);

    Route::get('feature/getFeatures', [App\Http\Controllers\Admin\FeatureController::class, 'getFeatures'])->name('feature.getFeatures');
    Route::post('feature/updateStatus', [App\Http\Controllers\Admin\FeatureController::class, 'updateStatus'])->name('feature.updateStatus');
    Route::delete('feature/delete', [App\Http\Controllers\Admin\FeatureController::class, 'delete'])->name('feature.delete');
    Route::resource('feature', App\Http\Controllers\Admin\FeatureController::class);


    Route::get('feature/getFeatures', [App\Http\Controllers\Admin\FeatureController::class, 'getFeatures'])->name('feature.getFeatures');
    Route::post('feature/updateStatus', [App\Http\Controllers\Admin\FeatureController::class, 'updateStatus'])->name('feature.updateStatus');
    Route::delete('feature/delete', [App\Http\Controllers\Admin\FeatureController::class, 'delete'])->name('feature.delete');
    Route::resource('feature', App\Http\Controllers\Admin\FeatureController::class);

    Route::get('weightPackage/getWeightPackages', [App\Http\Controllers\Admin\WeightPackageController::class, 'getWeightPackages'])->name('weightPackage.getWeightPackages');
    Route::post('weightPackage/updateStatus', [App\Http\Controllers\Admin\WeightPackageController::class, 'updateStatus'])->name('weightPackage.updateStatus');
    Route::delete('weightPackage/delete', [App\Http\Controllers\Admin\WeightPackageController::class, 'delete'])->name('weightPackage.delete');
    Route::resource('weightPackage', App\Http\Controllers\Admin\WeightPackageController::class);

    Route::get('serviceArea/getServiceAreas', [App\Http\Controllers\Admin\ServiceAreaController::class, 'getServiceAreas'])->name('serviceArea.getServiceAreas');
    Route::post('serviceArea/updateStatus', [App\Http\Controllers\Admin\ServiceAreaController::class, 'updateStatus'])->name('serviceArea.updateStatus');
    Route::delete('serviceArea/delete', [App\Http\Controllers\Admin\ServiceAreaController::class, 'delete'])->name('serviceArea.delete');
    Route::resource('serviceArea', App\Http\Controllers\Admin\ServiceAreaController::class);


    Route::get('serviceAreaSetting/getServiceAreaSettings', [App\Http\Controllers\Admin\ServiceAreaSettingController::class, 'getServiceAreaSettings'])->name('serviceAreaSetting.getServiceAreaSettings');
    Route::post('serviceAreaSetting/updateStatus', [App\Http\Controllers\Admin\ServiceAreaSettingController::class, 'updateStatus'])->name('serviceAreaSetting.updateStatus');
    Route::delete('serviceAreaSetting/delete', [App\Http\Controllers\Admin\ServiceAreaSettingController::class, 'delete'])->name('serviceAreaSetting.delete');
    Route::resource('serviceAreaSetting', App\Http\Controllers\Admin\ServiceAreaSettingController::class);


    Route::get('district/getDistricts', [App\Http\Controllers\Admin\DistrictController::class, 'getDistricts'])->name('district.getDistricts');
    Route::post('district/updateStatus', [App\Http\Controllers\Admin\DistrictController::class, 'updateStatus'])->name('district.updateStatus');
    Route::delete('district/delete', [App\Http\Controllers\Admin\DistrictController::class, 'delete'])->name('district.delete');
    Route::resource('district', App\Http\Controllers\Admin\DistrictController::class);

    Route::get('upazila/getUpazilas', [App\Http\Controllers\Admin\UpazilaController::class, 'getUpazilas'])->name('upazila.getUpazilas');
    Route::post('upazila/updateStatus', [App\Http\Controllers\Admin\UpazilaController::class, 'updateStatus'])->name('upazila.updateStatus');
    Route::delete('upazila/delete', [App\Http\Controllers\Admin\UpazilaController::class, 'delete'])->name('upazila.delete');
    Route::resource('upazila', App\Http\Controllers\Admin\UpazilaController::class);


    Route::get('area/getAreas', [App\Http\Controllers\Admin\AreaController::class, 'getAreas'])->name('area.getAreas');
    Route::post('area/updateStatus', [App\Http\Controllers\Admin\AreaController::class, 'updateStatus'])->name('area.updateStatus');
    Route::delete('area/delete', [App\Http\Controllers\Admin\AreaController::class, 'delete'])->name('area.delete');
    Route::get('area/excelImport', [App\Http\Controllers\Admin\AreaController::class, 'excelImport'])->name('area.excelImport');
    Route::post('area/excelImport', [App\Http\Controllers\Admin\AreaController::class, 'excelImportStore'])->name('area.excelImport');
    Route::resource('area', App\Http\Controllers\Admin\AreaController::class);


    Route::get('branch/getBranches', [App\Http\Controllers\Admin\BranchController::class, 'getBranches'])->name('branch.getBranches');
    Route::get('branch/printBranches', [App\Http\Controllers\Admin\BranchController::class, 'printBranches'])->name('branch.printBranches');
    Route::post('branch/updateStatus', [App\Http\Controllers\Admin\BranchController::class, 'updateStatus'])->name('branch.updateStatus');
    Route::delete('branch/delete', [App\Http\Controllers\Admin\BranchController::class, 'delete'])->name('branch.delete');
    Route::resource('branch', App\Http\Controllers\Admin\BranchController::class);

    Route::get('branchUser/getBranchUsers', [App\Http\Controllers\Admin\BranchUserController::class, 'getBranchUsers'])->name('branchUser.getBranchUsers');
    Route::get('branchUser/printBranchUsers', [App\Http\Controllers\Admin\BranchUserController::class, 'printBranchUsers'])->name('branchUser.printBranchUsers');
    Route::post('branchUser/updateStatus', [App\Http\Controllers\Admin\BranchUserController::class, 'updateStatus'])->name('branchUser.updateStatus');
    Route::delete('branchUser/delete', [App\Http\Controllers\Admin\BranchUserController::class, 'delete'])->name('branchUser.delete');
    Route::get('branchUser/{branchUser}/branch-user-login', [App\Http\Controllers\Admin\BranchUserController::class, 'branchUserLogin'])->name('branchUser.branchUserLogin');
    Route::resource('branchUser', App\Http\Controllers\Admin\BranchUserController::class);

    Route::get('merchant/getMerchants', [App\Http\Controllers\Admin\MerchantController::class, 'getMerchants'])->name('merchant.getMerchants');
    Route::get('merchant/printMerchants', [App\Http\Controllers\Admin\MerchantController::class, 'printMerchants'])->name('merchant.printMerchants');
    Route::post('merchant/updateStatus', [App\Http\Controllers\Admin\MerchantController::class, 'updateStatus'])->name('merchant.updateStatus');
    Route::delete('merchant/delete', [App\Http\Controllers\Admin\MerchantController::class, 'delete'])->name('merchant.delete');
    Route::get('merchant/{merchant}/merchant-login', [App\Http\Controllers\Admin\MerchantController::class, 'merchantLogin'])->name('merchant.merchantLogin');
    Route::get('merchant/merchantBulkImport', [App\Http\Controllers\Admin\MerchantController::class, 'merchantBulkImport'])->name('merchant.merchantBulkImport');
    Route::get('merchant/merchantBulkImportCheck', [App\Http\Controllers\Admin\MerchantController::class, 'merchantBulkImportCheck'])->name('merchant.merchantBulkImportCheck');
    Route::get('merchant/merchantBulkImportReset', [App\Http\Controllers\Admin\MerchantController::class, 'merchantBulkImportReset'])->name('merchant.merchantBulkImportReset');
    Route::post('merchant/merchantBulkImportCheck', [App\Http\Controllers\Admin\MerchantController::class, 'merchantBulkImportEntry']);
    Route::post('merchant/merchantBulkImport', [App\Http\Controllers\Admin\MerchantController::class, 'merchantBulkImportStore']);
    Route::resource('merchant', App\Http\Controllers\Admin\MerchantController::class);

    Route::get('rider/getRiders', [App\Http\Controllers\Admin\RiderController::class, 'getRiders'])->name('rider.getRiders');
    Route::get('rider/printRiders', [App\Http\Controllers\Admin\RiderController::class, 'printRiders'])->name('rider.printRiders');
    Route::post('rider/updateStatus', [App\Http\Controllers\Admin\RiderController::class, 'updateStatus'])->name('rider.updateStatus');
    Route::delete('rider/delete', [App\Http\Controllers\Admin\RiderController::class, 'delete'])->name('rider.delete');
    Route::get('rider/{rider}/rider-login', [App\Http\Controllers\Admin\RiderController::class, 'riderLogin'])->name('rider.riderLogin');
    Route::resource('rider', App\Http\Controllers\Admin\RiderController::class);


    Route::get('vehicle/getVehicles', [App\Http\Controllers\Admin\VehicleController::class, 'getVehicles'])->name('vehicle.getVehicles');
    Route::get('vehicle/printVehicles', [App\Http\Controllers\Admin\VehicleController::class, 'printVehicles'])->name('vehicle.printVehicles');
    Route::post('vehicle/updateStatus', [App\Http\Controllers\Admin\VehicleController::class, 'updateStatus'])->name('vehicle.updateStatus');
    Route::delete('vehicle/delete', [App\Http\Controllers\Admin\VehicleController::class, 'delete'])->name('vehicle.delete');
    Route::resource('vehicle', App\Http\Controllers\Admin\VehicleController::class);


    Route::get('warehouse/getWarehouses', [App\Http\Controllers\Admin\WarehouseController::class, 'getWarehouses'])->name('warehouse.getWarehouses');
    Route::get('warehouse/printWarehouses', [App\Http\Controllers\Admin\WarehouseController::class, 'printWarehouses'])->name('warehouse.printWarehouses');
    Route::post('warehouse/updateStatus', [App\Http\Controllers\Admin\WarehouseController::class, 'updateStatus'])->name('warehouse.updateStatus');
    Route::delete('warehouse/delete', [App\Http\Controllers\Admin\WarehouseController::class, 'delete'])->name('warehouse.delete');
    Route::resource('warehouse', App\Http\Controllers\Admin\WarehouseController::class);


    Route::get('warehouseUser/getWarehouseUsers', [App\Http\Controllers\Admin\WarehouseUserController::class, 'getWarehouseUsers'])->name('warehouseUser.getWarehouseUsers');
    Route::get('warehouseUser/printWarehouseUsers', [App\Http\Controllers\Admin\WarehouseUserController::class, 'printWarehouseUsers'])->name('warehouseUser.printWarehouseUsers');
    Route::post('warehouseUser/updateStatus', [App\Http\Controllers\Admin\WarehouseUserController::class, 'updateStatus'])->name('warehouseUser.updateStatus');
    Route::delete('warehouseUser/delete', [App\Http\Controllers\Admin\WarehouseUserController::class, 'delete'])->name('warehouseUser.delete');
    Route::resource('warehouseUser', App\Http\Controllers\Admin\WarehouseUserController::class);


    Route::get('itemCategory/getItemCategories', [App\Http\Controllers\Admin\ItemCategoryController::class, 'getItemCategories'])->name('itemCategory.getItemCategories');
    Route::get('itemCategory/printItemCategories', [App\Http\Controllers\Admin\ItemCategoryController::class, 'printItemCategories'])->name('itemCategory.printItemCategories');
    Route::post('itemCategory/updateStatus', [App\Http\Controllers\Admin\ItemCategoryController::class, 'updateStatus'])->name('itemCategory.updateStatus');
    Route::delete('itemCategory/delete', [App\Http\Controllers\Admin\ItemCategoryController::class, 'delete'])->name('itemCategory.delete');
    Route::resource('itemCategory', App\Http\Controllers\Admin\ItemCategoryController::class);


    Route::get('unit/getUnits', [App\Http\Controllers\Admin\UnitController::class, 'getUnits'])->name('unit.getUnits');
    Route::get('unit/printUnits', [App\Http\Controllers\Admin\UnitController::class, 'printUnits'])->name('unit.printUnits');
    Route::post('unit/updateStatus', [App\Http\Controllers\Admin\UnitController::class, 'updateStatus'])->name('unit.updateStatus');
    Route::delete('unit/delete', [App\Http\Controllers\Admin\UnitController::class, 'delete'])->name('unit.delete');
    Route::resource('unit', App\Http\Controllers\Admin\UnitController::class);

    Route::get('item/getItems', [App\Http\Controllers\Admin\ItemController::class, 'getItems'])->name('item.getItems');
    Route::get('item/printItems', [App\Http\Controllers\Admin\ItemController::class, 'printItems'])->name('item.printItems');
    Route::post('item/updateStatus', [App\Http\Controllers\Admin\ItemController::class, 'updateStatus'])->name('item.updateStatus');
    Route::delete('item/delete', [App\Http\Controllers\Admin\ItemController::class, 'delete'])->name('item.delete');
    Route::post('item/excelImportStore', [App\Http\Controllers\Admin\ItemController::class, 'excelImportStore'])->name('item.excelImportStore');
    Route::resource('item', App\Http\Controllers\Admin\ItemController::class);


    /** Parcel Filter Route */
    Route::post('parcel/parcel-filter-list', [App\Http\Controllers\Admin\ParcelFilterController::class, 'filterParcelList'])->name('parcel.filterList');

    /** Parcel Route */
    Route::get('parcel/list', [App\Http\Controllers\Admin\ParcelController::class, 'list'])->name('parcel.list');
    Route::get('parcel/getParcelList', [App\Http\Controllers\Admin\ParcelController::class, 'getParcelList'])->name('parcel.getParcelList');

    Route::get('parcel/allParcelList', [App\Http\Controllers\Admin\ParcelController::class, 'allParcelList'])->name('parcel.allParcelList');
    Route::get('parcel/getAllParcelList', [App\Http\Controllers\Admin\ParcelController::class, 'getAllParcelList'])->name('parcel.getAllParcelList');
    Route::get('parcel/printAllParcelList', [App\Http\Controllers\Admin\ParcelController::class, 'printAllParcelList'])->name('parcel.printAllParcelList');
    Route::get('parcel/excelAllParcelList', [App\Http\Controllers\Admin\ParcelController::class, 'excelAllParcelList'])->name('parcel.excelAllParcelList');
    Route::get('parcel/orderTracking', [App\Http\Controllers\Admin\ParcelController::class, 'orderTracking'])->name('parcel.orderTracking');

    Route::get('parcel/orderTracking/{parcel_invoice?}', [App\Http\Controllers\Admin\ParcelController::class, 'orderTracking'])->name('parcel.orderTracking');
    Route::post('parcel/returnOrderTrackingResult', [App\Http\Controllers\Admin\ParcelController::class, 'returnOrderTrackingResult'])->name('parcel.returnOrderTrackingResult');


    Route::get('parcel/{parcel}/viewParcel', [App\Http\Controllers\Admin\ParcelController::class, 'viewParcel'])->name('parcel.viewParcel');
    Route::get('parcel/{parcel}/editParcel', [App\Http\Controllers\Admin\ParcelController::class, 'editParcel'])->name('parcel.editParcel');
    Route::patch('parcel/{parcel}/confirmEditParcel', [App\Http\Controllers\Admin\ParcelController::class, 'confirmEditParcel'])->name('parcel.confirmEditParcel');
    Route::delete('parcel/deleteParcel', [App\Http\Controllers\Admin\ParcelController::class, 'delete'])->name('parcel.deleteParcel');


    Route::get('/merchant-parcel-report', [App\Http\Controllers\Admin\MerchantParcelReportController::class, 'monthlyParcelReport'])->name('merchant.parcelReport');
    Route::post('/get-merchant-parcel-report', [App\Http\Controllers\Admin\MerchantParcelReportController::class, 'getMonthlyParcelReport'])->name('merchant.getParcelReport');

    Route::get('/rider-delivery-parcel-report', [App\Http\Controllers\Admin\RiderParcelReportController::class, 'deliveryParcelReport'])->name('rider.deliveryParcelReport');
    Route::post('/get-rider-delivery-parcel-report', [App\Http\Controllers\Admin\RiderParcelReportController::class, 'getDeliveryParcelReport'])->name('rider.getDeliveryParcelReport');



    Route::get('/merchant-pickup-parcel-report', [App\Http\Controllers\Admin\MerchantParcelReportController::class, 'todayPickupParcelReport'])->name('merchant.pickup.parcelReport');
    Route::post('/get-merchant-pickup-parcel-report', [App\Http\Controllers\Admin\MerchantParcelReportController::class, 'getTodayPickupParcelReport'])->name('merchant.pickup.getParcelReport');

    //================ Accounts  ================================

    //================ Branch Delivery Payment  ================================
    Route::get('account/branchDeliveryPaymentList', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'branchDeliveryPaymentList'])->name('account.branchDeliveryPaymentList');
    Route::get('account/getBranchDeliveryPaymentList', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'getBranchDeliveryPaymentList'])->name('account.getBranchDeliveryPaymentList');

    Route::get('account/branchDeliveryReceivePaymentList', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'branchDeliveryReceivePaymentList'])->name('account.branchDeliveryReceivePaymentList');
    Route::get('account/getBranchDeliveryReceivePaymentList', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'getBranchDeliveryReceivePaymentList'])->name('account.getBranchDeliveryReceivePaymentList');
    Route::get('account/branchDeliveryPaymentStatement', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'branchDeliveryPaymentStatement'])->name('account.branchDeliveryPaymentStatement');
    Route::post('account/getBranchDeliveryPaymentStatement', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'getBranchDeliveryPaymentStatement'])->name('account.getBranchDeliveryPaymentStatement');

    Route::get('account/{parcelDeliveryPayment}/viewBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'viewBranchDeliveryPayment'])->name('account.viewBranchDeliveryPayment');
    Route::get('account/{parcelDeliveryPayment}/printBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'printBranchDeliveryPayment'])->name('account.printBranchDeliveryPayment');
    Route::get('account/{parcelDeliveryPayment}/acceptBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'acceptBranchDeliveryPayment'])->name('account.acceptBranchDeliveryPayment');
    Route::patch('account/{parcelDeliveryPayment}/confirmAcceptBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'confirmAcceptBranchDeliveryPayment'])->name('account.confirmAcceptBranchDeliveryPayment');
    Route::get('account/{parcelDeliveryPayment}/rejectBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'rejectBranchDeliveryPayment'])->name('account.rejectBranchDeliveryPayment');
    Route::patch('account/{parcelDeliveryPayment}/confirmRejectBranchDeliveryPayment', [App\Http\Controllers\Admin\BranchDeliveryPaymentController::class, 'confirmRejectBranchDeliveryPayment'])->name('account.confirmRejectBranchDeliveryPayment');
    //================ Branch Delivery Payment  ================================

    Route::get('parcel/parcelPaymentRequestList', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'parcelPaymentRequestList'] )->name('parcel.parcelPaymentRequestList');
    Route::get('parcel/getParcelPaymentRequestList', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'getParcelPaymentRequestList'] )->name('parcel.getParcelPaymentRequestList');
    Route::get('parcel/viewParcelPaymentRequest/{parcelPaymentRequest}', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'viewParcelPaymentRequest'] )->name('parcel.viewParcelPaymentRequest');
    Route::post('parcel/acceptPaymentRequestParcel', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'acceptPaymentRequestParcel'] )->name('parcel.acceptPaymentRequestParcel');
    Route::post('parcel/rejectPaymentRequestParcel', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'rejectPaymentRequestParcel'] )->name('parcel.rejectPaymentRequestParcel');



    Route::get('parcel/{parcelPaymentRequest}/paymentGenerate', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'parcelPaymentGenerate'])->name('parcel.paymentGenerate');
    Route::post('parcel/confirmPaymentGenerate', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'confirmParcelPaymentGenerate'])->name('parcel.confirmParcelPaymentGenerate');
    Route::post('parcel/merchantDeliveryPaymentParcelAddCart', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'merchantDeliveryPaymentParcelAddCart'])->name('parcel.merchantDeliveryPaymentParcelAddCart');
    Route::get('parcel/merchantPaymentDeliveryList', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'merchantPaymentDeliveryList'] )->name('parcel.merchantPaymentDeliveryList');
    Route::get('parcel/getMerchantPaymentDeliveryList', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'getMerchantPaymentDeliveryList'] )->name('parcel.getMerchantPaymentDeliveryList');
    Route::get('parcel/{parcelMerchantDeliveryPayment}/viewMerchantDeliveryPayment', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'viewMerchantDeliveryPayment'] )->name('parcel.viewMerchantDeliveryPayment');
    Route::get('parcel/{parcelMerchantDeliveryPayment}/printMerchantDeliveryPayment', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'printMerchantDeliveryPayment'] )->name('parcel.printMerchantDeliveryPayment');

    Route::get('parcel/{parcelMerchantDeliveryPayment}/merchantPaymentDeliveryAccept', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'merchantDeliveryPaymentAccept'] )->name('parcel.merchantDeliveryPaymentAccept');
    Route::patch('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentAcceptConfirm', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'merchantDeliveryPaymentAcceptConfirm'] )->name('parcel.merchantDeliveryPaymentAcceptConfirm');

    Route::delete('parcel/merchantDeliveryPaymentDelete', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'merchantPaymentDeliveryDelete'] )->name('parcel.merchantDeliveryPaymentDelete');

    Route::post('parcel/printMerchantDeliveryPaymentList', [App\Http\Controllers\Admin\ParcelPaymentRequestController::class, 'printMerchantDeliveryPaymentList'])->name('parcel.printMerchantDeliveryPaymentList');




    //================ Merchant Delivery Payment  ================================
    Route::get('account/merchantPaymentDeliveryGenerate', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantPaymentDeliveryGenerate'])->name('account.merchantPaymentDeliveryGenerate');
    Route::post('account/merchantDeliveryPaymentParcelClearCart', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantDeliveryPaymentParcelClearCart'])->name('account.merchantDeliveryPaymentParcelClearCart');
    Route::post('account/returnMerchantDeliveryPaymentParcel', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'returnMerchantDeliveryPaymentParcel'])->name('account.returnMerchantDeliveryPaymentParcel');
    Route::post('account/merchantDeliveryPaymentParcelAddCart', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantDeliveryPaymentParcelAddCart'])->name('account.merchantDeliveryPaymentParcelAddCart');
    Route::post('account/merchantDeliveryPaymentParcelDeleteCart', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantDeliveryPaymentParcelDeleteCart'])->name('account.merchantDeliveryPaymentParcelDeleteCart');
    Route::post('account/confirmMerchantDeliveryPaymentGenerate', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'confirmMerchantDeliveryPaymentGenerate'])->name('account.confirmMerchantDeliveryPaymentGenerate');

    Route::get('account/merchantPaymentDeliveryList', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantPaymentDeliveryList'])->name('account.merchantPaymentDeliveryList');
    Route::get('account/getMerchantPaymentDeliveryList', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'getMerchantPaymentDeliveryList'])->name('account.getMerchantPaymentDeliveryList');

    Route::get('account/{parcelMerchantDeliveryPayment}/merchantPaymentDeliveryAccept', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantDeliveryPaymentAccept'])->name('account.merchantDeliveryPaymentAccept');
    Route::patch('account/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentAcceptConfirm', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantDeliveryPaymentAcceptConfirm'])->name('account.merchantDeliveryPaymentAcceptConfirm');

    Route::delete('account/merchantDeliveryPaymentDelete', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantPaymentDeliveryDelete'])->name('account.merchantDeliveryPaymentDelete');

    Route::get('account/merchantPaymentDeliveryStatement', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantPaymentDeliveryStatement'])->name('account.merchantPaymentDeliveryStatement');
    Route::post('account/getMerchantPaymentDeliveryStatement', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'getMerchantPaymentDeliveryStatement'])->name('account.getMerchantPaymentDeliveryStatement');
    Route::get('account/printMerchantPaymentDeliveryStatement', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'printMerchantPaymentDeliveryStatement'])->name('account.printMerchantPaymentDeliveryStatement');

    Route::get('account/{parcelMerchantDeliveryPayment}/viewMerchantDeliveryPayment', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'viewMerchantDeliveryPayment'])->name('account.viewMerchantDeliveryPayment');
    Route::get('account/{parcelMerchantDeliveryPayment}/printMerchantDeliveryPayment', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'printMerchantDeliveryPayment'])->name('account.printMerchantDeliveryPayment');

    Route::get('account/{parcelMerchantDeliveryPayment}/merchantPaymentDeliveryGenerateEdit', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'merchantPaymentDeliveryGenerateEdit'])->name('account.merchantPaymentDeliveryGenerateEdit');
    Route::patch('account/{parcelMerchantDeliveryPayment}/confirmMerchantPaymentDeliveryGenerateEdit', [App\Http\Controllers\Admin\MerchantDeliveryPaymentController::class, 'confirmMerchantPaymentDeliveryGenerateEdit'])->name('account.confirmMerchantPaymentDeliveryGenerateEdit');
    //================ Merchant Delivery Payment  ================================





    //Apu Sardar

    //=== Expenses =====//admin.expense.store
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::get('expense-create', [App\Http\Controllers\Admin\ExpenseController::class, 'create'])->name('expense-create');
    Route::get('get-expense', [App\Http\Controllers\Admin\ExpenseController::class, 'show'] )->name('getExpense');
    Route::post('expense-store', [App\Http\Controllers\Admin\ExpenseController::class, 'store'])->name('expense.store');
    Route::post('expense-update-status', [App\Http\Controllers\Admin\ExpenseController::class, 'updateStatus'])->name('expense.updateStatus');
    Route::delete('expense-delete-status', [App\Http\Controllers\Admin\ExpenseController::class, 'delete'])->name('expense.delete');
    Route::get('expense-edit/{id}', [App\Http\Controllers\Admin\ExpenseController::class, 'edit'])->name('expense.edit');

    Route::get('expense-print/{id}', [App\Http\Controllers\Admin\ExpenseController::class, 'printExpense'])->name('expense.print');
    Route::post('expense-filter', [App\Http\Controllers\Admin\ExpenseController::class, 'expenseFilter'])->name('filter.expense');
    Route::get('expense-filter-print/{type}/{expense_head_id}/{from_date}/{to_date}', [App\Http\Controllers\Admin\ExpenseController::class, 'expenseFilterPrint'])->name('filter.expense.print');
    Route::get('get/expense/filter', [App\Http\Controllers\Admin\ExpenseController::class, 'getFilter'])->name('getFilter');

    Route::PATCH('expense-update/{id}', [App\Http\Controllers\Admin\ExpenseController::class, 'update'])->name('expense.update');

    Route::get('expense-head', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'index'])->name('expense-head');
    Route::get('expense-head-create', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'create'])->name('expenseHead.create');
    Route::get('expense-head-create', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'create'])->name('expenseHead.create');
    Route::post('expense-head-store', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'store'])->name('expenseHead.store');
    Route::get('get-expense-heades', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'show'])->name('expenseHead.getHeads');
    Route::post('expense-head-update-status', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'updateStatus'])->name('expenseHead.updateStatus');
    Route::delete('expense-head-delete-status', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'delete'])->name('expenseHead.delete');
    Route::get('expense-head-edit/{id}', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'edit'])->name('expenseHead.edit');
    Route::PATCH('expense-head-update/{id}', [App\Http\Controllers\Admin\ExpenseHeadController::class, 'update'])->name('expenseHead.update');


    //account report
    Route::get('receipt_payment', [App\Http\Controllers\Admin\AccountController::class, 'receipt_payment'])->name('receipt-payment');
    Route::post('select_receipt_payment', [App\Http\Controllers\Admin\AccountController::class, 'select_receipt_payment'])->name('select-receipt-payment');
    Route::get('income_statement', [App\Http\Controllers\Admin\AccountController::class, 'income_statement'])->name('income-statement');
    Route::post('select_income_statement', [App\Http\Controllers\Admin\AccountController::class, 'select_income_statement'])->name('select-income-statement');

    //================ Accounts ================================



    //Apusardar

    //======================== Traditional Parcel Payment ====================//
    Route::get('traditional/branchParcelPaymentList', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'branchParcelPaymentList'])->name('account.traditional.branchParcelPaymentList');
    Route::post('traditional/branchParcelPaymentList', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'getBranchParcelPaymentList']);
    Route::get('traditional/branchParcelPaymentView/{parcelPayment}/details', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'viewBranchParcelPayment'])->name('account.traditional.viewBranchParcelPayment');
    Route::get('traditional/branchParcelPaymentAccept/{parcelPayment}', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'acceptBranchParcelPayment'])->name('account.traditional.acceptBranchParcelPayment');
    Route::patch('traditional/branchParcelPaymentAccept/{parcelPayment}/Confirm', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'confirmAcceptBranchParcelPayment'])->name('account.traditional.confirmAcceptBranchParcelPayment');
    Route::get('traditional/branchParcelPaymentReject/{parcelPayment}', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'rejectBranchParcelPayment'])->name('account.traditional.rejectBranchParcelPayment');
    Route::patch('traditional/branchParcelPaymentReject/{parcelPayment}/Confirm', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'confirmRejectBranchParcelPayment'])->name('account.traditional.confirmRejectBranchParcelPayment');

    /** Traditional Parcel Payment Report  */
    Route::get('traditional/branchParcelPaymentReport', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'branchBookingParcelPaymentReport'])->name('account.traditional.branchBookingParcelPaymentReport');
    Route::post('traditional/branchParcelPaymentReportList', [\App\Http\Controllers\Admin\TraditionalParcelPaymentController::class, 'branchBookingParcelPaymentReportAjax'])->name('account.traditional.branchBookingParcelPaymentReportAjax');


    // ============= Staff and Salary Route ======================//

    // Staff Payment //
    Route::get('staff/payment-list', [App\Http\Controllers\Admin\StaffPaymentController::class, 'index'])->name('account.staffPaymentList');
    Route::get('staff/get-payment-list', [App\Http\Controllers\Admin\StaffPaymentController::class, 'getStaffPaymentList'])->name('account.getStaffPaymentList');
    Route::get('staff/payment-statement', [App\Http\Controllers\Admin\StaffPaymentController::class, 'staffPaymentStatement'])->name('account.staffPaymentStatement');
    Route::post('staff/get-payment-statement', [App\Http\Controllers\Admin\StaffPaymentController::class, 'filterStaffPaymentStatement'])->name('account.getFilterStaffPaymentStatement');

    Route::get('staff/payment', [App\Http\Controllers\Admin\StaffPaymentController::class, 'create'])->name('account.staffPayment');
    Route::post('staff/payment-store', [App\Http\Controllers\Admin\StaffPaymentController::class, 'store'])->name('account.staffPaymentStore');

    Route::post('account/getStaff', [App\Http\Controllers\Admin\StaffPaymentController::class, 'getStaffByBranch'])->name('account.getStaffOption');


    Route::get('staff/getStaffs', [App\Http\Controllers\Admin\StaffController::class, 'getStaffs'])->name('account.getStaffs');
    Route::post('staff/updateStatus', [App\Http\Controllers\Admin\StaffController::class, 'updateStatus'])->name('account.staffUpdateStatus');
    Route::delete('staff/delete', [App\Http\Controllers\Admin\StaffController::class, 'delete'])->name('account.staffDelete');
    Route::resource('staff', App\Http\Controllers\Admin\StaffController::class);


    //================ Accounts ================================


    //================ Operation   ================================

    //================ Transportation Expenses  Income  ================================
    Route::get('account/transportIncomeExpenseGenerate', [App\Http\Controllers\Admin\TransportIncomeExpensesController::class, 'transportIncomeExpenseGenerate'])->name('account.transportIncomeExpenseGenerate');
    Route::post('account/confirmTransportIncomeExpenseGenerate', [App\Http\Controllers\Admin\TransportIncomeExpensesController::class, 'confirmTransportIncomeExpenseGenerate'])->name('account.confirmTransportIncomeExpenseGenerate');
    Route::get('account/transportIncomeExpenseList', [App\Http\Controllers\Admin\TransportIncomeExpensesController::class, 'transportIncomeExpenseList'])->name('account.transportIncomeExpenseList');
    Route::get('account/getTransportIncomeExpenseList', [App\Http\Controllers\Admin\TransportIncomeExpensesController::class, 'getTransportIncomeExpenseList'])->name('account.getTransportIncomeExpenseList');
    Route::get('account/{transportIncomeExpense}/viewTransportIncomeExpense', [App\Http\Controllers\Admin\TransportIncomeExpensesController::class, 'viewTransportIncomeExpense'])->name('account.viewTransportIncomeExpense');
    //================ Transportation Expenses  Income  ================================

    //================ Operation  ================================


    //===================== Traditional Part routes ============================//
    Route::get('bookingParcel/getBookingList', [App\Http\Controllers\Admin\BookingParcelController::class, 'getBookingParcelList'])->name('bookingParcel.getBookingList');
    Route::post('bookingParcel/printBookingList', [App\Http\Controllers\Admin\BookingParcelController::class, 'bookingParcelPrintList'])->name('bookingParcel.bookingParcelPrintList');
    Route::get('bookingParcel/{booking_parcel}/viewBookingParcel', [App\Http\Controllers\Admin\BookingParcelController::class, 'viewBookingParcel'])->name('bookingParcel.viewBookingParcel');
    Route::get('bookingParcel/{booking_parcel}/printBookingParcel', [App\Http\Controllers\Admin\BookingParcelController::class, 'printBookingParcel'])->name('bookingParcel.printBookingParcel');
    Route::resource('bookingParcel', App\Http\Controllers\Admin\BookingParcelController::class);


    /** Operation Assign Vehicle Controller Route */
    Route::get('operationBookingParcel/assignVehicleList', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'vehicleAssignList'])->name('operationBookingParcel.vehicleAssignList');
    Route::get('operationBookingParcel/assignVehicleToWarehouse', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'assignVehicleToWarehouse'])->name('operationBookingParcel.assignVehicleToWarehouse');
    Route::post('operationBookingParcel/getParcelListForVehicleToWareHouseAssign', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'getParcelListForVehicleToWareHouseAssign'])->name('operationBookingParcel.getParcelListForVehicleToWareHouseAssign');
    Route::post('operationBookingParcel/confirmWarehouseAssign', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'confirmWarehouseAssign'])->name('operationBookingParcel.confirmWarehouseAssign');
    Route::post('operationBookingParcel/rejectParcelFromVehicle', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'rejectParcelFromVehicle'])->name('operationBookingParcel.rejectParcelFromVehicle');

    /** After First Warehouse Assign */
    Route::post('operationBookingParcel/getParcelListForVehicleToWarehouseReceive', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'getParcelListForVehicleToWarehouseReceive'])->name('operationBookingParcel.getParcelListForVehicleToWarehouseReceive');
    Route::post('operationBookingParcel/getParcelListForWarehouseToVehicleWarehouseAssign', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'getParcelListForWarehouseToVehicleWarehouseAssign'])->name('operationBookingParcel.getParcelListForWarehouseToVehicleWarehouseAssign');
    Route::post('operationBookingParcel/confirmAssignVehicleOrWarehouse', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'confirmAssignVehicleOrWarehouse'])->name('operationBookingParcel.confirmAssignVehicleOrWarehouse');

    Route::get('operationBookingParcel/bookingParcelOperation', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'bookingParcelOperation'])->name('operationBookingParcel.bookingParcelOperation');
    Route::post('operationBookingParcel/rejectParcelFromWarehouse', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'rejectParcelFromWarehouse'])->name('operationBookingParcel.rejectParcelFromWarehouse');
    Route::post('operationBookingParcel/confirmWarehouseReceived', [App\Http\Controllers\Admin\BookingParcelAssignRejectController::class, 'confirmWarehouseReceived'])->name('operationBookingParcel.confirmWarehouseReceived');

    Route::resource('operationBookingParcel', \App\Http\Controllers\Admin\BookingParcelAssignRejectController::class);


    /** Profile Route */
    Route::get('profile', [App\Http\Controllers\Admin\HomeController::class, 'profile'])->name('profile');


    /** Export Report */
    /** Booking Parcel Export Route */
    Route::get('merchant-pickup-parcel-report/export', [\App\Http\Controllers\Export\MerchantPickupParcelReportExportController::class, 'exportReport'])->name('merchantPickupParcelReportExport');

    //service Type
    Route::get("service-types", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'index'])->name('service.type');
    Route::get("service-types/create", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'create'])->name('service.type.create');
    Route::post("service-types/create", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'store']);
    Route::get("service-types/datatable", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'datatable'])->name('service.type.datatable');
    Route::get("service-types/print", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'print'])->name('service.type.print');
    Route::post("service-types/updateStatus", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'updateStatus'])->name('service.type.update.status');
    Route::get("service-types/edit/{id}", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'edit'])->name('service.type.edit');
    Route::post("service-types/edit/{id}", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'update']);
    Route::delete("service-types/delete", [\App\Http\Controllers\Admin\ServiceTypeController::class, 'delete'])->name('service.type.delete');

    //Item Type
    Route::get("item-types", [\App\Http\Controllers\Admin\ItemTypeController::class, 'index'])->name('item.type');
    Route::get("item-types/create", [\App\Http\Controllers\Admin\ItemTypeController::class, 'create'])->name('item.type.create');
    Route::post("item-types/create", [\App\Http\Controllers\Admin\ItemTypeController::class, 'store']);
    Route::get("item-types/datatable", [\App\Http\Controllers\Admin\ItemTypeController::class, 'datatable'])->name('item.type.datatable');
    Route::get("item-types/print", [\App\Http\Controllers\Admin\ItemTypeController::class, 'print'])->name('item.type.print');
    Route::post("item-types/updateStatus", [\App\Http\Controllers\Admin\ItemTypeController::class, 'updateStatus'])->name('item.type.update.status');
    Route::get("item-types/edit/{id}", [\App\Http\Controllers\Admin\ItemTypeController::class, 'edit'])->name('item.type.edit');
    Route::post("item-types/edit/{id}", [\App\Http\Controllers\Admin\ItemTypeController::class, 'update']);
    Route::delete("item-types/delete", [\App\Http\Controllers\Admin\ItemTypeController::class, 'delete'])->name('item.type.delete');


    Route::get("rider-payments/", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'index'])->name('rider.payment');
    Route::get("rider-payments/create", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'create'])->name('rider.payment.create');
    Route::post("rider-payments/create", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'store']);
    Route::get("rider-payments/datatable", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'datatable'])->name('rider.payment.datatable');
    Route::post("rider-payments/getRiderByBranch", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'getRiderByBranch'])->name('rider.payment.getRiderByBranch');
    Route::post("rider-payments/getRiderById", [\App\Http\Controllers\Admin\RiderPaymentController::class, 'getRiderById'])->name('rider.payment.getRiderById');

    /** Notice Section Route */

    Route::get('notice/getNoticeList', [App\Http\Controllers\Admin\NoticeController::class, 'getNoticeList'] )->name('notice.getNoticeList');
    Route::post('notice/updateStatus', [App\Http\Controllers\Admin\NoticeController::class, 'updateStatus'] )->name('notice.updateStatus');
    Route::delete('notice/delete', [App\Http\Controllers\Admin\NoticeController::class, 'delete'] )->name('notice.delete');
    Route::resource('notice', App\Http\Controllers\Admin\NoticeController::class);

});
