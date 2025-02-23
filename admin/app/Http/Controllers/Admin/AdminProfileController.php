<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // TODO: Retrieve user recently completed projects
        return view('admin.admin-profile');
    }
}
