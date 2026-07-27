<?php

namespace App\Providers;

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
        $broadcastUpdate = function () {
            try {
                event(new \App\Events\RankingUpdated());
            } catch (\Exception $e) {
                // Prevent failures if Pusher credentials are not configured or connection fails
                logger()->error('Broadcasting failed: ' . $e->getMessage());
            }
        };

        \App\Models\Sale::saved($broadcastUpdate);
        \App\Models\Sale::deleted($broadcastUpdate);

        \App\Models\User::saved($broadcastUpdate);
        \App\Models\User::deleted($broadcastUpdate);

        \App\Models\Target::saved($broadcastUpdate);
        \App\Models\Target::deleted($broadcastUpdate);

        \App\Models\Notice::saved($broadcastUpdate);
        \App\Models\Notice::deleted($broadcastUpdate);

        \App\Models\Department::saved($broadcastUpdate);
        \App\Models\Department::deleted($broadcastUpdate);

        \App\Models\Benchmark::saved($broadcastUpdate);
        \App\Models\Benchmark::deleted($broadcastUpdate);

        \App\Models\Role::saved($broadcastUpdate);
        \App\Models\Role::deleted($broadcastUpdate);
    }
}
