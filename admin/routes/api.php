<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\EmailVerifyController;

// Broadcasting
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::prefix('v1')->group(function () {
    // Login
    Route::post('/login', LoginController::class);

    // Register
    Route::post('/register', RegisterController::class);

    // Logout
    Route::post('/logout', LogoutController::class)
        ->middleware('auth:sanctum');

    // Email Verification
    Route::get('/email/verify/{id}/{hash}', EmailVerifyController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        // User
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // News
        Route::get('/news', [NewsController::class, 'index']);
        Route::get('/tips', [ReportController::class, 'index']);

        // Report
        Route::post('/report', [ReportController::class, 'store']);
        Route::put('/report/{id}', [ReportController::class, 'update']);
    });
});
