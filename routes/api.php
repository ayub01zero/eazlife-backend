<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\CompanyLikesController;
use App\Http\Controllers\API\GeocodeController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\SliderController;
use App\Http\Controllers\API\SlotController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/verify', [UserController::class, 'verify']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/request-password-reset', [UserController::class, 'requestPasswordReset']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::post('/get-slider', [SliderController::class, 'getSlider']);


Route::post('/get-address-from-location', [GeocodeController::class, 'getAddressFromLocation']);
Route::post('/get-location-from-address', [GeocodeController::class, 'getLocationFromAddress']);


Route::post('/get-company-types', [CompanyController::class, 'getCompanyTypes']);
Route::post('/get-companies-near-location', [CompanyController::class, 'getCompaniesNearLocation']);
Route::post('/get-company-details', [CompanyController::class, 'getCompanyDetails']);
Route::post('/get-product-extras', [ProductController::class, 'getProductExtras']);

Route::post('/search', [CompanyController::class, 'search']);

Route::post('/get-available-slots', [SlotController::class, 'getAvailableSlots']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/edit-user', [UserController::class, 'edit']);
    Route::post('/get-notifications', [NotificationController::class, 'index']);
    Route::post('/toggle-company-like', [CompanyLikesController::class, 'like']);
    Route::post('/is-company-liked', [CompanyLikesController::class, 'isLiked']);

    Route::post('/store-order', [OrderController::class, 'store']);
    Route::post('/pay-order', [OrderController::class, 'payOrder']);
    Route::post('/fetch-new-client-secret', [OrderController::class, 'fetchNewClientSecret']);
    Route::post('/confirm-payment', [OrderController::class, 'confirmPayment']);
    Route::post('/store-reservation', [SlotController::class, 'store']);
    Route::post('/get-orders', [OrderController::class, 'index']);
    Route::post('/get-reservations', [ReservationController::class, 'index']);
    Route::post('/get-active-orders', [OrderController::class, 'getActiveOrders']);
});
