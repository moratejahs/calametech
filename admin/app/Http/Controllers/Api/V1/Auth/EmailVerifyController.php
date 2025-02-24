<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{
    public function __invoke(Request $request, string $id, string $hash)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return view('email-already-verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('email-verified');
    }
}
