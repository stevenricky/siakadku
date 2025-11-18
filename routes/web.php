<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// =============================================================================
// DEBUG ROUTES - TEMPORARY
// =============================================================================

// Test basic Laravel
Route::get('/test', function() {
    return response()->json([
        'status' => 'success', 
        'message' => 'Laravel is running',
        'timestamp' => now()
    ]);
});

// Test database connection
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'database' => 'Connected successfully',
            'driver' => DB::connection()->getDriverName()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'database' => $e->getMessage()
        ], 500);
    }
});

// Test environment
Route::get('/test-env', function() {
    return response()->json([
        'app_env' => app()->environment(),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'db_connection' => config('database.default')
    ]);
});

// Test view
Route::get('/test-view', function() {
    return view('welcome');
});


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;




// routes/web.php - tambahkan
Route::get('/test-maintenance-middleware', function () {
    return "Jika Anda melihat ini, middleware maintenance TIDAK berjalan";
})->middleware(\App\Http\Middleware\CheckMaintenanceMode::class);
// =============================================================================
// MAINTENANCE ROUTES 
// =============================================================================
Route::get('/maintenance-page', function () {
    return view('maintenance');
})->name('maintenance.page');

Route::post('/maintenance-access', [App\Http\Controllers\MaintenanceController::class, 'access'])
    ->name('maintenance.access');

// =============================================================================
// ROUTE LAINNYA...
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
    
    // User belum login, redirect ke login
    return redirect('/login');
});

// routes/web.php - GANTI route login

// Authentication routes dengan middleware maintenance
Route::get('login', [LoginController::class, 'create'])
    ->name('login')
    ->middleware(\App\Http\Middleware\CheckMaintenanceMode::class); // Gunakan class langsung

Route::post('login', [LoginController::class, 'store'])
    ->middleware(\App\Http\Middleware\CheckMaintenanceMode::class); // Gunakan class langsung




Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Profile routes
    Route::get('/profile', \App\Livewire\Profile\Edit::class)->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    

});

// Theme toggle route
Route::post('/theme-toggle', function (Request $request) {
    $request->validate([
        'theme' => 'required|in:light,dark'
    ]);
    
    $theme = $request->theme;
    return response()->json(['success' => true])
        ->cookie('theme', $theme, 60 * 24 * 30, null, null, false, false); // 30 days
})->name('theme.toggle');

// Health check route (untuk monitoring)
Route::get('/up', function () {
    return response()->json(['status' => 'OK', 'timestamp' => now()]);
});

// Storage route untuk file public
Route::get('/storage/{path}', function ($path) {
    return response()->file(storage_path("app/public/{$path}"));
})->where('path', '.*')->name('storage.local');

// =============================================================================
// TEST ROUTES - HAPUS ATAU KOMENTARI DI PRODUCTION
// =============================================================================
// Route group untuk testing notifikasi
Route::prefix('test')->name('test.')->group(function () {
    Route::get('/notif-siswa', function() {
        try {
            $user = \App\Models\User::where('role', 'siswa')->first();
            
            if ($user) {
                // Create test notification secara langsung
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
                // Create test notification secara langsung
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
                // Create test notification secara langsung
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
// END TEST ROUTES
// =============================================================================

// Role-based routes dengan middleware
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    require __DIR__.'/admin.php';
});

Route::prefix('guru')->name('guru.')->middleware(['auth', 'guru'])->group(function () {
    require __DIR__.'/guru.php';
});

Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'siswa'])->group(function () {
    require __DIR__.'/siswa.php';
});

// Unauthorized access page
Route::get('/unauthorized', function () {
    return view('errors.unauthorized');
})->name('unauthorized');

// Fallback untuk 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Untuk Guru
Route::get('/guru/pengumuman-kelas', \App\Livewire\Guru\PengumumanKelas::class)
     ->name('guru.pengumuman-kelas')
     ->middleware(['auth', 'guru']);

// Untuk Siswa  
Route::get('/siswa/pengumuman-kelas', \App\Livewire\Siswa\PengumumanSekolah::class)
     ->name('siswa.pengumuman-kelas')
     ->middleware(['auth', 'siswa']);
