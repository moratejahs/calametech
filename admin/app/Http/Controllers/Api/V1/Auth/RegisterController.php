<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use Storage;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = Storage::disk('public')->put('avatars', $validated['avatar']);
        }

        if ($request->hasFile('id_picture')) {
            $validated['id_picture'] = Storage::disk('public')->put('id_pictures', $validated['id_picture']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'contact_number' => $validated['phone'],
            'avatar' => $validated['avatar'],
            'email' => $validated['email'],
            'email_verified_at' => now(),
            'plain_password' => $validated['password'],
            'password' => $validated['password'],
            'id_picture' => $validated['id_picture'],
            'id_type' => $validated['id_type'],
        ]);

        $user->roles()->attach(2); // User role

        // event(new Registered($user)); // Will send an email verification

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'success' => 'Signup successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->contact_number,
                'avatar' => $user->avatar,
            ],
        ], 201);
    }
}
