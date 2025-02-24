<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer(

            [
                'super-admin.include.project_modal.super-admin-store-project',
                'super-admin.include.project_modal.super-admin-view-project',
                'super-admin.include.user_modal.super-admin-store-user',
                'super-admin.include.user_modal.super-admin-show-user',

            ],

            function ($view) {

                $userRecord = User::all(['id', 'name']);
                $roleRecord = Role::all(['id', 'description']);
                $statusRecord = ['Not Started', 'In progress', 'Done'];

                $view->with([
                    'userRecord' => $userRecord,
                    'roleRecord' => $roleRecord,
                    'statusRecord' => $statusRecord,
                ]);

            }
        );

    }
}
