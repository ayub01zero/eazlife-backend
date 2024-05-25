<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware('guest')->group(function () {
//     Route::domain('aanmelden.' . parse_url(env('APP_URL'), PHP_URL_HOST))->group(function () {
//         Route::get('/', [RegisteredUserController::class, 'create'])->name('register');
//         Route::post('/', [RegisteredUserController::class, 'store']);
//     });

//     Route::get('/', function () {
//         return Redirect::route('register');
//     });
// });

Route::middleware('guest')->group(function () {
    Route::get('/business', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/business', [RegisteredUserController::class, 'store']);

    Route::get('/', function () {
        return Redirect::route('register');
    });
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/{company?}', [CompanyController::class, 'index'])->name('dashboard');

    Route::resource('companies', CompanyController::class);
    Route::post('/companies/{company}/upload-inventory', [CompanyController::class, 'uploadInventory'])->name('upload_inventory');
    Route::get('/companies/{company}/download-inventory', [CompanyController::class, 'downloadInventory'])->name('download_inventory');
    Route::post('/companies/{company}/update-opening-times', [CompanyController::class, 'updateOpeningTimes'])->name('update_opening_times');
    Route::post('/companies/{company}/update-status', [CompanyController::class, 'updateStatus'])->name('update_status');
    Route::patch('/companies/{company}/update-logo', [CompanyController::class, 'updateLogo'])->name('update_logo');
    Route::patch('/companies/{company}/update-banner', [CompanyController::class, 'updateBanner'])->name('update_banner');
    Route::delete('/companies/{company}/delete-logo', [CompanyController::class, 'deleteLogo'])->name('delete_logo');
    Route::delete('/companies/{company}/delete-banner', [CompanyController::class, 'deleteBanner'])->name('delete_banner');

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/send', [OrderController::class, 'send'])->name('orders.send');
    Route::post('/orders/pay', [OrderController::class, 'pay'])->name('orders.pay');

    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    Route::prefix('companies/{company}')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('companies.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('companies.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('companies.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('companies.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('companies.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('companies.users.destroy');
    });

    Route::resource('companies.products', ProductController::class)
        ->scoped([
            'products' => 'product_id'
        ]);
    Route::resource('companies.slots', SlotController::class);
    Route::post('companies/{company}/slots/create-for-week', [SlotController::class, 'createForWeek'])->name('companies.slots.create_for_week');
    // ->scoped([
    //     'slots' => 'slot_id'
    // ]);

    Route::get('/companies/{company}/product_categories/create-at-company', [ProductCategoryController::class, 'createAtCompany'])->name('product_categories.create-at-company');
    Route::post('/companies/{company}/product_categories/store-at-company', [ProductCategoryController::class, 'storeAtCompany'])->name('product_categories.store-at-company');
});

require __DIR__ . '/auth.php';
