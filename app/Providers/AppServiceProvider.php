<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('contains_number', function ($attribute, $value) {
            if (!is_string($value)) {
                return false;
            }
            return preg_match('/\d/', $value) === 1;
        });

        Validator::extend('contains_uppercase', function ($attribute, $value) {
            if (!is_string($value)) {
                return false;
            }
            return preg_match('/[A-Z]/', $value) === 1;
        });
    }
}
