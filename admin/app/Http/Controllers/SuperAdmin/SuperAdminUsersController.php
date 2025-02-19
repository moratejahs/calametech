<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class SuperAdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $userRecord = DB::table('role_user as role_users')
        ->select(
            'role_users.user_id',
            'role_users.role_id',
            'users.name',
            'users.email',
            'contact_number',
            'users.address',
            'users.password',
            'users.plain_password',
            'roles.description'
        )
        ->join('users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'roles.id', '=', 'role_users.role_id')
        ->get();

        return view('super-admin.super-admin-users', compact('userRecord'));
    }

    public function store(Request $request){

        $roleId = $request->role_name;

        $userData = User::create([
            'name'           => $request->name,
            'address'        => $request->address,
            'email'          => $request->email,
            'contact_number' => $request->contact,
            'password'       => bcrypt($request->password),
            'plain_password' => Crypt::encrypt($request->password),
        ]);

        $userData->roles()->attach($roleId);

        $request->session()->flash('success_message', 'Added Successfully!');

        return redirect()->back();
    }

    public function edit (Request $request){

        $userId = $request->userId;
        $roleId  = $request->roleId;

        $userData = User::findOrFail($userId);
        $userData ->update([
            'name'           => $request->name,
            'address'        => $request->address,
            'email'          => $request->email,
            'contact_number' => $request->contact,
            'password'       => bcrypt($request->password),
            'plain_password' => Crypt::encrypt($request->password),
        ]);

        $userData->roles()->sync($roleId);

        $request->session()->flash('success_message', 'Updated Successfully!');

        return redirect()->back();
    }

    public function destroy(Request $request){

        $userId  = $request->remove_userId;
        $roleId  = $request->remove_roleId;

        $userData = User::findOrFail($userId);
        $userData ->delete();
        $userData ->roles()->detach($roleId);

        $request->session()->flash('success_message', 'Deleted Successfully!');

        return redirect()->back();
    }

}
