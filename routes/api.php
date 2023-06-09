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
    Route::group(['middleware' => 'auth:sanctum', 'abilities:cms'], function () {
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



