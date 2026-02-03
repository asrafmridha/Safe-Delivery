<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Admin;


// Route::post('/merchant/login', [App\Http\Controllers\API\Merchant\HomeController::class, 'login'])->name('api.merchant.login');

Route::get('/getDistricts', [App\Http\Controllers\API\HomeController::class, 'getDistricts']);
Route::get('/getDistrict', [App\Http\Controllers\API\HomeController::class, 'getDistrict']);
Route::get('/getUpazilas', [App\Http\Controllers\API\HomeController::class, 'getUpazilas']);
Route::get('/getUpazila', [App\Http\Controllers\API\HomeController::class, 'getUpazila']);
Route::get('/getServiceAreas', [App\Http\Controllers\API\HomeController::class, 'getServiceAreas']);



Route::get('/getAreas', [App\Http\Controllers\API\HomeController::class, 'getAreas']);
Route::get('/getArea', [App\Http\Controllers\API\HomeController::class, 'getArea']);

Route::get('/getBranches', [App\Http\Controllers\API\HomeController::class, 'getBranches']);
Route::get('/getBranch', [App\Http\Controllers\API\HomeController::class, 'getBranch']);

Route::get('/getRiders', [App\Http\Controllers\API\HomeController::class, 'getRiders']);
Route::get('/getRider', [App\Http\Controllers\API\HomeController::class, 'getRider']);

Route::get('/getWeightPackages', [App\Http\Controllers\API\HomeController::class, 'getWeightPackages']);
Route::get('/getWeightPackage', [App\Http\Controllers\API\HomeController::class, 'getWeightPackage']);

Route::get('/getDistrictWeightPackageRate', [App\Http\Controllers\API\HomeController::class, 'getDistrictWeightPackageRate']);
Route::get('/getMerchantUpazilaWeightPackageCODAndCharge', [App\Http\Controllers\API\HomeController::class, 'getMerchantUpazilaWeightPackageCODAndCharge']);


Route::post('orderStatusUpdate', [App\Http\Controllers\API\ParcelController::class, 'orderStatusUpdate']);

// Route::post('orderStatusUpdate', [App\Http\Controllers\API\ParcelController::class, 'orderStatusUpdate']);

Route::post('pathaoParcelStatus', [App\Http\Controllers\API\ParcelController::class, 'pathaoParcelStatus']);


// Merchant Route
Route::group(['as' => 'api.merchant.'], base_path('routes/merchant_api.php'));
Route::group(['as' => 'api.rider.'], base_path('routes/rider_api.php'));

// Route::group(['prefix' => 'auth'], function ($router) {
//     Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);
//     Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
//     Route::post('refresh', [App\Http\Controllers\API\AuthController::class, 'refresh']);
//     Route::post('me', [App\Http\Controllers\API\AuthController::class, 'me']);
//     Route::post('payload', [App\Http\Controllers\API\AuthController::class, 'payload']);
// });




Route::any('/{any}', function(){
    return response()->json([
        'success'   => 401,
        'message' => 'No Route is found'
    ],401);
})->where('any', '.*');
