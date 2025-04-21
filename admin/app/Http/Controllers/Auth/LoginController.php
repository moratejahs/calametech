<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('handleLogout');
    }

    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function handleLogin(Request $request)
    {
        // dd($request->all());
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $this->redirectTo();
        }

        return redirect()
            ->back()
            ->withErrors(['auth-error' => 'Invalid email or password.'])
            ->withInput();
    }

    public function redirectTo()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return redirect()->route('super-admin.super-admin-dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.admin-dashboard');
        }

        return redirect()
            ->back()
            ->withErrors(['account' => 'Invalid email and password'])
            ->withInput();
    }

    public function handleLogout()
    {
        Auth::guard()->logout();

        return redirect()->route('auth.login');
    }
}
