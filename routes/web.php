<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// =============================================================================
// RAILWAY FIX ROUTES - UNTUK PRODUCTION FIX
// =============================================================================
Route::get('/railway-fix', function () {
    try {
        $results = [];
        
        // 1. Disable maintenance mode
        if (file_exists(storage_path('framework/down'))) {
            \Illuminate\Support\Facades\Artisan::call('up');
            $results[] = '✅ Maintenance mode disabled';
        } else {
            $results[] = '✅ Maintenance mode already disabled';
        }
        
        // 2. Clear cache
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results[] = '✅ Config cache cleared';
        
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results[] = '✅ Route cache cleared';
        
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results[] = '✅ View cache cleared';
        
        // 3. Test database connection
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            $results[] = '✅ Database connected';
        } catch (\Exception $e) {
            $results[] = '❌ Database error: ' . $e->getMessage();
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Railway fix applied successfully',
            'results' => $results,
            'maintenance_mode' => false,
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => env('APP_DEBUG') ? $e->getTrace() : 'Debug disabled'
        ], 500);
    }
});

Route::get('/railway-status', function () {
    $isDown = file_exists(storage_path('framework/down'));
    
    try {
        $dbConnected = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected: ' . $e->getMessage();
    }
    
    return response()->json([
        'maintenance_mode' => $isDown,
        'database_status' => $dbStatus,
        'timestamp' => now(),
        'app_env' => env('APP_ENV'),
        'app_debug' => env('APP_DEBUG'),
        'app_key_exists' => !empty(env('APP_KEY')),
        'session_driver' => env('SESSION_DRIVER'),
        'database_connection' => env('DB_CONNECTION')
    ]);
});

// =============================================================================
// TEST ROUTES
// =============================================================================
Route::get('/test-maintenance-middleware', function () {
    return "Jika Anda melihat ini, middleware maintenance TIDAK berjalan";
});

Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId()
    ]);
});

// =============================================================================
// MAINTENANCE ROUTES 
// =============================================================================
Route::get('/maintenance-page', function () {
    return view('maintenance');
})->name('maintenance.page');

Route::post('/maintenance-access', [MaintenanceController::class, 'access'])
    ->name('maintenance.access');

// =============================================================================
// CORE APPLICATION ROUTES
// =============================================================================

// Simple root route
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        return match($user->role) {
            'admin' => redirect('/admin/dashboard'),
            'guru' => redirect('/guru/dashboard'),
            'siswa' => redirect('/siswa/dashboard'),
            default => redirect('/login')
        };
    }
    
    return redirect('/login');
});

// =============================================================================
// AUTHENTICATION ROUTES - TANPA MAINTENANCE MIDDLEWARE
// =============================================================================
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);

// Sanctum CSRF cookie route
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});

// =============================================================================
// AUTHENTICATED ROUTES
// =============================================================================
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Profile routes
    Route::get('/profile', \App\Livewire\Profile\Edit::class)->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});

// =============================================================================
// THEME & UTILITY ROUTES
// =============================================================================
Route::post('/theme-toggle', function (Request $request) {
    $request->validate([
        'theme' => 'required|in:light,dark'
    ]);
    
    $theme = $request->theme;
    return response()->json(['success' => true])
        ->cookie('theme', $theme, 60 * 24 * 30, null, null, false, false);
})->name('theme.toggle');

// Health check route
Route::get('/up', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return response()->json([
            'status' => 'OK', 
            'timestamp' => now(),
            'database' => 'Connected'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Error',
            'database' => 'Disconnected',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Storage route untuk file public
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path("app/public/{$path}");
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.local');

// =============================================================================
// TEST NOTIFICATION ROUTES
// =============================================================================
Route::prefix('test')->name('test.')->group(function () {
    Route::get('/notif-siswa', function() {
        try {
            $user = \App\Models\User::where('role', 'siswa')->first();
            
            if ($user) {
                $user->notifications()->create([
                    'type' => 'App\\Notifications\\TestNotification',
                    'data' => json_encode([
                        'title' => '📚 Test Notifikasi Siswa',
                        'message' => 'Ini adalah notifikasi test untuk siswa. Dikirim pada ' . now()->format('H:i:s'),
                        'type' => 'info',
                        'data' => ['route' => 'siswa.dashboard'],
                        'timestamp' => now()->toISOString()
                    ]),
                    'read_at' => null,
                ]);
                
                return "✅ Test notification created for student: {$user->name}";
            }
            
            return "❌ No student user found";
        } catch (\Exception $e) {
            return "❌ Error: " . $e->getMessage() . " at line " . $e->getLine();
        }
    });

    Route::get('/notif-guru', function() {
        try {
            $user = \App\Models\User::where('role', 'guru')->first();
            
            if ($user) {
                $user->notifications()->create([
                    'type' => 'App\\Notifications\\TestNotification',
                    'data' => json_encode([
                        'title' => '👨‍🏫 Test Notifikasi Guru',
                        'message' => 'Ini adalah notifikasi test untuk guru. Dikirim pada ' . now()->format('H:i:s'),
                        'type' => 'success',
                        'data' => ['route' => 'guru.dashboard'],
                        'timestamp' => now()->toISOString()
                    ]),
                    'read_at' => null,
                ]);
                
                return "✅ Test notification created for teacher: {$user->name}";
            }
            
            return "❌ No teacher user found";
        } catch (\Exception $e) {
            return "❌ Error: " . $e->getMessage() . " at line " . $e->getLine();
        }
    });

    Route::get('/notif-admin', function() {
        try {
            $user = \App\Models\User::where('role', 'admin')->first();
            
            if ($user) {
                $user->notifications()->create([
                    'type' => 'App\\Notifications\\TestNotification',
                    'data' => json_encode([
                        'title' => '👨‍💼 Test Notifikasi Admin',
                        'message' => 'Ini adalah notifikasi test untuk admin. Dikirim pada ' . now()->format('H:i:s'),
                        'type' => 'warning',
                        'data' => ['route' => 'admin.dashboard'],
                        'timestamp' => now()->toISOString()
                    ]),
                    'read_at' => null,
                ]);
                
                return "✅ Test notification created for admin: {$user->name}";
            }
            
            return "❌ No admin user found";
        } catch (\Exception $e) {
            return "❌ Error: " . $e->getMessage() . " at line " . $e->getLine();
        }
    });
});

// =============================================================================
// ROLE-BASED ROUTES
// =============================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    require __DIR__.'/admin.php';
});

Route::prefix('guru')->name('guru.')->middleware(['auth', 'guru'])->group(function () {
    require __DIR__.'/guru.php';
});

Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'siswa'])->group(function () {
    require __DIR__.'/siswa.php';
});

// Untuk Guru
Route::get('/guru/pengumuman-kelas', \App\Livewire\Guru\PengumumanKelas::class)
     ->name('guru.pengumuman-kelas')
     ->middleware(['auth', 'guru']);

// Untuk Siswa  
Route::get('/siswa/pengumuman-kelas', \App\Livewire\Siswa\PengumumanSekolah::class)
     ->name('siswa.pengumuman-kelas')
     ->middleware(['auth', 'siswa']);

// =============================================================================
// ERROR ROUTES
// =============================================================================
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');

// Fallback untuk 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
