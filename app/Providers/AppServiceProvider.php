<?php

namespace App\Providers;

use App\Models\Item;
use App\Observers\AuditableObserver;
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
    }

    protected $policies = [
    \App\Models\Item::class => \App\Policies\ItemPolicy::class,
    ];

}
