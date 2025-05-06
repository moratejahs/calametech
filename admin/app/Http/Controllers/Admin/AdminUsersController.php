<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('admin.admin-users');
    }
    public function userVerification(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->is_verified = true;
            $user->save();
            return redirect()->back()->with('success', 'User verified successfully');
        }
        return redirect()->back()->with('success', 'User not found');
    }

    public function store() {}
}
