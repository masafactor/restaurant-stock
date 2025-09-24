<?php

namespace App\Providers;

use App\Models\Item;
use App\Observers\AuditableObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);
        Item::observe(AuditableObserver::class);
        Gate::define('viewReports', fn($user) => in_array($user->role, ['Admin','Manager']));
    }

    protected $policies = [
    \App\Models\Item::class => \App\Policies\ItemPolicy::class,
    ];

}
