<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'errors' => [
                    'email' => ['Account not found.'],
                ],
            ], 422);
        }

        if ($user->email_verified_at === null) {
            return response()->json([
                'errors' => [
                    'email' => ['Email not verified.'],
                ],
            ], 422);
        }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => ['Account not found.'],
                ],
            ], 422);
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'success' => 'Logged in successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->contact_number,
                'address' => $user->address,
                'avatar' => $user->avatar,
            ],
        ], 200);
    }
}
