<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Cache;
use Illuminate\Support\Facades\Cache as FacadesCache;
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
        //
        User::created(function () {
            FacadesCache::forget('stats');
        });
    
        User::updated(function () {
            FacadesCache::forget('stats');
        });
    
        User::deleted(function () {
            FacadesCache::forget('stats');
        });
    
        Post::created(function () {
            FacadesCache::forget('stats');
        });
    
        Post::updated(function () {
            FacadesCache::forget('stats');
        });
    
        Post::deleted(function () {
            FacadesCache::forget('stats');
        });
    }
}
