<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\EmailVerifyController;
use App\Http\Controllers\Api\V1\Auth\AuthenticatedTokenSessionController;
use Illuminate\Support\Facades\Broadcast;

// Broadcasting routes
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::prefix('v1')->group(function () {
    // Login
    Route::post('/login', [AuthenticatedTokenSessionController::class, 'store']);

    // Register
    Route::post('/register', RegisterController::class);

    // Email Verification
    Route::get('/email/verify/{id}/{hash}', EmailVerifyController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        // Logout
        Route::post('/logout', [AuthenticatedTokenSessionController::class, 'destroy']);

        // Get User
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // News
        Route::get('/news', [NewsController::class, 'index']);

        // Report
        Route::post('/report', [ReportController::class, 'store']);
        Route::put('/report/{id}', [ReportController::class, 'update']);
    });
});
