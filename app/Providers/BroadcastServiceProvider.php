<?php
// app/Providers/BroadcastServiceProvider.php - COMMENT SEMUA

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // COMMENT DULU SEMUA KODE DI SINI
        // Broadcast::routes();
        // require base_path('routes/channels.php');
    }
}