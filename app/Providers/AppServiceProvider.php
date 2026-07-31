<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $helper = app_path('Helpers/PermissionHelper.php');

        if (is_file($helper)) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {

            $view->with('loggedInUser', (object)[
                'first_name' => session('first_name'),
                'last_name'  => session('last_name'),
                'user_type'  => session('user_type'),
            ]);
        });
    }
}
