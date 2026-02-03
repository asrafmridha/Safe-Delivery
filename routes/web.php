<?php

use App\Mail\WelcomeMail;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    Artisan::call('clear-compiled');
    return "Clear";
});
Route::get('/queue-listen', function() {
    Artisan::call('queue:listen');
    return "done";
});

Route::match(['post', 'get'],'/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('frontend.home');

Route::get('/test_sms', [App\Http\Controllers\Frontend\HomeController::class, 'test_sms']);

Route::get('/about', [App\Http\Controllers\Frontend\HomeController::class, 'about'])->name('frontend.about');
Route::get('/team', [App\Http\Controllers\Frontend\HomeController::class, 'teamMember'])->name('frontend.teamMember');
Route::get('/quotation', [App\Http\Controllers\Frontend\HomeController::class, 'quotation'])->name('frontend.quotation');
Route::get('/faq', [App\Http\Controllers\Frontend\HomeController::class, 'faq'])->name('frontend.faq');
Route::get('/services', [App\Http\Controllers\Frontend\HomeController::class, 'services'])->name('frontend.services');
Route::get('/service/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'serviceDetails'])->name('frontend.serviceDetails');
Route::get('/delivery', [App\Http\Controllers\Frontend\HomeController::class, 'delivery'])->name('frontend.delivery');
Route::get('/blogs', [App\Http\Controllers\Frontend\HomeController::class, 'blogs'])->name('frontend.blogs');
Route::get('/blog/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'blogDetails'])->name('frontend.blogDetails');
Route::get('/contact', [App\Http\Controllers\Frontend\HomeController::class, 'contact'])->name('frontend.contact');
Route::post('/visitorMessages', [App\Http\Controllers\Frontend\HomeController::class, 'visitorMessages'])->name('frontend.visitorMessages');
Route::post('/newsLetter', [App\Http\Controllers\Frontend\HomeController::class, 'newsLetter'])->name('frontend.newsLetter');

Route::post('/orderTracking', [App\Http\Controllers\Frontend\HomeController::class, 'orderTracking'])->name('frontend.orderTracking');
Route::get('/orderTracking', [App\Http\Controllers\Frontend\HomeController::class, 'orderTracking']);

Route::get('/privacy-policy', [App\Http\Controllers\Frontend\HomeController::class, 'getPrivacyPolicy'])->name('frontend.getPrivacyPolicy');

Route::get('/merchant-registration', [App\Http\Controllers\Frontend\HomeController::class, 'merchantRegistration'])->name('frontend.merchantRegistration');
Route::post('/merchant-registration', [App\Http\Controllers\Admin\MerchantController::class, 'confirmMerchantRegistration'])->name('frontend.confirmMerchantRegistration');

Route::post('/privacypolicy', [App\Http\Controllers\Frontend\HomeController::class, 'privacypolicy'])->name('frontend.privacypolicy');

Route::post('/returnWeightPackageOptionAndCharge', [\App\Http\Controllers\Frontend\HomeController::class, 'returnWeightPackageOptionAndCharge'] )->name('returnWeightPackageOptionAndCharge');

Route::get('/otp-merchant-registration', [App\Http\Controllers\AuthController::class, 'otp_merchant_registration_login'] )->name('frontend.otp_merchant_registration_login');
Route::post('/otp-merchant-registration', [App\Http\Controllers\AuthController::class, 'otp_merchant_registration_check'] )->name('frontend.otp_merchant_registration_check');


Route::post('district/districtByDivision', [App\Http\Controllers\Admin\DistrictController::class, 'districtByDivision'] )->name('district.districtByDivision');
Route::post('ItemCategory/getItemByCategory', [App\Http\Controllers\Admin\ItemCategoryController::class, 'getItemByCategory'] )->name('ItemCategory.getItemByCategory');

Route::post('upazila/districtOption', [App\Http\Controllers\Admin\UpazilaController::class, 'districtOption'] )->name('upazila.districtOption');
Route::post('area/districtWiseAreaOption', [App\Http\Controllers\Admin\AreaController::class, 'districtWiseAreaOption'] )->name('area.districtWiseAreaOption');
Route::post('area/areaOption', [App\Http\Controllers\Admin\AreaController::class, 'areaOption'] )->name('area.areaOption');
Route::post('weightPackage/weightPackageOption', [App\Http\Controllers\Admin\WeightPackageController::class, 'weightPackageOption'] )->name('weightPackage.weightPackageOption');

Route::post('branch/branchResult', [App\Http\Controllers\Admin\BranchController::class, 'branchResult'] )->name('branch.branchResult');

Route::post('rider/riderResult', [App\Http\Controllers\Admin\RiderController::class, 'riderResult'] )->name('rider.riderResult');
Route::post('rider/riderOption', [App\Http\Controllers\Admin\RiderController::class, 'riderOption'] )->name('rider.riderOption');


Route::post('merchant/serviceAreaCharge', [App\Http\Controllers\Admin\MerchantController::class, 'serviceAreaCharge'] )->name('merchant.serviceAreaCharge');

Route::post('merchant/returnMerchantUpazilaWeightPackageOptionAndCharge', [App\Http\Controllers\Admin\MerchantController::class, 'returnMerchantUpazilaWeightPackageOptionAndCharge'] )->name('merchant.returnMerchantUpazilaWeightPackageOptionAndCharge');
Route::get('parcel/{parcel}/printParcel', [App\Http\Controllers\Admin\ParcelController::class, 'printParcel'] )->name('parcel.printParcel');


Route::post('getPathaoZone', [App\Http\Controllers\Branch\PathaoController::class, 'getPathaoZone'])->name('getPathaoZone');
Route::post('getPathaoArea', [App\Http\Controllers\Branch\PathaoController::class, 'getPathaoArea'])->name('getPathaoArea');


Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'] )->name('frontend.login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login_check'])->name('frontend.login');
Route::get('/otp-login', [App\Http\Controllers\AuthController::class, 'otp_login'] )->name('frontend.otp_login');
Route::post('/otp-login', [App\Http\Controllers\AuthController::class, 'otp_check'] )->name('frontend.otp_login');


// Admin Route
Route::group(['as' => 'admin.'], base_path('routes/admin.php'));

// Merchant Route
Route::group(['as' => 'merchant.'], base_path('routes/merchant.php'));

// Branch Route
Route::group(['as' => 'branch.'], base_path('routes/branch.php'));

// Rider Route
Route::group(['as' => 'rider.'], base_path('routes/rider.php'));

// Rider Route
Route::group(['as' => 'warehouse.'], base_path('routes/warehouse.php'));


/** Booking Parcel Export Route */
Route::get('booking_parcel/export', [\App\Http\Controllers\BookingParcelExportController::class, 'bookingParcelListExport'])->name('bookingParcelExport');


/** For EventS */
Route::get('/event-test', function() {


    //new Ratchet\WebSocket\WsServer('127.0.0.1');

    event(new \App\Events\RealTimeMessage('Hello This Is Event Test!'));

    return "Hi, Event Test";
});

Route::get('/admin-event-test', [\App\Http\Controllers\Controller::class, 'adminDashboardCounterEvent'])->name('admin_dashboard_counter');

Route::get('/branch-event-test/{id}', [\App\Http\Controllers\Controller::class, 'branchDashboardCounterEvent'])->name('merchant_dashboard_counter');

Route::get('/merchant-event-test/{id}', [\App\Http\Controllers\Controller::class, 'merchantDashboardCounterEvent'])->name('merchant_dashboard_counter');

/** For Notifications */
Route::get('/merchant-registration-notification/markasread', [\App\Http\Controllers\MerchantRegisterNotificationController::class, 'notificationRead'])->name('notificationRead');


Route::get('/notification-test', function() {

    $admin_user = \App\Models\Admin::first();

    $admin_user->notify(new \App\Notifications\RealTimeNotification('Hello This Is Notification Test!'));

    return "Hi, Notification Test";
});

Route::get('/merchant-notification-test', function() {

    $admin_user = \App\Models\Merchant::where('id', 5)->first();
    $parcel_data = \App\Models\Parcel::where('merchant_id', $admin_user->id)->first();

    $admin_user->notify(new \App\Notifications\MerchantParcelNotification($parcel_data));

    return "Hi, Merchant Notification Test";
});


/** Test SMS */
Route::get('/send-sms/test', [App\Http\Controllers\HomeController::class, 'send_sms_test']);
Route::get('/vue_js_test', [App\Http\Controllers\HomeController::class, 'vue_js_test']);



/** Api Route Test */
Route::get('orderPlacement', [App\Http\Controllers\ApiTestController::class, 'storeOrder'])->name('paperfly.orderPlacement');
