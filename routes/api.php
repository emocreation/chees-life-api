<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BulkUpdateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerHistoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DragController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RowUpdateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TimeslotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\v1\AuthController as v1_AuthController;
use App\Http\Controllers\v1\BannerController as v1_BannerController;
use App\Http\Controllers\v1\CategoryController as v1_CategoryController;
use App\Http\Controllers\v1\DistrictController as v1_DistrictController;
use App\Http\Controllers\v1\HomeController as v1_HomeController;
use App\Http\Controllers\v1\ReviewController as v1_ReviewController;
use App\Http\Controllers\v1\ServiceController as v1_ServiceController;
use App\Http\Controllers\v1\SocialMediaController as v1_SocialMediaController;
use App\Http\Controllers\v1\TimeslotController as v1_TimeslotController;
use App\Http\Controllers\v1\UserController as v1_UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//CMS API
Route::name('auth.')->prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('base', 'base')->name('base');
    Route::post('login', 'login')->name('login');
    Route::middleware(['middleware' => 'auth:sanctum', 'abilities:cms'])->group(function () {
        Route::get('logout', 'logout')->name('logout');
        Route::get('user', 'user')->name('user');
        Route::post('user/update-password', 'updatePassword')->name('update-password');
    });
});
Route::middleware(['middleware' => 'auth:sanctum', 'abilities:cms'])->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'reviews' => ReviewController::class,
        'districts' => DistrictController::class,
        'categories' => CategoryController::class,
        'customers' => CustomerController::class,
        'banners' => BannerController::class,
        'services' => ServiceController::class,
        'roles' => RoleController::class,
        'customer_histories' => CustomerHistoryController::class,
        'timeslots' => TimeslotController::class
    ]);

    Route::apiResources([
        'social_medias' => SocialMediaController::class
    ], ['except' => ['store']]);

    Route::name('options.')->prefix('options')->controller(OptionController::class)->group(function () {
        Route::get('roles', 'role')->name('role');
        Route::get('permissions', 'permission')->name('permission');
        Route::get('customers', 'customer')->name('customer');
        Route::get('categories', 'category')->name('category');
    });

    Route::name('row_updates.')->prefix('row_updates')->controller(RowUpdateController::class)->group(function () {
        Route::post('banners', 'banner')->name('banner');
        Route::post('categories', 'category')->name('category');
        Route::post('services', 'service')->name('service');
        Route::post('districts', 'district')->name('district');
    });

    Route::name('drags.')->prefix('drags')->controller(DragController::class)->group(function () {
        Route::post('banners', 'banner')->name('banner');
        Route::post('categories', 'category')->name('category');
        Route::post('services', 'service')->name('service');
        Route::post('districts', 'district')->name('district');
    });

    Route::name('bulk_updates.')->prefix('bulk_updates')->controller(BulkUpdateController::class)->group(function () {
        Route::post('banners', 'banner')->name('banner');
        Route::post('categories', 'category')->name('category');
        Route::post('services', 'service')->name('service');
        Route::post('districts', 'district')->name('district');
        Route::post('reviews', 'review')->name('review');
        Route::post('social_medias', 'socialMedia')->name('socialMedia');
    });
});

//Frontend API
Route::name('v1.')->prefix('v1')->group(function () {
    Route::name('auth.')->prefix('auth')->controller(v1_AuthController::class)->group(function () {
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::get('verify/{token}', 'verify')->name('verify');
        Route::post('verify', 'resendVerificationEmail')->name('resendVerificationEmail');
        Route::post('reset_password', 'resetPasswordMail')->name('resetPasswordMail');
        Route::post('reset_password/{token}', 'resetPassword')->name('resetPassword');
        Route::middleware(['auth:sanctum', 'abilities:app'])->group(function () {
            Route::get('logout', 'logout')->name('logout');
        });
    });
    Route::name('users.')->prefix('users')->middleware(['auth:sanctum', 'abilities:app'])->controller(v1_UserController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('histories', 'show')->name('show');
        Route::put('', 'update')->name('update');
    });

    Route::get('home', [v1_HomeController::class, 'index'])->name('home.index');
    Route::get('banners', [v1_BannerController::class, 'index'])->name('banners.index');
    Route::get('categories', [v1_CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{slug}', [v1_CategoryController::class, 'show'])->name('categories.show');
    Route::get('reviews', [v1_ReviewController::class, 'index'])->name('reviews.index');
    Route::get('districts', [v1_DistrictController::class, 'index'])->name('districts.index');
    Route::get('social_medias', [v1_SocialMediaController::class, 'index'])->name('social_medias.index');
    Route::get('timeslots', [v1_TimeslotController::class, 'index'])->name('timeslots.index');

    Route::get('services/{category_slug}', [v1_ServiceController::class, 'index'])->name('services.index');
    Route::get('services/details/{slug}', [v1_ServiceController::class, 'show'])->name('services.show');
    Route::post('services', [v1_ServiceController::class, 'purchase'])->name('services.purchase');
    Route::post('services/webhook', [v1_ServiceController::class, 'webhook'])->name('services.webhook');
});



