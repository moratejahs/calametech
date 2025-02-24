<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatedTokenSessionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

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
            'user' => $user->only('id', 'name', 'email'),
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => 'Logged out successful',
        ], 200);
    }
}
