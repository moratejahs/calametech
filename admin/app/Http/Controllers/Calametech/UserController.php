<?php

namespace App\Http\Controllers\Calametech;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\AccountVerified;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $totalUsers = User::count();
        $totalVerifiedUsers = User::where('is_verified', true)->count();
        $totalUnverifiedUsers = User::where('is_verified', false)->count();
        return view('user.index', compact('users', 'totalUsers', 'totalVerifiedUsers', 'totalUnverifiedUsers'));
    }

    public function userVerification(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->is_verified = true;
            $user->save();

            event(new AccountVerified($user));

            return redirect()->back()->with('success', 'User verified successfully');
        }
        return redirect()->back()->with('success', 'User not found');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //{{  }}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
