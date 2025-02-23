<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticatedTokenSessionController;
use App\Http\Controllers\Api\V1\Auth\SignupController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
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

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthenticatedTokenSessionController::class, 'store']);

    Route::post('/signup', SignupController::class);

    // Verify Email
    Route::get('/email/verify/{id}/{hash}', function (Request $request) {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return view('email-already-verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('email-verified');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
        // Logout
        Route::post('/logout', [AuthenticatedTokenSessionController::class, 'destroy']);

        // Get User
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});
