<?php

use Illuminate\Http\Request;
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

use App\Http\Controllers\Api\v1\PropertyApiController;
use App\Http\Controllers\Api\v1\LocationApiController;
use App\Http\Controllers\Api\v1\PropertyTypeApiController;
use App\Http\Controllers\Api\v1\AgentApiController;
use App\Http\Controllers\Api\v1\AuthApiController;
use App\Http\Controllers\Api\v1\UserApiController;
use App\Http\Controllers\Api\v1\SubscriptionApiController;
use App\Http\Controllers\Api\v1\BookingApiController;
use App\Http\Controllers\Api\v1\CustomFieldApiController;

Route::prefix('v1')->group(function () {
    // Custom Fields Public API
    Route::get('/custom-fields', [CustomFieldApiController::class, 'index']);
    Route::get('/property-custom-fields', [CustomFieldApiController::class, 'propertyFields']);
    Route::get('/custom-field-values', [CustomFieldApiController::class, 'values']);
    // Public routes
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);

    Route::get('/properties', [PropertyApiController::class, 'index']);
    Route::get('/properties/{slug}', [PropertyApiController::class, 'show']);
    Route::get('/search', [PropertyApiController::class, 'search']);
    
    Route::get('/states', [LocationApiController::class, 'states']);
    Route::get('/cities', [LocationApiController::class, 'cities']);
    
    Route::get('/property-types', [PropertyTypeApiController::class, 'index']);
    Route::get('/agents', [AgentApiController::class, 'index']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout']);
        
        Route::get('/user/profile', [UserApiController::class, 'profile']);
        Route::get('/user/wishlist', [UserApiController::class, 'wishlist']);
        Route::post('/user/wishlist/{propertyId}', [UserApiController::class, 'toggleWishlist']);

        Route::get('/subscriptions', [SubscriptionApiController::class, 'index']);
        Route::post('/subscriptions/subscribe', [SubscriptionApiController::class, 'subscribe']);

        Route::post('/bookings', [BookingApiController::class, 'store']);
        Route::post('/custom-field-values', [CustomFieldApiController::class, 'values']);
    });
});
