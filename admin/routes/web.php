<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Calametech\UserController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminProjectsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminUsersController;
use App\Http\Controllers\Admin\AdminProjectsCompletedController;
use App\Http\Controllers\SuperAdmin\SuperAdminProfileController;
use App\Http\Controllers\Admin\AdminProjectsInProgressController;
use App\Http\Controllers\Admin\AdminProjectsNotStartedController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectsController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\Admin\AdminProjectsBehindScheduleController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectsCompletedController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectsInProgressController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectsNotStartedController;
use App\Http\Controllers\SuperAdmin\SuperAdminProjectsBehindScheduleController;

// Home Endpoints
Route::get('/', function () {
    return redirect('/login');
});

// Auth Endpoints
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [LoginController::class, 'handleLogin'])->name('login');
Route::post('/logout', [LoginController::class, 'handleLogout'])->name('logout');

// Admin Endpoints
Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.admin-dashboard');

    Route::get('users', [AdminUsersController::class, 'index'])
        ->name('admin.admin-users');

    Route::get('projects', [AdminProjectsController::class, 'index'])
        ->name('admin.admin-projects');

    Route::post('/update-project-status', [AdminProjectsController::class, 'updateProjectStatus'])
        ->name('admin.update.project.status');

    Route::post('store/projects', [AdminProjectsController::class, 'store'])
        ->name('store.admin.admin-projects');

    Route::put('edit/project', [AdminProjectsController::class, 'edit'])
        ->name('edit.admin.admin-project');

    Route::post('delete/project', [AdminProjectsController::class, 'destroy'])
        ->name('delete.admin.admin-project');

    Route::get('profile', [AdminProfileController::class, 'index'])
        ->name('admin.admin-profile');

    // Projects Completed Routes
    Route::get('projects/completed', [AdminProjectsCompletedController::class, 'index'])
        ->name('admin.admin-projects-completed');
    Route::put('edit/project/completed', [AdminProjectsCompletedController::class, 'edit'])
        ->name('edit.admin.admin-project-completed');
    Route::delete('delete/project/completed', [AdminProjectsCompletedController::class, 'destroy'])
        ->name('delete.admin.admin-project-completed');
    // Projects Not Started Routes
    Route::get('projects/not-started', [AdminProjectsNotStartedController::class, 'index'])
        ->name('admin.admin-projects-not-started');
    Route::put('edit/project/not-started', [AdminProjectsNotStartedController::class, 'edit'])
        ->name('edit.admin.admin-projects-not-started');
    Route::delete('delete/project/not-started', [AdminProjectsNotStartedController::class, 'destroy'])
        ->name('delete.admin.admin-projects-not-started');
    // Projects In Progress Routes
    Route::get('projects/in-progress', [AdminProjectsInProgressController::class, 'index'])
        ->name('admin.admin-projects-in-progress');
    Route::put('edit/project/in-progressd', [AdminProjectsInProgressController::class, 'edit'])
        ->name('edit.admin.admin-projects-in-progress');
    Route::delete('delete/project/in-progress', [AdminProjectsInProgressController::class, 'destroy'])
        ->name('delete.admin.admin-projects-in-progress');
    // Projects Behind Schedule Routes
    Route::get('projects/behind-schedule', [AdminProjectsBehindScheduleController::class, 'index'])
        ->name('admin.admin-projects-behind-schedule');
    Route::put('edit/projects/behind-schedule', [AdminProjectsBehindScheduleController::class, 'edit'])
        ->name('edit.admin.admin-projects-behind-schedule');
    Route::delete('delete/projects/behind-schedule', [AdminProjectsBehindScheduleController::class, 'destroy'])
        ->name('delete.admin.admin-projects-behind-schedule');

    Route::controller(UserController::class)->group(function (){
         Route::get('manage-users', 'index')->name('manage-users.index');
    });
});

// Super Admin Endpoints
Route::prefix('super-admin')->group(function () {

    Route::get('dashboard', [SuperAdminDashboardController::class, 'index'])
        ->name('super-admin.super-admin-dashboard');

    /* THIS IS FOR THE USER  FUNCTION */
    Route::get('users', [SuperAdminUsersController::class, 'index'])
        ->name('super-admin.super-admin-users');

    Route::post('store/users', [SuperAdminUsersController::class, 'store'])
        ->name('store.super-admin.super-admin-users');

    Route::put('edit/user', [SuperAdminUsersController::class, 'edit'])
        ->name('edit.super-admin.super-admin-user');

    Route::post('delete/user', [SuperAdminUsersController::class, 'destroy'])
        ->name('delete.super-admin.super-admin-user');

    /* THIS IS FOR THE PROJECT  FUNCTION */
    Route::post('/update-project-status', [SuperAdminProjectsController::class, 'updateProjectStatus'])
        ->name('update.project.status');

    Route::get('projects', [SuperAdminProjectsController::class, 'index'])
        ->name('super-admin.super-admin-projects');

    Route::get('projects/completed', [SuperAdminProjectsController::class, 'completedProjects'])
        ->name('super-admin.super-admin-projects-completed');

    Route::get('projects/in-progress', [SuperAdminProjectsController::class, 'inProgressProjects'])
        ->name('super-admin.super-admin-projects-in-progress');

    Route::get('projects/behind-schedule', [SuperAdminProjectsController::class, 'behindScheduleProjects'])
        ->name('super-admin.super-admin-projects-behind-schedule');

    Route::post('store/projects', [SuperAdminProjectsController::class, 'store'])
        ->name('store.super-admin.admin-projects');

    Route::put('edit/project', [SuperAdminProjectsController::class, 'edit'])
        ->name('edit.super-admin.admin-project');

    Route::post('destroy/projects', [SuperAdminProjectsController::class, 'destroy'])
        ->name('destroy.super-admin.admin-projects');

    Route::get('profile', [SuperAdminProfileController::class, 'index'])
        ->name('super-admin.super-admin-profile');

    // Projects Not Started Routes
    Route::get('projects/not-started', [SuperAdminProjectsNotStartedController::class, 'index'])
        ->name('super-admin.super-admin-projects-not-started');
    Route::put('edit/project/not-started', [SuperAdminProjectsNotStartedController::class, 'edit'])
        ->name('edit.super-admin.super-admin-projects-not-started');
    Route::delete('delete/project/not-started', [SuperAdminProjectsNotStartedController::class, 'destroy'])
        ->name('delete.super-admin.super-admin-projects-not-started');
    // Projects Completed Routes
    Route::get('projects/completed', [SuperAdminProjectsCompletedController::class, 'index'])
        ->name('super-admin.super-admin-projects-completed');
    Route::put('edit/project/completed', [SuperAdminProjectsCompletedController::class, 'edit'])
        ->name('edit.super-admin.super-admin-project-completed');
    Route::delete('delete/project/completed', [SuperAdminProjectsCompletedController::class, 'destroy'])
        ->name('delete.super-admin.super-admin-project-completed');
    // Projects In Progress Routes
    Route::get('projects/in-progress', [SuperAdminProjectsInProgressController::class, 'index'])
        ->name('super-admin.super-admin-projects-in-progress');
    Route::put('edit/project/in-progressd', [SuperAdminProjectsInProgressController::class, 'edit'])
        ->name('edit.super-admin.super-admin-projects-in-progress');
    Route::delete('delete/project/in-progress', [SuperAdminProjectsInProgressController::class, 'destroy'])
        ->name('delete.super-admin.super-admin-projects-in-progress');
    // Projects Behind Schedule Routes
    Route::get('projects/behind-schedule', [SuperAdminProjectsBehindScheduleController::class, 'index'])
        ->name('super-admin.super-admin-projects-behind-schedule');
    Route::put('edit/projects/behind-schedule', [SuperAdminProjectsBehindScheduleController::class, 'edit'])
        ->name('edit.super-admin.super-admin-projects-behind-schedule');
    Route::delete('delete/projects/behind-schedule', [SuperAdminProjectsBehindScheduleController::class, 'destroy'])
        ->name('delete.super-admin.super-admin-projects-behind-schedule');
});
