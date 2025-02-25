<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SOSController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\Auth\SignupController;
use App\Http\Controllers\Api\V1\Auth\EmailVerifyController;
use App\Http\Controllers\Api\V1\Auth\AuthenticatedTokenSessionController;

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

Route::prefix('v1')->group(function () {
    // Login
    Route::post('/login', [AuthenticatedTokenSessionController::class, 'store']);

    // Signup
    Route::post('/signup', SignupController::class);

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

        // SOS
        Route::post('/sos', SOSController::class);

        // Report
        Route::post('/report', ReportController::class);
    });
});
