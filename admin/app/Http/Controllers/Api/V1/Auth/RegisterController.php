<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        try {
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
                'is_verified' => false,
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
                    'is_verified' => (bool) $user->is_verified,
                ],
            ], 201);
        } catch (\Throwable $th) {
            Log::error('ERROR NI:' . $th->getMessage());
        }

    }
}
