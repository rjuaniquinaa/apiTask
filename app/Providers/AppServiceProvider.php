<?php

namespace App\Providers;

use App\Observers\TaskObserver;
use App\Task;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Mongodb\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Task::observe(TaskObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Fix of Jenssengers component for laravel 5.4.*
        Builder::macro('getName', function() {
            return 'mongodb';
        });
    }
}
