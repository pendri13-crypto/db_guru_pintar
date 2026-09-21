<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Paginator::defaultView('pagination.custom');

        // Share school and teacher settings globally with all blade views
        View::composer('*', function ($view) {
            static $setting = null;
            if ($setting === null) {
                try {
                    if (Schema::hasTable('school_settings')) {
                        $setting = SchoolSetting::first();
                    }
                } catch (\Throwable $e) {
                    $setting = null;
                }
            }
            $view->with('appSetting', $setting);
        });
    }
}
