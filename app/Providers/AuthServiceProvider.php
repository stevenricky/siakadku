<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\PembayaranSpp;
use App\Policies\PembayaranSppPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        PembayaranSpp::class => PembayaranSppPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role-based authorization
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('guru', function ($user) {
            return $user->role === 'guru';
        });

        Gate::define('siswa', function ($user) {
            return $user->role === 'siswa';
        });

        Gate::define('akses-admin', function ($user) {
            return in_array($user->role, ['admin']);
        });

        Gate::define('akses-guru', function ($user) {
            return in_array($user->role, ['admin', 'guru']);
        });

        Gate::define('akses-siswa', function ($user) {
            return in_array($user->role, ['admin', 'guru', 'siswa']);
        });
    }
}