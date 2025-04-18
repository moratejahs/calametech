<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => '09123456789',
            'address' => 'Tandag City',
            'email_verified_at' => now(),
            'plain_password' => $validated['password'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->attach(2); // User role

        // event(new Registered($user)); // Send email verification link

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'success' => 'Signup successfully',
            'token' => $token,
            'user' => $user->only('id', 'name', 'email'),
        ], 201);
    }
}
