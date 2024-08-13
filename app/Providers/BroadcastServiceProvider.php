<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class BroadcastServiceProvider extends ServiceProvider
{
   
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    { Schema::defaultStringLength(191);
        Schema::defaultStringLength(191);
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
