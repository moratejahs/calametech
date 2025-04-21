<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'phone' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'min:11', 'max:11'],
            'address' => ['required', 'string', 'max:100'],
        ], [
            'phone.regex' => 'The phone number must start with 09 and be 11 digits long.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['phone'],
            'address' => $validated['address'],
            'email_verified_at' => now(),
            'plain_password' => $validated['password'],
            'password' => $validated['password'],
        ]);

        $user->roles()->attach(2); // User role

        // event(new Registered($user)); // Send email verification link

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'success' => 'Signup successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->contact_number,
                'address' => $user->address,
            ],
        ], 201);
    }
}
