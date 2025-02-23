<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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

    public function store() {}
}
